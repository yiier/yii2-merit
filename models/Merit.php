<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 16/3/20 下午5:47
 * description:
 */

namespace yiier\merit\models;


use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%merit}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property integer $type
 * @property integer $merit
 * @property integer $pos_accu_merit
 * @property integer $level
 * @property integer $created_at
 * @property integer $updated_at
 */
class Merit extends \yii\db\ActiveRecord
{
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
        return '{{%merit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'merit', 'created_at', 'updated_at', 'level', 'pos_accu_merit'], 'integer'],
            [['user_id'], 'string', 'max' => 100],
            [['username'], 'string', 'max' => 20]
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
            'username' => Yii::t('app', '用户名'),
            'type' => Yii::t('app', '类型'),
            'merit' => Yii::t('app', '总值'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'level' => Yii::t('app', '用户等级'),
            'pos_accu_merit' => Yii::t('app', '正累积积分'),
        ];
    }
}