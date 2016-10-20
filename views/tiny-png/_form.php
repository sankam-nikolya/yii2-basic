<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TinyPng */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="tiny-png-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'tiny-png-form', 'data-modal-close'=> 1]]); ?>

    <div class="form-group form-actions text-right">
        <div class="btn-group" role="group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create & Close') : Yii::t('backend', 'Update & Close'), ['onclick' => 'document.getElementById("redirect").value="1"', 'class' => 'btn btn-default']) ?>
            <?php  echo Html::a(Yii::t('backend', 'Cancel'), 'index', ['class' => 'btn btn-default']);?>
        </div>
    </div>

    <?php echo $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?php $model->valid_from = !(empty($model->valid_from)) ? Yii::$app->formatter->asDate($model->valid_from, 'dd.MM.yyyy') : date('d.m.Y');
        echo $form->field($model, 'valid_from')->widget(\kartik\date\DatePicker::className(),
            [
                'type' => \kartik\date\DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ]
            ]
        );
    ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php echo Html::hiddenInput('redirect', 0, ['id' => 'redirect']); ?>

    <?php ActiveForm::end(); ?>

</div>
