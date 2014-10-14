<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));

echo $form->field($model, 'email')->textInput();?>
<div class="form-actions">
    <?php echo Html::submitButton('Resend', array('class' => 'btn btn-primary')); ?>
</div>

<?php ActiveForm::end();