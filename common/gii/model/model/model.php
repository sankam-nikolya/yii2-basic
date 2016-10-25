<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
$constants = [];
$behaviators_class = [];
$behaviators_code  = [];

$created_at = false;
$updated_at = false;

$author_id = false;
$updater_id = false;
$published_at = false;

$image_base = false;
$image_path = false;
$image_name = '';

$order = false;

foreach ($labels as $name => $label) {
    switch ($name) {
        case 'slug':
            $behaviators_class[] = "use yii\behaviors\SluggableBehavior;\n";
            $html  = "            [\n";
            $html .= "                'class' => SluggableBehavior::className(),\n";
            $html .= "                'attribute' => 'title',\n";
            $html .= "                'immutable' => true,\n";
            $html .= "                'ensureUnique' => true\n";
            $html .= "            ]";
            $html .= "";
            $html .= "";
            $behaviators_code[]  = $html;
            break;

        case 'created_at':
        case 'updated_at':
            ${$name} = $name;
            $behaviators_class[] = "use yii\behaviors\TimestampBehavior;\n";
            //$behaviators_code[]  = "            TimestampBehavior::className()";
            break;

        case 'author_id':
        case 'updater_id':
            ${$name} = $name;
            $behaviators_class[] = "use yii\behaviors\BlameableBehavior;\n";
            /*$html  = "            [\n";
            $html .= "                'class' => BlameableBehavior::className(),\n";
            $html .= "                'createdByAttribute' => 'author_id',\n";
            $html .= "                'updatedByAttribute' => 'updater_id',\n";
            $html .= "            ]";
            $behaviators_code[]  = $html;*/
            break;

        case 'status':
            $constants[] = "    const STATUS_NOT_ACTIVE = 0;\n";
            $constants[] = "    const STATUS_ACTIVE = 1;\n";
            break;

        case 'published_at':
            $published_at = true;
            break;

        case 'order':
            $order = true;
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

$html  = '';

if($created_at && $updated_at) {
    $behaviators_code[]  = "            TimestampBehavior::className()";
} elseif($created_at || $updated_at) {
    $created_at = ($created_at) ? "'" . $created_at ."'" : $created_at;
    $updated_at = ($updated_at) ? "'" . $updated_at . "'" : $updated_at;

    $html  = "            [\n";
    $html .= "                'class' => TimestampBehavior::className(),\n";
    $html .= "                'createdAtAttribute' => $created_at,\n";
    $html .= "                'updatedAtAttribute' => $updated_at,\n";
    $html .= "            ]";
    $behaviators_code[]  = $html;
}

if($author_id || $updater_id) {
    $author_id  = ($author_id) ? "'" . $author_id ."'" : $author_id;
    $updater_id = ($updater_id) ? "'" . $updater_id . "'" : $updater_id;

    $html  = "            [\n";
    $html .= "                'class' => BlameableBehavior::className(),\n";
    $html .= "                'createdByAttribute' => $author_id,\n";
    $html .= "                'updatedByAttribute' => $updater_id,\n";
    $html .= "            ]";
    $behaviators_code[]  = $html;
}

if($image_base && $image_path && $image_name) {
    $html  = "            [\n";
    $html .= "                'class' => UploadBehavior::className(),\n";
    $html .= "                'attribute' => '$image_name',\n";
    $html .= "                'pathAttribute' => '$image_path',\n";
    $html .= "                'baseUrlAttribute' => '$image_base'\n";
    $html .= "            ]";
    $behaviators_code[]  = $html;
}

if($order) {
    $html  = "            'sortable' => [\n";
    $html  .= "                'class' => \kotchuprik\sortable\behaviors\Sortable::className(),\n";
    $html  .= "                'query' => self::find(),\n";
    $html  .= "            ]";
    $behaviators_code[]  = $html;
}

if (!empty($relations))
{
    /*foreach ($relations as $name => $relation)
    {
        var_dump($relation);
    }*/
}

$behaviators_class = array_filter(array_unique($behaviators_class));
$behaviators_code  = array_filter(array_unique($behaviators_code));



?>

namespace <?= $generator->ns ?>;

use Yii;
<?php
    echo implode("", $behaviators_class);
?>
<?php if(!empty($rel_behavior)):?>
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
<?php endif;?>
<?php if($image_base && $image_path):?>
use trntv\filekit\behaviors\UploadBehavior;
<?php endif;?>
use common\models\User;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php if(!empty($constants)):?>
<?php echo implode("", $constants);?>

<?php endif;?>
<?php if($image_base && $image_path && !empty($image_name)):?>
    /**
     * @var array
     */
    public $<?php echo $image_name;?>;

<?php endif;?>
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>
<?php if(!empty($behaviators_code) || !empty($rel_behavior)):?>

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
<?php if(!empty($behaviators_code)):?>
<?php echo implode(",\n", $behaviators_code).",\n";?>
<?php endif;?>
<?php if(!empty($rel_behavior)):?>
<?php
    $_relations = [];
    foreach ($rel_behavior as $key => $behavior) {
        $_relations[] = "'".$behavior[0]."'";
    }
?>
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::className(),
                'relations' => [<?php echo implode(', ', $_relations);?>]
            ],
<?php endif;?>
        ];
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
<?php if($image_name && $image_path && $image_base):?>
<?= "            " . str_replace(
                        [
                            "[['".$image_name."_base_url', '".$image_name."_path'], 'string', 'max' => 1024],\n            ",
                            "'".$image_name."_base_url', ",
                            "'".$image_name."_path', ",
                            "'".$image_name."_base_url'",
                            "'".$image_name."_path'",
                        ],
                        '',
                        implode(",\n            ", $rules)) . ",\n" ?>
<?php echo '            ';?>[[<?php echo "'$image_name'";?>], 'safe'],
<?php else:?>
<?= "            " . implode(",\n            ", $rules) . ",\n" ?>
<?php endif;?>
<?php if($published_at):?>
<?= "            [['published_at'], 'default', 'value' => function() { return date(DATE_ISO8601);}],\n" ?>
<?= "            [['published_at'], 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],\n" ?>
<?php endif;?>
<?php if(!empty($rel_behavior)):?>
<?php
    $rel_behavior_rules = [];
    foreach ($rel_behavior as $key => $behavior) {
        $rel_behavior_rules[] = "'".$behavior[0]."'";
    }
?>
<?php echo '            ';?>[[<?php echo implode(', ', $rel_behavior_rules);?>], 'safe'],
<?php endif;?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
<?php if($name == 'status'):?>
            <?= "'$name' => " . $generator->generateString('Published') . ",\n" ?>
<?php elseif($name == $image_path || $name == $image_base):?>
<?php else :?>
            <?= "'$name' => " . $generator->generateString(str_replace([' ID', ' En'], '', $label)) . ",\n" ?>
<?php endif;?>
<?php endforeach; ?>
<?php if($image_name && $image_path && $image_base):?>
            <?= "'".$image_name."' => " . $generator->generateString(yii\helpers\BaseInflector::camel2words($image_name)) . ",\n" ?>
<?php endif;?>
<?php if(!empty($rel_behavior)):?>
<?php foreach ($rel_behavior as $key => $behavior) :?>
            <?= "'".$behavior[0]."' => " . $generator->generateString(yii\helpers\BaseInflector::camel2words($behavior[0])) . ",\n" ?>
<?php endforeach;?>
<?php endif;?>
        ];
    }

<?php if($order):?>
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
     
            if($insert) {
                $max = (int) self::find()->max('[[order]]');
                $this->order = $max + 1;
            }
     
            return true;
        }
        return false;
    }

<?php endif;?>
    /*
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // some action

        return true;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $action = 'delete';

            // some action

            return true;
        } else {
            return false;
        }
    }
    */

<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if(!empty($rel_behavior)):?>
<?php foreach ($rel_behavior as $key => $behavior) :?>

<?= $behavior[1]; ?>
<?php endforeach;?>
<?php endif;?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
