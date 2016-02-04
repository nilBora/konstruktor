<?php
$message = __d('billing', 'You are not successfully subscribed.');
if($subscription['BillingSubscription']['active']){
	$message =  __d('billing', 'You are successfully subscribed.');
}

	$user_id = Hash::get($currUser, 'User.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), '/User/view/'.$user_id);
	$this->Html->addCrumb(__d('billing', 'Subscriptions'), array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'));
	$this->Html->addCrumb('...', array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans'.'/disc-space'));
	$this->Html->addCrumb($message, array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans'.'/disc-space'));

	echo $this->Html->tag('h2', $message);
?>
