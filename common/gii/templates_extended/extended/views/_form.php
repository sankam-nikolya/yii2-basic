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

   function generateActiveField($attribute, $generator)
    {
        switch ($attribute) {
            case 'status':
                return "\$form->field(\$model, '$attribute')->checkbox()";
                break;

            case 'description':
            case 'description_en':
            case 'desc':
            case 'desc_en':
                $html  = "\$form->field(\$model, '$attribute')->widget(\n";
                $html .= "        \yii\imperavi\Widget::className(),\n";
                $html .= "        [\n";
                //$html .= "            'plugins' => ['fullscreen', 'fontcolor', 'video'],\n";
                $html .= "            'options' => [\n";
                //$html .= "                'minHeight' => 300,\n";
                //$html .= "                'maxHeight' => 400,\n";
                //$html .= "                'buttonSource' => true,\n";
                //$html .= "                'convertDivs' => false,\n";
                //$html .= "                'removeEmptyTags' => false,\n";
                //$html .= "                'imageUpload' => Yii::\$app->urlManager->createUrl(['/file-storage/upload-imperavi'])\n";
                $html .= "                'source' => false,\n";
                $html .= "                'buttons' => ['html','bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist'],\n";
                $html .= "                'minHeight' => 300,\n";
                $html .= "                'maxHeight' => 400,\n";
                $html .= "                'buttonSource' => true,\n";
                $html .= "                'convertDivs' => true,\n";
                $html .= "                'removeEmptyTags' => true,\n";
                $html .= "                'removeAttr' => true\n";
                $html .= "            ]\n";
                $html .= "        ]\n";
                $html .= "    );\n";
                return $html;
                break;

            case 'short_description':
            case 'description_short':
            case 'short_desc':
            case 'short_desc_en':
                $html  = "\$form->field(\$model, '$attribute')->widget(\n";
                $html .= "        \yii\imperavi\Widget::className(),\n";
                $html .= "        [\n";
                //$html .= "            'plugins' => ['fullscreen', 'fontcolor', 'video'],\n";
                $html .= "            'options' => [\n";
                //$html .= "                'minHeight' => 300,\n";
                //$html .= "                'maxHeight' => 400,\n";
                //$html .= "                'buttonSource' => true,\n";
                //$html .= "                'convertDivs' => false,\n";
                //$html .= "                'removeEmptyTags' => false,\n";
                //$html .= "                'imageUpload' => Yii::\$app->urlManager->createUrl(['/file-storage/upload-imperavi'])\n";
                $html .= "                'source' => false,\n";
                $html .= "                'buttons' => ['html','bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist'],\n";
                $html .= "                'minHeight' => 200,\n";
                $html .= "                'maxHeight' => 200,\n";
                $html .= "                'buttonSource' => true,\n";
                $html .= "                'convertDivs' => true,\n";
                $html .= "                'removeEmptyTags' => true,\n";
                $html .= "                'removeAttr' => true\n";
                $html .= "            ]\n";
                $html .= "        ]\n";
                $html .= "    );\n";
                return $html;
                break;

            case 'published_at':
                $html  = "\$model->published_at = !(empty(\$model->published_at)) ? Yii::\$app->formatter->asDate(\$model->published_at, 'dd.MM.yyyy') : date('d.m.Y');\n";
                $html .= "        echo \$form->field(\$model, 'published_at')->widget(\kartik\date\DatePicker::className(),\n";
                $html .= "            [\n";
                $html .= "                'type' => \kartik\date\DatePicker::TYPE_INPUT,\n";
                $html .= "                'pluginOptions' => [\n";
                $html .= "                    'autoclose' => true,\n";
                $html .= "                    'format' => 'dd.mm.yyyy',\n";
                $html .= "                    'todayHighlight' => true\n";
                $html .= "                ]\n";
                $html .= "            ]\n";
                $html .= "        );\n";
                $html .= "   ";
                return $html;
                break;

            case 'created_at':
            case 'updated_at':
                return "\$form->field(\$model, '$attribute')->widget(trntv\yii\datetime\DateTimeWidget::className(), ['phpDatetimeFormat' => 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ'])";
        }


        $tableSchema = $generator->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            } else {
                return "\$form->field(\$model, '$attribute')";
            }
        }

        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } elseif ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        } else {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\$model, '$attribute')->dropDownList("
                    . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$form->field(\$model, '$attribute')->$input()";
            } else {
                if($attribute == 'slug') {
                    $html  = "\$form->field(\$model, '$attribute')\n";
                    $html .= "        ->hint(".$generator->generateString('If you\'ll leave this field empty, slug will be generated automatically').")\n";
                    $html .= "        ->$input(['maxlength' => true])";
                    return $html;
                } else {
                    return "\$form->field(\$model, '$attribute')->$input(['maxlength' => true])";
                }
            }
        }
    }


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
use yii\bootstrap\Alert;
<?php if($image_base && $image_path && $image_name):?>
use trntv\filekit\widget\Upload;
<?php endif;?>

/* @var $this yii\web\View */
/* @var $model <?php echo ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="<?php echo Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?php echo "<?php " ?>$form = ActiveForm::begin(['options' => ['id' => '<?php echo Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form', 'data-modal-close'=> 1]]); ?>

    <div class="form-group form-actions text-right">
        <div class="btn-group" role="group">
            <?= "<?php echo " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-info']) ?>
            <?= "<?php echo " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create & Close') ?> : <?= $generator->generateString('Update & Close') ?>, ['onclick' => 'document.getElementById("redirect").value="1"', 'class' => 'btn btn-default']) ?>
            <?= "<?php " ?> echo Html::a(<?= $generator->generateString('Cancel') ?>, 'index', ['class' => 'btn btn-default']);?>
        </div>
    </div>

<?php echo "<?php ";?>if($flash = Yii::$app->session->getFlash('success')) :?>
    <div class="form-group">
        <?php echo "<?php ";?>echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]); ?>
    </div>
<?php echo "<?php ";?>elseif($flash = Yii::$app->session->getFlash('error')) :?>
    <div class="form-group">
        <?php echo "<?php ";?>echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]); ?>
    </div>
<?php echo "<?php ";?> endif; ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if($attribute == $image_base) {
        echo "    <?php echo \$form->field(\$model, '$image_name')->widget(\n";
        echo "        Upload::className(),\n";
        echo "        [\n";
        echo "            'url' => ['/file-storage/upload'],\n";
        echo "            'maxFileSize' => 5000000, // 5 MiB\n";
        echo "            'acceptFileTypes' => new \yii\web\JsExpression('/(\.|\/)(jpe?g|png)$/i'),\n";
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
    <?= "<?php " ?>echo Html::hiddenInput('redirect', 0, ['id' => 'redirect']); ?>

    <?php echo "<?php " ?>ActiveForm::end(); ?>

</div>
