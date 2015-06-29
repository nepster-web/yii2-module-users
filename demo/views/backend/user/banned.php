<?php
/**
 * Заблокировать пользователей
 * @var yii\base\View $this Представление
 * @var array $users Выбранные пользователи
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('users', 'USERS_BANNED');
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


<?php $form = ActiveForm::begin(); ?>

    <h6 class="heading-hr"><?= Yii::t('users', 'USERS_BANNED') ?></h6>

    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4">
            <?php foreach ($users as $user): ?>
                <?php echo Html::a('ID'.$user->id.' ['.$user->email.']', ['/users/user/update', 'id' => $user->id]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <p><br/></p>

    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($model, 'time_banned') ?></div>
    </div>

    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($model, 'reason')->textarea() ?></div>
    </div>

    <p><br/></p>

    <div class="text-left">
        <?php
            echo Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-success']);
        ?>
    </div>

<?php ActiveForm::end(); ?>