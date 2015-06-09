<?php

namespace nepster\users\commands;

use nepster\users\models\User;
use yii\helpers\Console;
use yii\log\Logger;
use Yii;

/**
 *
 */
class ControlController extends \yii\console\Controller
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
     *
     */
    public function actionCron()
    {



    }



    /**
     *
     */
    public function actionRbac()
    {
        $auth = Yii::$app->authManager;

        // Создание пользователей
        $userCreate = $auth->createPermission('user-create');
        $userCreate->description = 'PERMISSION_USER_CREATE';
        $auth->add($userCreate);

        // Редактирование пользователей
        $userUpdate = $auth->createPermission('user-update');
        $userUpdate->description = 'PARAM_USER_UPDATE';
        $auth->add($userUpdate);

        // Просмотр пользователей
        $userView = $auth->createPermission('user-view');
        $userView->description = 'PARAM_USER_VIEW';
        $auth->add($userView);

        // Удаление пользователей
        $userDelete = $auth->createPermission('user-delete');
        $userDelete->description = 'PERMISSION_USER_DELETE';
        $auth->add($userDelete);

        // Добавляем роль "admin"
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $userCreate);
        $auth->addChild($admin, $userUpdate);
        $auth->addChild($admin, $userView);
        $auth->addChild($admin, $userDelete);

        // Назначение ролей пользователям. 1 и 2 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($admin, 1);





        $rule = new \nepster\users\rbac\rules\UserGroupRule;
        $auth->add($rule);

        /*$author = $auth->createRole('author');
        $author->ruleName = $rule->name;
        $auth->add($author);*/

        /*
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $author);*/
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