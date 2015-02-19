<?php

namespace nepster\users\traits;

use nepster\users\Module;
use Yii;

/**
 * Class ModuleTrait
 */
trait ModuleTrait
{
    /**
     * @var \nepster\users\Module|null Module instance
     */
    private $_module;

    /**
     * @return \nepster\users\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $module = Module::getInstance();
            if ($module instanceof Module) {
                $this->_module = $module;
            } else {
                $this->_module = Yii::$app->getModule('users');
            }
        }
        return $this->_module;
    }
}
