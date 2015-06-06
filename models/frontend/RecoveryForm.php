<?php

namespace common\modules\users\models\frontend;

use Yii;

/**
 * Class RecoveryForm
 */
class RecoveryForm extends \nepster\users\models\frontend\RecoveryForm
{
    /**
     * @inheritdoc
     */
    public function recovery()
    {
        if ($user = parent::recovery()) {
            return $user;
        }
        return false;
    }
}
