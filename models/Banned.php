<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Banned
 */
class Banned extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_banned}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('users', 'USER_ID'),
            'reason' => Yii::t('users', 'REASON'),
            'ip' => Yii::t('users', 'IP'),
            'time_banned' => Yii::t('users', 'TIME_BANNED'),
            'ip_network' => Yii::t('users', 'IP_NETWORK'),
            'ip_mask' => Yii::t('users', 'IP_MASK'),
        ];
    }

    /**
     * Проверяет, заблокирован ли пользователь
     * Если пользователь заблокирован, возвращает массив
     * с причиной и датой блокировки
     * @param $userId
     * @return array|bool
     */
    public static function isBannedByUser($userId)
    {
        $banned = self::find()
            ->where('time_banned > :time', [':time' => time()])
            ->andWhere('user_id = :user_id', [':user_id' => $userId])
            ->asArray()
            ->one();

        if ($banned) {
            return $banned;
        }

        return false;
    }

    /**
     * Проверяет, заблокирован ли ip адрес пользователя
     * Если пользователь заблокирован, возвращает массив
     * с причиной и датой блокировки
     *
     * Производится побитное сравнение ip, маски и сети.
     * Все ip адреса должны быть пропущены через функцию ip2long
     *
     * Пример:
     * Сеть: ip2long("192.168.0.0");
     * Маска: ip2long("255.255.0.0");
     *
     * (IP & IP_MASK) = IP_NETWORK
     *
     * @param $userIp
     * @return array|bool|null|ActiveRecord
     */
    public static function isBannedByIp($userIp)
    {
        $banned = self::find()
            ->where('time_banned > :time', [':time' => time()])
            ->andWhere('(:ip & ip_mask) = ip_network', [':ip' => ip2long($userIp)])
            ->asArray()
            ->one();

        if ($banned) {
            return $banned;
        }

        return false;
    }

    /**
     * Заблокировать аккаунт пользователя
     * @param $userId
     * @param $time
     * @param null $reason
     * @return bool
     */
    public static function saveRecordByUser($userId, $time, $reason = null)
    {
        return self::saveRecord($userId, null, $time, $reason);
    }

    /**
     * Заблокировать ip адрес пользователя
     * @param $ip_network
     * @param $time
     * @param null $reason
     * @return bool
     */
    public static function saveRecordByIp($ip_network, $time, $reason = null)
    {
        //TODO правильно сгенерировать маску для блокировки подсетей
        $ip_mask = '255.255.255.255';
        // $ip_mask = '255.255.255.0';
        // $ip_mask = '255.255.0.0';
        // $ip_mask = '255.0.0.0';
        return self::saveRecord(null, null, $time, $reason, $ip_network, $ip_mask);
    }

    /**
     * Сохранить запись о блокировке пользователя
     * @param $userId
     * @param $ip
     * @param $time
     * @param null $reason
     * @param null $ip_network
     * @param null $ip_mask
     * @return bool
     * @throws \yii\db\Exception
     */
    protected static function saveRecord($userId, $ip, $time, $reason = null, $ip_network = null, $ip_mask = null)
    {
        $transaction = self::getDb()->beginTransaction();

        try {
            $model = new self;
            $model->user_id = $userId;
            $model->ip = ip2long($ip);
            $model->time_banned = $time;
            $model->reason = $reason;
            $model->ip_network = ip2long($ip_network);
            $model->ip_mask = ip2long($ip_mask);
            $model->save(false);

            $transaction->commit();
            return true;

        } catch (\Exception $e) {

            echo $e->getMessage();
            $transaction->rollBack();
            return false;
        }
    }

}