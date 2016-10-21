<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'menu-form', 'data-modal-close'=> 1]]); ?>

    <div class="form-group form-actions text-right">
        <div class="btn-group" role="group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create & Close') : Yii::t('backend', 'Update & Close'), ['onclick' => 'document.getElementById("redirect").value="1"', 'class' => 'btn btn-default']) ?>
            <?php  echo Html::a(Yii::t('backend', 'Cancel'), 'index', ['class' => 'btn btn-default']);?>
        </div>
    </div>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'slug')
        ->hint(Yii::t('backend', 'If you\'ll leave this field empty, it will be generated automatically'))
        ->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'max_levels')->textInput() ?>

    <?php echo $form->field($model, 'icons')->checkbox() ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php echo Html::hiddenInput('redirect', 0, ['id' => 'redirect']); ?>

    <?php ActiveForm::end(); ?>

</div>
