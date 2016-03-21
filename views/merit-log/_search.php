<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merit-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'merit_template_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'action_type') ?>

    <?php // echo $form->field($model, 'increment') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
