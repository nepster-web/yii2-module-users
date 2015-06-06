<?php

namespace common\modules\users\models\backend;

use yii\db\ActiveRecord;
use Yii;

/**
 * @inheritdoc
 */
class LegalPerson extends \nepster\users\models\LegalPerson
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
            ['name', 'trim'],

            // Юридический адрес
            ['address', 'trim'],

            // ОГРН
            ['BIN', 'trim'],

            // Банк
            ['bank', 'trim'],

            // Расчетный счет
            ['account', 'trim'],
        ];
    }
}
