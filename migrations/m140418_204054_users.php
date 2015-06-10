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
            'group' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "user" COMMENT "Группа"',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1 COMMENT "Статус"',
            'email' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Почтовый адрес"',
            'phone' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Главный телефон"',
            'email_verify' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Верификация почты"',
            'phone_verify' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Верификация телефона"',
            'online' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Сайчас на сайте"',
            'banned' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 COMMENT "Заблокирован"',
            'password' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Пароль в зашифрованном виде"',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен авторизации"',
            'api_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен для api"',
            'secure_key' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Секретный токен"',
            'time_activity' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL ',
            'ip_register' => Schema::TYPE_STRING . ' NULL DEFAULT NULL ',
            'time_register' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL ',
        ], $tableOptions . ' COMMENT = "Пользователи"');

        // Профили
        $this->createTable('{{%users_profile}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Имя"',
            'surname' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Фамилия"',
            'avatar_url' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "URL Аватара"',
            'whau' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Откуда пользователь узнал о сайте"',
            'birthday' => Schema::TYPE_DATE . ' NULL DEFAULT NULL COMMENT "Дата рождения"',
            'time_update' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
            'unique(`user_id`)',
        ], $tableOptions . ' COMMENT = "Профили пользователей"');

        // Действия
        $this->createTable('{{%users_actions}}', [
            'id' => Schema::TYPE_PK,
            'application' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Приложение"',
            'module' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Модуль"',
            'action' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Действие"',
            'data' => Schema::TYPE_TEXT . ' NULL DEFAULT NULL COMMENT "Данные"',
            'user_id' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
            'user_agent' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'hash' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "md5(User Agent + IP)"',
            'ip' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
            'time_create' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
        ], $tableOptions . ' COMMENT = "Действия пользователей"');

        // История блокировки пользователей
        $this->createTable('{{%users_banned}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
            'reason' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Причина бана"',
            'ip' => Schema::TYPE_BIGINT . ' NULL DEFAULT NULL COMMENT "IP пользователя через ip2long"',
            'time_banned' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL COMMENT "До какого времени действует бан"',
            'ip_network' => Schema::TYPE_BIGINT . ' NULL DEFAULT NULL COMMENT "IP адрес подсети через ip2long"',
            'ip_mask' => Schema::TYPE_BIGINT . ' NULL DEFAULT NULL COMMENT "Соответственно маска через ip2long"',
        ], $tableOptions . ' COMMENT = "История блокировки пользователей"');

        // Индексы
        $this->createIndex('{{%users_email}}', '{{%users}}', 'email', true);
        $this->createIndex('{{%users_api_key}}', '{{%users}}', 'api_key', true);
        $this->createIndex('{{users_user_id}}', '{{%users_banned}}', 'user_id');
        $this->createIndex('{{users_banned_ip}}', '{{%users_banned}}', 'ip');

        // Ключи
        $this->addForeignKey('{{%users_profile_user_id}}', '{{%users_profile}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%users_actions_user_id}}', '{{%users_actions}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{users_banned_user_id}}', '{{%users_banned}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_actions}}');
        $this->dropTable('{{%users_banned}}');
        $this->dropTable('{{%users_profile}}');
        $this->dropTable('{{%users}}');
    }
}
