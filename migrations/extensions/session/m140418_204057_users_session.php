<?php

use nepster\users\helpers\Security;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Таблица сессий
 */
class m140418_204057_users_session extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // СЕССИИ
        // https://github.com/yiisoft/yii2/blob/master/framework/web/DbSession.php
        /*
            Where 'BLOB' refers to the BLOB-type of your preferred DBMS. Below are the BLOB type that can be used for some popular DBMS:

            MySQL: LONGBLOB
            PostgreSQL: BYTEA
            MSSQL: BLOB
        */
        $this->createTable('{{%session}}', [
            'id CHAR(64) NOT NULL PRIMARY KEY',
            'user_id' => Schema::TYPE_INTEGER,
            'expire' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_BINARY,
        ], $tableOptions . ' COMMENT = "Сессии пользователей"');

        // Индексы
        $this->createIndex('{{%session_user_id}}', '{{%session}}', 'user_id');
        $this->createIndex('{{%session_expire}}', '{{%session}}', 'expire');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
