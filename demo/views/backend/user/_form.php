<?php
/* @var $this yii\web\View */
/* @var $user common\modules\users\models\backend\User */
/* @var $profile common\modules\users\models\Profile */
/* @var $person common\modules\users\models\LegalPerson */
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(); ?>

<h6 class="heading-hr">Общая информация</h6>

<?php if(!$user->isNewRecord) { ?>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('id')?></b>: <?= $user->id ?></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('auth_ip')?></b>: <?= $user->auth_ip ?></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('auth_time')?></b>: <?= $user->auth_time ?></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('create_ip')?></b>: <?= $user->create_ip ?></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('time_create')?></b>: <?= Yii::$app->formatter->asDatetime($user->time_create) ?></div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><b><?=$user->getAttributeLabel('time_update')?></b>: <?= Yii::$app->formatter->asDatetime($user->time_update) ?></div>
    </div>
<?php } ?>

<p><br/></p>

<h6 class="heading-hr">Активность</h6>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'role') ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'status')->dropDownList($user->statusArray) ?></div>
</div>

<p><br/></p>

<h6 class="heading-hr">Контакты</h6>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'email') ?></div>
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'mail_verify')->dropDownList (Yii::$app->formatter->booleanFormat) ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone') ?></div>
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone_verify')->dropDownList (Yii::$app->formatter->booleanFormat)  ?></div>
</div>

<p><br/></p>

<h6 class="heading-hr">Безопасность</h6>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-3"><?= $form->field($user, 'password')->passwordInput() ?></div>
</div>

<?php if(!$user->isNewRecord) { ?>
    <div class="row">
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><?= $form->field($user, 'api_key')->textInput(['readonly' => 'readonly']) ?></div>
    </div>
<?php } ?>

<p><br/></p>

<h6 class="heading-hr">Профиль</h6>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($profile, 'name') ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($profile, 'surname') ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($profile, 'birthday') ?></div>
</div>
<div class="row">
    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 col-lg-8"><?= $form->field($profile, 'whau') ?></div>
</div>

<p><br/></p>

<h6 class="heading-hr">Данные юридического лица</h6>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($person, 'name') ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($person, 'address') ?></div>
</div>
<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($person, 'BIN') ?></div>
</div>
<div class="row">
    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 col-lg-4"><?= $form->field($person, 'bank') ?></div>
</div>

<div class="row">
    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 col-lg-4"><?= $form->field($person, 'account') ?></div>
</div>

<p><br/></p>

<div class="text-left">
    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>