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
     * @param $ip
     * @return array|bool|null|ActiveRecord
     */
    public static function isBannedByIp($ip)
    {
        $banned = self::find()
            ->where('time_banned > :time', [':time' => time()])
            ->andWhere('(:ip & ip_mask) = ip_network', [':ip' => ip2long($ip)])
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
    public static function saveRecordByUser($userId, $time = null, $reason = null)
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
    public static function saveRecordByIp($ip_network, $time = null, $reason = null)
    {
        //TODO правильно сгенерировать маску для блокировки подсетей
        $ip_mask = '255.255.255.255';
        // $ip_mask = '255.255.255.0';
        // $ip_mask = '255.255.0.0';
        // $ip_mask = '255.0.0.0';
        return self::saveRecord(null, null, $time, $reason, $ip_network, $ip_mask);
    }

    /**
     * Разблокировать пользователя
     * Для истории запись о блокировке сохраняется, но при этом меняется дата
     * истечения бана на текущую.
     * @param $userId
     * @return bool
     */
    public static function reBannedByUser($userId)
    {
        if ($banned = self::isBannedByUser($userId)) {
            $banned->time_banned = time();
            return $banned->save(false);
        }

        return true;
    }

    /**
     * Разблокировать ip адрес
     * IP адрес может быть заблокирован 2 способами:
     *  - на прямую
     *  - под фильтром блокировки подсети
     * Поэтому в данном методе проверяем блокировку ip
     * на прямую, чтобы не разблокировать всю подсеть
     * @param $ip
     * @return bool
     */
    public static function reBannedByIp($ip)
    {
        $banned = self::find()
            ->where('time_banned > :time', [':time' => time()])
            ->andWhere('ip = :ip', [':ip' => ip2long($ip)])
            ->one();

        if ($banned) {
            $banned->time_banned = time();
            return $banned->save(false);
        }

        return true;
    }

    /**
     * Сохранить запись о блокировке пользователя
     *
     * @param $userId
     * @param $ip
     * @param $time
     * @param null $reason
     * @param null $ip_network
     * @param null $ip_mask
     * @return bool
     * @throws \yii\db\Exception
     */
    protected static function saveRecord($userId, $ip, $time = null, $reason = null, $ip_network = null, $ip_mask = null)
    {
        $transaction = self::getDb()->beginTransaction();

        try {
            // Проверяем информацию о блокировке
            $oldBannedInfo = self::find()
                ->where('user_id = :user_id OR ip = :ip', [':user_id' => $userId, ':ip' => $ip])
                ->andWhere('time_banned >= :time', [':time' => time()])
                ->orderBy(['time_banned' => SORT_DESC])
                ->one();

            if ($oldBannedInfo) {
                $model = $oldBannedInfo;
                $model->reason = $reason;
            } else {
                $model = new self;
                $model->user_id = $userId;
                $model->ip = $ip ? ip2long($ip) : null;
                $model->reason = $reason;
                $model->ip_network = ip2long($ip_network);
                $model->ip_mask = ip2long($ip_mask);
            }

            $model->time_banned = $time ? $time : time() + self::getModule()->params['intervalDefaultTimeBan'];
            $model->save(false);

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

}