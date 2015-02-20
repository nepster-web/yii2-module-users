<?php

use nepster\users\helpers\Security;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Create module tables.
 */
class m140418_204055_create_admin extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $security = Yii::$app->security;

        $columns = [
            'id',
            //'username',
            'role',
            'status',
            'email',
            'phone',
            'mail_verify',
            'phone_verify',
            'password',
            'auth_key',
            'api_key',
            'secure_key',
            'auth_ip',
            'auth_time',
            'create_ip',
            'create_time',
            'update_time',
            'ban_time',
            'ban_reason'
        ];

        $this->batchInsert('{{%users}}', $columns, [
            [
                1,
                //'admin',
                'administrator',
                1,
                'admin@admin.admin',
                null,
                1,
                1,
                $security->generatePasswordHash('admin'),
                $security->generateRandomString(),
                $security->generateRandomString(),
                $security->generateRandomString(),
                null,
                null,
                '127.0.0.1',
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                null,
                null,
            ],
        ]);

        $columns = [
            'id',
            'user_id',
            'name',
            'surname',
            'avatar_url',
            'whau',
            'birthday',
            'update_time',
        ];

        $this->batchInsert('{{%users_profile}}', $columns, [
            [
                1,
                1,
                'Admin',
                'Admin',
                null,
                null,
                null,
                date('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return false;
    }
}
