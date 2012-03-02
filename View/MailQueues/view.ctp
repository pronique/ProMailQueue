<div class="mailQueues view">
<h2>
    <?php echo h($mailQueue['MailQueue']['queue']); ?> Queue
    <small>( <?php  echo __('Mail Queue');?>) ID:<?php echo h($mailQueue['MailQueue']['id']); ?> </small>
    <small class="actions">
        <?php echo $this->Html->link(__('Edit'), array('action'=>'edit', $mailQueue['MailQueue']['id'] ), array('class'=>'btn small') );?>
        <?php if ( $mailQueue['MailQueue']['is_active'] ) {
            echo $this->Form->postLink(
                __('Pause'), 
                array( 'action'=>'toggleActive', $mailQueue['MailQueue']['id'], 'off' ),
                array('class'=>'btn small'),
                __('Are you sure you want to pause this queue? Email messages will NOT be delivered until resumed.')
            );
        } else { 
            echo $this->Form->postLink(
                __('Resume'), 
                array( 'action'=>'toggleActive', $mailQueue['MailQueue']['id'] , 'on' ),
                array('class'=>'btn small')
            );
        }    
        ?>
        <?php if ( $mailQueue['MailQueue']['mail_message_count'] > 0 ) {
            echo $this->Form->postLink(
                __('Empty'), 
                array( 'action'=>'doEmpty', $mailQueue['MailQueue']['id'] ),
                array('class'=>'btn small'),
                __('Are you sure you want to delete ALL queued email messages from this queue? There is no going back!')
            );
        }    
        ?>
    </small>
</h2>
	<dl>

        <dt><?php echo __('Status'); ?></dt>
        <dd>
            <?php if ( $mailQueue['MailQueue']['is_active'] ) : ?>
                <span style="color:green"><?php echo __('on');?></span>
            <?php else: ?>
                <span style="color:red"><?php echo __('off');?></span>
            <?php endif; ?>&nbsp
        </dd>
        <dt><?php echo __('Queue'); ?></dt>
        <dd>
            <?php echo h($mailQueue['MailQueue']['queue']); ?>
            &nbsp;
        </dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($mailQueue['MailQueue']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Priority'); ?></dt>
		<dd>
			<?php echo h($mailQueue['MailQueue']['priority']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email Config'); ?></dt>
		<dd>
			<?php echo h($mailQueue['MailQueue']['email_config']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Processed'); ?></dt>
		<dd>
			<?php echo h($mailQueue['MailQueue']['last_processed']); ?>
			&nbsp;
		</dd>
	</dl>
    <div>&nbsp;</div>
    <div class="related">
        <h3><?php echo __('Queued Messages');?> (<?php echo $mailQueue['MailQueue']['mail_message_count'];?>)</h3>
        <?php if (!empty($mailQueue['MailMessage'])):?>
        <table cellpadding = "0" cellspacing = "0">
        <tr>
            <th><?php echo __('From'); ?></th>
            <th><?php echo __('To'); ?></th>
            <th><?php echo __('Subject'); ?></th>
            <th><?php echo __('Message'); ?></th>
            <th><?php echo __('Priority'); ?></th>
            <th><?php echo __('Retries'); ?></th>
            <th><?php echo __('Last Error'); ?></th>
            <th><?php echo __('Send After'); ?></th>
            <th><?php echo __('Created'); ?></th>
            <th class="actions"><?php echo __('Actions');?></th>
        </tr>
        <?php
            $i = 0;
            foreach ($mailQueue['MailMessage'] as $mailMessage): ?>
            <tr>
                <td><?php echo $mailMessage['from'];?></td>
                <td><?php echo $mailMessage['to'];?></td>
                <td><?php echo $mailMessage['subject'];?></td>
                <td><?php echo $mailMessage['message'];?></td>
                <td><?php echo $mailMessage['priority'];?></td>
                <td><?php echo $mailMessage['retries'];?></td>
                <td><?php echo $mailMessage['last_error'];?></td>
                <td><?php echo $mailMessage['send_after'];?></td>
                <td><?php echo $mailMessage['created'];?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('controller' => 'mail_messages', 'action' => 'view', $mailMessage['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('controller' => 'mail_messages', 'action' => 'delete', $mailMessage['id']), null, __('Are you sure you want to delete # %s?', $mailMessage['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    <?php endif; ?>

    </div><!-- /.related -->
</div>
<?php echo $this->element( 'navigation' );?>