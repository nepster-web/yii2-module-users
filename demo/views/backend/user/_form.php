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

    <?php if(!$user->isNewRecord) { ?>
        <div class="row">
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6"><b><?=$user->getAttributeLabel('id')?></b>: <?= $user->id ?></div>
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6"><b><?=$user->getAttributeLabel('time_activity')?></b>: <?= Yii::$app->formatter->asDatetime($user->time_activity) ?></div>

            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6"><b><?=$user->getAttributeLabel('ip_register')?></b>: <?= $user->ip_register ?></div>
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6"><b><?=$user->getAttributeLabel('time_register')?></b>: <?= Yii::$app->formatter->asDatetime($user->time_register) ?></div>
        </div>
    <?php } ?>

    <p><br/></p>

    <h6 class="heading-hr"><?=Yii::t('users', 'USER')?></h6>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'group')->dropDownList(\nepster\users\rbac\models\AuthItem::getGroupsArray()) ?></div>
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"></div>
        </div>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'status')->dropDownList($user->statusArray) ?></div>
        </div>

    <p><br/></p>

    <h6 class="heading-hr"><?=Yii::t('users', 'CONTACTS')?></h6>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'email') ?></div>
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'email_verify')->dropDownList(Yii::$app->formatter->booleanFormat) ?></div>
        </div>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone') ?></div>
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone_verify')->dropDownList(Yii::$app->formatter->booleanFormat) ?></div>
        </div>

    <p><br/></p>

    <h6 class="heading-hr"><?=Yii::t('users', 'PASSWORD')?></h6>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-3"><?= $form->field($user, 'password')->passwordInput() ?></div>
        </div>

        <?php if(!$user->isNewRecord) { ?>
        <div class="row">
            <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><?= $form->field($user, 'api_key')->textInput(['readonly' => 'readonly']) ?></div>
        </div>
        <?php } ?>

    <p><br/></p>

    <h6 class="heading-hr"><?=Yii::t('users', 'PROFILE')?></h6>
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

    <h6 class="heading-hr"><?=Yii::t('users', 'LEGAL_PERSON')?></h6>
        <div class="row">
            <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($profile, 'legal_person')->dropDownList(Yii::$app->formatter->booleanFormat) ?></div>
        </div>
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
            <?= Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-success']) ?>
        </div>

<?php ActiveForm::end(); ?>