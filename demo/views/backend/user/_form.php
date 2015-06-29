<?php
/**
 * Форма пользователя
 * @var $this yii\web\View
 * @var $user common\modules\users\models\backend\User
 * @var $profile common\modules\users\models\Profile
 * @var $person common\modules\users\models\LegalPerson
 */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(); ?>

<?php if (!$user->isNewRecord) { ?>
    <div class="row">
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6">
            <p><b><?= $user->getAttributeLabel('id') ?></b>: <?= $user->id ?></p>
            <p><b><?= $user->getAttributeLabel('time_activity') ?></b>: <?= Yii::$app->formatter->asDatetime($user->time_activity) ?></p>
            <p><b><?= $user->getAttributeLabel('ip_register') ?></b>: <?= $user->ip_register ?></p>
            <p><b><?= $user->getAttributeLabel('time_register') ?></b>: <?= Yii::$app->formatter->asDatetime($user->time_register) ?></p>
        </div>
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-6">
            <p>
                <?php
                    $online = $user->isOnline();
                    echo $online ? Html::tag('span', Yii::t('users', 'ONLINE'), ['style' => 'color: green']) : Html::tag('span', Yii::t('users', 'OFFLINE'), ['style' => 'color: red']);
                ?>
            </p>
            <p>
                <?php
                $banned = $user->bannedInfo();
                if ($banned) {
                    echo Html::tag('span', Yii::t('users', 'BANNED'), ['style' => 'color: red']);
                    echo ' (' . Yii::$app->formatter->asDatetime($banned->time_banned) . ')';
                    if ($banned->reason) {
                        echo Html::tag('p', Yii::t('users', 'BEEN_BANNED_REASON {reason}', [
                            'reason' => $banned->reason
                        ]));
                    }
                    echo Html::tag('p', Html::a(Yii::t('users', 'ACTION_REBANNED'), ['/users/user/update', 'id' => $user->id, 'rebanned' => 1]));
                } else {
                    echo Html::tag('span', Yii::t('users', 'NOT_BANNED'), ['style' => 'color: green']);
                    echo Html::tag('p', Html::a(Yii::t('users', 'ACTION_BANNED'), ['/users/user/banned', 'ids[]' => $user->id]));
                }
                ?>
            </p>
            <p>
                <?php   echo Html::a(Yii::t('users', 'USERS_SEND_EMAIL'), ['/users/user/send-email', 'ids[]' => $user->id]); ?>
            </p>
        </div>
    </div>

<?php } ?>

    <p><br/></p>

    <h6 class="heading-hr"><?= Yii::t('users', 'USER') ?></h6>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'group')->dropDownList(\nepster\users\rbac\models\AuthItem::getGroupsArray(), $user->isNewRecord ?
                ['options' => [
                    'user' => ['selected ' => true]
                ]] : []) ?>
        </div>
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"></div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'status')->dropDownList($user->statusArray) ?></div>
    </div>

    <p><br/></p>

    <h6 class="heading-hr"><?= Yii::t('users', 'CONTACTS') ?></h6>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'email') ?></div>
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'email_verify')->dropDownList(Yii::$app->formatter->booleanFormat) ?></div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone') ?></div>
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-4"><?= $form->field($user, 'phone_verify')->dropDownList(Yii::$app->formatter->booleanFormat) ?></div>
    </div>

    <p><br/></p>

    <h6 class="heading-hr"><?= Yii::t('users', 'PASSWORD') ?></h6>
    <div class="row">
        <div class="col-md-3 col-md-4 col-sm-4 col-xs-6 col-lg-3"><?= $form->field($user, 'password')->passwordInput() ?></div>
    </div>

<?php if (!$user->isNewRecord) { ?>
    <div class="row">
        <div class="col-md-6 col-md-6 col-sm-6 col-xs-12 col-lg-4"><?= $form->field($user, 'api_key')->textInput(['readonly' => 'readonly']) ?></div>
    </div>
<?php } ?>

    <p><br/></p>

    <h6 class="heading-hr"><?= Yii::t('users', 'PROFILE') ?></h6>
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

    <h6 class="heading-hr"><?= Yii::t('users', 'LEGAL_PERSON') ?></h6>
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
        <?php
        if (Yii::$app->user->can('user-update')) {
            echo Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-success']);
        }
        ?>
    </div>

<?php ActiveForm::end(); ?>