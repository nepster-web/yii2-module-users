<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use Yii;

/**
 * Class Action
 */
class Action extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_actions}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time_create',
                ],
            ],
        ];
    }

    /**
     * Типы действий
     * @return array
     */
    public static function getTypeArray()
    {
        return [
            'auth' => Yii::t('users', 'ACTION_AUTH'),
            'auth-error' => Yii::t('users', 'ACTION_AUTH_ERROR'),
        ];
    }

    /**
     * Запись действия пользователя
     * @param $userId
     * @param $application
     * @param $module
     * @param $action
     * @param array $data
     * @return bool
     */
    public static function saveRecord($userId, $application, $module, $action, array $data = [])
    {
        $model = new self;
        $model->user_id = $userId;
        $model->application = $application ? $application : 'frontend';
        $model->module = $module;
        $model->action = $action;
        $model->data = $data ? Json::encode($data) : null;
        $model->hash = md5(Yii::$app->request->userAgent . Yii::$app->request->userIP);
        $model->ip = ip2long(Yii::$app->request->userIP);
        $model->time_create = time();
        return $model->save(false);
    }

    /**
     * Вернуть записи о действиях или их количество
     * @param int $userId
     * @param string $action
     * @param int $interval
     * @param bool $count
     * @return array|int
     */
    public static function getRecords($userId = null, $action = null, $interval = null, $count = false)
    {
        $query = self::find();

        if ($userId) {
            $query->andWhere('user_id = :user_id', [':user_id' => $userId]);
        }

        if ($action) {
            $query->andWhere('action = :action', [':action' => $action]);
        }


        if ($interval) {
            $query->andWhere('time_create >= :time', [':time' => time() - $interval]);
        }

        $query->asArray();

        if ($count) {
            return $query->count();
        }

        return $query->all();
    }
}
