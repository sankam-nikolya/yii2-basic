<?php

namespace app\controllers;

class DashboardController extends \common\controllers\BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
