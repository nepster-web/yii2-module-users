<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Юридическое лицо
 */
class m140418_204056_users_legal_person extends Migration
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

        // Добавить колонку legal_person, указатель на юридическое лицо
        $this->addColumn('{{%users_profile}}', 'legal_person', Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0 AFTER `birthday`');

        // Данные юридических лиц
        $this->createTable('{{%users_legal_person}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Полное наименование юридического лица"',
            'address' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Юридический адрес"',
            'BIN' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "ОГРН"',
            'bank' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Банк"',
            'account' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Расчетный счет"',
            'time_update' => Schema::TYPE_INTEGER . ' NULL DEFAULT NULL',
        ], $tableOptions . ' COMMENT = "Данные юридических лиц"');

        $this->addForeignKey('{{%users_legal_person_user_id}}', '{{%users_legal_person}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('ALTER TABLE {{%users_profile}} DROP `legal_person`');
        $this->dropTable('{{%users_legal_person}}');
    }
}
