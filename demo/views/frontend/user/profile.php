<?php
/**
 * Профиль
 * @var \yii\web\View this
 * @var \common\modules\users\models\Profile $model
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><?=Yii::t('users', 'PROFILE')?></h3>

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
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'surname') ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                &nbsp; <?= Html::a(Yii::t('users', 'CHANGE_PASSWORD'), ['/users/user/password']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>