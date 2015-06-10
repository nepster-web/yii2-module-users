<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Profile
 */
class Profile extends ActiveRecord
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
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                //'value' => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'time_update',
                ],
            ],
        ];
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
            'time_create' => Yii::t('users', 'TIME_CREATE'),
            'time_update' => Yii::t('users', 'TIME_UPDATE'),
        ];
    }

    /**
     * @return $this
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }
}
