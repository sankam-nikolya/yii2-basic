<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

<?php echo Html::beginForm([$this->context->id.'/bulkactions'],'post', ['class' => 'form-inline']);?>
<div class="row bulkactions">
    <div class="col-md-4">
        <?php echo Html::a(Yii::t('backend', 'Create Menus'), ['create'], ['class' => 'btn btn-success']) ?>
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
            //['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->title, ['update', 'id'=>$model->id]);
                }
            ],
            //'icons',
            [
                'class'=>\common\grid\EnumColumn::className(),
                'attribute' => 'icons',
                'format' => 'raw',
                'enum'=>[
                    '<div class="icon-row"><i class="glyphicon glyphicon-remove non-delete"></i></div>',
                    '<div class="icon-row"><i class="glyphicon glyphicon-ok non-save"></i></div>'
                ],
                'filter'=>[
                    Yii::t('backend', 'No'),
                    Yii::t('backend', 'Yes')
                ]
            ],
            //'max_levels',
            [
                'label' => Yii::t('backend','Menu Items'),
                'format' => 'raw',
                'value' => function ($model) {
                    $total = count($model->menuItems);
                    if($total > 0) {
                        return Html::a(
                            Yii::t('backend','Menu Items') . ' (' . $total . ')',
                            ['menu-items/items', 'id' => $model->id]
                        );
                    } else {
                        return Html::a(
                        Yii::t('backend','Create Menu Item'),
                        ['menu-items/create', 'menu_id' => $model->id]
                    );
                    }
                }
            ],
            /*[
                'attribute' => 'author_id',
                'value' => function($model) {
                    return $model->author->username;
                }
            ],*/
            /*[
                'attribute' => 'updater_id',
                'value' => function($model) {
                    return $model->updater->username;
                }
            ],*/
            // 'created_at:datetime',
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
            // 'updated_at:datetime',
            /* [
                'attribute' => 'updated_at',
                'value' => function($model) {
                    return date('d.m.Y, H:m:i', $model->updated_at);
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'type' => \kartik\date\DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]),
            ], */
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
                'template'=>'{menu-items} {update} {delete}',
                'buttons' => [
                    'menu-items' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-menu-hamburger"></span>',
                            ['menu-items/items', 'id' => $model->id], 
                            [
                                'title' => Yii::t('backend','Menu Items'),
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ]
            ]
        ],
    ]); ?>


<?php echo Html::endForm();?>
</div>