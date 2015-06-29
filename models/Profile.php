<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Модель Profile осуществляет работу с профилями
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
            'ActionBehavior' => [
                'class' => 'nepster\users\behaviors\ActionBehavior',
                'module' => $this->module->id,
                'actions' => [
                    ActiveRecord::EVENT_AFTER_INSERT => 'create-profile',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'update-profile',
                    ActiveRecord::EVENT_AFTER_DELETE => 'delete-profile',
                ],
            ],
            'TimestampBehavior' => [
                'class' => 'yii\behaviors\TimestampBehavior',
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
            'about_me' => Yii::t('users', 'ABOUT_ME'),
            'avatar_url' => Yii::t('users', 'AVATAR_URL'),
            'birthday' => Yii::t('users', 'BIRTHDAY'),
            'legal_person' => Yii::t('users', 'LEGAL_PERSON'),
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

    /**
     * Поиск профиля по идентификатору пользователя
     * @param $id
     * @return array|null|ActiveRecord
     */
    public static function findByUserId($id)
    {
        return self::find()
            ->where('user_id = :user_id', [':user_id' => $id])
            ->one();
    }
}
