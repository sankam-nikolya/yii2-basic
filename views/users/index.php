<?php

use common\grid\EnumColumn;
use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // 'id',
            [
                'attribute' => 'image',
                'label' => Yii::t('common', 'Picture'),
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        Html::img(
                            Yii::$app->glide->createSignedUrl([
                                'thumb/glide',
                                'path' => $model->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.gif')),
                                'w' => (int) Yii::$app->keyStorage->get('backend.list.thumb')
                            ], true),
                            ['class' => 'img-responsive']),
                    ['update', 'id'=>$model->id]);
                }
            ],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->username, ['update', 'id'=>$model->id]);
                }
            ],
            'email:email',
            [
                'class' => EnumColumn::className(),
                'attribute' => 'status',
                'enum' => User::statuses(),
                'filter' => User::statuses()
            ],
            //'created_at:datetime',
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date('d.m.Y, H:i:s', $model->created_at);
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
            //'logged_at:datetime',
            [
                'attribute' => 'logged_at',
                'value' => function($model) {
                    return date('d.m.Y, H:i:s', $model->logged_at);
                },
                'filter' => \kartik\date\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'logged_at',
                    'type' => \kartik\date\DatePicker::TYPE_INPUT,
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]),
            ],
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ]
        ],
    ]); ?>

</div>
