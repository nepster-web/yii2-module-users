# Переводы

Текущий подуль поддерживает [i18n](https://github.com/yiisoft/yii2/blob/master/docs/guide-ru/tutorial-i18n.md), 
файлы переводов находятся в директории vendor/nepster-web/yii2-module-users/messages.


**Подключение происходит в файле Bootstrap.php:**

```
    // Register translations
    $app->i18n->translations['users*'] = [
        'class' => 'yii\i18n\PhpMessageSource',
        'sourceLanguage' => 'en-US',
        'basePath' => '@nepster/users/messages',
        'fileMap' => [
            'users.rbac' => 'rbac.php',
        ],
    ];
```


В методе translate (Yii::t) указывается константа, например: Yii::t('users', 'USER'), которой соответствует значение из файла:

```
return [
    ...
        'USER' => 'Пользователь',
    ...
    ];
    
```


Удобство состоит в том, что если Вам необходимо заменить текст перевода на свой, это можно легко 
сделать переопределив перевод и при этом не меняя кода самого приложения.
 
 
Переопределение сообщений:
-------------------------

Если Вам необходимо добавить или переопределить сообщения переводов, Вы можете переопределить любой файл переводов. Следующий пример 
демонстрирует переопределение категории *users.rbac* для русского языка:


**Внесите в файл Bootstrap.php следующие изменения:**

``` 
    if (\Yii::$app->language == 'ru') {
        $app->i18n->translations['users.rbac'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'ru-RU',
            'basePath' => '@common/modules/users/messages',
            'fileMap' => [
                'users.rbac' => 'rbac.php',
            ],
        ];
    }
```


Пример нового файла переводов:

```
<?php
    
    $users = Yii::getAlias('@vendor/nepster-web/yii2-module-users/messages/ru/rbac.php');
    $users = require($users);
    
    return array_merge($users, [
        'ACCESS_DENIED' => 'Вам не разрешенно производить данное действие'
    ]);
```