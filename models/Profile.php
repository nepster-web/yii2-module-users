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
        return '{{%profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['name', 'surname', 'whau'],
            'update' => ['name', 'surname'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Name
            ['name', 'match', 'pattern' => '/^[a-zа-яё]+$/iu'],
            
            // Surname
            ['surname', 'match', 'pattern' => '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu'],
            
            // Whau
            ['whau', 'string', 'min' => 1, 'max' => 200],
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
            'whau' => Yii::t('users', 'WHAU')
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
