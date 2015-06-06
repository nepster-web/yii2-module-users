<?php

namespace common\modules\users\models\backend;

use common\modules\users\models\LegalPerson;
use yii\db\ActiveRecord;
use Yii;

/**
 * @inheritdoc
 */
class User extends \common\modules\users\models\User
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'create' => ['phone', 'email', 'phone_verify', 'mail_verify', 'password', 'role', 'status'],
            'update' => ['phone', 'email', 'phone_verify', 'mail_verify', 'password', 'role', 'status'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            'create' => self::OP_ALL,
            'update' => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'user' => Yii::t('users', 'USER'),
            'contacts' => Yii::t('users', 'CONTACTS'),
            'date_from' => Yii::t('users', 'DATE_FROM'),
            'date_to' => Yii::t('users', 'DATE_TO'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'phone_verify', 'email', 'mail_verify', 'role', 'status'], 'required'],
            [['phone', 'phone_verify', 'email', 'mail_verify', 'role', 'status'], 'trim'],

            [['phone_verify', 'mail_verify'], 'boolean'],

            ['email', 'email'],

            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            ['password', 'required', 'on' => 'create'],
            ['password', 'trim'],
            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/', 'message' => Yii::t('users', 'SIMPLE_PASSWORD')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (\nepster\users\models\User::beforeSave($insert)) {

            if ($this->password) {
                $this->setPassword($this->password);
            }

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

    }
}
