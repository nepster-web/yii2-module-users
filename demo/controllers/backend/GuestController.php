<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models as models;
use yii\widgets\ActiveForm;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;
use Yii;

/**
 * Контроллер управления неавторизированными пользователями
 * @package common\modules\users\controllers\backend
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
           $this->module->viewPath = '@common/modules/users/views/backend';
            return true;
        }
        return false;
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

        $model = new models\LoginForm(['scenario' => 'admin']);

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
}
