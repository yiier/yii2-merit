<?php

namespace yiier\merit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MeritLogSearch represents the model behind the search form about `yiier\merit\models\MeritLog`.
 */
class MeritLogSearch extends MeritLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'merit_template_id', 'type', 'action_type', 'increment', 'created_at'], 'integer'],
            [['description', 'username'], 'safe'],
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
        $query = MeritLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'merit_template_id' => $this->merit_template_id,
            'type' => $this->type,
            'action_type' => $this->action_type,
            'increment' => $this->increment,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}
