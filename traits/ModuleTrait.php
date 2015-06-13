<?php

namespace nepster\users\traits;

use nepster\users\Module;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\base\Model;
use Yii;

/**
 * Class ModuleTrait
 */
trait ModuleTrait
{
    /**
     * @var \nepster\users\Module|null Module instance
     */
    private static $_module;

    /**
     * @return Module|null|\yii\base\Module
     */
    public static function getModule()
    {
        if (self::$_module === null) {
            $module = Module::getInstance();
            if ($module instanceof Module) {
                self::$_module = $module;
            } else {
                self::$_module = Yii::$app->getModule('users');
            }
        }
        return self::$_module;
    }
}
