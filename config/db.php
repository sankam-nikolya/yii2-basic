<?php

$db = [];

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=bd_name',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
	'schemaCache' => 'yii\caching\FileCache',
];

if (YII_ENV_DEV) {
	$db = [
	    'class' => 'yii\db\Connection',
	    'dsn' => 'mysql:host=localhost;dbname=bd_name',
	    'username' => 'root',
	    'password' => '',
	    'charset' => 'utf8',
	];
}

return $db;