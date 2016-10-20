<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TinyPng;

/**
 * TinyPngSearch represents the model behind the search form about `backend\models\TinyPng`.
 */
class TinyPngSearch extends TinyPng
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'created_at', 'updated_at', 'valid_from'], 'safe'],
            [['id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TinyPng::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->created_at)) {
            $query->andFilterWhere([
                'between',
                'created_at',
                strtotime($this->created_at . ' 00:00:00'),
                strtotime($this->created_at  . ' 23:59:59')
            ]);
        }

        if(!empty($this->updated_at)) {
            $query->andFilterWhere([
                'between',
                'updated_at',
                strtotime($this->updated_at . ' 00:00:00'),
                strtotime($this->updated_at  . ' 23:59:59')
            ]);
        }

        if(!empty($this->valid_from)) {
            $query->andFilterWhere([
                'between',
                'valid_from',
                strtotime($this->valid_from . ' 00:00:00'),
                strtotime($this->valid_from  . ' 23:59:59')
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key]);

        return $dataProvider;
    }
}
