<?php

namespace nepster\users;

use Yii;

/**
 * Module [[Users]]
 */
class Module extends \yii\base\Module
{
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
