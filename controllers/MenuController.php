<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Menu;
use common\models\search\MenuSearch;
use common\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['administrator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkactions' => ['post'],
                    'menu-list' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Bulk actions with model items.
     * After action execution the browser will be redirected back to the page.
     * @return mixed
     */
    public function actionBulkactions()
    {
        $action     = trim(Yii::$app->request->post('action'));
        $selection  = (array)Yii::$app->request->post('selection');
        $redirect   = Yii::$app->request->referrer;

        if(empty($action)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('backend', 'Action not set.'));
            $this->redirect($redirect);
        }

        if(empty($selection)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('backend', 'Rows not set.'));
            $this->redirect($redirect);
        }

        switch ($action) {
            case 'delete':
                Menu::deleteAll(['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been deleted successfully.'));
                break;

            case 'publish':
                Menu::updateAll(['status' => Menu::STATUS_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been published successfully.'));
                break;

            case 'unpublish':
                Menu::updateAll(['status' => Menu::STATUS_NOT_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been unpublished successfully.'));
                break;
        }

        $this->redirect($redirect);
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        /**
         * Lists all Menu models.
         */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['id'=>SORT_ASC]
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend', 'Item «<strong>{title}</strong>» created.', ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend', 'Item «<strong>{title}</strong>» updated.', ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        Yii::$app->getSession()->setFlash('success', Yii::t('backend', 'Item «<strong>{title}</strong>» deleted.', ['title' => $model->title]));

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
