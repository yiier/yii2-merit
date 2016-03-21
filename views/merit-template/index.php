<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $searchModel yiier\merit\models\MeritTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Merit Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merit-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Merit Template'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'title',
            [
                'attribute' => 'type',
                'value' => function ($data) {
                    return MeritTemplate::getTypes()[$data->type];
                }
            ],
            'unique_id',
            [
                'attribute' => 'method',
                'value' => function ($data) {
                    return MeritTemplate::getMethods()[$data->method];
                }
            ],
            // 'event',
            [
                'attribute' => 'action_type',
                'value' => function ($data) {
                    return MeritTemplate::getActionTypes()[$data->action_type];
                }
            ],
            'increment',
            [
                'attribute' => 'rule_key',
                'value' => function ($data) {
                    return MeritTemplate::getRuleKeys()[$data->rule_key];
                }
            ],
            'rule_value',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return MeritTemplate::getStatuses()[$data->status];
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
