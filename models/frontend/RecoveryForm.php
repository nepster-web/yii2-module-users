<?php

namespace nepster\users\models\frontend;

use nepster\users\traits\ModuleTrait;
use nepster\users\models\User;
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
    private $_user;

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
            'email' => Yii::t('users', 'EMAIL')
        ];
    }

    /**
     * @return false OR \nepster\users\models\User
     */
    public function recovery()
    {
        $this->_user = User::findByEmail($this->email, ['status']);

        if ($this->_user !== null) {
            $this->_user->generateSecureKey();
            if ($this->_user->save(false)) {
                return $this->_user;
            }
        }
        return false;
    }
}
