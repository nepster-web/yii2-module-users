<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
use nepster\users\models\User;
use Yii;

/**
 * Class ResendForm
 */
class ResendForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var \nepster\users\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // E-mail
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::className(),
                'filter' => function ($query) {
                    $query->emailVerified(0);
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('users', 'EMAIL'),
            'phone' => Yii::t('users', 'PHONE'),
        ];
    }

    /**
     * @return false OR \nepster\users\models\User
     */
    public function resend()
    {
        $this->_user = User::findByEmail($this->email, ['status' => User::STATUS_INACTIVE]);
        if ($this->_user !== null) {
            $this->_user->generateSecureKey();
            if ($this->_user->save(false)) {
                return $this->_user;
            }
        }
        return false;
    }
}
