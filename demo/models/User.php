<?php

namespace app\modules\users\models;

use Yii;

/**
 * Class User
 */
class User extends \nepster\users\models\User
{
    /**
     * @var string
     */
    public $repassword;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['phone', 'email', 'password', 'repassword'],
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
            [['phone', 'email', 'password', 'repassword'], 'required'],
            [['phone', 'email', 'password', 'repassword'], 'trim'],

            [['phone', 'email'], 'unique'],

            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/', 'message' => Yii::t('users', 'SIMPLE_PASSWORD')],
            ['repassword', 'compare', 'compareAttribute' => 'password'],

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
                // IP пользователя
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->create_ip = Yii::$app->request->userIP;
                }
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
