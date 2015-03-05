<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Ban
 */
class Ban extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_ban}}';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->user_agent = Yii::$app->request->userAgent;
                    $this->ip = Yii::$app->request->userIP;
                }
            }
            return true;
        }
        return false;
    }

}