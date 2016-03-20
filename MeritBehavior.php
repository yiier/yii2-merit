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
            if ($meritTemplate = $this->hasMeritTemplate()) {
                return $this->update($meritTemplate);
            }
        }
        return false;
    }

    public function hasMeritTemplate()
    {
        $uniqueId = Yii::$app->controller->action->uniqueId;
        if (!in_array(Yii::$app->request->method, array_keys(MeritTemplate::getMethods()))) {
            return false;
        }
        $method = MeritTemplate::getMethods()[Yii::$app->request->method];
        /** @var MeritTemplate $model */
        $model = MeritTemplate::find()
            ->where(['unique_id' => $uniqueId, 'status' => MeritTemplate::STATUS_ACTIVE, 'method' => $method])
            ->one();
        if (!$model) {
            return false;
        }
        switch ($model->rule_key) {
            case 2:
                if ($model->rule_value <= $this->getMeritLogTimes($method, Yii::$app->user->id)) {
                    return false;
                }
                break;
            case 1:
                if ($model->rule_value <= $this->getMeritLogDay($method, Yii::$app->user->id)) {
                    return false;
                }
                break;
            default:
                # code...
                break;
        }
        return $model;
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


    public function update(MeritTemplate $meritTemplate)
    {
        $meritLog = new MeritLog();
        $userId = \Yii::$app->user->identity->getId();

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /** @var Merit $userMerit */
            $userMerit = Merit::findOne(['user_id' => $userId, 'type' => $meritTemplate->type]);
            if ($userMerit) {
                $userMerit->setAttributes([
                    'merit' => $userMerit->merit + $meritTemplate->increment
                ]);
            } else {
                $userMerit = new Merit();
                $userMerit->setAttributes([
                    'merit' => $meritTemplate->increment,
                    'user_id' => $userId,
                    'type' => $meritTemplate->type,
                ]);
            }
            if (!$userMerit->save()) {
                throw new Exception(array_values($userMerit->getFirstErrors())[0]);
            }

            $meritLog->setAttributes([
                'user_id' => $userId,
                'merit_template_id' => $meritTemplate->id,
                'type' => $meritTemplate->type,
                'description' => $meritTemplate->title . ': ' . MeritTemplate::getActionTypes()[$meritTemplate->action_type] . $meritTemplate->increment,
                'action_type' => $meritTemplate->action_type,
                'increment' => $meritTemplate->increment,
            ]);
            if (!$meritLog->save()) {
                throw new Exception(array_values($meritLog->getFirstErrors())[0]);
            }

            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }

}
