<?php
/**
 * Изменить пароль
 * @var \yii\web\View this
 * @var \common\modules\users\models\frontend\RecoveryConfirmationForm $model
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><?=Yii::t('users', 'RECOVERY.TITLE')?></h3>

<br/>

<?php if (Yii::$app->session->hasFlash('danger')) : ?>
<div class="alert alert-danger" role="alert">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
    <?php echo Yii::$app->session->getFlash('danger') ?>
</div>
<?php endif;?>

<?php if (Yii::$app->session->hasFlash('success')) : ?>
<div class="alert alert-success" role="alert">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
    <?php echo Yii::$app->session->getFlash('success') ?>
</div>
<?php endif;?>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'repassword')->passwordInput() ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            &nbsp; <?= Html::a(Yii::t('users', 'SIGNUP.TITLE'), ['/users/guest/signup']) ?>
            &nbsp; <?= Html::a(Yii::t('users', 'SIGNIN.TITLE'), ['/users/guest/login']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>