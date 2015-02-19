<?php

namespace common\modules\users\models;

use Yii;

/**
 * Class ResendForm
 */
class ResendForm extends \nepster\users\models\ResendForm
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
