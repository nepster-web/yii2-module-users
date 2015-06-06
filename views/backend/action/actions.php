<?php
/**
 * Все действия пользователей
 * @var yii\base\View $this Представление
 * @var $searchModel common\modules\users\models\backend\ActionSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('users', 'USER_ACTIONS');
?>

<?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p><br/></p>

<?php
echo GridView::widget([
    'id' => 'grid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-bordered'],
    'layout' => '{summary}<div class="panel panel-default"><div class="table-responsive">{items}</div><div class="table-footer">{pager}</div></div>',
    'columns' => [

        [
            'attribute' => 'application',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return $model->application;
            }
        ],

        [
            'attribute' => 'module',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return $model->module;
            }
        ],

        [
            'attribute' => 'action',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return $model->action;
            }
        ],

        [
            'attribute' => 'user',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                if ($user = $model->getUser()->one()) {
                    return Html::a($user->profile->name . ' ' . $user->profile->surname, ['/users/user/update', 'id' => $user->id]);
                }
                return null;
            }
        ],

        [
            'attribute' => 'ip',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return long2ip($model->ip);
            }
        ],

        [
            'attribute' => 'time_create',
            'format' => 'html',
            'contentOptions' => ['class' => 'text-left'],
            'value' => function ($model) {
                return Yii::$app->formatter->asDatetime($model->time_create);
            },
        ],
    ],

]);