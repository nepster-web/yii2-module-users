<?php
/**
 * Создать группу
 * @var yii\base\View $this Представление
 * @var $model nepster\users\rbac\models\AuthItem
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('users.rbac', 'GROUP_CREATE');
?>

<?php if (Yii::$app->session->hasFlash('danger')): ?>
    <div class="alert alert-danger indent-bottom">
        <?php echo Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success indent-bottom">
        <?php echo Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php
echo $this->render('_form', [
    'model' => $model,
]);
