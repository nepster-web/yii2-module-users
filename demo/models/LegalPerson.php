<?php

namespace common\modules\users\models;

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
    public function rules()
    {
        return [
            // Полное наименование юридического лица
            ['name', 'required'],
            ['name', 'trim'],

            // Юридический адрес
            ['address', 'required'],
            ['address', 'trim'],

            // ОГРН
            ['BIN', 'required'],
            ['BIN', 'trim'],

            // Банк
            ['bank', 'required'],
            ['bank', 'trim'],

            // Расчетный счет
            ['account', 'required'],
            ['account', 'trim'],
        ];
    }
}
