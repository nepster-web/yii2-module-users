# Консольные команды

**Отправить сообщение на EMAIL:**
```
php yii users/control/send-email [email] [view] [subject] [content]
```


**Отправить сообщение на несколько EMAIL:**
```
php yii users/control/multi-send-email [view] [subject] [content] [emails]
```


**Импортировать права доступа по умолчанию:**
*(Используется один раз)*
```
php yii users/control/rbac
```