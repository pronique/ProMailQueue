<div class="mailQueues form">
<?php echo $this->Form->create('MailQueue');?>
	<fieldset>
		<legend><?php echo __('Edit Queue'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('queue', array( 'label'=>__('Queue Name'), 'after'=>'no spaces permitted') );
		echo $this->Form->input('description');
		echo $this->Form->input(
            'priority', 
            array(
                'label'=>__('Priority') . ' <small>' . __('vs other queues') . '</small>',
                'after'=>'Valid range: 0-127'
            )
        );
        
        echo $this->Form->input(
            'email_config', 
            array(
                'after'=>__('as defined in app/Config/email.php'), 
                'value'=>Configure::read( PMQ_PLUGIN_NAME.'.Queue.defaultEmailConfig') 
            )
        );
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<?php echo $this->element( 'navigation' );?>
