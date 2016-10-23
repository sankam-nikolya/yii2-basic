<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'maintenance'
    ],
    'sourceLanguage'=>'en-GB',
    'language'=>$params['defaultLanguage'],
    'modules' => [
        'user' => [
            'class' => 'common\modules\user\Module',
            //'shouldBeActivated' => true
        ],
        /*'api' => [
            'class' => 'common\modules\api\Module',
            'modules' => [
                'v1' => 'common\modules\api\v1\Module'
            ]
        ]*/
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'zmQPlBed1FiD_P2GdMwzJ6Zmu!XglMy9aWBl',
        ],
        'cache' => require(__DIR__.'/_cache.php'),
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'db'=>[
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except'=>['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
                    'prefix'=>function () {
                        $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
                        return sprintf('[%s][%s]', Yii::$app->id, $url);
                    },
                    'logVars'=>[],
                    'logTable'=>'{{%system_log}}'
                ]
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => require(__DIR__.'/_urlManager.php'),

        'fileStorage' => [
            'class' => '\trntv\filekit\Storage',
            'baseUrl' => '@web/uploads',
            'filesystem'=> function() {
                $adapter = new \League\Flysystem\Adapter\Local(__DIR__ . '/../web/uploads');
                return new League\Flysystem\Filesystem($adapter);
            },
            'as log' => [
                'class' => 'common\behaviors\FileStorageLogBehavior',
                'component' => 'fileStorage'
            ]
        ],
        'i18n' => [
            'translations' => [
                'app'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                ],
                '*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                    'fileMap'=>[
                        'common'=>'common.php',
                        'backend'=>'backend.php',
                        'frontend'=>'frontend.php',
                    ],
                    'on missingTranslation' => ['\common\modules\i18n\Module', 'missingTranslation']
                ],
                /* Uncomment this code to use DbMessageSource
                 '*'=> [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'{{%i18n_source_message}}',
                    'messageTable'=>'{{%i18n_message}}',
                    'enableCaching' => YII_ENV_DEV,
                    'cachingDuration' => 3600,
                    'on missingTranslation' => ['\common\modules\i18n\Module', 'missingTranslation']
                ],
                */
            ],
        ],
        'keyStorage' => [
            'class' => 'common\components\keyStorage\KeyStorage'
        ],
        'maintenance' => [
            'class' => 'common\components\maintenance\Maintenance',
            'enabled' => function ($app) {
                return $app->keyStorage->get('frontend.maintenance') === 'enabled';
            }
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl'=>['/admin/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ],
        'commandBus' => [
            'class' => 'trntv\bus\CommandBus',
            'middlewares' => [
                [
                    'class' => '\trntv\bus\middlewares\BackgroundCommandMiddleware',
                    'backgroundHandlerPath' => '@console/yii',
                    'backgroundHandlerRoute' => 'command-bus/handle',
                ]
            ]
        ],
        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@app/web',
            'cachePath' => '@runtime/glide',
            'maxImageSize' => 4000,
            'signKey' => 'JhgdGFRjssRDGHsmHawgwoxjGF'
        ],
        'formatter'=>[
            'class'=>'yii\i18n\Formatter'
        ],
        /*'view' => [
            'class' => 'common\modules\minify\View',
            'enableMinify' => !YII_DEBUG,
            'web_path' => '@web',
            'base_path' => '@webroot',
            'minify_path' => '@webroot/min',
            'js_position' => [ \yii\web\View::POS_END ],
            'pack_js' => true,
            //'force_charset' => 'UTF-8',
            'expand_imports' => true,
            'compress_output' => true,
            'compress_options' => [
                'extra' => true,
            ]
        ]*/
    ],
    'as locale' => [
        'class' => 'common\behaviors\LocaleBehavior',
        'enablePreferredLanguage' => true
    ],
    'params' => $params,
  // 301 redirect for /
    /*'on beforeRequest' => function () {
        $pathInfo = Yii::$app->request->pathInfo;
        $query = Yii::$app->request->queryString;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $url = '/' . substr($pathInfo, 0, -1);
            if ($query) {
                $url .= '?' . $query;
            }
            Yii::$app->response->redirect($url, 301);
            Yii::$app->end();
        }
    },*/
    /*'as globalAccess'=>[
        'class'=>'common\behaviors\GlobalAccessBehavior',
        'rules'=>[
            [
                'controllers'=>['sign-in'],
                'allow' => true,
                'roles' => ['?'],
                'actions'=>['login']
            ],
        ]
    ],*/

];
if (YII_ENV_PROD) {
    $config['components']['log']['targets']['email'] = [
        'class' => 'yii\log\EmailTarget',
        'except' => ['yii\web\HttpException:*'],
        'levels' => ['error', 'warning'],
        'message' => ['from' => $config['params']['robotEmail'], 'to' => $config['params']['adminEmail']]
    ];
}

if (YII_ENV_DEV) {
    $config['components']['log']['targets']['email'] = [
        'class' => 'yii\log\EmailTarget',
        'except' => ['yii\web\HttpException:*'],
        'levels' => ['error', 'warning'],
        'message' => ['from' => $config['params']['robotEmail'], 'to' => $config['params']['adminEmail']]
    ];

    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'generators' => [
            'migration' => [
                'class' => '\sankam\gii\migration\generator',
                'templates' => [
                    'default' => '@vendor/sankam-nikolya/yii2-gii-migartion/default',
                    'sql' => '@vendor/sankam-nikolya/yii2-gii-migartion/sql',
                ],
            ],
            'crud' => [
                'class'=>'yii\gii\generators\crud\Generator',
                'templates'=>[
                    'default' => Yii::getAlias('@common/gii/templates'),
                    'extended' => Yii::getAlias('@common/gii/templates_extended/extended')
                ],
                'template' => 'extended',
                'messageCategory' => 'backend'
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates'=>[
                    'default' => Yii::getAlias('@common/gii/model'),
                    'default-witch-metatags' => Yii::getAlias('@common/gii/model_metatags')
                ],
                'template' => 'default',
                'messageCategory' => 'backend'
            ],
        ]
    ];
}

return $config;
