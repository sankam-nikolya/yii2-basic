<?php

namespace app\controllers;

use Yii;
use common\components\keyStorage\FormModel;

class DashboardController extends \common\controllers\BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $model = new FormModel([
            'keys' => [
                'app.name' => [
                    'label' => Yii::t('backend', 'App Name'),
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'rules' => [['required'], ['string']]
                ],
                'app.admin.mail' => [
                    'label' => Yii::t('backend', 'Admin email'),
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'rules' => [['required'], ['email']]
                ],
                'app.robot.email' => [
                    'label' => Yii::t('backend', 'Robot email'),
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'rules' => [['required'], ['email']]
                ],
                'frontend.maintenance' => [
                    'label' => Yii::t('backend', 'Frontend maintenance mode'),
                    'type' => FormModel::TYPE_DROPDOWN,
                    'items' => [
                        'disabled' => Yii::t('backend', 'Disabled'),
                        'enabled' => Yii::t('backend', 'Enabled')
                    ]
                ],
                'frontend.widget.cachetime' => [
                    'label' => Yii::t('backend', 'Widget cachetime (ms)'),
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'rules' => [['required'], ['integer']]
                ],
                /*'frontend.feedback.user.message' => [
                    'label' => Yii::t('backend', 'Frontend Feedback user message'),
                    'type' => FormModel::TYPE_WIDGET,
                    'widget' => '\yii\imperavi\Widget',
                    'options' => [
                        'options' => [
                            'source' => false,
                            'minHeight' => 300,
                            'maxHeight' => 400,
                            'buttonSource' => true,
                            'convertDivs' => true,
                            'removeEmptyTags' => true,
                            'removeAttr' => true
                        ]
                    ],
                    'rules' => [['required']]
                ],*/
                'backend.max.filesize' => [
                    'label' => Yii::t('backend', 'Upload max. file size'),
                    'type' => FormModel::TYPE_TEXTINPUT,
                    'rules' => [['required'], ['integer']]
                ],                
            ]
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend', 'Settings was successfully saved'));

            return $this->refresh();
        }

        return $this->render('settings', ['model' => $model]);
    }
}
