Базовый модуль пользователей Yii2
---------------------------------

## Особенности

* Обеспечивает простой и расширяемый функционал для приложения
* Базовые возможности:
    * Регистрация
    * Авторизация
    * Восстановление пароля
    * Активация аккаунта
        * E-MAIL
        * SMS
    * Удобная расширяемая архитектура
    * Базовый набор функций для управления
* Авторизация используя логин и/или почтовый адрес и/или телефон
* Простая и быстрая установка


## Установка

Предпочтительный способ установки этого виджета через [composer](http://getcomposer.org/download/).

Запустите в консоле

```
php composer.phar require nepster-web/yii2-module-users: dev-master
```

или добавьте

```
"nepster-web/yii2-module-users": "dev-master"
```

в файл `composer.json` в секцию require.


## Настройка

Текущий модуль, зависит от пакета [yii2-modules-installer](https://github.com/nepster-web/yii2-modules-installer). Поэтому прежде чем перейти к установке модуля пользователей
необходимо сконфигурировать консольное приложение следующим образом:

```
'controllerMap' => [
...
    'installer' => [
        'class' => 'nepster\modules\installer\Installer',
        'from' => "@vendor/nepster-web/yii2-module-{module}/demo",
        'to' => "@common/modules/{module}",
        'namespace' => "common\\modules\\{module}",
        'controller' => "yii\\base\\Controller",
    ]
...
],
```

Далее необходимо установить демонстрационные данные модуля:

```
yii users/install
```

После того как модуль успешно установлен, подключите модуль в Вашем приложении (например frontend):

```
'bootstrap' => [
...
    'common\modules\users\Bootstrap',
...
],
```

```
'modules' => [
    ...
    'users' => [
        'class' => 'common\modules\users\Module',
        'controllerNamespace' => 'common\modules\users\controllers\frontend',
    ],
    ...
],
```

Обратите внимание, указанные в примерах конфигурации нэймспейсы могут отличаться, так как при установке Вы могли указать другие.

Теперь необходимо выполнить миграции:

```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations
```

Модуль обладает дополнительными расширениями транзакций, которые позволяют добавить следующие возможности:

Добавить поле для логина:
```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations/extension/username
```

Добавить таблицу для данных юридических лиц:
```
yii migrate --migrationPath=@vendor/nepster-web/yii2-module-users/migrations/extension/legal_person
```