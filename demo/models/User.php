<?php

namespace common\modules\users\models;

use Yii;

/**
 * Class User
 */
class User extends \nepster\users\models\User
{
    /**
     * @var string
     */
    public static $password;

    /**
     * @var string
     */
    public static $repassword;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['username', 'email', 'password', 'repassword'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            'signup' => self::OP_ALL
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'repassword' => Yii::t('users', 'REPASSWORD')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            [['username', 'email'], 'unique'],
            [['username', 'email', 'password', 'repassword'], 'required'],
            [['username', 'email', 'password', 'repassword'], 'trim'],

            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/', 'message' => Yii::t('users', 'SIMPLE_PASSWORD')],
            ['repassword', 'compare', 'compareAttribute' => 'password'],

            ['email', 'string', 'max' => 100],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Хешируем пароль
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

        if ($insert) {
            // Сохраняем профиль
            $this->profile->user_id = $this->id;
            $this->profile->save();
        }
    }
}
