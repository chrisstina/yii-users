<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\yiiusers\models\Profile */

$this->title = 'Update Profile: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

echo $this->context->renderPartial('../common/_notifications');
?>
<div class="profile-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
