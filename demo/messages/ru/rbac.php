<?php

$users = Yii::getAlias('@vendor/nepster-web/yii2-module-users/messages/ru/rbac.php');
$users = require($users);

return array_merge($users, [
    'ACCESS_DENIED' => 'Вам не разрешенно производить данное действие'
]);