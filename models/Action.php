<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Action
 */
class Action extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_actions}}';
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
     * Типы действий
     * @return array
     */
    public static function getTypeArray()
    {
        return [
            'auth' => Yii::t('users', 'ACTION_AUTH'),
            'auth-error' => Yii::t('users', 'ACTION_AUTH_ERROR'),
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
