<?php

namespace nepster\users\rbac\models;

use yii\db\ActiveRecord;
use yii\rbac\Item;
use yii;

/**
 *
 */
class AuthItem extends ActiveRecord
{
    /**
     * @var array Список разрешений
     */
    public $permissions = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'update' => ['permissions'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        parent::afterValidate();

        if ($this->scenario == 'update') {

            $auth = Yii::$app->authManager;

            // Сохраняем разрешения для роли
            $parentPermission = $auth->getRole($this->name);
            $currentPermissions = $auth->getPermissionsByRole($this->name);

            foreach ($this->permissions as $permission => $value) {
                $permission = $auth->getPermission($permission);
                if ($permission) {
                    if ($value) {
                        if (!$auth->hasChild($parentPermission, $permission)) {
                            $auth->addChild($parentPermission, $permission);
                        }
                    } else {
                        $auth->removeChild($parentPermission, $permission);
                    }
                }
            }
        }
    }

    /**
     * Возвращает массив разрешений для роли
     * Устанавливает активные значения в $this->permissions, если роль уже обладает разрешениями
     * @return array
     */
    public function getRolePermissions()
    {
        $permissions = Yii::$app->authManager->getPermissions();
        $permissionsByRole = array_keys(Yii::$app->authManager->getPermissionsByRole($this->name));

        if ($permissions) {
            foreach ($permissions as $name => $permission) {
                if (in_array($name, $permissionsByRole)) {
                    $this->permissions[$name] = 1;
                }
            }
        }
        return $permissions;
    }
}
