<?php

namespace nepster\users\commands;

use nepster\users\models\User;
use yii\helpers\Console;
use yii\log\Logger;
use Yii;

/**
 * User message send. Params: [userId] [view] [subject]
 */
class SendController extends \yii\console\Controller
{
    /**
     * @var string
     */
    public $mailViewPath = '@common/modules/users/mails/';

    /**
     * @var \common\modules\users\models\User
     */
    private $_user;

    /**
     * Send
     *
     * @param $userId
     * @param $view - template view
     * @param $subject
     *
     * Command: php Yii users/send [userId] [view] [subject]
     */
    public function actionIndex($userId, $view, $subject)
    {
        $this->_user = User::find()
            ->where('id = :user_id', [':user_id' => $userId])
            ->with('profile')
            ->asArray()
            ->one();

        if ($this->_user) {
            $this->sendMail($view, $subject);
        } else {
            Yii::getLogger()->log('ERROR: send fail. DATA: ' . $userId . ', ' . $view. ', '  . $subject, Logger::LEVEL_ERROR, 'users.send');
        }
    }

    /**
     * Send mail
     */
    private function sendMail($view, $subject)
    {
        $mail = Yii::$app->getMailer();
        $mail->viewPath = $this->mailViewPath;
        $send = $mail->compose($view, ['user' => $this->_user])
            ->setFrom(Yii::$app->getMailer()->messageConfig['from'])
            ->setTo($this->_user['email'])
            ->setSubject($subject)
            ->send();

        if ($send) {
            $this->stdout("SUCESS" . PHP_EOL, Console::FG_GREEN);
            Yii::getLogger()->log('SUCCESS: E-MAIL: ' . $this->_user['email'] . ', view: ' . $view, Logger::LEVEL_INFO, 'users.send');
            return;
        }

        $this->stdout("ERROR" . PHP_EOL, Console::FG_RED);
        Yii::getLogger()->log('ERROR: E-MAIL: ' . $this->_user['email'] . ', view: ' . $view, Logger::LEVEL_ERROR, 'users.send');
    }
}