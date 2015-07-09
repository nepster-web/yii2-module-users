<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Пример персональных миграций
 * Добавление дополнительных полей
 */
class m140418_204059_users_extra extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%users_profile}}', 'extra', Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users_profile}}', 'extra');
    }
}
