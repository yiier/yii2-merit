<?php

namespace yiier\merit\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MeritTemplateSearch represents the model behind the search form about `yiier\merit\models\MeritTemplate`.
 */
class MeritTemplateSearch extends MeritTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'method', 'event', 'action_type', 'rule_key', 'rule_value', 'increment', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'unique_id'], 'safe'],
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
        $query = MeritTemplate::find();

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
            'type' => $this->type,
            'method' => $this->method,
            'event' => $this->event,
            'action_type' => $this->action_type,
            'rule_key' => $this->rule_key,
            'rule_value' => $this->rule_value,
            'increment' => $this->increment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'unique_id', $this->unique_id]);

        return $dataProvider;
    }
}
