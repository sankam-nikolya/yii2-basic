<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$ignored_fields = [
        'created_at',
        'updated_at',
        'author_id',
        'updater_id',
        'views'
    ];

$image_base = false;
$image_path = false;
$image_name = '';

foreach ($generator->getColumnNames() as $attribute) {
    if (strpos($attribute, '_base_url') !== false) {
        $image_base = $attribute;
        $ignored_fields[] = $attribute;
    }

    if (strpos($attribute, '_path') !== false) {
        $image_path = $attribute;
        $image_name = str_replace('_path', '', $attribute);
        $ignored_fields[] = $attribute;
    }
}
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
<?php if($image_base && $image_path && $image_name):?>
use trntv\filekit\widget\Upload;
<?php endif;?>

/* @var $this yii\web\View */
/* @var $model <?php echo ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="<?php echo Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?php echo "<?php " ?>$form = ActiveForm::begin(['options' => ['id' => '<?php echo Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form', 'data-modal-close'=> 1]]); ?>


<?php foreach ($generator->getColumnNames() as $attribute) {
    if($attribute == $image_base) {
        echo "    <?php echo \$form->field(\$model, '$image_name')->widget(\n";
        echo "        Upload::className(),\n";
        echo "        [\n";
        echo "            'url' => ['file-storage/upload'],\n";
        echo "            'maxFileSize' => Yii::\$app->keyStorage->get('system.max.filesize', 5000000),\n";
        echo "            'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),\n";
        echo "        ]\n";
        echo "    );?>\n\n";
    }
    if($attribute == 'published_at') {
        echo "    <?php " . generateActiveField($attribute, $generator) . " ?>\n\n";
    } elseif (in_array($attribute, $safeAttributes)) {
        /*echo "    <?php echo " . $generator->generateActiveField($attribute) . " ?>\n\n";*/
        if(!in_array($attribute, $ignored_fields)) {
            echo "    <?php echo " . generateActiveField($attribute, $generator) . " ?>\n\n";
        }
    }
} ?>

    <?php echo "<?php " ?>ActiveForm::end(); ?>

</div>
