<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:48
 * description:
 */

namespace yiier\merit\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yiier\merit\Module;

/**
 * This is the model class for table "{{%merit_template}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property string $unique_id
 * @property integer $method
 * @property integer $event
 * @property integer $action_type
 * @property integer $rule_key
 * @property integer $rule_value
 * @property integer $increment
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer events_type
 * @property integer continuous_count
 */
class MeritTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const MERIT_POST = 2;

    /**
     * @inheritdoc
     */
    const MERIT_GET = 1;

    const STATUS_ACTIVE = 1;

    const STATUS_DELETE = 0;

    /**
     * @var  int 减法
     */
    const ACTIVE_TYPE_SUB = 1;

    /**
     * @var int 加法
     */
    const ACTIVE_TYPE_ADD = 2;

    /**
     * @var int 普通的事件，比如每天登陆送积分,此为默认类型
     */
    const EVENTS_TYPE_NORMAL = 0;

    /**
     * @var int 连续登陆事件类型
     */
    const EVENTS_TYPE_CONTINUOUS = 1;

    /**
     * 自动更新created_at和updated_at时间
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merit_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'method', 'event', 'action_type', 'rule_key', 'rule_value', 'increment', 'status', 'created_at', 'updated_at', 'events_type', 'continuous_count'], 'integer'],
            [['title', 'unique_id'], 'required'],
            [['title', 'unique_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', '类型'),
            'title' => Yii::t('app', '标题'),
            'unique_id' => Yii::t('app', 'action uniqueId'),
            'method' => Yii::t('app', '请求方式'),
            'event' => Yii::t('app', '事件 0:不绑定'),
            'action_type' => Yii::t('app', '操作类型'),
            'rule_key' => Yii::t('app', '规则类型'),
            'rule_value' => Yii::t('app', '规则值'),
            'increment' => Yii::t('app', '变化值'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'events_type' => Yii::t('app', '事件类型'),
            'continuous_count' => Yii::t('app', '持续次数')
        ];
    }

    public static function getMethods()
    {
        return [
            static::MERIT_POST => 'POST',
            static::MERIT_GET => 'GET',
        ];
    }

    public static function getActionTypes()
    {
        return [
            2 => '+',
            1 => '-',
        ];
    }

    public static function getRuleKeys()
    {
        return [
            Yii::t('app', '不限制'),
            Yii::t('app', '按天限制'),
            Yii::t('app', '按次限制'),
        ];
    }

    public static function getTypes()
    {
        /** @var Module $merit */
        $merit = new Module('merit');
        return $merit->types;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => '开启',
            self::STATUS_DELETE => '停用',
        ];
    }

    public static function getEventsType()
    {
        return [
            self::EVENTS_TYPE_NORMAL => '做一次，就有一次',
            self::EVENTS_TYPE_CONTINUOUS => '连续做，送额外奖励',
        ];
    }
}
