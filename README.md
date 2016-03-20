Reputation engine for Yii2
==========================
用于实现积分，等级功能的设计

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiier/yii2-merit "*"
```

or add

```
"yiier/yii2-merit": "*"
```

to the require section of your `composer.json` file.

Migrations
----------

Run the following command

```
php yii migrate --migrationPath=@yiier/merit/migrations/
```

Usage
-----

Configure Controller class as follows :

```php
use use yiier\merit\MeritBehavior;

class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            MeritBehavior::className(),
        ];
    }
```