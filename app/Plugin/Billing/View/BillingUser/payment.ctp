<?php echo $this->element('BraintreePayments.payments_form', array(
	'formUrl' => array('plugin' => 'billing','controller' => 'billing_user', 'action' => 'checkout'),
	'formButtonText' => __d('billing', 'Add funds')
));
