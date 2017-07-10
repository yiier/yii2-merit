<?php

use yii\db\Migration;

class m170710_030015_add_level extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%merit}}','level',
            $this->integer()
                ->notNull()
                ->defaultValue(0)
                ->comment("根据总积分、正值累积积分计算出来的用户等级")
        );
        $this->addColumn('{{%merit}}','pos_accu_merit',
            $this->integer()
                ->notNull()
                ->defaultValue(0)
                ->comment("正值累积积分")
        );
    }

    public function safeDown()
    {
        echo "m170710_030015_add_level cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_030015_add_level cannot be reverted.\n";

        return false;
    }
    */
}
