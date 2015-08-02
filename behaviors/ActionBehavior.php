<?php

namespace nepster\users\behaviors;

use nepster\users\models\Action;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\base\Behavior;
use yii\base\Model;
use yii\base\Event;
use Yii;

/**
 * Поведение фиксирует действия текущего пользователя в моделе
 *
 * Пример использования:
 *
 *   public function behaviors()
 *   {
 *       return [
 *           'ActionBehavior' => [
 *               'class' => 'nepster\users\behaviors\ActionBehavior',
 *               'module' => 'site',
 *               'actions' => [
 *                   ActiveRecord::EVENT_BEFORE_INSERT => 'create-record',
 *                   ActiveRecord::EVENT_BEFORE_UPDATE => 'update-record',
 *                   ActiveRecord::EVENT_BEFORE_DELETE => [
 *                      'action' => 'delete-user',
 *                      'dependencies' => [
 *                          'profile',
 *                          'person'
 *                      ]
 *                  ],
 *               ],
 *           ],
 *       ];
 *   }
 */
class ActionBehavior extends Behavior
{
    /**
     * Название модуля
     * @var string
     */
    public $module;

    /**
     * @var array Название действия для различных событий модели
     *
     * [
     *     ActiveRecord::EVENT_BEFORE_INSERT => 'create_record',
     *     ActiveRecord::EVENT_BEFORE_UPDATE => 'update_record',
     *     ActiveRecord::EVENT_BEFORE_DELETE => 'delete_record',
     * ]
     */
    public $actions = [];

    /**
     * Исключение свойств
     * Если если изменения в моделе будет только среди исключенных полей,
     * изменения не будут зафиксированы
     * @var array
     */
    public $exclude = ['time_activity'];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return array_fill_keys(array_keys($this->actions), 'action');
    }

    /**
     * Сохранить действие
     * @param Event $event
     */
    public function action($event)
    {
        $save = true;
        $data = [];

        if (!empty($this->actions[$event->name])) {


            if ($event->name == ActiveRecord::EVENT_BEFORE_DELETE) {

                $action = $this->actions[$event->name];

                if (is_array($this->actions[$event->name])) {

                    if (!isset($this->actions[$event->name]['action'])) {
                        throw new InvalidParamException('Param "action" must be in configuration array');
                    }

                    $action = $this->actions[$event->name]['action'];
                }

                $data = $this->deleteRecord($event);

            } else {

                if (is_array($this->actions[$event->name])) {
                    throw new InvalidParamException('Any action except "' . ActiveRecord::EVENT_BEFORE_DELETE . '" should not be an array');
                }

                $action = $this->actions[$event->name];
                $diff = array_diff($event->sender->getAttributes(), $event->sender->getOldAttributes());
                $diff = array_diff(array_keys($diff), $this->exclude);

                if ($diff) {
                    $data = [
                        'old-attributes' => $event->sender->getOldAttributes(),
                        'attributes' => $event->sender->getAttributes(),
                    ];
                } else {
                    $save = false;
                }
            }

            if ($save) {
                $userId = null;
                if (!Yii::$app instanceof \yii\console\Application) {
                    $userId = Yii::$app->user->id;
                }
                Action::saveRecord($userId, $this->module, $action, $data);
            }
        }
    }


    /**
     * Возвращает данные удаленной записи
     * @param $event
     * @return array
     */
    private function deleteRecord($event)
    {
        $data = [];

        if (is_array($this->actions[$event->name])) {

            if ($this->actions[$event->name]['dependencies']) {

                $dependencies = (array)$this->actions[$event->name]['dependencies'];

                foreach ($dependencies as $relation) {
                    if ($event->sender->$relation && $event->sender->$relation instanceof Model) {
                        $data[$relation] = [
                            'old-attributes' => $event->sender->$relation->getOldAttributes(),
                            'attributes' => $event->sender->$relation->getAttributes(),
                        ];
                    }
                }
            }

        } else {
            $data = [
                'old-attributes' => $event->sender->getOldAttributes(),
                'attributes' => $event->sender->getAttributes(),
            ];
        }

        return $data;
    }
}
