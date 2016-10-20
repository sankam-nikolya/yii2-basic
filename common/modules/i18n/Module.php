<?php

namespace common\modules\i18n;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\i18n\controllers';

    public function init()
    {
        parent::init();
    }

    /**
     * @param \yii\i18n\MissingTranslationEvent $event
     */
    public static function missingTranslation($event)
    {
        // do something with missing translation
    }
}
