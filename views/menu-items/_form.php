<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\web\View;


$formatJs = '
$("#menuitems-link_type").change(function(e) {
    var val = parseInt($(this).val());
    var el = $("#menuitems-active");
    if(val == '.\common\models\MenuItems::CONTROLLER_LINK.'){
        el.removeAttr("disabled");
    }else{
        el.val("");
        el.attr("disabled","disabled");
    }
});';

$this->registerJs($formatJs, View::POS_READY);

/* @var $this yii\web\View */
/* @var $model common\models\MenuItems */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="menu-items-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'menu-items-form', 'data-modal-close'=> 1]]); ?>

    <div class="form-group form-actions text-right">
        <div class="btn-group" role="group">
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
            <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create & Close') : Yii::t('backend', 'Update & Close'), ['onclick' => 'document.getElementById("redirect").value="1"', 'class' => 'btn btn-default']) ?>
            <?php  echo Html::a(Yii::t('backend', 'Cancel'), ['index'], ['class' => 'btn btn-default']);?>
        </div>
    </div>

    <?php echo $form->field($model, 'menu_id')->dropDownList(\yii\helpers\ArrayHelper::map(
           \common\models\Menu::find()->active()->all(),
            'id',
            'title'
        )) ?>

    <?php echo $form->field($model, 'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(
           (!empty($model->menu_id)) ? \common\models\MenuItems::find()->where(['menu_id' => $model->menu_id])->andWhere(['<>', 'id', $model->id])->active()->all() : \common\models\MenuItems::find()->where(['<>', 'id', $model->id])->active()->all(),
            'id',
            'title'
        ), ['prompt'=>Yii::t('backend', 'Select parent item')]) ?>

    <?php echo $form->field($model, 'link_type')->dropDownList(\common\models\MenuItems::getLinkTypes()) ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if(!empty($model->menu->icons)):?>
    <?php echo $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
    <?php endif;?>

    <?php echo $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'visible')->dropDownList(\common\models\MenuItems::getLinkVisibilities()) ?>

    <?php 
        $options = ['maxlength' => true];
        if($model->link_type != \common\models\MenuItems::CONTROLLER_LINK) {
            $options['disabled'] = 'disabled';
        }
        echo $form->field($model, 'active')->textInput($options) ?>

    <?php if($model->child) :?>
    <?php echo $form->field($model, 'show_child')->checkbox() ?>
    <?php endif;?> 

    <?php echo $form->field($model, 'target')->checkbox() ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <?php echo Html::hiddenInput('redirect', 0, ['id' => 'redirect']); ?>

    <?php ActiveForm::end(); ?>

</div>
