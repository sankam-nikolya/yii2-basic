<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class Html5shiv extends AssetBundle
{
    public $sourcePath = '@bower/html5shiv';
    public $js = [
        'dist/html5shiv.min.js'
    ];

    public $jsOptions = [
        'condition'=>'lt IE 9',
        'position' => View::POS_HEAD
    ];
}
