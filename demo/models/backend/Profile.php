<?php

namespace common\modules\users\models\backend;

use yii\db\ActiveRecord;
use Yii;

/**
 * @inheritdoc
 */
class Profile extends \nepster\users\models\Profile
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'create' => ['name', 'surname', 'birthday', 'whau'],
            'update' => ['name', 'surname', 'birthday', 'whau'],
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
            ['whau', 'string', 'min' => 1, 'max' => 255],

            // Birthday
            ['birthday', 'string'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
        ];
    }
}
