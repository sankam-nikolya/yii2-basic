<?php

namespace common\modules\api\v1;

use Yii;

class Module extends \common\modules\api\Module
{
    public $controllerNamespace = 'common\modules\api\v1\controllers';

    public function init()
    {
        parent::init();
        Yii::$app->user->identityClass = 'common\modules\api\v1\models\ApiUserIdentity';
        Yii::$app->user->enableSession = false;
        Yii::$app->user->loginUrl = null;
    }
}
