<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Menu;
use common\models\MenuItems;
use common\models\search\MenuItemsSearch;
use common\controllers\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuItemsController implements the CRUD actions for MenuItems model.
 */
class MenuItemsController extends BackendController
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
                    'menu-items-list' => ['post'],
                    'sorting' => ['post']
                ]
            ]
        ];
    }

    public function actions(){
        return [
            'sorting' => [
                'class' => \kotchuprik\sortable\actions\Sorting::className(),
                'query' => MenuItems::find(),
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
                MenuItems::deleteAll(['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been deleted successfully.'));
                break;

            case 'publish':
                MenuItems::updateAll(['status' => MenuItems::STATUS_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been published successfully.'));
                break;

            case 'unpublish':
                MenuItems::updateAll(['status' => MenuItems::STATUS_NOT_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  Yii::t('backend', 'The selected rows have been unpublished successfully.'));
                break;
        }

        $this->redirect($redirect);
    }

    /*public function actionMenuItemsList($q = null, $id = null) {
        $request = Yii::$app->request;
        $q  = $request->post('q');
        $id = $request->post('id');
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['results' => ['id' => '', 'text' => '']];
            if (!is_null($q)) {
                $query = new yii\db\Query;
                $query->select('[[id]], [[title]] AS [[text]]')
                    ->from(MenuItems::tableName())
                    ->where(['like', 'title', $q])
                    ->andWhere(['status' => 1])
                    ->limit(20);
                $command = $query->createCommand();
                $data = $command->queryAll();
                $out['results'] = array_values($data);
            } elseif ($id > 0) {
                $item = MenuItems::find($id)->one();
                $out['results'] = ['id' => $id, 'text' => $item->title];
            }
            return $out;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    } */

    /**
     * Lists all MenuItems models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuItemsSearch();
        /**
         * Lists all MenuItems models.
         */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => [
                    'menu_id' => SORT_ASC,
                    'parent_id' => SORT_ASC,
                    'order' => SORT_ASC
                ]
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all MenuItems models.
     * @return mixed
     */
    public function actionItems($id)
    {
        

        $searchModel = new MenuItemsSearch();
        /**
         * Lists all MenuItems models.
         */
        $dataProvider = $searchModel->searchByMenu($id, Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => [
                    'order' => SORT_ASC,
                    'parent_id' => SORT_ASC,
                ]
        ];
        $dataProvider->pagination = false;
        
        $menu = Menu::findOne($id);

        return $this->render('items', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'menu' => $menu
        ]);
    }

    /**
     * Displays a single MenuItems model.
     * @param integer $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/


    /**
     * Creates a new MenuItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenuItems();
        $redirect = (boolean) Yii::$app->request->post('redirect');
        $menu_id = (int) Yii::$app->request->get('menu_id', 0);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('backend', 'Item «<strong>{title}</strong>» created.', ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['menu-items/items', 'id' => $model->menu_id]);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            if(!empty($menu_id)) {
                $model->menu_id = $menu_id;
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuItems model.
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
                return $this->redirect(['menu-items/items', 'id' => $model->menu_id]);
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
     * Deletes an existing MenuItems model.
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
     * Finds the MenuItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
        }
    }
}
