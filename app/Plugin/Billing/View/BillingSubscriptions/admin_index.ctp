<?php
$this->extend('/Common/admin_index');
$this->set('title_for_layout', __d('billing', 'Subscriptions'));
?>

<table class="table table-striped table-hover">
	<thead>
	<tr>
		<th><?php echo __d('billing', 'Remote ID'); ?></th>
		<th><?php echo __d('billing', 'User'); ?></th>
		<th><?php echo __d('billing', 'Group > Plan (Remote plan)'); ?></th>
		<th><?php echo $this->Paginator->sort('active'); ?></th>
		<th><?php echo $this->Paginator->sort('status'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th><?php echo $this->Paginator->sort('expires'); ?></th>
		<th class="actions"><?php echo __d('billing', 'Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($billingSubscriptions as $billingSubscription): ?>
	<tr>
		<td><?php echo h($billingSubscription['BillingSubscription']['remote_subscription_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($billingSubscription['User']['full_name'], array('plugin' => false, 'controller' => 'users', 'action' => 'view', $billingSubscription['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($billingSubscription['BillingGroup']['title'], array('controller' => 'billing_groups', 'action' => 'edit', $billingSubscription['BillingGroup']['id'])); ?>
			<span> > </span>
			<?php echo $this->Html->link($billingSubscription['BillingPlan']['title'], array('controller' => 'billing_plans', 'action' => 'edit', $billingSubscription['BillingPlan']['id'])); ?>
			(<?php echo h($billingSubscription['BillingSubscription']['remote_plan_id']); ?>)
		</td>
		<td><?php echo $billingSubscription['BillingSubscription']['active'] ? __d('billing', 'Yes') : __d('billing', 'No'); ?>&nbsp;</td>
		<td><?php echo h($billingSubscription['BillingSubscription']['status']); ?>&nbsp;</td>
		<td><?php echo h($billingSubscription['BillingSubscription']['modified']); ?>&nbsp;</td>
		<td><?php echo h($billingSubscription['BillingSubscription']['expires']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__d('billing', 'View'), array('action' => 'view', $billingSubscription['BillingSubscription']['id']), array('class' => 'btn btn-primary')); ?>
			<?php if($billingSubscription['BillingSubscription']['active']): ?>
				<?php echo $this->Form->postLink(__d('billing', 'Cancel'), array('action' => 'cancel', $billingSubscription['BillingSubscription']['id']), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to cancel # %s?', $billingSubscription['BillingSubscription']['id'])); ?>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<?php $this->start('actions'); ?>
<div class="btn-group colored">
	<?php echo $this->Html->link(__d('billing', 'List Users'), array('controller' => 'users', 'action' => 'index'), array('class' => 'btn btn-default')); ?>
	<?php echo $this->Html->link(__d('billing', 'List Billing Groups'), array('controller' => 'billing_groups', 'action' => 'index'), array('class' => 'btn btn-default')); ?>
	<?php echo $this->Html->link(__d('billing', 'List Billing Plans'), array('controller' => 'billing_plans', 'action' => 'index'), array('class' => 'btn btn-default')); ?>
</div>
<?php $this->end(); ?>
