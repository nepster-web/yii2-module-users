<?php

namespace app\modules\users\controllers\frontend;

use app\modules\users\models as models;
use yii\web\Controller;
use Yii;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
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
                        'roles' => ['?', '@']
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
            $this->module->viewPath = '@app/modules/users/views/frontend';
            return true;
        } else {
            return false;
        }
    }
}
