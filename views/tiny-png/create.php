<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TinyPng */

$this->title = Yii::t('backend', 'Create TinyPng API-key');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'TinyPng API-keys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiny-png-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
