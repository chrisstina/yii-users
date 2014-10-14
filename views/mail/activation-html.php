<?php

use yii\helpers\Html;

$url = $user->renderActivationLink()?>

<p>Hello!</p>
<p>Someone has just registered this email <?php echo $user['email']?> on our site.</p>
<p>To complete the registration process, please, follow the link:</p>
<p><?php echo Html::a(Html::encode($url), $url) ?></p>