<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Модель LegalPerson осуществляет работу с данными юридических лиц
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
            'name' => Yii::t('users', 'LEGAL_PERSON_NAME'),
            'address' => Yii::t('users', 'LEGAL_PERSON_ADDRESS'),
            'BIN' => Yii::t('users', 'LEGAL_PERSON_BIN'),
            'bank' => Yii::t('users', 'LEGAL_PERSON_BANK'),
            'account' => Yii::t('users', 'LEGAL_PERSON_ACCOUNT'),
        ];
    }
}
