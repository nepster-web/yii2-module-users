<?php

namespace common\modules\users\models\backend;

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
            'create' => ['phone', 'email', 'phone_verify', 'email_verify', 'password', 'group', 'status'],
            'update' => ['phone', 'email', 'phone_verify', 'email_verify', 'password', 'group', 'status'],
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
    public function rules()
    {
        return [
            [['phone', 'phone_verify', 'email', 'email_verify', 'group', 'status'], 'required'],
            [['phone', 'phone_verify', 'email', 'email_verify', 'group', 'status'], 'trim'],

            [['phone_verify', 'mail_verify'], 'boolean'],

            [['phone', 'email'], 'unique'],
            ['email', 'email'],

            ['group', 'in', 'range' => array_keys(\nepster\users\rbac\models\AuthItem::getGroupsArray())],
            ['status', 'in', 'range' => array_keys(self::getStatusArray())],

            ['password', 'required', 'on' => 'create'],
            ['password', 'trim'],
            ['password', '\nepster\users\validators\PasswordValidator'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (\nepster\users\models\User::beforeSave($insert)) {
            if (!empty($this->password)) {
                $this->setPassword($this->password);
            } else {
                $this->password = $this->getOldAttribute('password');
            }
            return true;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->setGroup($this->group);

        if ($this->scenario == 'update') {

            // Сохраняем профиль
            $this->profile->save(false);

            // Сохраняем данные юридического лица
            if (!$this->person->user_id) {
                $this->person->user_id = $this->id;
            }
            $this->person->save(false);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(LegalPerson::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
}
