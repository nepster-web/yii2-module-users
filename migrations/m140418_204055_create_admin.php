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
            'email',
            'phone',
            'role',
            'status',
            'mail_verify',
            'phone_verify',
            'password',
            'auth_key',
            'api_key',
            'secure_key',
            'auth_ip',
            'auth_time',
            'create_ip',
            'time_create',
            'time_update',
        ];

        $this->batchInsert('{{%users}}', $columns, [
            [
                1,
                //'admin',
                'admin@admin.ru',
                null,
                'admin',
                1,
                1,
                1,
                $security->generatePasswordHash('admin'),
                $security->generateRandomString(),
                $security->generateRandomString(),
                $security->generateRandomString(),
                null,
                null,
                '127.0.0.1',
                time(),
                time(),
            ],
        ]);

        $columns = [
            'id',
            'user_id',
            'name',
            'surname',
            'avatar_url',
            'whau',
            //'birthday',
        ];

        $this->batchInsert('{{%users_profile}}', $columns, [
            [
                1,
                1,
                'Admin',
                'Admin',
                null,
                null,
                //null,
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
