<?php
/* @var $this yii\web\View */
/* @var $model nepster\users\rbac\models\AuthItem */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;

?>

<?php $permissions = $model->getRolePermissions(); ?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-12 col-lg-2"><?= $form->field($model, 'name') ?></div>
    <div class="col-md-3 col-md-4 col-sm-4 col-xs-12 col-lg-3"><?= $form->field($model, 'description') ?></div>
</div>

<div class="row">
    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <?php foreach ($permissions as $id => $permission): ?>
            <?php $field = $form->field($model, 'permissions[' . $id . ']'); ?>
            <?php echo $field->begin(); ?>
            <?php
                echo Html::activeCheckbox($model, 'permissions[' . $id . ']', [
                    'label' => Yii::t('users.rbac', $permission->description),
                    'class' => 'pull-left',
                ]);
            ?>
            <?php echo $field->end(); ?>
        <?php endforeach; ?>
    </div>
</div>

<?php echo Html::submitButton(Yii::t('users', 'SEND'), ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end(); ?>



<?php

$this->registerCss('
    label b {margin-left: 5px;}
    label p {font-weight: normal; margin-left: 20px;}
');