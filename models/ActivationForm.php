<?php

namespace nepster\users\models;

use users\traits\ModuleTrait;
use Yii;

/**
 * Class ActivationForm
 */
class ActivationForm extends \yii\base\Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $secure_key;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['secure_key', 'required'],
            ['secure_key', 'trim'],
            ['secure_key', 'string', 'max' => 53],
            ['secure_key', 'exist', 'targetClass' => User::className(),
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
            'secure_key' => Yii::t('users', 'SECURE_KEY')
        ];
    }

    /**
     * @return boolean
     */
    public function activation()
    {
        $model = User::findIdentityByAccessToken($this->secure_key);
        if ($model !== null) {
            return $model->mailVerification();
        }
        return false;
    }
    
}
