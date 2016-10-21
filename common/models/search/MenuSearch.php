<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Menu;

/**
 * MenuSearch represents the model behind the search form about `common\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'icons', 'max_levels', 'author_id', 'updater_id', 'status'], 'integer'],
            [['title', 'slug'], 'safe'],
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
        $query = Menu::find();

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

        $query->andFilterWhere([
            'id' => $this->id,
            'icons' => $this->icons,
            'max_levels' => $this->max_levels,
            'author_id' => $this->author_id,
            'updater_id' => $this->updater_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied and current user id
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByUser($params)
    {
        $query = Menu::find();

        if(!Yii::$app->user->can('administrator')) {
            $query->where('[[author_id]]=:author_id', [':author_id' => Yii::$app->user->getId()]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'icons' => $this->icons,
            'max_levels' => $this->max_levels,
            'updater_id' => $this->updater_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
