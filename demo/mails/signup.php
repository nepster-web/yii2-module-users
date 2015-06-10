<?php
/**
 * Activation email view.
 *
 * @var \yii\web\View $this View
 * @var \common\modules\users\models\User $user
 */
use yii\helpers\Html;
use yii\helpers\Url;
$url = Url::toRoute(['/users/guest/activation', 'token' => $user['secure_key']], true);
?>
<p><?=Yii::t('users', 'BODY_SIGNUP_HELLO {name}', ['name' => $user['profile']['name'] . ' ' . $user['profile']['surname']])?></p>
<p><?=Yii::t('users', 'BODY_SIGNUP_TOKEN {url}', ['url' => Html::a(Html::encode($url), $url)])?></p>