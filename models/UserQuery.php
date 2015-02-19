<?php

namespace nepster\users\models;

use yii\db\ActiveQuery;

/**
 * Class UserQuery
 */
class UserQuery extends ActiveQuery
{
    /**
     * @return $this
     */
	public function mailVerified()
	{
		$this->andWhere(['mail_verify' => 1]);
		return $this;
	}

    /**
     * @return $this
     */
	public function mailUnverified()
	{
		$this->andWhere(['mail_verify' => 0]);
		return $this;
	}

    /**
     * @return $this
     */
	public function phoneVerified()
	{
		$this->andWhere(['phone_verify' => 1]);
		return $this;
	}

    /**
     * @return $this
     */
	public function phoneUnverified()
	{
		$this->andWhere(['phone_verify' => 0]);
		return $this;
	}

    /**
     * @return $this
     */
	public function active()
	{
		$this->andWhere(['status' => User::STATUS_ACTIVE]);
		return $this;
	}

    /**
     * @return $this
     */
    public function inactive()
    {
        $this->andWhere(['status' => User::STATUS_INACTIVE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->andWhere(['status' => User::STATUS_DELETED]);
        return $this;
    }

    /**
     * @return $this
     */
	public function banned()
	{
		//$this->andWhere([]);
		return $this;
	}
}
