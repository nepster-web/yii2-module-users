<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Блокировка пользователей
 */
class Banned extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ActionBehavior' => [
                'class' => 'nepster\users\behaviors\ActionBehavior',
                'module' => $this->module->id,
                'actions' => [
                    ActiveRecord::EVENT_AFTER_INSERT => 'create-banned',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'update-banned',
                    ActiveRecord::EVENT_AFTER_DELETE => 'delete-banned',
                ],
            ],
        ];
    }

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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Время блокировки
            ['time_banned', 'required'],
            ['time_banned', 'validateTime'],

            // Причина
            ['reason', 'string'],
            ['reason', 'trim'],
        ];
    }

    /**
     * Валидации времени до которого будет заблокирован пользователь
     * @param $attribute
     */
    public function validateTime($attribute)
    {
        $DateValidator = new \yii\validators\DateValidator(['format' => 'php:Y-i-d']);
        $DateTimeValidator = new \yii\validators\DateValidator(['format' => 'php:Y-m-d H:i:s']);

        if (!$DateValidator->validate($this->time_banned) && !$DateTimeValidator->validate($this->time_banned) && !$DateTimeValidator->validate($this->time_banned . ':00')) {
            $this->addError($attribute, 'Не правильная дата');
        }
    }

    /**
     * Заблокировать пользователей
     * @param array $models
     * @return int
     */
    public function bannedUsers(array $models)
    {
        $count = 0;

        $reason = $this->reason;
        $time = strtotime($this->time_banned);

        foreach ($models as $model) {
            if ($model instanceof User) {
                if (Yii::$app->user->bannedByUser($model->id, $time, $reason)) {
                    ++$count;
                }
            }
        }

        return $count;
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
                $model->ip_network = $ip_network ? ip2long($ip_network) : null;
                $model->ip_mask = $ip_mask ? ip2long($ip_mask) : null;
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