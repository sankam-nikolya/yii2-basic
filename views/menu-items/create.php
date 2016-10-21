<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuItems */

$this->title = Yii::t('backend', 'Create Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Menu'), 'url' => ['menu/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-items-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
