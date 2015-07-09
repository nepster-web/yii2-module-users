# Действия пользователей

Когда возникает необходимость фиксировать различные действия пользователей, мы можем использовать метод **action**, который вызывается через компонент Yii::$app->user.

К примеру необходимо обезопасить приложение от подбора паролей и блокировать IP адрес пользователя после n неуспешных попыток авторизации или логировать действия в админ панеле. 


Для этого после определенного действия необходимо вызвать:

```
    Yii::$app->user->action($userId, $module, $action, $data);
```

 * $userId - идентификатор пользователя 
 * $module - модуль, через который было совершено действие 
 * $action - идентификатор действия (например error-auth)
 * $data - данные, если необходимо (иногда бывает полезно сохранить данные, которые были изменены. После случайного изменения или удаления, их можно будет восстановить.)


Действие будет записано в таблицу {{users_actions}}.


**Для работы с моделями можно использовать поведение:**

```
    public function behaviors()
    {
        return [
            'ActionBehavior' => [
                'class' => 'nepster\users\behaviors\ActionBehavior',
                'module' => 'site',
                'actions' => [
                    ActiveRecord::EVENT_AFTER_INSERT => 'create-record',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'update-record',
                    ActiveRecord::EVENT_AFTER_DELETE => 'delete-record',
                ],
            ],
        ];
    }
```


**Расширенный пример для удаления записей:**

```
public function behaviors()
{
    return [
        'ActionBehavior' => [
            'class' => 'nepster\users\behaviors\ActionBehavior',
            'module' => 'site',
            'actions' => [
                ActiveRecord::EVENT_BEFORE_INSERT => 'create-record',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'update-record',
                ActiveRecord::EVENT_BEFORE_DELETE => [
                    'action' => 'delete-record',
                    'dependencies' => [
                        'relation1',
                        'relation2'
                    ]
                ],
            ],
        ],
    ];
}
```

В ситуации, когда удаление связанных записей происходит по внешним ключам, можно определить зависимости.
Тогда перед удаление записи будет собран общий массив с данными указанных реляций и записан в таблицу действий.