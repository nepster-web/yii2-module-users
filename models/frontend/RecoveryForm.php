<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
use Yii;

/**
 * Class RecoveryForm
 */
class RecoveryForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $email;

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
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'string', 'max' => 100],
            [
                'email',
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
            'email' => Yii::t('users', 'EMAIL')
        ];
    }    
    
    /**
     * @return false OR \nepster\users\models\User
     */
    public function recovery()
    {
        $this->_model = User::findByEmail($this->email, 'active');

        if ($this->_model !== null) {
            $this->_model->generateSecureKey();
            if($this->_model->save(false)) {
                return $this->_model;
            }            
        }
        return false;
    }
}
