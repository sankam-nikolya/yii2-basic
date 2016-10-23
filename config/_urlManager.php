<?php
return [
    'class'=>'yii\web\UrlManager',
    'enablePrettyUrl'=>true,
    'showScriptName'=>false,
    //'enableStrictParsing' => true,
    'normalizer' => [
        'class' => 'yii\web\UrlNormalizer',
        //'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY, // use temporary redirection instead of permanent
    ],
    'rules'=> [
    	//['pattern'=>'admin/login', 'route'=>'/user/sign-in/login'],

        // Api
        // ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/article', 'only' => ['index', 'view', 'options']],
        // ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/user', 'only' => ['index', 'view', 'options']],



        /* ----------- ADMIN PANEL ----------- */

        ['pattern'=>'login',  'route'=>'admin/login'],
        ['pattern'=>'logout', 'route'=>'admin/logout'],

        ['pattern'=>'admin',  'route'=>'dashboard/index'],

        ['pattern'=>'admin/account', 'route'=>'users/account'],
        ['pattern'=>'admin/profile', 'route'=>'users/profile'],
        ['pattern'=>'admin/users',   'route'=>'users/index'],
        ['pattern'=>'admin/users/<id:\d+>/<action:(create|update|delete)>', 'route'=>'users/<action>'],
        ['pattern'=>'admin/users/<action:(avatar-upload|avatar-delete)>', 'route'=>'users/<action>'],

        ['pattern'=>'admin/menu', 'route'=>'menu/index'],
        ['pattern'=>'admin/menu/bulkactions', 'route'=>'menu/bulkactions'],
        ['pattern'=>'admin/menu/<id:\d+>/<action:(create|update|delete|bulkactions)>', 'route'=>'menu/<action>'],
        
        ['pattern'=>'admin/menu/list', 'route'=>'menu-items/index'],
        ['pattern'=>'admin/menu/bulkactions', 'route'=>'menu-items/bulkactions'],
        ['pattern'=>'admin/menu/<id:\d+>/items', 'route'=>'menu-items/items'],
        ['pattern'=>'admin/menu/list/<id:\d+>/<action:(create|update|delete)>', 'route'=>'menu-items/<action>'],

        ['pattern'=>'admin/tiny-png', 'route'=>'tiny-png/index'],
        ['pattern'=>'admin/tiny-png/bulkactions', 'route'=>'tiny-png/bulkactions'],
        ['pattern'=>'admin/tiny-png/<id:\d+>/<action:(create|update|delete)>', 'route'=>'tiny-png/<action>'],

        ['pattern'=>'admin/settings', 'route'=>'key-storage/index'],
        ['pattern'=>'admin/settings/<id>/<action:(create|update|delete)>', 'route'=>'key-storage/<action>'],

        ['pattern'=>'admin/storage', 'route'=>'file-storage/index'],
        ['pattern'=>'admin/storage/<action:(upload|upload-imperavi)>', 'route'=>'file-storage/<action>'],
        ['pattern'=>'admin/storage/<id:\d+>/<action:(optimize|view|delete)>', 'route'=>'file-storage/<action>'],

        ['pattern'=>'admin/cache', 'route'=>'cache/index'],
        ['pattern'=>'admin/cache/<action:(flush-cache-key|flush-cache-tag)>', 'route'=>'cache/<action>'],
 
        ['pattern'=>'admin/log', 'route'=>'log/index'],
        ['pattern'=>'admin/log/<id:\d+>/<action:(view|delete)>', 'route'=>'log/<action>'],

        /* ----------- SYSTEM ----------- */

        ['pattern'=>'thumb/<path:(.*)>', 'route'=>'thumb/glide', 'encodeParams' => false],
        ['pattern'=>'captcha', 'route'=>'thumb/captcha', 'encodeParams' => false],
    ]
];
