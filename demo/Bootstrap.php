<?php

namespace common\modules\users;

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
        // Register translations
        $app->i18n->translations['users*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@nepster/users/messages',
            'fileMap' => [
                'users.rbac' => 'rbac.php',
            ],
        ];


        /*
            Переопределить файл переводов для категории users.rbac
            if (\Yii::$app->language == 'ru') {
                $app->i18n->translations['users.rbac'] = [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-RU',
                    'basePath' => '@common/modules/users/messages',
                    'fileMap' => [
                        'users.rbac' => 'rbac.php',
                    ],
                ];
            }
        */

        
        // Add module URL rules.
        $app->urlManager->addRules([
                'activation/<token>' => 'users/guest/activation',
                'recovery-confirmation/<token>' => 'users/guest/recovery-confirmation',
                '<_a:(login|signup|activation|resend|recovery)>' => 'users/guest/<_a>',
                '<_a:logout|profile|password|legal-person>' => 'users/user/<_a>',
            ]
        );
    }
}
