<?php if(Yii::$app->session->hasFlash('danger')) :?>
    <div class="alert alert-danger" role="alert">
        <?php echo Yii::$app->session->getFlash('danger') ?>
    </div>
<?php endif;

if(Yii::$app->session->hasFlash('success')) :?>
    <div class="alert alert-success" role="alert">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif;