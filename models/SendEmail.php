<?php

namespace nepster\users\models;

use Yii;

/**
 * @inheritdoc
 */
class SendEmail extends \yii\base\Model
{
    /**
     * @var string
     */
    public $text;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text' => Yii::t('users', 'DESCRIPTION'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['text', 'required'],
        ];
    }
}
