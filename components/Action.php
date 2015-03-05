<?php

namespace nepster\users\components;

use nepster\users\models as models;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\db\Query;
use Yii;


/**
 * Компонент сохраняющий действия пользователя
 *
 * Сохранить действие
 * Yii::$app->userAction
 *      ->app('frontend')
 *      ->module($this->module->id)
 *      ->action('auth')
 *      ->user($userId)
 *      ->data([])
 *      ->save();
 *
 * Кол-во действий
 * $ban = Yii::$app->userAction
 *      ->action('auth-error')
 *      //->time('')
 *      ->count();
 */
class Action extends Component
{
    /**
     * @var string
     */
    public $connection= 'db';

    /**
     * @var string
     */
    private $_app;

    /**
     * @var string
     */
    private $_module;

    /**
     * @var string
     */
    private $_action;

    /**
     * @var int
     */
    private $_userId;

    /**
     * @var array
     */
    private $_data;

    /**
     * @var int
     */
    private $_time;

    /**
     * @param string $module
     * @return $this
     */
    public function module($module)
    {
        $this->_module = $module;
        return $this;
    }

    /**
     * @param string $app
     * @return $this
     */
    public function app($app)
    {
        $this->_app = $app;
        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function action($action)
    {
        if (!in_array($action, array_keys(models\Action::getTypeArray()))) {
            throw new InvalidParamException("Action {$action} not found");
        }
        $this->_action = $action;
        return $this;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function user($userId)
    {
        $this->_userId = $userId;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function data(array $data = [])
    {
        $this->_data = Json::encode($data);
        return $this;
    }

    /**
     * @param int $time
     * @return $this
     */
    public function time($time)
    {
        $this->_time = (int)$time;
        return $this;
    }

    /**
     * Сохранить
     */
    public function save()
    {
        if (!$this->_module) {
            throw new InvalidParamException("Module can not be empty");
        }

        if (!$this->_action) {
            throw new InvalidParamException("Action can not be empty");
        }

        $model = new models\Action();
        $model->application = $this->_app ? $this->_app : null;
        $model->module = $this->_module;
        $model->action = $this->_action;
        $model->user_id = $this->_userId ? $this->_userId : null;
        $model->data = $this->_data ? $this->_data : null;
        return $model->save(false);
    }

    /**
     * Верификация бана
     * @param bool $ip
     * @return array
     */
    public function verifyBan($ip = true)
    {
        $model = models\Ban::find()
            ->where('time < :time', [':time' => time()]);

        if ($this->_userId) {
            $model->andWhere('user_id = :user_id', [':user_id' => $this->_userId]);
        }

        if ($ip) {
            if (is_bool($ip) && !Yii::$app instanceof \yii\console\Application) {
                $ip = Yii::$app->request->userIP;
            }
            $model->andWhere('ip = :ip', [':ip' => $ip]);
        }

        return $model->asArray()->one();
    }

    /**
     * Заблокировать пользователя
     * @param bool $ip
     * @return array
     */
    public function ban($reason = null, $ip = true)
    {
        if (!$this->_time) {
            throw new InvalidParamException("Time can not be empty");
        }

        $model = new models\Ban();
        $model->time = $this->_time;
        $model->reason = $reason;
        $model->user_id = $this->_userId ? $this->_userId : null;
        return $model->save(false);
    }

}