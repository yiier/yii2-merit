<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:48
 * description:
 */

namespace yiier\merit\models;

use Yii;

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
            [['type', 'method', 'event', 'action_type', 'rule_key', 'rule_value', 'increment', 'status', 'created_at'], 'integer'],
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
            'type' => Yii::t('app', '分类 1:积分 2:声望 3:徽章'),
            'title' => Yii::t('app', '标题'),
            'unique_id' => Yii::t('app', 'action uniqueId'),
            'method' => Yii::t('app', '请求方式 1 get 2 post'),
            'event' => Yii::t('app', '事件 0:不绑定'),
            'action_type' => Yii::t('app', '操作类型 1减去 2新增'),
            'rule_key' => Yii::t('app', '规则类型 0:不限制 1:按天限制 2:按次限制'),
            'rule_value' => Yii::t('app', '规则值'),
            'increment' => Yii::t('app', '变化值'),
            'status' => Yii::t('app', '状态 0暂停 1开启'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }

    public static function getMethods()
    {
        return [
            'GET' => static::MERIT_GET,
            'POST' => static::MERIT_POST,
        ];
    }

    public static function getActionTypes()
    {
        return [
            1 => '-',
            2 => '+',
        ];
    }
}