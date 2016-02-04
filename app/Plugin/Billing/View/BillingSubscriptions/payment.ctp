<?php
	$user_id = Hash::get($currUser, 'User.id');
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), '/User/view/'.$user_id);
	$this->Html->addCrumb(__d('billing', 'Subscriptions'), array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'subscriptions'));
	$this->Html->addCrumb('...', array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'plans/'.'disc-space'));
	$this->Html->addCrumb(__d('billing', 'Subscribe'), array('plugin' => 'billing', 'controller' => 'billing_subscriptions', 'action' => 'payment'));

echo $this->element('BraintreePayments.payments_form', array(
	'formUrl' => array('controller' => 'billing_subscriptions', 'action' => 'checkout'),
	'formButtonText' => __d('billing', 'Subscribe')
));

