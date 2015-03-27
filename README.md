--- В разработке


Базовый модуль пользователей Yii2
---------------------------------

## Особенности

**Модуль обеспечивает простой и расширяемый функционал для приложения, а так же поддерживает следующие возможности:**

* Регистрация
* Авторизация
* Активация аккаунта
* Восстановление пароля
* Изменение пароля
* Компонент фиксирующий действия пользователей
* Блокировка пользователей
* Мультиязычность
* Гибкая конфигурация
* Демонстрационные данные для быстрой работы


## Установка

Предпочтительный способ установки этого виджета через [composer](http://getcomposer.org/download/).

Запустите в консоле

```
php composer.phar require --prefer-dist nepster-web/yii2-module-users "dev-master"
```

или добавьте

```
"nepster-web/yii2-module-users": "dev-master"
```

в файл `composer.json` в секцию require.


## Настройка

Текущий модуль, зависит от следующих расширений:

 [yii2-extensions-installer](https://github.com/nepster-web/yii2-extensions-installer),
 [yii2-console-runner-extension](https://github.com/vova07/yii2-console-runner-extension) и
 [yii2-swiftmailer](https://github.com/yiisoft/yii2-swiftmailer). 


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
    'userAction' => [
        'class' => 'nepster\users\components\Action',
    ],
    'user' => [
        'identityClass' => 'common\modules\users\models\User',
        'enableAutoLogin' => true,
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