<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FileStorageItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $components array */
/* @var $totalSize integer */

$this->title = Yii::t('backend', 'File Storage Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-storage-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row text-right">
        <div class="pull-right">
            <div class="col-xs-12">
                <dl>
                    <dt>
                        <?php echo Yii::t('backend', 'Used size') ?>:
                    </dt>
                    <dd>
                        <?php echo Yii::$app->formatter->asShortSize($totalSize); ?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="pull-right">
            <div class="row">
                <div class="col-xs-12">
                    <dl>
                        <dt>
                            <?php echo Yii::t('backend', 'Count') ?>:
                        </dt>
                        <dd>
                            <?php echo $dataProvider->totalCount ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'component',
                'filter' => $components
            ],
            'path',
            'type',
            'size:shortSize',
            //'name',
            'upload_ip',
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date('d.m.Y, H:m:i', $model->created_at);
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => \kartik\date\DatePicker::TYPE_INPUT,
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            [
                'class' => \common\grid\EnumColumn::className(),
                'attribute' => 'optimized',
                'label' => Yii::t('backend', 'Optimized'),
                'enum' => [
                    Yii::t('backend', 'No'),
                    Yii::t('backend', 'Yes')
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'update'=>function ($url, $model) {
                        if(in_array($model->type, \app\controllers\FileStorageController::image_types)) {
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-compressed"></span>', ['optimize','id'=>$model->id],
                                    ['title' => Yii::t('backend', 'Optimize'), 'data-pjax' => '0']);
                        } else {
                            return '';  
                        }
                    }
                ],
                'template' => '{update} {view} {delete}'
            ]
        ]
    ]); ?>

</div>
