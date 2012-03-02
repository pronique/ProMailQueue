<div class="mailMessages form">
<?php echo $this->Form->create('MailMessage');?>
	<fieldset>
		<legend><?php echo __('Compose Email'); ?></legend>
	<?php
        echo $this->Form->input('id');
        echo $this->Form->input('from', array('type'=>'text') );
        echo $this->Form->input('to', array('type'=>'text'));
        echo $this->Form->input('subject', array('type'=>'text'));
		echo $this->Form->input('message', array('label'=>''));
        
		echo $this->Form->input('mail_queue_id');
        echo $this->Form->input('priority', array('maxlength'=>3));
		if ( !empty( $this->data['MailMessage']['send_after'] ) ) {
            echo $this->Form->input('send_after');
        } else { ?>
            <div class="input datetime" style="display:none">
                <?php echo $this->Form->input(
                    'send_after', 
                    array(
                        'type' => 'datetime', 
                        'div'=>false, 
                        'separator'=>'',
                        'minYear'=>date('Y'), 
                        'orderYear'=>'asc',
                        'interval' => 5 
                    )
                ); ?>
                <div><small><small><i>Server Time: <?php echo date('m D Y, H:mA');?></i></small></small></div>
            </div>
            <div class="input unhide">
                <?php echo $this->Html->link(__d('ProMailQueue', 'Schedule Delivery'), '#', array('id'=>'ScheduleDelivery') );?>
            </div>
        <?php }
	?>
	</fieldset>
    <div class="input submit">
        <?php $cancelLink =  ' ' . $this->Html->link( 'Cancel', array( 'action'=>'index' ) ); ?>
        <?php echo $this->Form->submit( __('Send'), array( 'label'=>'', 'after'=>$cancelLink,'escape'=>false ) ); ?>
    </div> 
<?php echo $this->Form->end();?>
</div>
<?php echo $this->element( 'navigation' );?>
<script>
jQuery('#ScheduleDelivery').on( 'click', function() {
    jQuery(this).parent().parent().find('.input.datetime').slideDown();
    jQuery(this).parent().hide();
    
});
</script>