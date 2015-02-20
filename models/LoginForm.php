<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use Yii;

/**
 * Class LoginForm
 */
class LoginForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var boolean
     */
    public $rememberMe = true;

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
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'USERNAME'),
            'password' => Yii::t('users', 'PASSWORD'),
            'rememberMe' => Yii::t('users', 'REMEMBER_ME')
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['username', 'password', 'rememberMe'],
        ];
    }

    /**
     * Валидация пароля
     */
    public function validatePassword($attribute)
    {
        $this->_user = static::getUser($this->username);
        
        if (!$this->_user || !$this->_user->validatePassword($this->$attribute)) {
            $this->addError('username', '');
            $this->addError($attribute, Yii::t('users', 'INVALID_USERNAME_OR_PASSWORD'));
        }
        
        if ($this->_user && !$this->_user->status) {
            $this->addError($attribute, Yii::t('users', 'MUST_ACTIVATE_ACCOUNT'));
        }
    }

    /**
     * Авторизация пользователя
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}
