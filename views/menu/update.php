<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = Yii::t('backend', 'Editing') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="menu-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
