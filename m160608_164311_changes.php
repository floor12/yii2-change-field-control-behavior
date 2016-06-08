<?php

use yii\db\Schema;
use yii\db\Migration;

class m160608_164311_changes extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%changes}}',
            [
                'id' => Schema::TYPE_PK . "",
                'class' => Schema::TYPE_STRING . "(30) NOT NULL",
                'property' => Schema::TYPE_STRING . "(30) NOT NULL",
                'object_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'value_old' => Schema::TYPE_TEXT . " NOT NULL",
                'value_new' => Schema::TYPE_TEXT . " NOT NULL",
                'created' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'user_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'ip' => Schema::TYPE_STRING . "(20) NOT NULL",
                'readed' => Schema::TYPE_INTEGER . "(11) NOT NULL DEFAULT '0'",
                'approved' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'canceled' => Schema::TYPE_INTEGER . "(11) NOT NULL",
            ],
            $tableOptions
        );

        $this->createIndex('object_id', '{{%changes}}', 'object_id', 0);
        $this->createIndex('class', '{{%changes}}', 'class', 0);
        $this->createIndex('property', '{{%changes}}', 'property', 0);
    }

    public function safeDown()
    {
        $this->dropIndex('object_id', '{{%changes}}');
        $this->dropIndex('class', '{{%changes}}');
        $this->dropIndex('property', '{{%changes}}');
        $this->dropTable('{{%changes}}');
    }
}
