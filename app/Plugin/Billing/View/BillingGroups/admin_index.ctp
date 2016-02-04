<?php
$this->extend('/Common/admin_index');
$this->set('title_for_layout', __d('billing', 'Billing Groups'));
?>

<table class="table table-striped table-hover">
<thead>
<tr>
		<th><?php echo $this->Paginator->sort('title'); ?></th>
		<th><?php echo $this->Paginator->sort('active'); ?></th>
		<th class="actions"><?php echo __d('billing', 'Actions'); ?></th>
</tr>
</thead>
<tbody>
	<?php foreach ($billingGroups as $billingGroup): ?>
	<tr>
		<td><?php echo h($billingGroup['BillingGroup']['title']); ?>&nbsp;</td>
		<td><?php echo $billingGroup['BillingGroup']['active'] ? __d('billing', 'Yes') : __d('billing', 'No'); ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__d('billing', 'Edit'), array('action' => 'edit', $billingGroup['BillingGroup']['id']), array('class' => 'btn btn-warning')); ?>
			<?php echo $this->Form->postLink(__d('billing', 'Delete'), array('action' => 'delete', $billingGroup['BillingGroup']['id']), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to delete # %s?', $billingGroup['BillingGroup']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>
</table>

<?php $this->start('actions'); ?>
<div class="btn-group colored">
	<?php echo $this->Html->link(__d('billing', 'New Billing Group'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
</div>
<?php $this->end(); ?>
