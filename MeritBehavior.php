<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:42
 * description:
 */
namespace yiier\merit;

use yii\base\Behavior;
use yii\db\Exception;
use yii\web\Controller;
use yiier\merit\models\Merit;
use yiier\merit\models\MeritLog;
use yiier\merit\models\MeritTemplate;
use Yii;

class MeritBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => 'afterAction',
        ];
    }

    public function afterAction()
    {
        if (!Yii::$app->user->isGuest) {
            if ($meritTemplates = $this->hasMeritTemplate()) {
                /** @var  MeritTemplate $meritTemplate */
                foreach ($meritTemplates as $meritTemplate) {
                    switch ($meritTemplate->rule_key) {
                        case 2:
                            if ($meritTemplate->rule_value <= $this->getMeritLogTimes($meritTemplate, Yii::$app->user->id)) {
                                return false;
                            }
                            break;
                        case 1:
                            if ($meritTemplate->rule_value <= $this->getMeritLogDay($meritTemplate, Yii::$app->user->id)) {
                                return false;
                            }
                            break;
                        default:
                            # code...
                            break;
                    }
                    $this->update($meritTemplate);
                }
            }
        }
        return false;
    }

    public function hasMeritTemplate()
    {
        $uniqueId = Yii::$app->controller->action->uniqueId;
        // 必须是 GET 或者 POST 请求
        if (!in_array(Yii::$app->request->method, MeritTemplate::getMethods())) {
            return false;
        }

        $method = array_flip(MeritTemplate::getMethods())[Yii::$app->request->method];
        // 支持同一个 $uniqueId 不同 Type
        $meritTemplates = MeritTemplate::find()
            ->where(['unique_id' => $uniqueId, 'status' => MeritTemplate::STATUS_ACTIVE, 'method' => $method])
            ->all();
        if (!$meritTemplates) {
            return false;
        }
        return $meritTemplates;
    }

    /**
     * 查看次数
     * @param MeritTemplate $meritTemplate
     * @param $userId
     * @return int|string
     */
    public function getMeritLogTimes(MeritTemplate $meritTemplate, $userId)
    {
        return MeritLog::find()
            ->where(['merit_template_id' => $meritTemplate->id, 'type' => $meritTemplate->type, 'user_id' => $userId])
            ->count();
    }

    /**
     * 按次查看
     * @param MeritTemplate $meritTemplate
     * @param $userId
     * @return int|string
     */
    public function getMeritLogDay(MeritTemplate $meritTemplate, $userId)
    {
        return MeritLog::find()
            ->where(['merit_template_id' => $meritTemplate->id, 'type' => $meritTemplate->type, 'user_id' => $userId])
            ->andWhere(['between', 'created_at', strtotime(date('Ymd') . '000000'), strtotime(date('Ymd') . '235959')])
            ->count();
    }

    /**
     * @param MeritTemplate $meritTemplate
     * @throws Exception
     */
    public function update(MeritTemplate $meritTemplate)
    {
        $meritLog = new MeritLog();
        $user = \Yii::$app->user->identity;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /** @var Merit $userMerit */
            $userMerit = Merit::findOne(['user_id' => $user->getId(), 'type' => $meritTemplate->type]);
            // is sub 判断是否是减法
            $actionSub = ($meritTemplate->action_type == MeritTemplate::ACTIVE_TYPE_SUB);
            if ($userMerit) {
                $merit = call_user_func($actionSub ? 'bcsub' : 'bcadd', $userMerit->merit, $meritTemplate->increment);
                $userMerit->setAttributes([
                    'merit' => (integer)$merit
                ]);
            } else {
                $userMerit = new Merit();
                $userMerit->setAttributes([
                    'merit' => ($actionSub ? '-' : '') . $meritTemplate->increment,
                    'user_id' => $user->getId(),
                    'username' => $user->username,
                    'type' => $meritTemplate->type,
                ]);
            }
            if (!$userMerit->save()) {
                Yii::error('Merit 操作失败' . json_encode(array_values($userMerit->getFirstErrors())), 'error');
                throw new Exception(array_values($userMerit->getFirstErrors())[0]);
            }
            $description = $meritTemplate->title . ': '
                . MeritTemplate::getActionTypes()[$meritTemplate->action_type]
                . $meritTemplate->increment
                . MeritTemplate::getTypes()[$meritTemplate->type];

            $meritLog->setAttributes([
                'user_id' => $user->getId(),
                'username' => $user->username,
                'merit_template_id' => $meritTemplate->id,
                'type' => $meritTemplate->type,
                'description' => $description,
                'action_type' => $meritTemplate->action_type,
                'increment' => $meritTemplate->increment,
                'created_at' => time(),
            ]);
            if (!$meritLog->save()) {
                throw new Exception(array_values($meritLog->getFirstErrors())[0]);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }

    }

}
