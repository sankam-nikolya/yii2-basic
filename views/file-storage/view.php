<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FileStorageItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'File Storage Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-storage-item-view">

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'component',
            'base_url:url',
            'path',
            'type',
            'size:size',
            'name',
            'upload_ip',
            'created_at:datetime'
        ],
    ]) ?>

</div>
