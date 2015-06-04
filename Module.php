<?php

namespace nepster\users;

use Yii;

/**
 * Module [[Users]]
 */
class Module extends \yii\base\Module
{
    //TODO: Разобраться с конфигурацией

    /**
     * @var bool Импортировать
     */
    public $importSettingsInFile = '@common/modules/users/config.php';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /**
         * Роль пользователя по умолчанию
         */
        $this->params['defaultRole'] = 'user';

        /**
         * Доступ ролей в панель управления
         */
        $this->params['accessRoleToControlpanel'] = ['admin'];

        /**
         * Кол-во попыток неправильной авторизации
         */
        $this->params['amountAttemptsAuth'] = 5;

        /**
         * Интервал времени на которое будет заблокирован пользователь
         */
        $this->params['allowedTimeAttemptsAuth'] = 600; // 10 минут

        /**
         * Интервал времени на который блокировать пользователя
         */
        $this->params['intervalAuthBan'] = 900; // 15 минут

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

        // $this->params = array_merge($this->params, require(Yii::getAlias($this->importSettingsInFile)));

        /*
        echo '<pre>';
        print_r($this->params);*/

    }


    /**
     * @var string Роль пользователя по умолчанию
     */
    public $defaultRole = 'user';

    /**
     * @var array Доступ ролей в панель управления
     */
    public $accessRoleToControlpanel = ['admin'];

    /**
     * @var int Кол-во попыток неправильной авторизации
     */
    public $amountAttemptsAuth = 5;

    /**
     * @var int Интервал времени на которое будет заблокирован пользователь
     */
    public $allowedTimeAttemptsAuth = 600; // 10 минут

    /**
     * @var int Интервал времени на который блокировать пользователя
     */
    public $intervalAuthBan = 900; // 15 минут

    /**
     * @var boolean Обязательное подтверждение почтового адреса после регистрации
     */
    public $requireEmailConfirmation = true;

    /**
     * @var boolean Обязательное подтверждение телефона после регистрации
     */
    public $requirePhoneConfirmation = false;

    /**
     * @var integer Время, по истечению которого секретный ключ активации становится недействительным
     */
    public $activationWithin = 86400; // 24 hours

    /**
     * @var integer Время, по истечению которого секретный ключ восстановления пароля становится недействительным
     */
    public $recoveryWithin = 14400; // 4 hours

    /**
     * @var integer Кол-во пользователей, котое необходимо выводить на страницу
     */
    public $recordsPerPage = 10;

    /**
     * @var string Путь к временной папке с аватарками пользователей
     */
    public $avatarsTempPath = '@statics/temp/users/avatars';

    /**
     * @var string Путь к папке с аватарками пользователей
     */
    public $avatarPath = '@statics/web/users/avatars';

    /**
     * @var string Адрес папки на сайте с аватарками пользователей
     */
    public $avatarUrl = '/statics/users/avatars';

}
