<?php
/*
    Авторизация
    @var \yii\web\View this
    @var \common\modules\users\models\LoginForm $model
*/
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><i class="glyphicon glyphicon-user"></i>  <?=Yii::t('users', 'SIGNIN.TITLE')?></h3>

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
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>