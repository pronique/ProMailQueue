<div class="mailMessages form">
<?php echo $this->Form->create('MailMessage');?>
	<fieldset>
		<legend><?php echo __('Edit Mail Message'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('mail_queue_id');
        
		if ( !empty( $this->data['MailMessage']['send_after'] ) ) {
            echo $this->Form->input('send_after');?>
            <div><small><small><i>Server Time: <?php echo date('m D Y, H:mA');?></i></small></small></div>
            <?php
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
        <?php echo $this->Form->input( 'Save', array('type'=>'submit', 'label'=>'', 'div'=>'false')); ?>
        <?php //echo $this->Html->link(__( 'Delete'), '#', array('id'=>'ScheduleDelivery') );?>
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