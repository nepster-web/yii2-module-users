# Блокировка пользователей

Когда возникает необходимость блокировать пользователей, мы можем использовать определенные методы, которые вызываются через компонент Yii::$app->user.

Заблокировать пользователя можно по идентификатору (блокируется аккаунт) или по ip адресу.

> **Внимание:** можно заблокировать сразу несколько ip адресов (подсеть).


**В Yii::$app->user доступны следующие методы:**

```
isBanned($userId = null, $ip = null) - Проверяет заблокирован ли текущий пользователь

bannedByUser($userId, $time = null, $reason = null) - Заблокировать аккаунт пользователя

bannedByIp($ip_network, $time, $reason = null) - Заблокировать ip адрес или подсеть

reBannedByUser($userId) - Разблокировать аккаунт пользователя

reBannedByIp($ip) - Разблокировать ip адрес
```

**Пример:**
```
    Yii::$app->user->bannedByUser($userId, $time, $reason);
```

 * $userId - идентификатор пользователя 
 * $time - время окончания блокировки в секундах
 * $reason - причина блокировки

После выполнения данного метода, пользователь с $userId будет заблокирован до $time, а при попытки авторизоваться увидит сообщение о блокировке и $reason.