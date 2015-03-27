<?php

namespace app\modules\users\models;

use Yii;

/**
 * Class Profile
 */
class Profile extends \nepster\users\models\Profile
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        /*
            $labels = parent::attributeLabels();

            return array_merge($labels, [

            ]);
        */
        return parent::attributeLabels();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'signup' => ['name', 'surname', 'whau'],
            'update' => ['name', 'surname'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Name
            ['name', 'match', 'pattern' => '/^[a-zа-яё]+$/iu'],

            // Surname
            ['surname', 'match', 'pattern' => '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu'],

            // Whau
            ['whau', 'string', 'min' => 1, 'max' => 200],
        ];
    }
}
