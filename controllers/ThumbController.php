<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ThumbController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'glide' => 'trntv\glide\actions\GlideAction',
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

}
