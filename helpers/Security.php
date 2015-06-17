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
     * @return string
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
     * @return boolean
     */
    public static function isValidToken($token, $duration)
    {
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return ($timestamp + $duration > time());
    }
}
