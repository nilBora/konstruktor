<?php
$this->extend('/Common/admin_form');
$this->set('title_for_layout', __d('billing', 'Billing Plans'));

$units = Hash::combine(Configure::read('Billing'), "units.{s}.unit", "units.{s}.name");
$groupUnits = $this->request->data['BillingGroup']['limit_units'];
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
	echo $this->Form->input('id');
	echo $this->Translate->input('BillingPlan.eng.title');
	echo $this->Translate->input('BillingPlan.rus.title', array('label' => false));
	echo $this->Form->input('slug');
	echo $this->Form->input('group_id', array(
		'options' => $billingGroups
	));
	echo $this->Form->input('description');
	echo $this->Form->input('limit_value', array(
		'label' => __d('billing', 'Limit plan to'),
		'placeholder' => __d('billing', '2'),
		'beforeInput' => '<div class="input-group">',
		'afterInput' => '<span class="input-group-addon">'.$units[$groupUnits].'</span></div>'
	));
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
	<?php echo $this->Form->postLink(__d('billing', 'Delete'), array('action' => 'delete', $this->Form->value('BillingPlan.id')), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to delete # %s?', $this->Form->value('BillingPlan.id'))); ?>
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
