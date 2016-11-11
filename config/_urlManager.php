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
        /* ----------- FRONTEND ----------- */




        /* ----------- ADMIN PANEL ----------- */

        ['pattern'=>'login',  'route'=>'admin/login'],
        ['pattern'=>'logout', 'route'=>'admin/logout'],

        ['pattern'=>'admin',  'route'=>'dashboard/index'],

        ['pattern'=>'admin/account', 'route'=>'users/account'],
        ['pattern'=>'admin/profile', 'route'=>'users/profile'],
        ['pattern'=>'admin/users',   'route'=>'users/index'],
        ['pattern'=>'admin/users/<id:\d+>/<action:(update|delete)>', 'route'=>'users/<action>'],
        ['pattern'=>'admin/users/<action:(create|avatar-upload|avatar-delete)>', 'route'=>'users/<action>'],

        ['pattern'=>'admin/menu', 'route'=>'menu/index'],
        ['pattern'=>'admin/menu/<action:(create|bulkactions)>', 'route'=>'menu/<action>'],
        ['pattern'=>'admin/menu/<id:\d+>/<action:(create|update|delete|bulkactions)>', 'route'=>'menu/<action>'],
        
        ['pattern'=>'admin/menu/list', 'route'=>'menu-items/index'],
        ['pattern'=>'admin/menu/list/<action:(bulkactions|sorting)>', 'route'=>'menu-items/<action>'],
        ['pattern'=>'admin/menu/list/<id:\d+>/create', 'route'=>'menu-items/create'],
        ['pattern'=>'admin/menu/<id:\d+>/items', 'route'=>'menu-items/items'],
        ['pattern'=>'admin/menu/list/<id:\d+>/<action:(update|delete)>', 'route'=>'menu-items/<action>'],

        ['pattern'=>'admin/tiny-png', 'route'=>'tiny-png/index'],
        ['pattern'=>'admin/tiny-png/<action:(create|bulkactions)>', 'route'=>'tiny-png/<action>'],
        ['pattern'=>'admin/tiny-png/<id:\d+>/<action:(update|delete)>', 'route'=>'tiny-png/<action>'],

        ['pattern'=>'admin/settings', 'route'=>'key-storage/index'],
        ['pattern'=>'admin/settings/<action:(create|bulkactions)>', 'route'=>'key-storage/<action>'],
        ['pattern'=>'admin/settings/<id>/<action:(update|delete)>', 'route'=>'key-storage/<action>'],

        // ['pattern'=>'admin/settings', 'route'=>'dashboard/settings'],
        ['pattern'=>'admin/storage', 'route'=>'file-storage/index'],
        ['pattern'=>'admin/storage/<action:(upload|upload-imperavi|upload-delete)>', 'route'=>'file-storage/<action>'],
        ['pattern'=>'admin/storage/<id:\d+>/<action:(optimize|view|delete)>', 'route'=>'file-storage/<action>'],

        ['pattern'=>'admin/cache', 'route'=>'cache/index'],
        ['pattern'=>'admin/cache/<action:(flush-cache-key|flush-cache-tag)>', 'route'=>'cache/<action>'],
 
        ['pattern'=>'admin/log', 'route'=>'log/index'],
        ['pattern'=>'admin/log/<id:\d+>/<action:(view|delete)>', 'route'=>'log/<action>'],


        /* ----------- SYSTEM ----------- */

        ['pattern'=>'debug/<action>', 'route'=>'debug/<action>'],
        ['pattern'=>'thumb/<path:(.*)>', 'route'=>'thumb/glide', 'encodeParams' => false],
        ['pattern'=>'captcha', 'route'=>'thumb/captcha', 'encodeParams' => false],


        /* ----------- API ----------- */
        
        // ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/article', 'only' => ['index', 'view', 'options']],
        // ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/user', 'only' => ['index', 'view', 'options']],
    ]
];
