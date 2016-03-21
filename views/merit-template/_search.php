<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritTemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merit-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'unique_id') ?>

    <?= $form->field($model, 'method') ?>

    <?php // echo $form->field($model, 'event') ?>

    <?php // echo $form->field($model, 'action_type') ?>

    <?php // echo $form->field($model, 'rule_key') ?>

    <?php // echo $form->field($model, 'rule_value') ?>

    <?php // echo $form->field($model, 'increment') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
