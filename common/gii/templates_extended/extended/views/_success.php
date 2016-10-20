<?php echo '<?php';?>

use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model backend\models\Countries */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php echo '<?php';?> echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $msg]); ?>