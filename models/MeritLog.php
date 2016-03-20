<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:47
 * description:
 */

namespace yiier\merit\models;

use Yii;

/**
 * This is the model class for table "{{%merit_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $merit_template_id
 * @property integer $type
 * @property string $description
 * @property integer $action_type
 * @property integer $increment
 * @property integer $created_at
 */
class MeritLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%merit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'merit_template_id', 'type', 'action_type', 'increment', 'created_at'], 'integer'],
            [['description'], 'required'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'merit_template_id' => Yii::t('app', '模板ID'),
            'type' => Yii::t('app', '分类 1:积分 2:声望 3:徽章'),
            'description' => Yii::t('app', '描述'),
            'action_type' => Yii::t('app', '操作类型 1减去 2新增'),
            'increment' => Yii::t('app', '变化值'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}