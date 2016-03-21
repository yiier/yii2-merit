<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiier\merit\models\MeritTemplate;

/* @var $this yii\web\View */
/* @var $model yiier\merit\models\MeritTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="merit-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(MeritTemplate::getTypes()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unique_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'method')->dropDownList(MeritTemplate::getMethods()) ?>

    <?php // echo  $form->field($model, 'event')->textInput() ?>

    <?= $form->field($model, 'action_type')->dropDownList(MeritTemplate::getActionTypes()) ?>

    <?= $form->field($model, 'increment')->textInput() ?>

    <?= $form->field($model, 'rule_key')->dropDownList(MeritTemplate::getRuleKeys()) ?>

    <?= $form->field($model, 'rule_value')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(MeritTemplate::getStatuses()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
