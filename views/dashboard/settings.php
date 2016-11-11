<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = Yii::t('backend', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('backend', 'Settings');
?>
<div class="article-form">
	<?php echo \common\components\keyStorage\FormWidget::widget([
	    'model' => $model,
	    'formClass' => '\yii\bootstrap\ActiveForm',
	    'submitText' => Yii::t('backend', 'Save'),
	    'submitOptions' => ['class' => 'btn btn-success']
	]); ?>
</div>

