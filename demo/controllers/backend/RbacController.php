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
 * Class RbacController
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
                        'roles' => ['@']
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
        } else {
            return false;
        }
    }

    /**
     * Все группы
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new models\backend\RbacSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создать группу
     * @return mixed
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
     * @return mixed
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
     * Установить права доступа для пользователя
     * @return mixed
     */
    public function actionToUser($id)
    {
        echo $id;
    }

    /**
     * Находит модель пользователя на основе значения первичного ключа.
     * Если модель не найдена, будет сгенерировано исключение HTTP 404.
     * @param integer $id
     * @return models\User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('users', 'ROLE_NOT_FOUND'));
    }
}
