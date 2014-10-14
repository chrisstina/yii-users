<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Account activation';

echo $this->context->renderPartial('../common/_notifications');

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
$form->action = Yii::$app->urlManager->createUrl('/yiiusers/default/resend');

echo $form->field($model, 'username')->textInput();?>
<div class="form-actions">
    <?php echo Html::submitButton('Resend', array('class' => 'btn btn-primary')); ?>
</div>

<?php ActiveForm::end();