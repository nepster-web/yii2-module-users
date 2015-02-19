<?php

namespace common\modules\users\controllers\frontend;

use common\modules\users\models as models;
use yii\widgets\ActiveForm;
use yii\base\Controller;
use yii\web\Response;
use yii\helpers\Url;
use Yii;

/**
 * Class GuestController
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
     * Sign Up page.
     * If record will be successful created, user will be redirected to home page.
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
                        $user->send('mail');
                        Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_SIGNUP_WITHOUT_LOGIN', [
                            'url' => Url::toRoute('resend')
                        ]));
                    } else {
                        Yii::$app->user->login($user);
                        Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_SIGNUP_WITH_LOGIN'));
                    }
                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_SIGNUP'));
                    return $this->refresh();
                }
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user);
            }
        }

        return $this->render('signup.twig', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Resend email confirmation token page.
     */
    public function actionResend()
    {
        $model = new models\ResendForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($this->module->requireEmailConfirmation === true) {
                    if ($model->resend()) {
                        Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_RESEND'));
                        return $this->redirect(['login']);
                    } else {
                        Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_RESEND'));
                        return $this->refresh();
                    }
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('users.flash', 'FAIL_RESEND_OFF'));
                    return $this->refresh();
                }
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('resend.twig', [
            'model' => $model
        ]);
    }

    /**
     * Sign In page.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new models\LoginForm();

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

        return $this->render('login.twig', [
            'model' => $model
        ]);
    }

    /**
     * Activate a new user page.
     *
     * @param string $token Activation token.
     *
     * @return mixed View
     */
    public function actionActivation($token)
    {
        $model = new models\ActivationForm(['access_token' => $token]);
        if ($model->validate() && $model->activation()) {
            Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_ACTIVATION'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_ACTIVATION'));
        }
        return $this->redirect(['login']);
    }

    /**
     * Request password recovery page.
     */
    public function actionRecovery()
    {
        $model = new models\RecoveryForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_RECOVERY'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_RECOVERY'));
                }
                return $this->refresh();                
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('recovery.twig', [
            'model' => $model
        ]);
    }

    /**
     * Confirm password recovery request page.
     *
     * @param string $token Confirmation token
     *
     * @return mixed View
     */
    public function actionRecoveryConfirmation($token)
    {
        $model = new RecoveryConfirmationForm(['access_token' => $token]);
    
        if (!$model->isValidToken()) {
            Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_RECOVERY_CONFIRMATION_WITH_INVALID_KEY'));
            return $this->redirect(['recovery']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->recovery()) {
                    Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_RECOVERY_CONFIRMATION'));
                    return $this->redirect(['login']);
                } else {
                    Yii::$app->session->setFlash('danger',  Yii::t('users.flash', 'FAIL_RECOVERY_CONFIRMATION'));
                    return $this->refresh();
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('recovery-confirmation.twig', [
            'model' => $model
        ]);
    }
}
