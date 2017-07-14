<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $searchModel yiier\merit\models\MeritSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $levelCalc \yiier\merit\models\LevelCalcInterface*/

$this->title = Yii::t('app', 'Merits');
$this->params['breadcrumbs'][] = $this->title;
$levelCalc =  \yii\di\Instance::ensure(Yii::$app->params['yiier\merit\models\LevelCalc']);
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
            'pos_accu_merit',
            'level',
            [
                'attribute' => 'level',
                'label' => '等级名称',
                'value' => function ($data) use ($levelCalc) {
                    return $levelCalc->get_levelName($data->level);
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}'],
        ],
    ]); ?>

</div>
