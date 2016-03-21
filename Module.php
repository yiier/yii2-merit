<?php

namespace yiier\merit;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yiier\merit\controllers';

    public $types;

    public function init()
    {
        parent::init();
        if (!$this->types) {
            $this->types = [
                1 => '积分',
                2 => '声望',
                3 => '徽章',
            ];
        }
    }
}
