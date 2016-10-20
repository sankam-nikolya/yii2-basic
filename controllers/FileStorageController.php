<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\FileStorageItem;
use common\models\search\FileStorageItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use League\Flysystem\File as FlysystemFile;

/**
 * FileStorageController implements the CRUD actions for FileStorageItem model.
 */
class FileStorageController extends \common\controllers\BackendController
{
    const image_types = [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ];
    
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
                    'upload-delete' => ['delete']
                ]
            ]
        ];
    }


    public function actions()
    {
        return [
            'upload' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'deleteRoute' => 'upload-delete',
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $optimizeImageCommand = new \common\commands\OptimizeImageCommand([
                        'image' => $file
                    ]);
                    Yii::$app->commandBus->handle($optimizeImageCommand);
                }
            ],
            'upload-delete' => [
                'class' => 'trntv\filekit\actions\DeleteAction'
            ],
            'upload-imperavi' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'fileparam' => 'file',
                'responseUrlParam'=> 'filelink',
                'multiple' => false,
                'disableCsrf' => true,
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $optimizeImageCommand = new \common\commands\OptimizeImageCommand([
                        'image' => $file
                    ]);
                    Yii::$app->commandBus->handle($optimizeImageCommand);
                }
            ]
        ];
    }

    /**
     * Lists all FileStorageItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileStorageItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder'=>['created_at'=>SORT_DESC]
        ];
        $components = \yii\helpers\ArrayHelper::map(
            FileStorageItem::find()->select('component')->distinct()->all(),
            'component',
            'component'
        );
        $totalSize = FileStorageItem::find()->sum('size') ?: 0;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'components' => $components,
            'totalSize' => $totalSize
        ]);
    }

    /**
     * Displays a single FileStorageItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Deletes an existing FileStorageItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     */
    public function actionOptimize($id)
    {
        if(empty($id)) {
            return $this->redirect(['index']);
        }

        $id = (int) $id;
        $file = FileStorageItem::findOne($id);

        if(!empty($file)) {
            $filesystem = Yii::$app->fileStorage->filesystem;
            if ($filesystem->has($file->path) === false) {
                throw new NotFoundHttpException(404);
            }

            if(in_array($file->type, self::image_types)) {
                $optimizeImageCommand = new \common\commands\OptimizeImageCommand([
                    'image' => new FlysystemFile($filesystem, $file->path)
                ]);

                Yii::$app->commandBus->handle($optimizeImageCommand);
            }

        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the FileStorageItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileStorageItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileStorageItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
