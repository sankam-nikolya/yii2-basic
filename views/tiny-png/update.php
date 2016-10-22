<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TinyPng */

$this->title = Yii::t('backend', 'Editing') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'TinyPng API-keys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="tiny-png-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
