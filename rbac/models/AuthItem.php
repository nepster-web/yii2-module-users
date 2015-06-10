<?php

namespace nepster\users\rbac\models;

use yii\helpers\ArrayHelper;
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
            'create' => ['name', 'description', 'permissions'],
            'update' => ['name', 'description', 'permissions'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'unique', 'targetAttribute' => 'name'],
            ['description', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            'create' => self::OP_ALL,
            'update' => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            $this->type = 1;

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

            return true;
        }

        return false;
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

    /**
     * Список всех групп
     * @return array
     */
    public static function getGroupsArray()
    {
        $groups = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($groups, 'name', 'description');
    }
}
