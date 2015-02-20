<?php

namespace nepster\users\models;

use users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class ActivationForm
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
                //'value' => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
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
            'bank' => Yii::t('users', 'LEGAL_PERSON_BALK'),
            'account' => Yii::t('users', 'LEGAL_PERSON_ACCOUNT'),
        ];
    }
}
