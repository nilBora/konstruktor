<?php
$this->extend('/Common/admin_form');
$this->set('title_for_layout', __d('billing', 'Add new Billing Plan'));
?>

<?php
$this->assign('form_create', $this->Form->create('BillingPlan', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	)
)));
?>
<?php
	echo $this->Translate->input('BillingPlan.eng.title');
	echo $this->Translate->input('BillingPlan.rus.title', array('label' => false));
	echo $this->Form->input('slug');
	echo $this->Form->input('group_id', array(
		'options' => $billingGroups
	));
	echo $this->Form->input('description');
	echo $this->Form->input('free', array(
		'type' => 'checkbox',
		'label' => __d('billing', 'Basic free plan'),
		'class' => false,
	));
	echo $this->Form->input('remote_plans', array(
		'div' => array('class' => 'form-group', 'id' => 'remotePlans'),
		'type' => 'select',
		'multiple' => true,
		'size' => 10,
		'options' => $remote_plans,
	));
?>
<?php $this->assign('form_end', $this->Form->end()); ?>

<?php $this->start('actions'); ?>
<div class="btn-group colored">
	<?php echo $this->Html->link(__d('billing', 'List Billing Plans'), array('action' => 'index'), array('class' => 'btn btn-primary')); ?>
</div>
<?php $this->end(); ?>

<script>
$(function() {
	function freePlanSwitch(){
		if($('#BillingPlanFree').is(':checked')){
			$('#remotePlans').fadeOut('slow');
			$('#BillingPlanRemotePlans').val('');
		} else{
			$('#remotePlans').fadeIn('slow');
		}
	}
	$('#BillingPlanFree').change(function(){
		freePlanSwitch();
	});
	freePlanSwitch();
});
</script>
