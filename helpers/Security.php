<?php

namespace nepster\users\helpers;

use Yii;

/**
 * Class Security
 */
class Security
{
    /**
     * Генерирует случайный секретный ключ с временным суффиксом
     * @return string Random key
     */
    public static function generateExpiringRandomString()
    {
        return Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Проверить истек ли срок жизни секретного ключа
     *
     * @param string $token
     * @param integer $duration
     * @return boolean true если токен не исчек
     */
    public static function isValidToken($token, $duration)
    {
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return ($timestamp + $duration > time());
    }
}
