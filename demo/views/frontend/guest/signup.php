<?php
/**
 * Регистрация
 * @var \yii\web\View $this
 * @var \common\modules\users\models\User $user
 * @var \common\modules\users\models\Profile $profile
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><i class="glyphicon glyphicon-user"></i>  <?=Yii::t('users', 'SIGNUP.TITLE')?></h3>

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
        <h3>Основные данные</h3>
        <?= $form->field($user, 'phone') ?>
        <?= $form->field($user, 'email') ?>
        <?= $form->field($user, 'password')->passwordInput() ?>
        <?= $form->field($user, 'repassword')->passwordInput() ?>
        <br/>
        <h3>Данные профиля</h3>
        <?= $form->field($profile, 'name') ?>
        <?= $form->field($profile, 'surname') ?>
        <?= $form->field($profile, 'whau') ?>
        <br/>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            &nbsp; <?= Html::a(Yii::t('users', 'SIGNIN.TITLE'), ['/users/guest/login']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>