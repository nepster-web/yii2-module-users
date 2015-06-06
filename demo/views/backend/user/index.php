<?php
/**
 * Все пользователи
 * @var yii\base\View $this Представление
 * @var $searchModel common\modules\users\models\backend\UserSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '-----';

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


<?php
$actions = '
    <div class="table-actions">
        <label>Действия:</label>
        <select data-placeholder="Select action..." class="form-control" style="display: inline-block; width: auto">
            <option value=""></option>
            <option value="rebanned">Разблокировать</option>
            <option value="banned">Заблокировать</option>
        </select>
        <button class="btn btn-primary">OK</button>
    </div>';
?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo Html::a(Yii::t('users', 'USER_CREATE'), ['create'], ['class' => 'btn btn-primary']);?>


<?php echo GridView::widget([
    'id' => 'grid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'layout' => '{summary}<div class="panel panel-default"><div class="table-responsive">{items}</div><div class="table-footer"> ' . $actions . ' {pager}</div></div>',

    'columns' => [

        [
            'class' => 'yii\grid\CheckboxColumn',
            'contentOptions' => ['class' => 'text-left'],
        ],

        [
            'attribute' => 'user',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'label' => Yii::t('users', 'USER'),
            'value' => function ($model) {
                return 'ID' . $model->id . ' ' . $model->profile->name . ' ' . $model->profile->surname;
            }
        ],

        [
            'attribute' => 'role',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return $model->role;
            }
        ],

        [
            'attribute' => 'contacts',
            'format' => 'html',
            'label' => Yii::t('users', 'CONTACTS'),
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {

                if ($model->mail_verify) {
                    $mail = Html::tag('span', $model->email, ['style' => 'color: black', 'title' => 'E-MAIL Верифицирован']);
                } else {
                    $mail = Html::tag('span', $model->email, ['style' => 'color: silver', 'title' => 'E-MAIL Не верифицирован']);
                }

                if ($model->phone_verify) {
                    $phone = Html::tag('span', $model->phone, ['style' => 'color: black', 'title' => 'Телефон Верифицирован']);
                } else {
                    $phone = Html::tag('span', $model->phone, ['style' => 'color: silver', 'title' => 'Телефон Не верифицирован']);
                }

                return $mail . '<br/>' . $phone;
            }
        ],

        [
            'attribute' => 'status',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return $model->status;
            },
        ],

        [
            'attribute' => 'time_create',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Yii::$app->formatter->asDatetime($model->time_create);
            },
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update} &nbsp; {delete}",
            'contentOptions' => ['class' => 'text-center'],
            'header' => 'Управление',
        ]
    ],

]);