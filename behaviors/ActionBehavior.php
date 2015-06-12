<?php

namespace nepster\users\behaviors;

use nepster\users\models\Action;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\base\Behavior;
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
 *                   ActiveRecord::EVENT_AFTER_INSERT => 'create-record',
 *                   ActiveRecord::EVENT_AFTER_UPDATE => 'update-record',
 *                   ActiveRecord::EVENT_AFTER_DELETE => 'delete-record',
 *               ],
 *           ],
 *       ];
 *   }
 */
class ActionBehavior extends Behavior
{
    /**
     * @var string Название модуля
     */
    public $module;

    /**
     * @var array Название действия для различных событий модели
     *
     * ```php
     * [
     *     ActiveRecord::EVENT_AFTER_INSERT => 'create_record',
     *     ActiveRecord::EVENT_AFTER_UPDATE => 'update_record',
     *     ActiveRecord::EVENT_AFTER_DELETE => 'delete_record',
     * ]
     * ```
     */
    public $actions = [];

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
        if (!empty($this->actions[$event->name])) {
            $action = $this->actions[$event->name];
            $data = [
                'old-attributes' => $event->sender->getOldAttributes(),
                'attributes' => $event->sender->getAttributes(),
            ];
            Action::saveRecord(Yii::$app->user->id, $this->module, $action, $data);
        }
    }
}
