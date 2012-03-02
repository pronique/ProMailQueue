<div class="mailMessages index">
	<h2><?php echo __('Queued Emails');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
            <th><?php echo $this->Paginator->sort('mail_queue_id', __('Queue'));?></th>
            <th><?php echo $this->Paginator->sort('from');?></th>
			<th><?php echo $this->Paginator->sort('to');?></th>
			<th style="min-width:120px;"><?php echo $this->Paginator->sort('subject');?></th>
            <th><?php echo $this->Paginator->sort('priority');?></th>
			<th><?php echo $this->Paginator->sort('created', __('Age') );?></th>
            <th><?php echo $this->Paginator->sort('retries');?></th>
			<th><?php echo $this->Paginator->sort('last_error');?></th>
			<th><?php echo $this->Paginator->sort('send_after');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($mailMessages as $mailMessage): ?>
	<tr>
        <td>
            <?php echo $this->Html->link(
                $mailMessage['MailQueue']['queue'], 
                array('controller' => 'mail_queues', 'action' => 'view', $mailMessage['MailQueue']['id']),
                array('title'=>$mailMessage['MailQueue']['queue'] . ' ' . __('Queue') )
            ); ?>
        </td>
		<td><?php echo h( String::truncate( $mailMessage['MailMessage']['from'], 12 ) ); ?>&nbsp;</td>
		<td><?php echo h( String::truncate( $mailMessage['MailMessage']['to'], 12 ) ); ?>&nbsp;</td>
		<td>
            <?php $subject = h( String::truncate( $mailMessage['MailMessage']['subject'], 24 ) ); ?>
            <?php $subjectFull = h(  $mailMessage['MailMessage']['subject'], 24  ); ?>&nbsp;
            <?php echo $this->Html->link( $subject, array('action'=>'view', $mailMessage['MailMessage']['id'] ), array('title'=>$subjectFull) ); ?>&nbsp; 
        </td>
        <td><?php echo h($mailMessage['MailMessage']['priority']); ?>&nbsp;</td>
        <td >
            <?php 
            $secondsAgo = floor((time() - strtotime($mailMessage['MailMessage']['created'])));
            if ( $secondsAgo < 60 ) {
                $age = $secondsAgo . ' seconds';
            } elseif( $secondsAgo < (60 * 60 ) ) {
                $age = floor( $secondsAgo / 60 ) . ' minutes';
            } else {
                $age = floor( $secondsAgo / 3600 ) . ' hours';
            } 
            ?>
            <span title="<?php echo __('Queued at ') . $mailMessage['MailMessage']['created'];?>" style="white-space:nowrap">
                <?php echo $age; ?>
            </span>
        &nbsp;</td>
		<td><?php echo h($mailMessage['MailMessage']['retries']); ?>&nbsp;</td>
		<td><?php echo h($mailMessage['MailMessage']['last_error']); ?>&nbsp;</td>
		<td><?php echo h($mailMessage['MailMessage']['send_after']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $mailMessage['MailMessage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mailMessage['MailMessage']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $mailMessage['MailMessage']['id']), null, __('Are you sure you want to delete # %s?', $mailMessage['MailMessage']['id'])); ?>
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