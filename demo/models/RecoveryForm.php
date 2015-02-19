<?php

namespace frontend\modules\users\models;

use Yii;

/**
 * Class RecoveryForm
 * @package frontend\modules\users\models
 * RecoveryForm is the model behind the recovery form.
 */
class RecoveryForm extends \common\modules\users\models\RecoveryForm
{
    /**
     * Send a recovery password token.
     *
     * @return boolean true if recovery token was successfully sent
     */
    public function recovery()
    {
        if ($model = parent::recovery()) {
            // Отправить уведомление
            Yii::$app->consoleRunner->run('users/send ' . $this->module->sendTransport . ' ' . $model->id . ' recovery "' . Yii::t('users.send', 'SUBJECT_RECOVERY') . '"' );
            return true;
        }
        return false;
    }
}
