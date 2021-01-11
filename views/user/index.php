<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=> 'firstname',
                'label' => 'Name',
                'value' => function($searchModel){
                    return $searchModel->firstname . ' ' . $searchModel->lastname;
                }
            ],
            [
                'attribute' => 'date_of_birth',
                'label' => 'Age',
                'filter' => FALSE,
                'value' => function($model){
                    $date = new DateTime($model->date_of_birth);
                    $now = new DateTime();
                    $interval = $now->diff($date);
                    return $interval->y;
                }
            ],
            'email:email',
            'gender',
            [
                'attribute' => 'social',
                'label' => 'Social',
                'value' => function($model){
                    return str_replace(',',' ', $model->social);
                }
            ],
            [
                'attribute' => 'profile_image',
                'label' => 'Profile Image',
                'filter' => FALSE,
                'options' => ['style' => 'width:75px;'],
                'format' => 'raw',
                'value' => function($model){
                    return Html::tag('div','',[
                        'style'=>'width:150px;height:95px;
                        border-top: 10px solid rgba(255, 255, 255, .46);
                        background-image:url('.$model->photoViewer.');
                        background-size: cover;
                        background-position:center center;
                        background-repeat:no-repeat;
                          
                    ']);
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
