<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
use Yii;

/**
 * Class PasswordForm
 */
class PasswordForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repassword;

    /**
     * @var string
     */
    public $oldpassword;

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
            [['password', 'repassword', 'oldpassword'], 'required'],
            [['password', 'repassword', 'oldpassword'], 'trim'],
            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/', 'message' => Yii::t('users', 'SIMPLE_PASSWORD')],
            ['password', 'compare', 'compareAttribute' => 'oldpassword', 'operator' => '!=='],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['oldpassword', 'validateOldPassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('users', 'NEW_PASSWORD'),
            'repassword' => Yii::t('users', 'NEW_REPASSWORD'),
            'oldpassword' => Yii::t('users', 'OLDPASSWORD')
        ];
    }

    /**
     * Валидация пароля
     */
    public function validateOldPassword($attribute)
    {
        $user = $this->user;
        if (!$user || !$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('users', 'OLD_PASSWORD_IS_INCORRECT'));
        }
    }

    /**
     * Устанавливаем новый пароль
     * @return boolean
     */
    public function password()
    {
        if (($model = $this->user) !== null) {
            return $model->password($this->password);
        }
        return false;
    }

    /**
     * Поиск пользователя по id.
     *
     * @return User|null User instance
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['id' => Yii::$app->user->identity->id])->active()->one();
        }
        return $this->_user;
    }
    
}
