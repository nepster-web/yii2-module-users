<?php

namespace nepster\users\components;

use nepster\users\traits\ModuleTrait;
use nepster\users\models\Action;
use nepster\users\models\Banned;
use Yii;

/**
 * Class User
 */
class User extends \yii\web\User
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public function afterLogin($identity, $cookieBased, $duration)
    {
        parent::afterLogin($identity, $cookieBased, $duration);

        // обновляем время авторизации и статус online
        if ($identity && $identity instanceof \common\modules\users\models\User) {
            $identity->time_activity = time();
            $identity->online = 1;
            $identity->save(false);
        }

        // Авторизация с поддомена по кукам
        if (Yii::$app->id == 'app-backend') {
            //TODO: проверить права доступа
            // echo 'Авторизация с поддомена по кукам';
            // die();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterLogout($identity)
    {
        parent::afterLogout($identity);

        // обновляем время авторизации и статус online
        if ($identity && $identity instanceof \common\modules\users\models\User) {
            $identity->time_activity = time();
            $identity->online = 0;
            $identity->save(false);
        }
    }

    /**
     * Записать действие пользователя в историю
     * @param $userId
     * @param $module
     * @param $action
     * @param array $data
     * @return bool
     */
    public function action($userId, $module, $action, $data = [])
    {
        return Action::saveRecord($userId, $module, $action, $data);
    }

    /**
     * Проверяет заблокирован ли текущий пользователь
     * @param null $userId
     * @param null $ip
     * @return false or array
     */
    public function isBanned($userId = null, $ip = null)
    {
        if (!$this->isGuest || $userId) {
            if ($banned = Banned::isBannedByUser($userId)) {
                return $banned;
            }
        }

        if (!$ip) {
            $ip = Yii::$app->request->userIP;
        }

        return Banned::isBannedByIp($ip);
    }

    /**
     * Заблокировать аккаунт пользователя
     * @param $userId
     * @param $time
     * @param null $reason
     * @return mixed
     */
    public function bannedByUser($userId, $time, $reason = null)
    {
        return Banned::saveRecordByUser($userId, $time, $reason);
    }

    /**
     * Заблокировать ip адрес или подсеть
     * @param $ip_network
     * @param $time
     * @param null $reason
     * @return mixed
     */
    public function bannedByIp($ip_network, $time, $reason = null)
    {
        return Banned::saveRecordByIp($ip_network, $time, $reason);
    }

    /**
     * Вроверить дополнительные условия для авторизации
     * @param null $userId
     * @return bool
     */
    public function verifyAuthCondition($userId)
    {
        $actions = Action::getRecords($userId, 'auth-error', $this->module->params['intervalAuthBan'], true);

        if ($actions >= $this->module->params['amountAttemptsAuth']) {
            return false;
        }

        return true;
    }

}