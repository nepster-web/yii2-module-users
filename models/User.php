<?php

namespace nepster\users\models;

use nepster\users\traits\ModuleTrait;
use nepster\users\helpers\Security;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\db\Query;
use Yii;

/**
 * Модель User осуществляет работу с пользователями
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
            'TimestampBehavior' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time_register',
                ],
            ],
            'ActionBehavior' => [
                'class' => 'nepster\users\behaviors\ActionBehavior',
                'module' => $this->module->id,
                'actions' => [
                    ActiveRecord::EVENT_AFTER_INSERT => 'create-user',
                    ActiveRecord::EVENT_AFTER_UPDATE => 'update-user',
                    ActiveRecord::EVENT_AFTER_DELETE => 'delete-user',
                ],
            ],
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
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('users', 'ID'),
            'username' => Yii::t('users', 'USERNAME'),
            'group' => Yii::t('users', 'GROUP'),
            'status' => Yii::t('users', 'STATUS'),
            'banned' => Yii::t('users', 'BANNED'),
            'email' => Yii::t('users', 'EMAIL'),
            'phone' => Yii::t('users', 'PHONE'),
            'email_verify' => Yii::t('users', 'EMAIL_VERIFY'),
            'phone_verify' => Yii::t('users', 'PHONE_VERIFY'),
            'password' => Yii::t('users', 'PASSWORD'),
            'auth_key' => Yii::t('users', 'AUTH_KEY'),
            'api_key' => Yii::t('users', 'API_KEY'),
            'secure_key' => Yii::t('users', 'SECURE_KEY'),
            'time_activity' => Yii::t('users', 'TIME_ACTIVITY'),
            'ip_register' => Yii::t('users', 'IP_REGISTER'),
            'time_register' => Yii::t('users', 'TIME_REGISTER'),
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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {

                // Статус по умолчанию
                if (!$this->status) {
                    $this->status = $this->module->params['requireEmailConfirmation'] ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
                }

                // Роль по умолчанию
                if (!$this->group) {
                    $this->group = $this->module->params['defaultGroup'];
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
     * Профиль пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * Действия пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::className(), ['user_id' => 'id']);
    }

    /**
     * Блокировки пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getBanned()
    {
        return $this->hasMany(Banned::className(), ['user_id' => 'id']);
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
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Поиск пользователей IDs.
     *
     * @param $ids User IDs
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
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['api_key' => $token]);
    }

    /**
     * Поиск пользователя по секретному ключу
     *
     * @param $secureKey
     * @param null $scope
     * @return array|null|ActiveRecord
     */
    public static function findBySecureKey($secureKey, $scope = null)
    {
        $query = static::find()->where(['secure_key' => $secureKey]);
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
     * Auth Key validation.
     *
     * @param string $authKey
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Password validation.
     *
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        if ($password && $this->password) {
            return Yii::$app->security->validatePassword($password, $this->password);
        }
        return false;
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
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
        $this->api_key = Yii::$app->security->generateRandomString();
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
     * @return boolean
     */
    public function password($password)
    {
        $this->setPassword($password);
        return $this->save(false);
    }

    /**
     * Изменить группу пользователя
     *
     * @param $newGroup
     * @return boolean
     */
    public function setGroup($newGroup)
    {
        $auth = Yii::$app->authManager;

        $groups = $auth->getRolesByUser($this->id);
        foreach ($groups as $group) {
            $auth->revoke($group, $this->id);
        }

        $group = $auth->getRole($newGroup);
        $result = $auth->assign($group, $this->id);

        return $result;
    }

    /**
     * Статус Online текущего пользователя
     *
     * @return bool
     */
    public function isOnline()
    {
        return ((time() - $this->time_activity) <= $this->module->params['intervalInactivityForOnline']);
    }

    /**
     * Статус Banned текущего пользователя
     *
     * @return bool
     */
    public function isBanned()
    {
        if (Banned::isBannedByUser($this->id)) {
            return true;
        }

        return false;
    }

    /**
     * Информация о блокировке пользователя
     *
     * @return array|bool
     */
    public function bannedInfo()
    {
        if ($banned = Banned::isBannedByUser($this->id)) {
            return $banned;
        }

        return false;
    }
}
