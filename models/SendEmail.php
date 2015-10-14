<?php

namespace nepster\users\models;

use yii\helpers\Html;
use Yii;

/**
 * @inheritdoc
 */
class SendEmail extends \yii\base\Model
{
    /**
     * @var string
     */
    public $theme;

    /**
     * @var string
     */
    public $text;

    /**
     * @var array
     */
    private $_emails;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme' => Yii::t('users', 'THEME'),
            'text' => Yii::t('users', 'DESCRIPTION'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['theme', 'string', 'max' => 32],
            ['text', 'required'],
            ['text', 'string', 'max' => 2000],
        ];
    }

    /**
     * Получает список почтовых адресов для рассылки
     * @param array $users
     */
    public function setUsers(array $users)
    {
        foreach ($users as &$user) {
            $this->_emails[] = $user->email;
        }
    }

    /**
     * Почтовая рассылка
     * @return bool
     */
    public function send()
    {
        if (empty(trim($this->theme))) {
            $theme = Yii::t('users', 'EMAIL_SUBJECT_NOTIFICATION');
        } else {
            $theme = Html::encode($this->theme);
        }
        $emails = implode(' ', $this->_emails);
        $command = 'users/control/multi-send-email message "' . addslashes($theme) . '" "' . addslashes($this->text) . '" ' . $emails;
        Yii::$app->consoleRunner->run($command);
        return true;
    }
}
