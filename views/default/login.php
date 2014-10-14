<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';

$form = ActiveForm::begin();
echo $form->field($model, 'username');
echo $form->field($model, 'password')->passwordInput();?>

<div class="form-actions">
    <?php echo Html::submitButton('Login', array('class' => 'btn btn-primary')); ?>
</div>

<?php ActiveForm::end(); 