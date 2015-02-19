<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
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
    private $_model;

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
                        $query->mailUnverified();
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
        $this->_model = User::findByEmail($this->email, 'inactive');
        
        if ($this->_model !== null) {
            $this->_model->generateSecureKey();
            if($this->_model->save(false)) {
                return $this->_model;
            }            
        }
        return false;
    }
}
