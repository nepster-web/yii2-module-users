<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models as models;
use nepster\users\rbac\models\AuthItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use Yii;

/**
 * Контроллер для управления правами доступа
 * @package common\modules\users\controllers\backend
 */
class RbacController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('user-access-rules-control');
                        }
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
        if (parent::beforeAction($action)) {
            $this->module->viewPath = '@common/modules/users/views/backend';
            return true;
        }
        return false;
    }

    /**
     * Все группы
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new models\backend\search\RbacSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создать группу
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $model = new AuthItem(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCES_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->redirect(['index']);
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Редактировать группу
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCES_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удалить группу
     * Если группа будет удалена, то сработает редирект на index
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!in_array($model->name, $this->module->defaultGroups)) {
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCES_DELETE'));
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_DELETE'));
            }
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('users.rbac', 'RBAC_NOT_ALLOWED_DELETE'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Находит модель пользователя на основе значения первичного ключа.
     * Если модель не найдена, будет сгенерировано исключение HTTP 404.
     * @param integer $id
     * @return AuthItem
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('users.rbac', 'GROUP_NOT_FOUND'));
    }
}
