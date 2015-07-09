<?php

namespace common\modules\users\controllers\frontend;

use common\modules\users\models as models;
use yii\web\Controller;
use Yii;

/**
 * Class DefaultController
 * @package common\modules\users\controllers\frontend
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
            $this->module->viewPath = '@common/modules/users/views/frontend';
            return true;
        }

        return false;
    }
}
