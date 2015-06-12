<?php

namespace nepster\users;

use Yii;

/**
 * Module [[Users]]
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /**
         * Группа пользователя по умолчанию
         */
        $this->params['defaultGroup'] = 'user';

        /**
         * Доступ ролей в панель управления
         */
        $this->params['accessGroupsToControlpanel'] = ['admin'];

        /**
         * Кол-во попыток неправильной авторизации
         */
        $this->params['amountAttemptsAuth'] = 10;

        /**
         * Интервал времени на которое будет заблокирован пользователь
         */
        $this->params['allowedTimeAttemptsAuth'] = 600; // 10 минут

        /**
         * Интервал времени на который блокировать пользователя
         */
        $this->params['intervalAuthBan'] = 900; // 15 минут

        /**
         * Интервал времени бездействия, после которого считается, что пользователь покинул сайт
         */
        $this->params['intervalInactivityForOnline'] = 600; // 10 минут

        /**
         * Обязательное подтверждение почтового адреса после регистрации
         */
        $this->params['requireEmailConfirmation'] = true;

        /**
         * Обязательное подтверждение телефона после регистрации
         */
        $this->params['requirePhoneConfirmation'] = false;

        /**
         * Время, по истечению которого секретный ключ активации становится недействительным
         */
        $this->params['activationWithin'] = 86400; // 24 hours

        /**
         * Время, по истечению которого секретный ключ восстановления пароля становится недействительным
         */
        $this->params['recoveryWithin'] = 14400; // 4 hours

        /**
         * Кол-во пользователей, котое необходимо выводить на страницу
         */
        $this->params['recordsPerPage'] = 10;

        /**
         * Путь к временной папке с аватарками пользователей
         */
        $this->params['avatarsTempPath'] = '@statics/temp/users/avatars';

        /**
         * Путь к папке с аватарками пользователей
         */
        $this->params['avatarPath'] = '@statics/web/users/avatars';

        /**
         * Адрес папки на сайте с аватарками пользователей
         */
        $this->params['avatarUrl'] = '/statics/users/avatars';
    }
}