<?php
/**
 * Данные юридического лица
 * @var \yii\web\View this
 * @var \common\modules\users\models\LegalPerson $model
*/

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><i class="glyphicon glyphicon-user"></i>  Данные юридического лица</h3>

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
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'address')->textInput() ?>
            <?= $form->field($model, 'BIN')->textInput() ?>
            <?= $form->field($model, 'bank')->textInput() ?>
            <?= $form->field($model, 'account')->textInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>