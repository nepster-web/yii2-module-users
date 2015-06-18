<?php

namespace common\modules\users\models\backend;

use yii\db\ActiveRecord;
use Yii;

/**
 * @inheritdoc
 */
class LegalPerson extends \common\modules\users\models\LegalPerson
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'create' => ['name', 'address', 'BIN', 'bank', 'account'],
            'update' => ['name', 'address', 'BIN', 'bank', 'account'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            'create' => self::OP_ALL,
            'update' => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Полное наименование юридического лица
            ['name', 'string'],
            ['name', 'trim'],

            // Юридический адрес
            ['address', 'string'],
            ['address', 'trim'],

            // ОГРН
            ['BIN', 'string'],
            ['BIN', 'trim'],

            // Банк
            ['bank', 'string'],
            ['bank', 'trim'],

            // Расчетный счет
            ['account', 'string'],
            ['account', 'trim'],
        ];
    }
}
