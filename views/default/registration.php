<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'User registration';
$this->params['breadcrumbs'][] = 'Registation form';

echo $this->context->renderPartial('../common/_notifications');

$form = ActiveForm::begin(array('options' => array('class' => 'form-horizontal')));
echo $form->field($user, 'email')->textInput();
echo $form->field($user, 'password')->passwordInput();
echo $form->field($user, 'password_confirm')->passwordInput();
echo $form->field($profile, 'name')->textInput() ?>
<div class="form-actions">
    <?php echo Html::submitButton('Register', array('class' => 'btn btn-primary')); ?>
</div>

<?php ActiveForm::end();