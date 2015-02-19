Базовый модуль пользователей Yii2
---------------------------------

## Особенности

* Обеспечивает простой и расширяемый функционал для приложения.
* Базовые возможности:
    * Регистрация
    * Авторизация
    * Восстановление пароля
    * Активация аккаунта
        * E-MAIL
        * SMS
    * Удобная расширяемая архитектура
    * Базовый набор функций для управления
* Вход используя логин и/или почтовый адрес и/или телефон
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

**После успешной установки пакета:**

Необходимо добавить следующий код в конфигурацию консольного приложения:

```php
    'modules' => [
        ...
        'users' => [
            'class' => 'nepster\users\Module',
            'controllerMap' => [
                'install' => 'nepster\users\commands\InstallController',
            ],
        ],
        ...
    ],
```

Теперь установите демонстрационные данные модуля:
```
yii users/install
```

Можно использовать следующие ключи:

--from = "@vendor/nepster-web/yii2-module-users/demo";

--to = "@common/modules/users";

--namespace = "common\\modules\\users";

--extendsController = "yii\\base\\Controller";


Далее сконфигурируйте приложение, в которое установили данные модуля:

```php
	'modules' => [
		...
        'users' => [
            'class' => 'common\modules\users\Module',
            'controllerNamespace' => 'common\modules\users\controllers\frontend',
        ],
        ...
    ],
```