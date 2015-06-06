<?php

namespace common\modules\users\models\backend;

use common\modules\users\models\Profile;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;

/**
 * @inheritdoc
 */
class UserSearch extends User
{
    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $contacts;

    /**
     * @var string
     */
    public $date_from;

    /**
     * @var string
     */
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['user', 'role', 'contacts', 'date_from', 'date_to'], 'string'],
            ['status', 'in', 'range' => array_keys(self::getStatusArray())],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 's';
    }

    /**
     * Создает экземпляр поставщика данных с поисковым запросом
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()
            ->joinWith([
                'profile'
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'defaultPageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['user'] = [
            'asc' => [self::tableName() . '.id' => SORT_ASC],
            'desc' => [self::tableName() . '.id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['contacts'] = [
            'asc' => [self::tableName() . '.email' => SORT_ASC],
            'desc' => [self::tableName() . '.email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            'status' => $this->status,
        ]);

        if (!empty($this->date_from)) {
            $dateFrom = strtotime($this->date_from);
            $query->andWhere('time_create >= :date_from', [':date_from' => $dateFrom]);
        }

        if (!empty($this->date_to)) {
            $dateTo = strtotime($this->date_to) + 86400; // Минимальный диапазон выборки 24 часа
            $query->andWhere('time_create <= :date_to', [':date_to' => $dateTo]);
        }

        if (!empty($this->user)) {
            $query->andWhere('name LIKE :name OR surname LIKE :surname', [':name' => $this->user, ':surname' => $this->user]);
        }

        if (!empty($this->contacts)) {
            $query->andWhere('phone LIKE :phone OR email LIKE :email', [':phone' => $this->contacts, ':email' => $this->contacts]);
        }

        return $dataProvider;
    }
}
