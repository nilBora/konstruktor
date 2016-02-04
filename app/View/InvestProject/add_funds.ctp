<?php
$this->Html->addCrumb(Hash::get($investGroup, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($investGroup, 'Group.id')));
$this->Html->addCrumb(Hash::get($investProject, 'name'), array('controller' => 'InvestProject', 'action' => 'view/'.Hash::get($investProject, 'id')));
$this->Html->addCrumb(__('Add funds'), array('controller' => 'InvestProject', 'action' => 'addFunds/'.Hash::get($investReward, 'InvestReward.id')));
?>
<!-- insert additional info for payment here -->
<?php
echo $this->element('BraintreePayments.payments_form', array(
	'formUrl' => array('controller' => 'InvestProject', 'action' => 'checkoutReward'),
	'formButtonText' => __('Fund project')
));
