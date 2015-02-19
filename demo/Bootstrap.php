<?php

namespace frontend\modules\users;

use Yii;

/**
 * Users module bootstrap class.
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module URL rules.
        $app->urlManager->addRules([
                'activation/<token>' => 'users/guest/activation',
                'recovery-confirmation/<token>' => 'users/guest/recovery-confirmation',
                '<_a:(login|signup|activation|resend|recovery)>' => 'users/guest/<_a>',
                '<_a:logout>' => 'users/user/<_a>',
                'profile' => 'users/user/profile',
                'password' => 'users/user/password',
            ]
        );
    }
}
