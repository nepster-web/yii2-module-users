<?php

namespace nepster\users\validators;

use yii\validators\RegularExpressionValidator;
use yii\validators\Validator;
use Yii;

/**
 * Валидатор пароля
 */
class PasswordValidator extends Validator
{
    /**
     * @var string
     */
    public $pattern = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z-_!@,#$%]{6,16}$/';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('users', 'SIMPLE_PASSWORD');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $valid = preg_match($this->pattern, $value);
        if (!$valid) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $RegularExpressionValidator = new RegularExpressionValidator([
            'pattern' => $this->pattern,
            'message' => $this->message,
        ]);
        return $RegularExpressionValidator->clientValidateAttribute($model, $attribute, $view);
    }

}