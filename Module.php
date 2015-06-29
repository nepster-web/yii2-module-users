<?php

namespace nepster\users;

use Yii;

/**
 * Module [[Users]]
 */
class Module extends \yii\base\Module
{
    /**
     * Группа пользователя по умолчанию, которая будет присвоена при регистрации
     */
    public $defaultGroup = 'user';

    /**
     * Группы по умолчанию (нельзя удалять и редактировать)
     */
    public $defaultGroups = ['admin', 'user'];

    /**
     * Доступ ролей в панель управления
     */
    public $accessGroupsToControlpanel = ['admin'];

    /**
     * Кол-во попыток неправильной авторизации
     */
    public $amountAttemptsAuth = 10;

    /**
     * Интервал времени на которое будет заблокирован пользователь
     */
    public $allowedTimeAttemptsAuth = 600; // 10 минут

    /**
     * Интервал времени на который блокировать пользователя по умолчанию
     */
    public $intervalDefaultTimeBan = 31536000; // 365 дней

    /**
     * Интервал времени на который блокировать пользователя
     */
    public $intervalAuthBan = 900; // 15 минут

    /**
     * Интервал времени бездействия, после которого считается, что пользователь покинул сайт
     */
    public $intervalInactivityForOnline = 600; // 10 минут

    /**
     * Обязательное подтверждение почтового адреса после регистрации
     */
    public $requireEmailConfirmation = true;

    /**
     * Обязательное подтверждение телефона после регистрации
     */
    public $requirePhoneConfirmation = false;

    /**
     * Время, по истечению которого секретный ключ активации становится недействительным
     */
    public $activationWithin = 86400; // 24 hours

    /**
     * Время, по истечению которого секретный ключ восстановления пароля становится недействительным
     */
    public $recoveryWithin = 14400; // 4 hours

    /**
     * Кол-во пользователей, которое необходимо выводить на страницу
     */
    public $recordsPerPage = 10;

    /**
     * Путь к временной директории с аватарками пользователей
     */
    public $avatarsTempPath = '@statics/temp/users/avatars';

    /**
     * Путь к директории с аватарками пользователей
     */
    public $avatarPath = '@statics/web/users/avatars';

    /**
     * Адрес директории на сайте с аватарками пользователей
     */
    public $avatarUrl = '/statics/users/avatars';

    /**
     * @inheritdoc
     */
    // TODO: Переопределение текущей конфигурации конфигурацией из базы данных
    /**
     Переопределние значений конфигурации
    public function init()
    {
        parent::init();

        $reflect = new \ReflectionClass(get_called_class());
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            $property = $prop->getName();
            $this->$property = 'Значение';
        }
    }*/
}