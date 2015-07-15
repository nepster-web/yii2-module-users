<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
use nepster\users\helpers\Security;
use nepster\users\models\User;
use Yii;

/**
 * Class RecoveryConfirmationForm
 */
class RecoveryConfirmationForm extends \yii\base\Model
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
    public $secure_key;

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
            [['password', 'repassword', 'secure_key'], 'required'],
            [['password', 'repassword', 'secure_key'], 'trim'],
            ['password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/', 'message' => Yii::t('users', 'SIMPLE_PASSWORD')],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            ['secure_key', 'string', 'max' => 64],
            [
                'secure_key',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                    $query->status(User::STATUS_ACTIVE);
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
            'password' => Yii::t('users', 'PASSWORD'),
            'repassword' => Yii::t('users', 'REPASSWORD')
        ];
    }

    /**
     * @return boolean
     */
    public function isValidToken()
    {
        if (Security::isValidToken($this->secure_key, $this->module->recoveryWithin) === true) {
            return ($this->_user = User::findBySecureKey($this->secure_key, ['status'])) !== null;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function recovery()
    {
        $model = $this->_user;
        if ($model !== null) {
            return $model->recovery($this->password);
        }
        return false;
    }
}
