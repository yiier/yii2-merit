<?php
/**
 * Created by PhpStorm.
 * User: soul11201 <soul11201@gmail.com>
 * Date: 2017/7/10
 * Time: 13:51
 */

namespace yiier\merit\models;


interface LevelCalcInterface
{
    /**
     *
     *根据总积分、正值累积积分计算出来的用户等级
     *
     * @param integer $merit
     * @param integer $pos_accu_merit
     * @return integer the level result >=0 if -1 returned means maybe wrong
     */
    public function calc_level($merit, $pos_accu_merit);

    /**
     *
     * 获取级别对应的级别名称
     *
     * @param integer $level
     * @return string
     */
    public function get_levelName($level);
}