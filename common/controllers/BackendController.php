<?php

namespace common\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class BackendController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }


    public function beforeAction($action)
    {
        $this->layout = 'admin';
        if (Yii::$app->user->isGuest || !Yii::$app->user->can('loginToBackend')) {
            return $this->redirect(['/admin/login']);
        }
        return parent::beforeAction($action);
    }

}
