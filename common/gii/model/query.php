<?php
/**
 * This is the template for generating the ActiveQuery class.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
/* @var $className string class name */
/* @var $modelClassName string related model class name */

$modelFullClassName = $modelClassName;
if ($generator->ns !== $generator->queryNs) {
    $modelFullClassName = '\\' . $generator->ns . '\\' . $modelFullClassName;
}

$status = false;
$author_id = false;
$published_at = false;

foreach ($labels as $name => $label) {
    switch ($name) {
        case 'status':
            $status = true;
            break;

        case 'published_at':
            $published_at = true;
            break;

        case 'author_id':
            $author_id = true;
            break;
    }
}

echo "<?php\n";
?>

namespace <?= $generator->queryNs ?>;

use <?= $modelFullClassName ?>;

/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * @see <?= $modelFullClassName . "\n" ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->queryBaseClass, '\\') . "\n" ?>
{
<?php if($author_id):?>
    public function only_users()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->andWhere(['author_id' => Yii::$app->user->id]);
        }
    }

<?php endif;?>
<?php if($status):?>
    public function active()
    {
        $this->andWhere(['status' => <?= $modelClassName;?>::STATUS_ACTIVE]);
<?php if($published_at):?>
        $this->andWhere(['<', 'published_at', time()]);
<?php endif;?>
        return $this;
    }

    public function not_active()
    {
        $this->andWhere(['status' => <?= $modelClassName;?>::STATUS_NOT_ACTIVE]);
<?php if($published_at):?>
        $this->orWhere(['>=', 'published_at', time()]);
<?php endif;?>
        return $this;
    }

<?php endif;?>
    /**
     * @inheritdoc
     * @return <?= $modelFullClassName ?>[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return <?= $modelFullClassName ?>|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
