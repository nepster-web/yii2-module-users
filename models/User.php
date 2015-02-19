<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use nepster\users\helpers\Security;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class User
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    use ModuleTrait;

    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_DELETED = 2;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'value' => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Поиск пользователей IDs.
     *
     * @param $ids Users IDs
     * @param null $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] Users
     */
    public static function findIdentities($ids, $scope = null)
    {
        $query = static::find()->where(['id' => $ids]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }    

    /**
     * Поиск пользователя по логину
     *
     * @param string $username Username
     * @param string $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] User
     */
    public static function findByUsername($username, $scope = null)
    {
        $query = static::find()->where(['username' => $username]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }

    /**
     * Поиск пользователя по email
     *
     * @param string $email Email
     * @param string $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] User
     */
    public static function findByEmail($email, $scope = null)
    {
        $query = static::find()->where(['email' => $email]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }    

    /**
     * Поиск пользователя по телефону
     *
     * @param string $phone Phone
     * @param string $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] User
     */
    public static function findByPhone($phone, $scope = null)
    {
        $query = static::find()->where(['phone' => $phone]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
        
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['api_key' => $token]);
    }

    /**
     * Auth Key validation.
     * @param string $authKey
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Password validation.
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('users', 'USERNAME'),
            'role' => Yii::t('users', 'ROLE'),
            'status' => Yii::t('users', 'STATUS'),
            'email' => Yii::t('users', 'EMAIL'),
            'phone' => Yii::t('users', 'PHONE'),
            'mail_verify' => Yii::t('users', 'MAIL_VERIFY'),
            'phone_verify' => Yii::t('users', 'PHONE_VERIFY'),
            'password' => Yii::t('users', 'PASSWORD'),
            'auth_key' => Yii::t('users', 'AUTH_KEY'),
            'api_key' => Yii::t('users', 'API_KEY'),
            'secure_key' => Yii::t('users', 'SECURE_KEY'),
            'auth_ip' => Yii::t('users', 'AUTH_KEY'),
            'auth_time' => Yii::t('users', 'AUTH_TIME'),
            'create_ip' => Yii::t('users', 'CREATE_IP'),
            'create_time' => Yii::t('users', 'CREATE_TIME'),
            'update_time' => Yii::t('users', 'UPDATE_TIME'),
            'ban_time' => Yii::t('users', 'BAN_TIME'),
            'ban_reason' => Yii::t('users', 'BAN_REASON'),
        ];
    }

    /**
     * @return Profile|null User profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                
                // Статус по умолчанию
                if (!$this->status) {
                    $this->status = $this->module->requireEmailConfirmation ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
                }
                
                // Роль по умолчанию
                if (!$this->role) {
                    $this->role = $this->module->defaultRole;
                }
                
                // Генерация секретных токенов
                $this->generateAuthKey();
                $this->generateApiKey();
                $this->generateSecureKey();

            }

            return true;
        }

        return false;
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Верификация e-mail
     *
     * @return boolean true if account was successfully activated
     */
    public function mailVerification()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->mail_verify = 1;
        $this->generateSecureKey();
        return $this->save(false);
    }

    /**
     * Верефикация телефона
     *
     * @return boolean true if account was successfully activated
     */
    public function phoneVerification()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->phone_verify = 1;
        $this->generateSecureKey();
        return $this->save(false);
    }

    /**
     * Сгенерировать секретный ключ
     */
    public function generateSecureKey()
    {
        $this->secure_key = Security::generateExpiringRandomString();
    }

    /**
     * Сгенерировать секретный ключ для api
     */
    public function generateApiKey()
    {
        $this->api_key = Security::generateExpiringRandomString();
    }
    
    /**
     *  Восстановить пароль
     *
     * @param string $password New Password
     * @return boolean true if password was successfully recovered
     */
    public function recovery($password)
    {
        $this->setPassword($password);
        $this->generateSecureKey();
        return $this->save(false);
    }

    /**
     * Сгенерировать новый hash пароля
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Изменить пароль пользователя
     *
     * @return boolean true if password was successfully changed
     */
    public function password($password)
    {
        $this->setPassword($password);
        return $this->save(false);
    }   

}
