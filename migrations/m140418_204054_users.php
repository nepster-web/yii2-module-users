<?php

use nepster\users\helpers\Security;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Create module tables.
 */
class m140418_204054_users extends Migration
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

        // Пользователи
        $this->createTable('{{%users}}', [
            'id' => Schema::TYPE_PK,
            'role' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "user" COMMENT "Роль"',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Почтовый адрес"',
            'phone' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Главный телефон"',
            'mail_verify' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Верификация почты"',
            'phone_verify' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Верификация телефона"',
            'password' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Пароль в зашифрованном виде"',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен авторизации"',
            'api_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен для api"',
            'secure_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен"',
            'auth_ip' => Schema::TYPE_STRING . ' NULL DEFAULT NULL ',
            'auth_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL ',
            'create_ip' => Schema::TYPE_STRING . ' NULL DEFAULT NULL ',
            'create_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL ',
            'update_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL ',
            'ban_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL COMMENT "Время бана"',
            'ban_reason' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Причина бана"',
        ], $tableOptions . ' COMMENT = "Пользователи"');

        // Профили
        $this->createTable('{{%users_profile}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Имя"',
            'surname' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Фамилия"',
            'avatar_url' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "URL Аватара"',
            'whau' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Откуда пользователь узнал о сайте"',
            'birthday' => Schema::TYPE_DATETIME . ' NULL DEFAULT "0000-00-00 00:00:00" COMMENT "Дата рождения"',
            'update_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
            'unique(`user_id`)',
        ], $tableOptions . ' COMMENT = "Профили пользователей"');

        // Действия
        $this->createTable('{{%users_actions}}', [
            'id' => Schema::TYPE_PK,
            'application' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Приложение"',
            'action' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Действие"',
            'data' => Schema::TYPE_TEXT . ' NULL DEFAULT NULL COMMENT "Данные"',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_agent' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'create_ip' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'create_time' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
        ], $tableOptions . ' COMMENT = "Действия пользователей"');

        // Индексы
        $this->createIndex('{{%users_email}}', '{{%users}}', 'email', true);

        // Ключи
        $this->addForeignKey('{{%users_profile_user_id}}', '{{%users_profile}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%users_actions_user_id}}', '{{%users_actions}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');


        // СЕССИИ
        // https://github.com/yiisoft/yii2/blob/master/framework/web/DbSession.php
        /*
            Where 'BLOB' refers to the BLOB-type of your preferred DBMS. Below are the BLOB type that can be used for some popular DBMS:

            MySQL: LONGBLOB
            PostgreSQL: BYTEA
            MSSQL: BLOB
        */

        $dataType = 'BLOB';
        if ($this->db->driverName === 'mysql') {
            $dataType = 'LONGBLOB';
        }

        $this->createTable('{{%session}}', [
            'id CHAR(64) NOT NULL PRIMARY KEY',
            'user_id' => 'int(11) unsigned',
            'expire INTEGER',
            'data ' . $dataType,
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
        $this->dropTable('{{%users_actions}}');
        $this->dropTable('{{%users_profile}}');
        $this->dropTable('{{%users}}');
    }
}
