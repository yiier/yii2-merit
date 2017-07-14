<?php

use yii\db\Migration;

class m170713_091926_add_continus_events extends Migration
{
    /**
     * 创建表选项
     * @var string
     */
    public $tableOptions = null;

    public function init()
    {
        parent::init();

        if ($this->db->driverName === 'mysql') { //Mysql 表选项
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
    }

    public function safeUp()
    {
        $this->addColumn('{{%merit_template}}', 'events_type',
            $this->integer()
                ->notNull()
                ->defaultValue(0)
                ->comment("0: 普通的 1：连续登陆有额外的奖励")
        );

        $this->addColumn('{{%merit_template}}', 'continuous_count',
            $this->integer()
                ->notNull()
                ->defaultValue(0)
                ->comment("获得额外积分，需要连续做的次数")
        );

        $this->createTable('{{%continuous}}', [
            'user_id' => $this->string(100)->notNull()->defaultValue('')->comment('用户ID'),
            'count' => $this->integer()->defaultValue(0)->notNull(),
            'next_start' => $this->integer()->defaultValue(0)->notNull(),
            'next_end' => $this->integer()->defaultValue(0)->notNull(),
        ], $this->tableOptions);
        $this->createIndex('user_id','{{%continuous}}', 'user_id');

    }

    public function safeDown()
    {
        echo "m170713_091926_add_continus_events cannot be reverted.\n";
        $this->dropColumn('{{%merit_template}}', 'continuous_count');
        $this->dropColumn('{{%merit_template}}', 'events_type');
        $this->dropTable('{{%continuous}}');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170713_091926_add_continus_events cannot be reverted.\n";

        return false;
    }
    */
}
