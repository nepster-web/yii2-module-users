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
    public function behaviors()
    {
        return [
            'ActionBehavior' => [
                'class' => 'nepster\users\behaviors\ActionBehavior',
                'module' => $this->module->id,
                'actions' => [
                    ActiveRecord::EVENT_AFTER_INSERT => 'create-legalperson',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'update-legalperson',
                    ActiveRecord::EVENT_AFTER_DELETE => 'delete-legalperson',
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
    public static function tableName()
    {
        return '{{%users_legal_person}}';
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
