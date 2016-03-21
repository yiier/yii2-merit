<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritTemplate */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Merit Template',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Merit Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="merit-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
