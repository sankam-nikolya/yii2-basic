<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\widgets\Menu;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAsset;
use xj\bootbox\BootboxAsset;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;

AdminAsset::register($this);
BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
        <?php 
            NavBar::begin([
                'brandLabel' => Yii::$app->keyStorage->get('app.name', Yii::t('backend', 'Site')),
                'innerContainerOptions' => ['class'=>'container'],
                'options' => [
                    'class' => 'navbar navbar-default navbar-fixed-side',
                ],
            ]);
            echo Menu::widget([
                'options'=>['class'=>'nav navbar-nav'],
                'linkTemplate' => '<a href="{url}"{linkOptions}>{icon}<span>{label}</span>{right-icon}{badge}</a>',
                'submenuTemplate'=>"\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n",
                'activateParents'=>true,
                'items'=>[
                    [
                        'label'=>Yii::t('backend', 'Main'),
                        'options'=>['class'=>'header'],
                    ],
                    [
                        'label'=>Yii::t('backend', 'Content'),
                        'url' => '#',
                        'options'=>['class'=>'dropdown'],
                        'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'aria-expanded'=>'false', 'role' => 'button'],
                        'items'=>[
                            ['label'=>Yii::t('backend', 'Static pages'), 'url'=>['/page/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Articles'), 'url'=>['/article/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Article Categories'), 'url'=>['/article-category/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Text Widgets'), 'url'=>['/widget-text/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Menu Widgets'), 'url'=>['/widget-menu/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                            ['label'=>Yii::t('backend', 'Carousel Widgets'), 'url'=>['/widget-carousel/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                        ]
                    ],
                    [
                        'label'=>Yii::t('backend', 'System'),
                        'visible'=>Yii::$app->user->can('administrator'),
                        'options'=>['class'=>'header'],
                    ],
                    [
                        'label'=>Yii::t('backend', 'Menu'),
                        'url'=>['menu/index'],
                        'options'=>['class'=>'dropdown'],
                        'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'aria-expanded'=>'false', 'role' => 'button'],
                        'visible'=>Yii::$app->user->can('administrator'),
                        'items'=>[
                            ['label'=>Yii::t('backend', 'Menu'), 'url'=>['menu/index'], 'active'=> Yii::$app->controller->id == 'menu'],
                            ['label'=>Yii::t('backend', 'Menu Items'), 'url'=>['menu-items/index'], 'active'=> Yii::$app->controller->id == 'menu-items'],
                        ]
                    ],
                    [
                        'label'=>Yii::t('backend', 'Users'),
                        'url'=>['/users/index'],
                        'visible'=>Yii::$app->user->can('administrator'),
                        'active'=> Yii::$app->controller->id == 'users'
                    ],
                    [
                        'label'=>Yii::t('backend', 'Settings'),
                        'url' => '#',
                        'options'=>['class'=>'dropdown'],
                        'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'aria-expanded'=>'false', 'role' => 'button'],
                        'visible'=>Yii::$app->user->can('administrator'),
                        'items'=>[
                            /* [
                                'label'=>Yii::t('backend', 'i18n'),
                                'url' => '#',
                                'options'=>['class'=>'dropdown-toggle'],
                                'items'=>[
                                    ['label'=>Yii::t('backend', 'i18n Source Message'), 'url'=>['/i18n/i18n-source-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                    ['label'=>Yii::t('backend', 'i18n Message'), 'url'=>['/i18n/i18n-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ]
                            ], */
                            ['label'=>Yii::t('backend', 'TinyPng'), 'url'=>['tiny-png/index'], 'active'=> Yii::$app->controller->id == 'tiny-png'],
                            ['label'=>Yii::t('backend', 'Key-Value Storage'), 'url'=>['key-storage/index'], 'active'=> Yii::$app->controller->id == 'key-storage'],
                            ['label'=>Yii::t('backend', 'File Storage'), 'url'=>['file-storage/index'], 'active'=> Yii::$app->controller->id == 'file-storage'],
                            ['label'=>Yii::t('backend', 'Cache'), 'url'=>['cache/index'], 'active'=> Yii::$app->controller->id == 'cache'],
                            [
                                'label'=>Yii::t('backend', 'Logs'),
                                'url'=>['log/index'],
                                'badge'=>\common\models\SystemLog::find()->count(),
                                'badgeBgClass'=>'label-danger',
                                'active'=> Yii::$app->controller->id == 'log'
                            ],
                        ]
                    ]
                ]
            ]);
        NavBar::end();?>
        </div>
        <div class="col-sm-9 col-lg-10 content">
            <div class="row">
            <?php
                NavBar::begin([
                    'brandLabel' => $this->title,
                    'innerContainerOptions' => ['class'=>'container-fluid'],
                    'options' => [
                        'class' => 'navbar navbar-default navbar-static-top',
                    ],
                ]);
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => [
                        /*[
                            'label' => '<i class="glyphicon glyphicon-warning-sign"></i>',
                            'encode' => false,
                            'url' => '#',
                            'items'=> [
                                '<li>sassass</li>',
                                '<li class="divider"></li>',
                                '<li>sassass</li>',
                            ]
                        ],*/
                        [
                            'label' => Html::img(
                                Yii::$app->glide->createSignedUrl([
                                        'thumb/glide',
                                            'path' => Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.gif')),
                                        'h' => (int) Yii::$app->keyStorage->get('backend.avatar.thumb')
                                    ], true),
                                ['alt' => $item['label'], 'class' => 'login-img']) .
                                ' <span>' . Yii::$app->user->identity->getPublicIdentity() . '</span>',
                            'url' => '#',
                            'encode' => false,
                            'options' => ['class'=>'dropdown'],
                            'linkOptions' => ['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown', 'aria-expanded'=>'false', 'role' => 'button'],
                            'items'=>[
                                    ['label' => Yii::t('backend', 'Profile'), 'url'=>['users/profile']],
                                    ['label' => Yii::t('backend', 'Account'), 'url'=>['users/account']],
                                    '<li class="divider"></li>',
                                    '<li>'
                                        . Html::beginForm(['admin/logout'], 'post', ['class' => 'navbar-form'])
                                        . Html::submitButton(Yii::t('backend', 'Logout') . '</span>', ['class' => 'btn btn-link form-control'])
                                        . Html::endForm() .
                                    '</li>'
                                ]
                        ]
                    ]
                ]);
                NavBar::end(); ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'homeLink' => [
                            'label' => Yii::t('backend', 'Admin Panel'),
                            'url' => ['dashboard/index']
                        ]
                    ]) ?>
                </div>
            </div>
            <?php if (Yii::$app->session->hasFlash('alert')):?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo \yii\bootstrap\Alert::widget([
                        'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ])?>
                </div>
            </div>
            <?php endif; ?>
            <?php if($flash = Yii::$app->session->getFlash('success')) :?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]); ?>
                </div>
            </div>
            <?php elseif($flash = Yii::$app->session->getFlash('error')) :?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]); ?>
                </div>
            </div>
            <?php  endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $content ?>
                </div>
            </div>
            <div class="row before-footer">
                <footer class="footer">
                    <div class="container-fluid">
                        <p class="pull-left">&copy; <?= Yii::$app->keyStorage->get('app.company', '') . ' ' . date('Y') ?></p>
                        <p class="pull-right"><?= Yii::powered() ?></p>
                    </div>
                </footer>
            </div>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
<?php
    yii\bootstrap\Modal::begin([
        'options' => [
            'tabindex' => false
        ],
        'headerOptions' => ['id' => 'modalHeader'],
        'id' => 'modal-form',
        'size' => 'modal-md',
        'clientOptions' => [],// ['backdrop' => 'static', 'keyboard' => FALSE],
        'footer' => Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-success']) . ' ' .
                    Html::button(Yii::t('common', 'Cancel'), [
                            'class' => 'btn btn-default',
                            'data' => [
                                'dismiss' => 'modal',
                                'default-text' => Yii::t('common', 'Cancel'),
                                'success-text' => Yii::t('common', 'Close')
                            ]
                        ])
    ]);
    echo "<div id=\"modalContent\" class=\"preloader\"></div>";
    yii\bootstrap\Modal::end();
?>
</html>
<?php $this->endPage() ?>
