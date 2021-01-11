<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            
            [['email', 'gender', 'password', 'firstname', 'lastname', 'date_of_birth', 'social', 'profile_image'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = User::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'gender' => $this->gender,
        ]);
        
        $userSearch = [];
        if(isset($params["UserSearch"])){
            $userSearch = $params["UserSearch"];
            if($userSearch["firstname"]){
                $this->firstname = $userSearch["firstname"];
                $this->lastname = $userSearch["firstname"];
            }
        }
        
        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->orFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'social', $this->social])
            ->andFilterWhere(['like', 'profile_image', $this->profile_image]);

        return $dataProvider;
    }
}
