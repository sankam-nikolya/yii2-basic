<?php
/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
return [
    'id' => 'storage',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'glide/index',
    'controllerMap' => [
        'glide' => '\trntv\glide\controllers\GlideController'
    ],
    'components' => [
        'urlManager' =>  [
            'class'=>'yii\web\UrlManager',
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'hostInfo' => '@web',
            'rules'=> [
                ['pattern'=>'thumb/<path:(.*)>', 'route'=>'glide/index', 'encodeParams' => false]
            ]
        ],
        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@web/uploads',
            'cachePath' => '@web/uploads/cache',
            'urlManager' => 'urlManagerStorage',
            'maxImageSize' => 4000,
            'signKey' => 'JhgdGFRjssRDGHsmHawgwoxjGF'
        ],
    ]
];
