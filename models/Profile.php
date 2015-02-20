<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use Yii;

/**
 * Class Profile
 */
class Profile extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('users', 'NAME'),
            'surname' => Yii::t('users', 'SURNAME'),
            'whau' => Yii::t('users', 'WHAU'),
            'avatar_url' => Yii::t('users', 'AVATAR_URL'),
            'birthday' => Yii::t('users', 'BIRTHDAY'),
            'create_time' => Yii::t('users', 'CREATE_TIME'),
            'update_time' => Yii::t('users', 'UPDATE_TIME'),
        ];
    }

    /**
     * @return Profile|null Profile user
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }
}
