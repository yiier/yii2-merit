<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritTemplate */

$this->title = Yii::t('app', 'Create Merit Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Merit Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merit-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
