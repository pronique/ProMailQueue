<div class="actions">
    <h3><?php echo __('ProMailQueue'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Queues'), array('controller'=>'mail_queues', 'action' => 'index'));?></li>
        <li><?php echo $this->Html->link(__('Queued Emails'), array('controller' => 'mail_messages', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('Email Archive'), array('controller' => 'mail_messages', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('Email Log'), array('controller' => 'mail_messages', 'action' => 'index')); ?> </li>
        <li>&nbsp;</li>
        <li><?php echo $this->Html->link(__('Send Test Email'), array('controller' => 'test_email', 'action' => 'compose')); ?> </li>
    </ul>
</div>