<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItems */

$this->title = Yii::t('backend', 'Editing') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Menu'), 'url' => ['menu/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->menu->title, 'url' => ['items', 'id' => $model->menu->id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="menu-items-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
