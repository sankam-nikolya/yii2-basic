<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

$slug = false;
$author_id = false;

$published_at = false;

$image_base = false;
$image_path = false;
$image_name = '';

if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        switch ($name) {
            case 'slug':
                $slug = true;
                break;

            case 'author_id':
                $author_id = true;
                break;

            case 'published_at':
                $published_at = true;
                break;
        }

        if (strpos($name, '_base_url') !== false) {
            $image_base = $name;
        }

        if (strpos($name, '_path') !== false) {
            $image_path = $name;
            $image_name = str_replace('_path', '', $name);
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        switch ($column->name) {
            case 'slug':
                $slug = true;
                break;

            case 'author_id':
                $author_id = true;
                break;

            case 'published_at':
                $published_at = true;
                break;
        }

        if (strpos($column->name, '_base_url') !== false) {
            $image_base = $column->name;
        }

        if (strpos($column->name, '_path') !== false) {
            $image_path = $column->name;
            $image_name = str_replace('_path', '', $column->name);
        }
    }
}

echo "<?php\n";
?>

namespace <?php echo StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?php echo ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?php echo ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?php echo ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
<?php if($image_base && $image_path && $image_name):?>

<?php endif; ?>

/**
 * <?php echo $controllerClass ?> implements the CRUD actions for <?php echo $modelClass ?> model.
 */
class <?php echo $controllerClass ?> extends <?php echo StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?php if($author_id):?>
            'modelAccess' => [
                'class' => \common\filters\OwnModelAccessFilter::className(),
                'only' => ['view', 'update', 'delete', 'bulkactions'],
                'modelClass' => <?php echo $modelClass ?>::className(),
                'modelCreatedByAttribute' => 'author_id'
            ],
<?php endif;?>
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkactions' => ['post'],
                    '<?php echo yii\helpers\Inflector::camel2id($modelClass.'List') ?>' => ['post'],
                ],
            ],
        ];
    }

<?php if($image_base && $image_path && $image_name):?>
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            '<?php echo $image_name;?>-upload' => [
                'class' => \trntv\filekit\actions\UploadAction::className(),
                'deleteRoute' => '<?php echo $image_name;?>-delete',
                'validationRules' => [
                    ['file', 'file', 'extensions' => 'png, jpg']
                ],
                'on afterSave' => function ($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    $optimizeImageCommand = new \common\commands\OptimizeImageCommand([
                        'image' => $file
                    ]);
                    Yii::$app->commandBus->handle($optimizeImageCommand);
                }
            ],
            '<?php echo $image_name;?>-delete' => [
                'class' => \trntv\filekit\actions\DeleteAction::className()
            ]
        ];
    }
<?php endif;?>

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
            Yii::$app->getSession()->setFlash('error', <?php echo $generator->generateString('Action not set.') ?>);
            $this->redirect($redirect);
        }

        if(empty($selection)) {
            Yii::$app->getSession()->setFlash('error', <?php echo $generator->generateString('Rows not set.') ?>);
            $this->redirect($redirect);
        }

        switch ($action) {
            case 'delete':
                <?php echo $modelClass ?>::deleteAll(['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  <?php echo $generator->generateString('The selected rows have been deleted successfully.') ?>);
                break;

            case 'publish':
                <?php echo $modelClass ?>::updateAll(['status' => <?php echo $modelClass ?>::STATUS_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  <?php echo $generator->generateString('The selected rows have been published successfully.') ?>);
                break;

            case 'unpublish':
                <?php echo $modelClass ?>::updateAll(['status' => <?php echo $modelClass ?>::STATUS_NOT_ACTIVE],  ['id' => $selection]);
                Yii::$app->getSession()->setFlash('success',  <?php echo $generator->generateString('The selected rows have been unpublished successfully.') ?>);
                break;
        }

        $this->redirect($redirect);
    }

<?php
    $empty_str  = '';
    $select_str = '';
    $one_str    = '';

    if($image_base && $image_path && !empty($image_name)) {
        $empty_str  = ", '".$image_name."_base_url' => '', '".$image_name."_path' => ''";
        $select_str = ", [[".$image_name."_base_url]], [[".$image_name."_path]]";
        $one_str    = ", '".$image_name."_base_url' => \$item->".$image_name."_base_url, '".$image_name."_path' => \$item->".$image_name."_path";
    }
?>
    public function action<?php echo $modelClass ?>List($q = null, $id = null) {
        $request = Yii::$app->request;
        $q  = $request->post('q');
        $id = $request->post('id');
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['results' => ['id' => '', 'text' => ''<?php echo $empty_str;?>]];
            if (!is_null($q)) {
                $query = new yii\db\Query;
                $query->select('[[id]], [[title]] AS [[text]]<?php echo $select_str;?>')
                    ->from(<?php echo $modelClass ?>::tableName())
                    ->where(['like', 'title', $q])
                    ->andWhere(['status' => 1])
                    ->limit(20);
                $command = $query->createCommand();
                $data = $command->queryAll();
                $out['results'] = array_values($data);
            } elseif ($id > 0) {
                $item = <?php echo $modelClass ?>::find($id)->one();
                $out['results'] = ['id' => $id, 'text' => $item->title<?php echo $one_str;?>];
            }
            return $out;
        } else {
            throw new NotFoundHttpException(<?php echo $generator->generateString('The requested page does not exist.') ?>);
        }
    }

    /**
     * Lists all <?php echo $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?php echo isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
<?php if($author_id):?>
        /**
         * Lists all <?php echo $modelClass ?> models.
         */
<?php endif;?>
        <?php echo ($author_id) ? '// ' : '';?>$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
<?php if($published_at):?>
        /* $dataProvider->sort = [
            'defaultOrder' => ['published_at' => SORT_DESC]
        ]; */
<?php else:?>
        /* $dataProvider->sort = [
            'defaultOrder' => ['id'=>SORT_DESC]
        ]; */
<?php endif;?>
<?php if($author_id):?>

        /**
         * Lists all <?php echo $modelClass ?> models for current user.
         */
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams);
<?php endif;?>
<?php if($published_at):?>
        $dataProvider->sort = [
            'defaultOrder' => ['published_at' => SORT_DESC]
        ];
<?php else:?>
        $dataProvider->sort = [
            'defaultOrder' => ['id' => SORT_DESC]
        ];
<?php endif;?>

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?php echo $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?php echo $modelClass ?> model.
     * <?php echo implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    /*public function actionView(<?php echo $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?php echo $actionParams ?>),
        ]);
    }*/

    /**
     * Creates a new <?php echo $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $this->renderAjax('_success', [
                        'msg' => <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» created.'), ')') ?>, ['title' => $model->title]),
                    ]);
                }
                Yii::$app->getSession()->setFlash('success', <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» created.'), ')') ?>, ['title' => $model->title]));
                if ($redirect) {
                    return $this->redirect(['index']);
                } else {
                    return $this->redirect(['update', <?= $urlParams ?>]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form_ajax', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /* public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» created.'), ')') ?>, ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['update', <?= $urlParams ?>]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    } */

    /**
     * Updates an existing <?php echo $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?php echo implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $this->renderAjax('_success', [
                    'msg' => <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» updated.'), ')') ?>, ['title' => $model->title]),
                ]);
            }

            Yii::$app->getSession()->setFlash('success', <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» updated.'), ')') ?>, ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['update', <?= $urlParams ?>]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form_ajax', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing <?php echo $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?php echo implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    /* public function actionUpdate(<?php echo $actionParams ?>)
    {
        $model = $this->findModel(<?php echo $actionParams ?>);
        $redirect = (boolean) Yii::$app->request->post('redirect');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» updated.'), ')') ?>, ['title' => $model->title]));

            if ($redirect) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['update', <?= $urlParams ?>]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    } */

    /**
     * Deletes an existing <?php echo $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?php echo implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?php echo $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        if (Yii::$app->getRequest()->isAjax) {
            $this->findModel(<?php echo $actionParams ?>)->delete();

            return $this->renderAjax('_success', [
                    'msg' => <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» deleted.'), ')') ?>, ['title' => $model->title]),
                ]);
        }

        Yii::$app->getSession()->setFlash('success', <?php echo rtrim($generator->generateString('Item «<strong>{title}</strong>» deleted.'), ')') ?>, ['title' => $model->title]));

        $this->findModel(<?php echo $actionParams ?>)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?php echo $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?php echo implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?php echo                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?php echo $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?php echo $modelClass ?>::findOne(<?php echo $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(<?php echo $generator->generateString('The requested page does not exist.')?>);
        }
    }
}
