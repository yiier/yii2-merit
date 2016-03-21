<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $searchModel yiier\merit\models\MeritLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Merit Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merit-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'user_id',
            'username',
            'merit_template_id',
            [
                'attribute' => 'type',
                'value' => function ($data) {
                    return MeritTemplate::getTypes()[$data->type];
                }
            ],
            'description',
            [
                'attribute' => 'action_type',
                'value' => function ($data) {
                    return MeritTemplate::getActionTypes()[$data->action_type];
                }
            ],
             'increment',
             'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
