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
     * @return $this
     */
    public function mailVerified()
    {
        $this->andWhere([User::tableName() . '.mail_verify' => 1]);
        return $this;
    }

    /**
     * @return $this
     */
    public function mailUnverified()
    {
        $this->andWhere([User::tableName() . '.mail_verify' => 0]);
        return $this;
    }

    /**
     * @return $this
     */
    public function phoneVerified()
    {
        $this->andWhere([User::tableName() . '.phone_verify' => 1]);
        return $this;
    }

    /**
     * @return $this
     */
    public function phoneUnverified()
    {
        $this->andWhere([User::tableName() . '.phone_verify' => 0]);
        return $this;
    }

    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function inactive()
    {
        $this->andWhere([User::tableName() . '.status' => User::STATUS_INACTIVE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        $this->andWhere([User::tableName() . '.status' => User::STATUS_DELETED]);
        return $this;
    }

    /**
     * @return $this
     */
    public function banned()
    {
        $this->andWhere([User::tableName() . '.banned' => 1]);
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
