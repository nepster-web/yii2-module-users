<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Данные юридических лиц
 */
class LegalPerson extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_legal_person}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                //'value' => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    // ActiveRecord::EVENT_BEFORE_INSERT => 'time_create',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'time_update',
                ],
            ],
            'BlameableBehavior' => [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('users', 'LEGAL_PERSON_NAME'),
            'address' => Yii::t('users', 'LEGAL_PERSON_ADDRESS'),
            'BIN' => Yii::t('users', 'LEGAL_PERSON_BIN'),
            'bank' => Yii::t('users', 'LEGAL_PERSON_BANK'),
            'account' => Yii::t('users', 'LEGAL_PERSON_ACCOUNT'),
        ];
    }

    /**
     * @return LegalPerson|null LegalPerson user
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('legalPerson');
    }

    /**
     * Поиск по идентификатору пользователя
     * @param $id
     * @return null|static
     */
    public static function findByUserId($id)
    {
        return static::findOne(['user_id' => $id]);
    }
}
