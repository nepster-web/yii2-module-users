<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\db\ActiveQuery;

/**
 * Class UserQuery
 */
class UserQuery extends ActiveQuery
{
    use ModuleTrait;

    /**
     * @param int $state
     * @return $this
     */
    public function mailVerified($state = 1)
    {
        $this->andWhere([User::tableName() . '.mail_verify' => $state]);
        return $this;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function phoneVerified($state = 1)
    {
        $this->andWhere([User::tableName() . '.phone_verify' => $state]);
        return $this;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function status($state = null)
    {
        $state = $state ? $state : User::STATUS_ACTIVE;
        $this->andWhere([User::tableName() . '.status' => $state]);
        return $this;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function banned($state = 1)
    {
        $this->andWhere([User::tableName() . '.banned' => $state]);
        return $this;
    }

    /**
     * @return $this
     */
    public function control()
    {
        $this->andWhere([User::tableName() . '.role' => $this->module->accessRoleToControlpanel]);
        return $this;
    }
}
