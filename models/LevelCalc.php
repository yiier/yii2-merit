<?php
/**
 * Created by PhpStorm.
 * User: soul11201 <soul11201@gmail.com>
 * Date: 2017/7/10
 * Time: 11:31
 */

namespace yiier\merit\models;


class LevelCalc implements LevelCalcInterface
{
    const LEVEL0 = 0;
    const LEVEL1 = 1;
    const LEVEL2 = 2;
    const LEVEL3 = 3;
    const LEVEL4 = 4;
    const LEVEL5 = 5;

    private static  $_level_name = [
        self::LEVEL0 => '潜水',
        self::LEVEL1 => '冒泡',
        self::LEVEL2 => '吐槽',
        self::LEVEL3 => '活跃',
        self::LEVEL4 => '唠叨',
        self::LEVEL5 => '传说',
    ];
    private static $_jifen_level_map = [
        1700 => self::LEVEL5,
        1600 => self::LEVEL4,
        1500 => self::LEVEL3,
        1000 => self::LEVEL2,
        500  => self::LEVEL1,
        0    => self::LEVEL0,
    ];

    public function calc_level($merit, $pos_accu_merit)
    {
        foreach (self::$_jifen_level_map as $jifen_thresh_hold => $level)
        {
            if($merit >= $jifen_thresh_hold)
            {
                return $level;
            }
        }
        return -1;
    }

    public function get_levelName($level)
    {
        return self::$_level_name[$level];
    }
}