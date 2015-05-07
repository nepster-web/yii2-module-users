# Установка модуля пользователей



Поэтому прежде чем перейти к установке модуля пользователей необходимо сконфигурировать консольное приложение добавить настройки вышеуказанных расширений.


Далее необходимо запустить инсталлер и установить модуль следуя инструкциям:

```
yii installer
```

**После того как модуль успешно установлен:**

Добавьте настройки в консольное приложение:
```
'modules' => [
...
    'users' => [
        'class' => 'common\modules\users\Module',
        'controllerMap' => [
            'send' => [
                'class' => 'nepster\users\commands\SendController',
                'mailViewPath' => '@common/modules/users/mails/',
            ]
        ],
    ],
...
],
```

Сконфигурируйте Ваше основное приложение (например frontend):

```
'bootstrap' => [
...
    'common\modules\users\Bootstrap',
]
```

```
'modules' => [
    ...
    'users' => [
        'class' => 'common\modules\users\Module',
        'controllerNamespace' => 'common\modules\users\controllers\frontend',
    ],
]
```

```
'components' => [
    ...
    'user' => [
        'class' => 'yii\web\User',
        'identityClass' => 'common\modules\users\models\User',
        'enableAutoLogin' => true,
        'absoluteAuthTimeout' => 31536000,  // сессия живет 365 дней
        'loginUrl' => ['/users/guest/login'],
    ],
    'userAction' => [
        'class' => 'nepster\users\components\Action',
    ],
]
```

Обратите внимание, указанные в примерах конфигурации неймспейсы могут отличаться, поэтому не забудьте указать правильные пути к классам.


**Теперь необходимо выполнить миграции:**

```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations
```

**Модуль обладает дополнительными расширениями миграций, которые позволяют добавить следующие возможности:**

Добавить поле для логина:
```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations/extensions/username
```

Добавить таблицу сессий:
```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations/extensions/session
```

Добавить таблицу данных для юридических лиц:
```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations/extensions/legal_person
```