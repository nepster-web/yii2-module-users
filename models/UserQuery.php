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
    public function emailVerified($state = 1)
    {
        $this->andWhere([User::tableName() . '.email_verify' => $state]);
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
    public function status($state = User::STATUS_ACTIVE)
    {
        $this->andWhere([User::tableName() . '.status' => $state]);
        return $this;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function banned($state = 1)
    {
        $time = time();

        $this->joinWith([
            'banned' => function ($query) use ($state, $time) {
                if ($state) {
                    $query->andWhere('time_banned >= :time', [':time' => $time]);
                } else {
                    $query->andWhere('time_banned < :time OR time_banned IS NULL', [':time' => $time]);
                }
            }
        ]);
        return $this;
    }

    /**
     * @param int $state
     * @return $this
     */
    public function online($state = 1)
    {
        $time = time() - $this->module->intervalInactivityForOnline;

        if ($state) {
            $this->andWhere(User::tableName() . '.time_activity >= :time', [':time' => $time]);
        } else {
            $this->andWhere(User::tableName() . '.time_activity < :time', [':time' => $time]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function control()
    {
        $this->andWhere([User::tableName() . '.group' => $this->module->accessGroupsToControlpanel]);
        return $this;
    }
}