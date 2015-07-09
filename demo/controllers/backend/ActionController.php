<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models as models;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

/**
 * Контроллер управления действиями пользователей
 * @package common\modules\users\controllers\backend
 */
class ActionController extends Controller
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
                        'roles' => $this->module->accessGroupsToControlpanel,
                    ],
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!Yii::$app->user->can('user-actions-view')) {
            throw new ForbiddenHttpException(Yii::t('users.rbac', 'ACCESS_DENIED'));
        }

        if (parent::beforeAction($action)) {
            $this->module->viewPath = '@common/modules/users/views/backend';
            return true;
        }
        return false;
    }

    /**
     * Действия пользователей
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new models\backend\search\ActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
