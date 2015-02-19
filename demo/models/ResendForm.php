<?php

namespace frontend\modules\users\models;

use Yii;

/**
 * Class ResendForm
 * @package frontend\modules\users\models
 * ResendForm is the model behind the resend form.
 */
class ResendForm extends \common\modules\users\models\ResendForm
{
    /**
     * Send a resend access token.
     *
     * @return boolean true
     */
    public function resend()
    {
        if ($model = parent::resend()) {
            Yii::$app->consoleRunner->run('users/send ' . $this->module->sendTransport . ' ' . $model->id . ' signup "' . Yii::t('users.send', 'SUBJECT_SIGNUP') . '"' );
            return true;
        }
        return false;
    }
}
