<div class="yiiusers-default-index">
    <h1><?php echo  $this->context->action->uniqueId ?></h1>
    
    <?php echo $this->context->renderPartial('../common/_notifications'); ?>
    
    <p>
        This is the view content for action "<?php echo $this->context->action->id ?>".
        The action belongs to the controller "<?php echo get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
