<div class="mailMessages view">
<h2><?php  echo __('Email Message');?>
    <small class="actions">
        <?php echo $this->Html->link(__('Edit'), array('action'=>'edit', $mailMessage['MailMessage']['id'] ) );?>
    </small>
</h2>
	<dl>
		<dt><?php echo __('From'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['from']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('To'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subject'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['subject']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Headers'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['headers']); ?>&nbsp;
		</dd>
		<dt><?php echo __('Message'); ?></dt>
		<dd>
            <?php echo h($mailMessage['MailMessage']['message']); ?>&nbsp;
		</dd>
		<dt><?php echo __('Priority'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['priority']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Retries'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['retries']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Error'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['last_error']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mail Queue'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mailMessage['MailQueue']['queue'], array('controller' => 'mail_queues', 'action' => 'view', $mailMessage['MailQueue']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Send After'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['send_after']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($mailMessage['MailMessage']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<?php echo $this->element( 'navigation' );?>