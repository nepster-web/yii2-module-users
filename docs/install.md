# Установка

Предпочтительный способ установки через [composer](http://getcomposer.org/download/).

Запустите в консоле

```
php composer.phar require --prefer-dist nepster-web/yii2-module-users "dev-master"
```

или добавьте

```
"nepster-web/yii2-module-users": "dev-master"
```

в файл `composer.json` в секцию require.


Для установки модуля пользователей необходимо сконфигурировать Ваше консольное приложение, добавив настройки расширения [yii2-extensions-installer](https://github.com/nepster-web/yii2-extensions-installer).

```
'controllerMap' => [
    ...
    'installer' => [
        'class' => 'nepster\modules\installer\Installer',
    ]
    ...
],
```


**Далее запустить в консоле**

```
yii installer
```

и следуйте дальнейшим инструкциям.


# Настройка

После того как модуль успешно установлен внесите соответствующую конфигурацию.

**Сконфигурируйте консольное приложение:**

```
'modules' => [
...
    'users' => [
        'class' => 'common\modules\users\Module',
        'controllerMap' => [
            'control' => [
                'class' => 'nepster\users\commands\ControlController',
                'mailViewPath' => '@common/modules/users/mails/',
            ]
        ],
    ],
...
],
```

**Сконфигурируйте основное приложение:**

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
        'class' => 'nepster\users\components\User',
        'identityClass' => 'common\modules\users\models\User',
        'enableAutoLogin' => true,
        'absoluteAuthTimeout' => 31536000,  // сессия живет 365 дней
        'loginUrl' => ['/users/guest/login'],
    ],
]
```


> **Обратите внимание:** Указанные в примерах конфигурации неймспейсы могут отличаться, поэтому не забудьте указать правильные пути к классам.


# Консольные команды

**Импортируйте стандартные группы (role) и права доступа (permission):**

```
    php yii users/control/rbac
```

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