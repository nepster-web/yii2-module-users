<?php
/**
 * Создать Права доступа
 * @var yii\base\View $this Представление
 * @var $user common\modules\users\models\backend\---
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('users', 'RBAC_CREATE');
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
