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
            'username' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Логин"',
            'role' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "user" COMMENT "Роль"',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Почтовый адрес"',
            'phone' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Главный телефон"',
            'mail_verify' => Schema::TYPE_SMALLINT . ' NOT NULL COMMENT "Верификация почты"',
            'phone_verify' => Schema::TYPE_SMALLINT . ' NOT NULL COMMENT "Верификация телефона"',
            'password' => Schema::TYPE_STRING . ' NOT NULL "Пароль в зашифрованном виде"',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен авторизации"',
            'api_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен для api"',
            'secure_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен"',
            'auth_ip' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_time' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'create_ip' => Schema::TYPE_STRING . ' NOT NULL',
            'create_time' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'update_time' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'ban_time' => Schema::TYPE_TIMESTAMP . ' NOT NULL COMMENT "Время бана"',
            'ban_reason' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Причина бана"',
        ], $tableOptions . ' COMMENT = "Пользователи"');

        // Профили
        $this->createTable('{{%users_profile}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' COMMENT "Имя"',
            'surname' => Schema::TYPE_STRING . ' COMMENT "Фамилия"',
            'avatar_url' => Schema::TYPE_STRING . ' COMMENT "URL Аватара"',
            'whau' => Schema::TYPE_STRING . ' COMMENT "Откуда пользователь узнал о сайте"',
            'birthday' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT "0000-00-00 00:00:00" COMMENT "Дата рождения"',
            'create_time' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'update_time' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'unique(`user_id`)',
        ], $tableOptions . ' COMMENT = "Профили пользователей"');

        // Действия
        $this->createTable('{{%user_actions}}', [
            'id' => Schema::TYPE_PK,
            'application' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Приложение"',
            'module' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Модуль"',
            'controller' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Контроллер"',
            'action' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Действие"',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_agent' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'create_ip' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'create_time' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
        ], $tableOptions);

        // Индексы
        $this->createIndex('{{%user_email}}', '{{%user}}', 'email', true);
        $this->createIndex('{{%user_username}}', '{{%user}}', 'username', true);

        // Ключи
        $this->addForeignKey('{{%users_profile_user_id}}', '{{%users_profile}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('{{%user_actions_user_id}}', '{{%user_actions}}', 'user_id', '{{%user}}', 'id');


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

        // Foreign Keys
        $this->addForeignKey('FK_session_user', '{{%session}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%user_actions}}');
        $this->dropTable('{{%users_profile}}');
        $this->dropTable('{{%users}}');
    }
}
