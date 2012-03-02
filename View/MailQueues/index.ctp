<div class="mailQueues index">
    <h2><?php  echo __('Queues');?>
        <small class="actions">
            <?php echo $this->Html->link(__('Add'), array('action'=>'add' ) );?>
        </small>
    </h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
            <th style="width: 40px;"><?php echo $this->Paginator->sort('is_active', __('*') );?></th>
			<th><?php echo $this->Paginator->sort('queue', __('Queue') );?></th>
			<th style="width: 90px;"><?php echo $this->Paginator->sort('priority');?></th>
            <th style="width: 120px;"><?php echo $this->Paginator->sort('mail_message_count','Messages');?></th>
			<th style="width: 200px;"><?php echo $this->Paginator->sort('last_processed');?></th>
	</tr>
	<?php
	foreach ($mailQueues as $mailQueue): ?>
	<tr>
        <td>
            <?php if ( $mailQueue['MailQueue']['is_active'] ) : ?>
                <span style="color:green"><?php echo __('on');?></span>
            <?php else: ?>
                <span style="color:red"><?php echo __('off');?></span>
            <?php endif; ?>&nbsp
        </td>
		<td>
            <?php 
            $queueDesc = $mailQueue['MailQueue']['queue'];
            echo $this->Html->link(
                $mailQueue['MailQueue']['queue'], 
                array('action' => 'view', $mailQueue['MailQueue']['id']),
                array('title'=>$queueDesc )
            );
            ?>&nbsp;
        </td>
		<td><?php echo h($mailQueue['MailQueue']['priority']); ?>&nbsp;</td>
		<td>
            <?php echo h($mailQueue['MailQueue']['mail_message_count']); ?>
            <?php /* echo $this->Html->link(
                $mailQueue['MailQueue']['mail_message_count'], 
                array('controller'=>'mail_messages','action' => 'index', $mailQueue['MailQueue']['id'])
            ); */ ?>&nbsp;
        </td>
		<td>
            <?php if ( $mailQueue['MailQueue']['last_processed'] ) {
                echo $this->Time->timeAgoInWords( 
                $mailQueue['MailQueue']['last_processed'],
                array( 'end'=>'+3 days')
                );
            } else {
                echo __('never');
            }?>&nbsp;
        </td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php echo $this->element( 'navigation' );?>