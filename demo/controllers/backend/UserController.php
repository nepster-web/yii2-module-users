<?php

namespace common\modules\users\controllers\backend;

use common\modules\users\models as models;
use nepster\users\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;
use Yii;

/**
 * Class UserController
 */
class UserController extends Controller
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
     * Выход
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Все пользователи
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new models\backend\UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создать пользователя
     */
    public function actionCreate()
    {
        $user = new models\backend\User(['scenario' => 'create']);
        $profile = new models\Profile(['scenario' => 'create']);
        $person = new models\LegalPerson(['scenario' => 'create']);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post()) && $person->load(Yii::$app->request->post())) {
            if (!ActiveForm::validateMultiple([$user, $profile, $person])) {
                $user->populateRelation('profile', $profile);
                $user->populateRelation('person', $person);
                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCES_UPDATE'));
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validateMultiple([$user, $profile, $person]);
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
            'person' => $person,
        ]);
    }

    /**
     * Редактировать пользователя
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $user->password = ''; // Сброс пароля
        $profile = $user->profile ? $user->profile : new models\Profile();
        $person = $user->person ? $user->person : new models\LegalPerson();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post()) && $person->load(Yii::$app->request->post())) {
            if (!ActiveForm::validateMultiple([$user, $profile, $person])) {
                $user->populateRelation('profile', $profile);
                $user->populateRelation('person', $person);
                if ($user->save(false)) {
                    Yii::$app->session->setFlash('success', Yii::t('users', 'SUCCES_UPDATE'));
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('users', 'FAIL_UPDATE'));
                }
                return $this->refresh();
            } else if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validateMultiple([$user, $profile, $person]);
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile,
            'person' => $person,
        ]);
    }

    /**
     * Удалить пользователя
     * Если пользователь будет удален, то сработает редирект на index
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // TODO: сообщение о удалении

        $user = $this->findModel($id);
        $user->delete = 1;
        $user->save(false);

        return $this->redirect(['index']);
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
        // TODO: переводы добавить

        if (($model = models\backend\User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }
}
