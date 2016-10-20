<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$additional_condition = [];
$safe_replace = [];
$safe_replace_condition = [];

$author_id = false;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        switch ($name) {
            case 'author_id':
                $author_id = true;
                break;
            case 'created_at':
            case 'updated_at':
            case 'published_at':
                $_condition  = "        if(!empty(\$this->".$name.")) {\n";
                $_condition .= "            \$query->andFilterWhere([\n";
                $_condition .= "                'between',\n";
                $_condition .= "                '" . $name . "',\n";
                $_condition .= "                strtotime(\$this->" . $name . " . ' 00:00:00'),\n";
                $_condition .= "                strtotime(\$this->" . $name . "  . ' 23:59:59')\n";
                $_condition .= "            ]);\n";
                $_condition .= "        }\n";
                $additional_condition[] = $_condition;
                $safe_replace[] = "'" . $name . "', ";
                $safe_replace_condition[] = "            '" . $name . "' => \$this->" . $name . ",\n";
                break;
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        switch ($column->name) {
            case 'author_id':
                $author_id = true;
                break;
            case 'created_at':
            case 'updated_at':
            case 'published_at':
                $_condition  = "        if(!empty(\$this->".$column->name.")) {\n";
                $_condition .= "            \$query->andFilterWhere([\n";
                $_condition .= "                'between',\n";
                $_condition .= "                '" . $column->name . "',\n";
                $_condition .= "                strtotime(\$this->" . $column->name . " . ' 00:00:00'),\n";
                $_condition .= "                strtotime(\$this->" . $column->name . "  . ' 23:59:59')\n";
                $_condition .= "            ]);\n";
                $_condition .= "        }\n";
                $additional_condition[] = $_condition;
                $safe_replace[] = "'" . $column->name . "', ";
                $safe_replace_condition[] = "            '" . $column->name . "' => \$this->" . $column->name . ",\n";
                break;
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

namespace <?php echo StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use <?php echo ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?php echo $searchModelClass ?> represents the model behind the search form about `<?php echo $generator->modelClass ?>`.
 */
class <?php echo $searchModelClass ?> extends <?php echo isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
<?php
if(!empty($safe_replace)) {
    $rules = str_replace($safe_replace, '', $rules);
    $safe  = "            [[" . implode('', $safe_replace) . "], 'safe'],\n";
    echo str_replace(', ]', ']', $safe);
}
if($image_base && $image_path):?>
            <?php echo str_replace(["'$image_base', ", "'$image_path', "], '', implode(",\n            ", $rules)) ?>,
<?php else:?>
            <?php echo implode(",\n            ", $rules) ?>,
<?php endif;?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = <?php echo isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
<?php if(!empty($additional_condition)):?>

<?php echo implode("\n", $additional_condition);?>
<?php endif;?>

        <?php
        $condition = str_replace([
                "            ->andFilterWhere(['like', '$image_base', \$this->$image_base])\n",
                "            ->andFilterWhere(['like', '$image_path', \$this->$image_path])\n"
            ],
            "",
            implode("\n        ", $searchConditions));
        if(!empty($safe_replace_condition)) {
            echo str_replace($safe_replace_condition, '', $condition);
        } else {
            echo $condition;
        }
        ?>

        return $dataProvider;
    }
<?php if($author_id):?>

    /**
     * Creates data provider instance with search query applied and current user id
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByUser($params)
    {
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        if(!Yii::$app->user->can('administrator')) {
            $query->where('[[author_id]]=:author_id', [':author_id' => Yii::$app->user->getId()]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
<?php if(!empty($additional_condition)):?>

<?php echo implode("\n", $additional_condition);?>
<?php endif;?>

        // grid filtering conditions
        <?php
        $condition = str_replace([
                "            'author_id' => \$this->author_id,\n",
                "            ->andFilterWhere(['like', '$image_base', \$this->$image_base])\n",
                "            ->andFilterWhere(['like', '$image_path', \$this->$image_path])\n"
            ],
            "",
            implode("\n        ", $searchConditions));
        if(!empty($safe_replace_condition)) {
            echo str_replace($safe_replace_condition, '', $condition);
        } else {
            echo $condition;
        }
        ?>

        return $dataProvider;
    }
<?php endif;?>
}
