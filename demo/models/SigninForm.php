<?php

namespace app\modules\users\models;

use Yii;

/**
 * Class SigninForm
 */
class SigninForm extends \nepster\users\models\SigninForm
{
    /**
     * @var \nepster\users\models\User
     */
    protected static $_user;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        return array_merge($labels, [
            'username' => Yii::t('users', 'MAIL_OR_PHONE'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        parent::beforeValidate();

        self::$_user = $this->getUser($this->username);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $userId = self::$_user ? self::$_user->id : null;
        if (!$this->getErrors()) {
            // Проверить заблокирован пользователь или нет
            $verifyBan = Yii::$app->userAction
                ->user($userId)
                ->verifyBan(false);

            if ($verifyBan) {
                $this->addError('password', Yii::t('users', 'YOU_HAVE_BEEN_BANNED {time}', [
                    'time' =>  Yii::$app->formatter->asDate($verifyBan['time'], 'd MMMM yyyy h:mm')
                ]));
            } else {
                Yii::$app->userAction
                    ->module($this->module->id)
                    ->action('auth')
                    ->user($userId)
                    ->save();
            }
        } else {
            $data = [
                'attributes' => $this->getAttributes(),
                'errors' => $this->getErrors(),
            ];
            Yii::$app->userAction
                ->module($this->module->id)
                ->action('auth-error')
                ->user($userId)
                ->data($data)
                ->save();
        }
        return true;
    }

    /**
     * Поиск пользователя
     * @return \nepster\users\models\User
     */
    public function getUser($username)
    {
        $scope = $this->scenario == 'admin' ? 'control' : null;

        /*
        $validator = new \yii\validators\EmailValidator();

        // Поиск пользователя по e-mail
        if ($validator->validate($username)) {
            return User::findByEmail($username, $scope);
        }
        // Поиск пользователя по телефону
        else if(strncasecmp($username, "+", 1) === 0) {
            $username = str_replace('+', '', $username);
            return User::findByPhone($username, $scope);
        }

        return User::findByUsername($username, $scope);
        */

        // Поиск пользователя по телефону
        if(strncasecmp($username, "+", 1) === 0) {
            $username = str_replace('+', '', $username);
            return User::findByPhone($username, $scope);
        }
        else {
            return User::findByEmail($username, $scope);
        }
    }
}
