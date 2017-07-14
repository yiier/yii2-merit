<?php
/**
 * Created by PhpStorm.
 * User: soul11201 <soul11201@gmail.com>
 * Date: 2017/7/12
 * Time: 16:25
 */

namespace yiier\merit\models;

/**
 * This is the model class for table "continuous".
 *
 * @property string $user_id
 * @property integer $count
 * @property integer $next_start
 * @property integer $next_end
 */
class Continuous extends \yii\db\ActiveRecord
{
    public $range = 24 * 3600;
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->count = 0;
        $this->next_start = 0;
        $this->next_end = 0;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'continuous';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count', 'next_start', 'next_end'], 'integer'],
            [['user_id'], 'string', 'max' => 100],
        ];
    }

    public function calc_count()
    {
        $time = time();

        //已累计
        if($this->next_start > $time)
        {
            return $this->count;
        }

        // 连续登陆中间断开重新计数
        if($this->next_end < $time)
        {
            $this->count = 0;
        }

        //重新计数初始化
        if($this->count == 0)
        {
            $this->next_start    = strtotime(date('Y-m-d')." 00:00:00"); //今日凌晨
            $this->next_end  =  $this->next_start + $this->range;
        }

        $this->next_start += $this->range;
        $this->next_end  += $this->range;
        ++ $this->count;

        return $this->count;
    }
}

