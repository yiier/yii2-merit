<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:42
 * description:
 */
namespace yiier\merit;

use yii\base\Behavior;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yiier\merit\models\Continuous;
use yiier\merit\models\LevelCalcInterface;
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

        if($meritTemplate->events_type == MeritTemplate::EVENTS_TYPE_CONTINUOUS)
        {
            $user_id = $user->getId();

            $m = Continuous::find()
                ->where(['user_id' => $user_id])
                ->one();

            if(!$m)
            {
                $m = new Continuous;
            }

            $count = $m->count;

            if($count == $m->calc_count() || $m->count != $meritTemplate->continuous_count)
            {
                $transaction->commit();
                return ;
            }

            if(!$m->save())
            {
                throw new Exception(VarDumper::dumpAsString($m->getFirstErrors()));
            }
        }

        try {
            /** @var Merit $userMerit */
            $userMerit = Merit::findOne(['user_id' => $user->getId(), 'type' => $meritTemplate->type]);
            // is sub 判断是否是减法
            $actionSub = ($meritTemplate->action_type == MeritTemplate::ACTIVE_TYPE_SUB);
            if ($userMerit) {
                if($actionSub){
                    $merit = bcsub($userMerit->merit, $meritTemplate->increment);
                    $pos_accu_merit = $userMerit->pos_accu_merit;
                }else{
                    $merit = bcadd($userMerit->merit, $meritTemplate->increment);
                    $pos_accu_merit = bcadd ($userMerit->pos_accu_merit, $meritTemplate->increment);
                }

                $level = Instance::ensure(Yii::$app->params['yiier\merit\models\LevelCalc']);
                if(! $level instanceof LevelCalcInterface)
                {
                    throw new \Exception('property \'levelCalc\' was not the implemention of \yiier\merit\models\LevelCalcInterface');
                }

                $res = $level->calc_level($merit, $pos_accu_merit);

                if($res < 0){
                    throw new \Exception("wrong calculated result !!!");
                }

                $userMerit->setAttributes([
                    'merit' => (integer)$merit,
                    'pos_accu_merit' => (integer)$pos_accu_merit,
                    'level' => $res,
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
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            $transaction->rollBack();
        }
    }

}
