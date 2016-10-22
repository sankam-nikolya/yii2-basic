<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\TinyPngSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'TinyPng API-keys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiny-png-index">

<?php echo Html::beginForm([$this->context->id.'/bulkactions'],'post', ['class' => 'form-inline']);?>
<div class="row bulkactions">
    <div class="col-md-4">
        <?php echo Html::a(Yii::t('backend', 'Create TinyPng API-key'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-md-8">
        <div class="input-group pull-right" role="group" aria-label="Actions">
            <?php echo Html::dropDownList('action','',['delete'=>Yii::t('backend', 'Delete'),'publish'=>Yii::t('backend', 'Publish'), 'unpublish'=>Yii::t('backend', 'Unpublish')],['class'=>'form-control','prompt' => Yii::t('backend', '-- select action --')])?>
            <span class="input-group-btn">
                <?php echo Html::submitButton(Yii::t('backend', 'Execute'), ['class' => 'btn btn-info','data-confirm' => Yii::t('backend', 'Confirm selected action')]);?>
            </span>
        </div>
    </div>
</div>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'key',
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
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            [
                'attribute' => 'valid_from',
                'value' => function($model) {
                    return date('d.m.Y', $model->valid_from);
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'valid_from',
                    'type' => \kartik\date\DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            [
                'class'=>\common\grid\EnumColumn::className(),
                'label' => Yii::t('backend', 'Status'),
                'attribute' => 'status',
                'enum'=>[
                    Yii::t('backend', 'Not Published'),
                    Yii::t('backend', 'Published')
                ],
                'filter'=>[
                    Yii::t('backend', 'Not Published'),
                    Yii::t('backend', 'Published')
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ]
        ],
    ]); ?>


<?php echo Html::endForm();?>
</div>