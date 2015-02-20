<?php

namespace common\modules\users\models;

use Yii;

/**
 * Class LoginForm
 */
class LoginForm extends \nepster\users\models\LoginForm implements \nepster\users\interfaces\LoginFormInterface
{
    /**
     * Поиск пользователя
     * @return \nepster\users\models\User
     */
    public static function getUser($username)
    {
        /*
        $validator = new \yii\validators\EmailValidator();

        // Поиск пользователя по e-mail
        if ($validator->validate($username)) {
            return User::findByEmail($username);
        }
        // Поиск пользователя по телефону
        else if(strncasecmp($username, "+", 1) === 0) {
            $username = str_replace('+', '', $username);
            return User::findByPhone($username);
        }

        return User::findByUsername($username);
        */

        // Поиск пользователя по телефону
        if(strncasecmp($username, "+", 1) === 0) {
            $username = str_replace('+', '', $username);
            return User::findByPhone($username);
        }
        else {
            return User::findByEmail($username);
        }
    }
}
