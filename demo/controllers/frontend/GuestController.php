<?php

namespace common\modules\users\controllers\frontend;

use common\modules\users\models as models;
use yii\widgets\ActiveForm;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use Yii;

/**
 * Контроллер управления неавторизированными пользователями
 * @package common\modules\users\controllers\frontend
 */
class GuestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->module->viewPath = '@common/modules/users/views/frontend';
            return true;
        } else {
            return false;
        }
    }

    /**
     * Регистрация
     * @return array|string|Response
     */
    public function actionSignup()
    {
        $user = new models\User(['scenario' => 'signup']);
        $profile = new models\Profile(['scenario' => 'signup']);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->validate() && $profile->validate()) {
                $user->populateRelation('profile', $profile);
                if ($user->save(false)) {
                    if ($this->module->requireEmailConfirmation === true) {
                        Yii::$app->consoleRunner->run('users/control/send-email ' . $user->id . ' signup "' . Yii::t('users', 'SUBJECT_SIGNUP') . '"');
                        Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_SIGNUP_WITHOUT_LOGIN', [
                            'url' => Url::toRoute('resend')
                        ]));
                    } else {
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_SIGNUP_WITH_LOGIN'));
                    }
                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_SIGNUP'));
                    return $this->refresh();
                }
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
        }

        return $this->render('signup', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Повторная отправка ключа активации
     * @return array|string|Response
     */
    public function actionResend()
    {
        $model = new models\frontend\ResendForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($this->module->requireEmailConfirmation === true) {
                    if ($user = $model->resend()) {
                        Yii::$app->consoleRunner->run('users/control/send-email ' . $user->id . ' signup "' . Yii::t('users', 'SUBJECT_SIGNUP') . '"');
                        Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_RESEND'));
                        return $this->redirect(['login']);
                    } else {
                        Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_RESEND'));
                        return $this->refresh();
                    }
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'FAIL_RESEND_OFF'));
                    return $this->refresh();
                }
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('resend', [
            'model' => $model
        ]);
    }

    /**
     * Авторизация
     * @return array|string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new models\LoginForm(['scenario' => 'user']);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->login()) {
                    return $this->goHome();
                }
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Активация
     * @param $token
     * @return Response
     */
    public function actionActivation($token)
    {
        $model = new models\frontend\ActivationForm(['secure_key' => $token]);
        if ($model->validate() && $model->activation()) {
            Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_ACTIVATION'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_ACTIVATION'));
        }
        return $this->redirect(['login']);
    }

    /**
     * Восстановить пароль
     * @return array|string|Response
     */
    public function actionRecovery()
    {
        $model = new models\frontend\RecoveryForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($user = $model->recovery()) {
                    Yii::$app->consoleRunner->run('users/control/send-email ' . $user->id . ' recovery "' . Yii::t('users', 'SUBJECT_RECOVERY') . '"');
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_RECOVERY'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_RECOVERY'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('recovery', [
            'model' => $model
        ]);
    }

    /**
     * Подтверждение восстановления пароля
     * @param $token
     * @return array|string|Response
     */
    public function actionRecoveryConfirmation($token)
    {
        $model = new models\frontend\RecoveryConfirmationForm(['secure_key' => $token]);

        if (!$model->isValidToken()) {
            Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_RECOVERY_CONFIRMATION_WITH_INVALID_KEY'));
            return $this->redirect(['recovery']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCESS_RECOVERY_CONFIRMATION'));
                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_RECOVERY_CONFIRMATION'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('recovery-confirmation', [
            'model' => $model
        ]);
    }
}
