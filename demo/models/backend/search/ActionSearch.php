<?php

namespace common\modules\users\models\backend\search;

use nepster\users\models\Action;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;

/**
 * @inheritdoc
 */
class ActionSearch extends Action
{
    /**
     * @var string
     */
    public $username;

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
            [['username', 'application', 'module', 'action', 'date_from', 'date_to', 'ip'], 'string'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'username' => Yii::t('users', 'USER'),
            'date_from' => Yii::t('users', 'DATE_FROM'),
            'date_to' => Yii::t('users', 'DATE_TO'),
        ]);
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
                'user' => function ($query) {
                    return $query->joinWith([
                        'profile'
                    ]);
                }
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

        $dataProvider->sort->attributes['username'] = [
            'asc' => [self::tableName() . '.user_id' => SORT_ASC],
            'desc' => [self::tableName() . '.user_id' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->date_from)) {
            $dateFrom = strtotime($this->date_from);
            $query->andWhere(self::tableName(). '.time_create >= :date_from', [':date_from' => $dateFrom]);
        }

        if (!empty($this->date_to)) {
            $dateTo = strtotime($this->date_to) + 86400; // Минимальный диапазон выборки 24 часа
            $query->andWhere(self::tableName(). '.time_create <= :date_to', [':date_to' => $dateTo]);
        }

        if (!empty($this->application)) {
            $query->andWhere('application LIKE :application', [':application' => '%' . $this->application . '%']);
        }

        if (!empty($this->module)) {
            $query->andWhere('module LIKE :module', [':module' => '%' . $this->module . '%']);
        }

        if (!empty($this->action)) {
            $query->andWhere('action LIKE :action', [':action' => '%' . $this->action . '%']);
        }

        if (!empty($this->username)) {
            $query->andWhere('name LIKE :name OR surname LIKE :surname', [':name' => '%' . $this->username . '%', ':surname' => '%' . $this->username . '%']);
        }

        if (!empty($this->ip)) {
            $query->andWhere('ip LIKE :ip', [':ip' => '%' . ip2long($this->ip) . '%']);
        }

        return $dataProvider;
    }
}
