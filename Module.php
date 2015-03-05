<?php

namespace nepster\users;

use Yii;

/**
 * Module [[Users]]
 */
class Module extends \yii\base\Module
{
    /**
     * @var string Роль пользователя по умолчанию
     */
    public $defaultRole = 'user';

    /**
     * @var array Доступ ролей в панель управления
     */
    public $accessRoleToControlpanel = ['admin'];

    /**
     * @var int Кол-во попыток авторизации
     */
    public $amountAttemptsAuth = 5;

    /**
     * Сколько раз пользователь может неправильно ввести логин и пароль
     * прежде чем попасть в бан
     * @var int Интервал времени для попыток авторизации
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
     * @var Path Путь к папке с аватарками пользователей
     */
    public $avatarPath = '@statics/web/users/avatars';

    /**
     * @var Avatars Адрес папки на сайте с аватарками пользователей
     */
    public $avatarUrl = '/statics/users/avatars';
}
