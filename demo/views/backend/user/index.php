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

$this->title = Yii::t('users', 'USER_ALL');
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
        <label>' . Yii::t('users', 'ACTIONS') . ':</label>
        <select name="action" class="form-control" style="display: inline-block; width: auto">
            <option value=""></option>
            <option value="rebanned">' . Yii::t('users', 'ACTION_REBANNED') . '</option>
            <option value="banned">' . Yii::t('users', 'ACTION_BANNED') . '</option>
            <option value="deleted">' . Yii::t('users', 'ACTION_DELETED') . '</option>
            <option value="recover">' . Yii::t('users', 'ACTION_RECOVER') . '</option>
        </select>
        <button class="btn btn-primary">OK</button>
    </div>';
?>


<?php

    if (Yii::$app->user->can('user-view')) {
        echo Html::tag('p', 'Есть права на просмотр пользователя', ['style' => 'font-weight: bold; color: green']);
    }

    if (Yii::$app->user->can('user-create')) {
        echo Html::tag('p', 'Есть права на создание пользователя', ['style' => 'font-weight: bold; color: green']);
    }

    if (Yii::$app->user->can('user-update')) {
        echo Html::tag('p', 'Есть права на редактирование пользователя', ['style' => 'font-weight: bold; color: green']);
    }

    if (Yii::$app->user->can('user-delete')) {
        echo Html::tag('p', 'Есть права на удаление пользователя', ['style' => 'font-weight: bold; color: green']);
    }

    if (Yii::$app->user->can('user-undefined')) {
        echo Html::tag('p', 'Несуществующее правило', ['style' => 'color: red; font-weight: bold; color: green']);
    }


    echo Html::tag('p', Html::tag('b', 'Ваша группа: ' . Yii::$app->user->identity->group));


    echo '<h3>Текущий пользователь:</h3>';

    echo Yii::$app->user->identity->isOnline() ? Html::tag('b', 'Онлайн', ['style' => 'color: green']) : Html::tag('b', 'Офлайн', ['style' => 'color: red']);
    echo " &nbsp; ";
    echo Yii::$app->user->identity->isBanned() ? Html::tag('b', 'Забанен', ['style' => 'color: red']) : Html::tag('b', 'Не забанен', ['style' => 'color: green']);

?>







<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo Html::a(Yii::t('users', 'USER_CREATE'), ['create'], ['class' => 'btn btn-primary']); ?>

    <p><br/></p>

<?php

echo Html::beginForm(['/users/user/multi-control']);

echo GridView::widget([
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
                return Html::a('ID' . $model->id . ' ' . $model->profile->name . ' ' . $model->profile->surname, ['/users/user/update', 'id' => $model->id]);
            }
        ],

        [
            'attribute' => 'group',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Html::a(ArrayHelper::getValue(\nepster\users\rbac\models\AuthItem::getGroupsArray(), $model->group), ['/users/rbac/update', 'id' => $model->group]);
            }
        ],

        [
            'attribute' => 'contacts',
            'format' => 'html',
            'label' => Yii::t('users', 'CONTACTS'),
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {

                $options = [];

                if (!$model->email_verify) {
                    $options = ['style' => 'color: silver'];
                }

                $mail = Html::tag('span', $model->email, $options);

                if (!$model->phone_verify) {
                    $options = ['style' => 'color: silver'];
                }

                $phone = Html::tag('span', $model->phone, $options);

                return $mail . '<br/>' . $phone;
            }
        ],

        [
            'attribute' => 'status',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return $model->getStatusArray($model->status);
            },
        ],

        [
            'attribute' => 'banned',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return ArrayHelper::getValue(Yii::$app->formatter->booleanFormat, $model->banned);
            },
        ],

        [
            'attribute' => 'time_register',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Yii::$app->formatter->asDatetime($model->time_register);
            },
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update} &nbsp; {delete}",
            'contentOptions' => ['class' => 'text-center'],
            'header' => Yii::t('users', 'CONTROL'),
        ],
    ],

]);

echo Html::endForm();