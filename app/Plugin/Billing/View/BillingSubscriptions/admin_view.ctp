<?php
$this->extend('/Common/admin_form');
$this->set('title_for_layout', __d('billing', 'Subscription'));
?>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('billing', 'General Info') ?></h3>
			</div>
			<div class="panel-body">
				<dl class="dl-horizontal">
					<dt><?php echo __d('billing', 'User'); ?></dt>
					<dd>
						<?php echo $this->Html->link($billingSubscription['User']['id'], array('plugin' => false, 'controller' => 'users', 'action' => 'view', $billingSubscription['User']['id'])); ?>
						&nbsp;
					</dd>
					<dt><?php echo __d('billing', 'Billing Group'); ?></dt>
					<dd>
						<?php echo $this->Html->link($billingSubscription['BillingGroup']['title'], array('controller' => 'billing_groups', 'action' => 'view', $billingSubscription['BillingGroup']['id'])); ?>
						<span> > </span>
						<?php echo $this->Html->link($billingSubscription['BillingPlan']['title'], array('controller' => 'billing_plans', 'action' => 'view', $billingSubscription['BillingPlan']['id'])); ?>
						(<?php echo h($billingSubscription['BillingSubscription']['remote_plan_id']); ?>)
					</dd>
					<dt><?php echo __d('billing', 'Active'); ?></dt>
					<dd>
						<?php echo h($billingSubscription['BillingSubscription']['active']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __d('billing', 'Expiration'); ?></dt>
					<dd>
						<?php echo h($billingSubscription['BillingSubscription']['expires']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __d('billing', 'Created'); ?></dt>
					<dd>
						<?php echo h($billingSubscription['BillingSubscription']['created']); ?>
						&nbsp;
					</dd>
					<dt><?php echo __d('billing', 'Modified'); ?></dt>
					<dd>
						<?php echo h($billingSubscription['BillingSubscription']['modified']); ?>
						&nbsp;
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('billing', 'Braintree Subscription') ?></h3>
			</div>
			<div class="panel-body">
				<dl class="dl-horizontal">
					<dt><?php echo __d('billing', 'Subscription ID'); ?></dt>
					<dd>
						<?php echo $billingSubscription['BraintreeSubscription']->id ?>
					</dd>
					<dt><?php echo __d('billing', 'Current billing cycle'); ?></dt>
					<dd>
						<?php echo $billingSubscription['BraintreeSubscription']->currentBillingCycle ?>
					</dd>
					<dt><?php echo __d('billing', 'Start date'); ?></dt>
					<dd>
						<?php echo $billingSubscription['BraintreeSubscription']->billingPeriodStartDate->format('Y-m-d') ?>
					</dd>
					<dt><?php echo __d('billing', 'End date'); ?></dt>
					<dd>
						<?php echo $billingSubscription['BraintreeSubscription']->billingPeriodEndDate->format('Y-m-d') ?>
					</dd>
					<dt><?php echo __d('billing', 'Next bill amount'); ?></dt>
					<dd>
						<?php echo $billingSubscription['BraintreeSubscription']->nextBillAmount ?>
					</dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('billing', 'Braintree Plan') ?></h3>
			</div>
			<div class="panel-body">
				<dt><?php echo __d('billing', 'Subscription ID'); ?></dt>
				<dd>
					<?php echo $billingSubscription['BraintreePlan']->id ?>
				</dd>
				<dt><?php echo __d('billing', 'Name'); ?></dt>
				<dd>
					<?php echo $billingSubscription['BraintreePlan']->name ?>
				</dd>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('billing', 'Braintree Transactions') ?></h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-hover">
					<thead>
					<tr>
						<th><?php echo __d('billing', 'Transactiond ID'); ?></th>
						<th><?php echo __d('billing', 'Payment'); ?></th>
						<th><?php echo __d('billing', 'Date & Time'); ?></th>
						<th><?php echo __d('billing', 'Amount'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($billingSubscription['BraintreeSubscription']->transactions as $transaction): ?>
						<tr>
							<td><?php echo $transaction->id ?></td>
							<td>
								<?php echo $this->Html->image($transaction->creditCardDetails->imageUrl, array('width' => 28, 'height' => 19)) ?>
								<?php echo $transaction->creditCardDetails->maskedNumber ?>
							</td>
							<td><?php echo $transaction->createdAt->format('Y-m-d H:i:s'); ?></td>
							<td><?php echo $transaction->amount.' '.$transaction->currencyIsoCode; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('billing', 'Braintree Status History') ?></h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-hover">
					<thead>
					<tr>
						<th><?php echo __d('billing', 'Source'); ?></th>
						<th><?php echo __d('billing', 'Date & Time'); ?></th>
						<th><?php echo __d('billing', 'Status'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($billingSubscription['BraintreeSubscription']->statusHistory as $record): ?>
						<tr>
							<td><?php echo $record->subscriptionSource ?></td>
							<td><?php echo $record->timestamp->format('Y-m-d H:i:s'); ?></td>
							<td><?php echo $record->status; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->start('actions'); ?>
<div class="btn-group">
	<?php echo $this->Html->link(__d('billing', 'List Subscriptions'), array('action' => 'index'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->postLink(__d('billing', 'Cancel Subscription'), array('action' => 'cancel', $billingSubscription['BillingSubscription']['id']), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to cancel # %s?', $billingSubscription['BillingSubscription']['id'])); ?>
</div>
<?php $this->end(); ?>
