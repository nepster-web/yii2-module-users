<?php

namespace frontend\modules\users\controllers;

use nepster\users\models as models;
use yii\widgets\ActiveForm;
use yii\web\Response;
use Yii;

/**
 * Frontend controller for authenticated users.
 */
class UserController extends \frontend\components\Controller
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
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Log Out page.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Change password page.
     */
    public function actionPassword()
    {
        $model = new PasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->password()) {
                    Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCESS_PASSWORD_CHANGE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_PASSWORD_CHANGE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('password.twig', [
            'model' => $model
        ]);
    }

    /**
     * Profile updating page.
     */
    public function actionProfile()
    {
        $model = Profile::findByUserId(Yii::$app->user->id);
        $model->setScenario('signup');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users.flash', 'SUCCES_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users.flash', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('profile.twig', [
            'model' => $model
        ]);
    }
}
