# Модуль пользователей для Yii2

Модуль обеспечивает простой и расширяемый функционал для работы с пользователями в приложении. 
Поддерживает следующие возможности:

* Регистрация
* Авторизация
* Активация аккаунта
* Восстановление пароля
* Изменение пароля
* Компонент фиксирующий действия пользователей
* Блокировка пользователей
* Мультиязычность
* Гибкая настройка и расширяемость
* Установка демонстрационных данных для быстрой работы


> **NOTE:** Модуль находится в стадии разработки.


## Установка

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


## Документация

[Подробная установка и настройка](docs/install.md) | [Полное руководство](docs/README.md)


## Зависимости

Текущий модуль, устанавливает следующие пакеты:

 * [yii2-authclient](https://github.com/yiisoft/yii2-authclient)
 * [yii2-extensions-installer](https://github.com/nepster-web/yii2-extensions-installer)
 * [yii2-console-runner-extension](https://github.com/vova07/yii2-console-runner-extension)
 * [yii2-swiftmailer](https://github.com/yiisoft/yii2-swiftmailer)
 

## Лицензия

Данный модуль выпущен под лицензией MIT. Подробную информацию читайте в файле [LICENSE.md](LICENSE.md).