<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritTemplate */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Merit Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merit-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'type',
                'value' => MeritTemplate::getTypes()[$model->type]
            ],
            'unique_id',
            [
                'attribute' => 'method',
                'value' => MeritTemplate::getMethods()[$model->method]
            ],
            // 'event',
            [
                'attribute' => 'action_type',
                'value' => MeritTemplate::getActionTypes()[$model->action_type]
            ],
            'increment',
            [
                'attribute' => 'rule_key',
                'value' => MeritTemplate::getRuleKeys()[$model->rule_key]
            ],
            'rule_value',
            [
                'attribute' => 'status',
                'value' => MeritTemplate::getStatuses()[$model->status]
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
