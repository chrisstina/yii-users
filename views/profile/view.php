<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model chriss\yiiusers\models\Profile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

echo $this->context->renderPartial('../common/_notifications');
?>
<div class="profile-view">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('Update', ['update'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>
