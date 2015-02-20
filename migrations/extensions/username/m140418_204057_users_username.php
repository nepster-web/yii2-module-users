<?php

use nepster\users\helpers\Security;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Добавление дополнительного поля username
 */
class m140418_204057_users_username extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'username', Schema::TYPE_STRING . ' NOT NULL AFTER `id`');
        $this->createIndex('{{%users_username}}', '{{%users}}', 'username', true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'username');
    }
}
