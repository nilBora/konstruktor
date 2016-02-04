<?php
$this->extend('/Common/admin_index');
$this->set('title_for_layout', __d('billing', 'Billing Plans'));
?>

<table class="table table-striped table-hover">
<thead>
<tr>
	<th><?php echo $this->Paginator->sort('title'); ?></th>
	<th><?php echo __d('billing', 'Remote Plans'); ?></th>
	<th><?php echo __d('billing', 'Is free'); ?></th>
	<th class="actions"><?php echo __d('billing', 'Actions'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($billingPlans as $billingPlan): ?>
	<tr>
		<td><?php echo h($billingPlan['BillingPlan']['title']); ?>&nbsp;</td>
		<td>
			<?php
				$planNames = array();
				foreach($billingPlan['BillingPlan']['remote_plans'] as $remoteId){
					if (array_key_exists($remoteId, $remote_plans)){
						$planNames[] = $remote_plans[$remoteId];
					}
				}
				echo $this->Html->tag('strong', implode(', ', $planNames));
			?>
		</td>
		<td><?php echo $billingPlan['BillingPlan']['free'] ? __d('billing', 'Free') : __d('billing', 'Paid'); ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__d('billing', 'Edit'), array('action' => 'edit', $billingPlan['BillingPlan']['id']), array('class' => 'btn btn-warning')); ?>
			<?php echo $this->Form->postLink(__d('billing', 'Delete'), array('action' => 'delete', $billingPlan['BillingPlan']['id']), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to delete # %s?', $billingPlan['BillingPlan']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php $this->start('actions'); ?>
<div class="btn-group colored">
	<?php echo $this->Html->link(__d('billing', 'New Billing Plan'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>
</div>
<?php $this->end(); ?>
