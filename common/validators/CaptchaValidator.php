<?php
namespace common\validators;


use yii\captcha\CaptchaAction;

class CaptchaValidator extends \yii\captcha\CaptchaValidator
{
    public $actionConfig = [];
    /**
     * @inheritdoc
     */
    public function createCaptchaAction()
    {
        return (new CaptchaAction('captcha', \Yii::$app->controller, $this->actionConfig));
    }
}