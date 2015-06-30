<?php

namespace common\modules\users\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * @inheritdoc
 */
class User extends \nepster\users\models\User
{
    /**
     * @var string
     */
    public $repassword;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['phone', 'email', 'password', 'repassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            'signup' => self::OP_ALL
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'repassword' => Yii::t('users', 'REPASSWORD'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'email', 'password', 'repassword'], 'required'],
            [['phone', 'email', 'password', 'repassword'], 'trim'],

            [['phone', 'email'], 'unique'],
            ['email', 'email'],

            ['password', '\nepster\users\validators\PasswordValidator'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Хешируем пароль
                $this->setPassword($this->password);
                // IP пользователя
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->ip_register = Yii::$app->request->userIP;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            // Сохраняем профиль
            $this->profile->user_id = $this->id;
            $this->profile->save(false);

            // Сохраняем данные юридического лица
            if ($this->person) {
                $this->person->user_id = $this->id;
                $this->person->save(false);
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(LegalPerson::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * Данные статуса
     * @param null $status
     * @return array|mixed
     */
    public static function getStatusArray($status = null)
    {
        $statuses = [
            self::STATUS_ACTIVE => Yii::t('users', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('users', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => Yii::t('users', 'STATUS_DELETED'),
        ];

        if ($status !== null) {
            return \yii\helpers\ArrayHelper::getValue($statuses, $status);
        }

        return $statuses;
    }
}
