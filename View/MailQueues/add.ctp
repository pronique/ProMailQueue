<div class="mailQueues form">
<?php echo $this->Form->create('MailQueue');?>
	<fieldset>
		<legend><?php echo __('Add Mail Queue'); ?></legend>
	<?php
        echo $this->Form->input('id');
        echo $this->Form->input('queue', array( 'label'=>__('Queue Name'), 'after'=>__('no spaces permitted') ) );
        echo $this->Form->input('description');
        echo $this->Form->input(
            'priority', 
            array(
                'label'=>__('Priority') . ' <small>' . __('...among queues') . '</small>',
                'after'=>__('Valid range: 0-127'),
                'value'=>Configure::read( PMQ_PLUGIN_NAME.'.Queue.defaultQueuePriority')  
            )
        );
        
        echo $this->Form->input(
            'email_config', 
            array(
                'after'=>__('as defined in app/Config/email.php'), 
            )
        );
        
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<?php echo $this->element( 'navigation' );?>