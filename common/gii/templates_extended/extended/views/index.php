<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?php echo $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
<?php echo !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?php echo $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename(str_replace('Controller', '', $generator->controllerClass))))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?php echo Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

<?php echo "<?php";?> echo Html::beginForm([$this->context->id.'/bulkactions'],'post', ['class' => 'form-inline']);<?php echo "?>\n";?>
<div class="row bulkactions">
    <div class="col-md-4">
        <?php echo "<?php echo " ?>Html::a(<?php echo $generator->generateString('Create ' . Inflector::pluralize(Inflector::camel2words(StringHelper::basename(str_replace('Controller', '', $generator->controllerClass)))));?>, ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-md-8">
        <div class="input-group pull-right" role="group" aria-label="<?php echo Yii::t('app/form', 'Actions');?>">
            <?php echo "<?php";?> echo Html::dropDownList('action','',['delete'=><?php echo $generator->generateString('Delete');?>,'publish'=><?php echo $generator->generateString('Publish');?>, 'unpublish'=><?php echo $generator->generateString('Unpublish');?>],['class'=>'form-control','prompt' => <?php echo $generator->generateString('-- select action --');?>])<?php echo "?>\n";?>
            <span class="input-group-btn">
                <?php echo "<?php";?> echo Html::submitButton(<?php echo $generator->generateString('Execute');?>, ['class' => 'btn btn-info','data-confirm' => <?php echo $generator->generateString('Confirm selected action');?>]);<?php echo "?>\n";?>
            </span>
        </div>
    </div>
</div>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?php echo "<?php echo " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?php echo !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\CheckboxColumn'],
            //['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            switch ($name) {
                case 'id':
                    echo "            // '" . $name . "',\n";
                    --$count;
                    break;
                case 'title':
                case 'name':
                    echo "            [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'format' => 'raw',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return Html::a(\$model->". $name.", ['update', 'id'=>\$model->id]);\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'description':
                case 'description_short':
                case 'short_description':
                    echo "            [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'format' => 'text',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \yii\helpers\StringHelper::truncate( strip_tags(\$model->".$name."), 100, '...');\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'status':
                    echo "            [\n";
                    echo "                'class'=>\common\grid\EnumColumn::className(),\n";
                    echo "                'label' => ". $generator->generateString('Status').",\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'enum'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ],\n";
                    echo "                'filter'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ]\n";
                    echo "            ],\n";
                    break;

                case 'author_id':
                case 'updater_id':
                    echo "            [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \$model->".str_replace('_id', '', $name)."->username;\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'created_at':
                case 'updated_at':
                    echo "            // '" . $name . ":datetime',\n";
                    echo "            [\n";
                    echo "                'attribute' => '" . $name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y, H:m:i', \$model->" . $name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ],\n";
                    break;

                case 'published_at':
                    echo "            // '" . $name . ":datetime',\n";
                    echo "            [\n";
                    echo "                'attribute' => '" . $name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y', \$model->" . $name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ],\n";
                    break;

                default:
                    echo "            '" . $name . "',\n";
                    break;
            }
        } else {
            switch ($name) {
                case 'title':
                case 'name':
                    echo "            /* [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'format' => 'raw',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return Html::a(\$model->". $name.", ['update', 'id'=>\$model->id]);\n";
                    echo "                }\n";
                    echo "            ], */\n";
                    break;

                case 'description':
                case 'description_short':
                case 'short_description':
                    echo "            /* [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'format' => 'text',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \yii\helpers\StringHelper::truncate( strip_tags(\$model->".$name."), 100, '...');\n";
                    echo "                }\n";
                    echo "            ], */\n";
                    break;

                case 'status':
                    echo "            /* [\n";
                    echo "                'class'=>\common\grid\EnumColumn::className(),\n";
                    echo "                'label' => ". $generator->generateString('Status').",\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'enum'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ],\n";
                    echo "                'filter'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ]\n";
                    echo "            ], */\n";
                    break;

                case 'author_id':
                case 'updater_id':
                    echo "            /* [\n";
                    echo "                'attribute' => '". $name."',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \$model->".str_replace('_id', '', $name)."->username;\n";
                    echo "                }\n";
                    echo "            ], */\n";
                    break;

                case 'created_at':
                case 'updated_at':
                    echo "            // '" . $name . ":datetime',\n";
                    echo "            /* [\n";
                    echo "                'attribute' => '" . $name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y, H:m:i', $model->" . $name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ], */\n";
                    break;

                case 'published_at':
                    echo "            // '" . $name . ":datetime',\n";
                    echo "            /* [\n";
                    echo "                'attribute' => '" . $name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y', $model->" . $name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ], */\n";
                    break;

                default:
                    echo "            // '" . $name . "',\n";
                    break;
            }
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            switch ($column->name) {
                case 'id':
                    echo "            // '" . $column->name . "',\n";
                    --$count;
                    break;
                case 'title':
                case 'name':
                    echo "            [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'format' => 'raw',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return Html::a(\$model->". $column->name.", ['update', 'id'=>\$model->id]);\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'description':
                case 'description_short':
                case 'short_description':
                    echo "            [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'format' => 'text',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \yii\helpers\StringHelper::truncate( strip_tags(\$model->".$column->name."), 100, '...');\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'status':
                    echo "            [\n";
                    echo "                'class'=>\common\grid\EnumColumn::className(),\n";
                    echo "                'label' => ". $generator->generateString('Status').",\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'enum'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ],\n";
                    echo "                'filter'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ]\n";
                    echo "            ],\n";
                    break;

                case 'author_id':
                case 'updater_id':
                    echo "            [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \$model->".str_replace('_id', '', $column->name)."->username;\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'created_at':
                case 'updated_at':
                    echo "            // '" . $column->name . ":datetime',\n";
                    echo "            [\n";
                    echo "                'attribute' => '" . $column->name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y, H:m:i', \$model->" . $column->name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $column->name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ],\n";
                    break;

                case 'published_at':
                    echo "            // '" . $column->name . ":datetime',\n";
                    echo "            [\n";
                    echo "                'attribute' => '" . $column->name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y', \$model->" . $column->name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $column->name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ],\n";
                    break;

                default:
                    echo "            '" . $column->name . "',\n";
                    break;
            }
        } else {
            switch ($column->name) {
                case 'title':
                case 'name':
                    echo "            /* [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'format' => 'raw',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return Html::a(\$model->". $column->name.", ['update', 'id'=>\$model->id]);\n";
                    echo "                }\n";
                    echo "            ], */\n";
                    break;

                case 'description':
                case 'description_short':
                case 'short_description':
                    echo "            [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'format' => 'text',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \yii\helpers\StringHelper::truncate( strip_tags(\$model->".$column->name."), 100, '...');\n";
                    echo "                }\n";
                    echo "            ],\n";
                    break;

                case 'status':
                    echo "            /* [\n";
                    echo "                'class'=>\common\grid\EnumColumn::className(),\n";
                    echo "                'label' => ". $generator->generateString('Status').",\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'enum'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ],\n";
                    echo "                'filter'=>[\n";
                    echo "                    ".$generator->generateString('Not Published').",\n";
                    echo "                    ".$generator->generateString('Published')."\n";
                    echo "                ]\n";
                    echo "            ], */\n";
                    break;

                case 'author_id':
                case 'updater_id':
                    echo "            /* [\n";
                    echo "                'attribute' => '". $column->name."',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return \$model->".str_replace('_id', '', $column->name)."->username;\n";
                    echo "                }\n";
                    echo "            ], */\n";
                    break;

                case 'created_at':
                case 'updated_at':
                    echo "            // '" . $column->name . ":datetime',\n";
                    echo "            /* [\n";
                    echo "                'attribute' => '" . $column->name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y, H:m:i', \$model->" . $column->name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $column->name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ], */\n";
                    break;

                case 'published_at':
                    echo "            // '" . $column->name . ":datetime',\n";
                    echo "            /* [\n";
                    echo "                'attribute' => '" . $column->name . "',\n";
                    echo "                'value' => function(\$model) {\n";
                    echo "                    return date('d.m.Y', \$model->" . $column->name . ");\n";
                    echo "                },\n";
                    echo "                'filter' => \kartik\date\DatePicker::widget([\n";
                    echo "                    'model' => \$searchModel,\n";
                    echo "                    'attribute' => '" . $column->name . "',\n";
                    echo "                    'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                    echo "                    'pluginOptions' => [\n";
                    echo "                        'autoclose' => true,\n";
                    echo "                        'format' => 'dd.mm.yyyy',\n";
                    echo "                        'todayHighlight' => true\n";
                    echo "                    ]\n";
                    echo "                ]),\n";
                    echo "            ], */\n";
                    break;

                default:
                    echo "            // '" . $column->name . "',\n";
                    break;
            }
        }
    }
}
?>

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ]
        ],
    ]); ?>
<?php else: ?>
    <?php echo "<?php echo " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?php echo $nameAttribute ?>), ['view', <?php echo $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>


<?php echo "<?php";?> echo Html::endForm();<?php echo "?>\n";?>
</div>