<?php
/**
 * Группы пользователей
 * @var yii\base\View $this Представление
 * @var $searchModel common\modules\users\models\backend\search\RbacSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('users.rbac', 'GROUPS');
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

<p><br/></p>

<?php echo Html::a(Yii::t('users.rbac', 'GROUP_CREATE'), ['create'], ['class' => 'btn btn-primary']); ?>

<p><br/></p>

<?php
echo GridView::widget([
    'id' => 'grid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'layout' => '{summary}<div class="panel panel-default"><div class="table-responsive">{items}</div><div class="table-footer">{pager}</div></div>',
    'columns' => [

        [
            'attribute' => 'name',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return $model->name;
            }
        ],

        [
            'attribute' => 'description',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Yii::t('users.rbac', $model->description);
            }
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update} &nbsp; {delete}",
            'contentOptions' => ['class' => 'text-center'],
            'header' => Yii::t('users', 'CONTROL'),
        ]
    ],

]);