<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $searchModel yiier\merit\models\MeritSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Merits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'user_id',
            'username',
            [
                'attribute' => 'type',
                'value' => function ($data) {
                    return MeritTemplate::getTypes()[$data->type];
                }
            ],
            'merit',
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
