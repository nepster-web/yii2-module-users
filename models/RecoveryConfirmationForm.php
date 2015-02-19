<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use nepster\users\helpers\Security;
use Yii;

/**
 * Class RecoveryConfirmationForm
 */
class RecoveryConfirmationForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Repeat password
     */
    public $repassword;

    /**
     * @var string Confirmation token
     */
    public $access_token;

    /**
     * @var \common\modules\users\models\User User instance
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
            ['access_token', 'string', 'max' => 64],
            [
                'access_token',
                'exist',
                'targetClass' => User::className(),
                'filter' => function ($query) {
                        $query->active();
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
            return ($this->_user = User::findIdentityByAccessToken($this->secure_key)) !== null;
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
