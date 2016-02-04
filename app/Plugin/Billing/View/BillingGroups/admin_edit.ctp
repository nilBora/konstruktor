<?php
$this->extend('/Common/admin_form');
$this->set('title_for_layout', __d('billing', 'Edit Billing Group'));
?>

<?php
$this->assign('form_create', $this->Form->create('BillingGroup', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	)
)));
?>
<?php
	echo $this->Form->input('id');
	echo $this->Translate->input('BillingGroup.eng.title');
	echo $this->Translate->input('BillingGroup.rus.title', array('label' => false));
	echo $this->Form->input('slug');
	echo $this->Form->input('limit_units', array(
		'type' => 'select',
		'label' => 'Limit units for billing group',
		'options' => Hash::combine(Configure::read('Billing'), "units.{s}.unit", "units.{s}.name"),
	));
	echo $this->Form->input('active', array(
		'type' => 'checkbox',
		'label' => 'Is group enabled',
		'class' => false,
		'checked' => true
	));
?>
<?php $this->assign('form_end', $this->Form->end()); ?>

<?php $this->start('actions'); ?>
<div class="btn-group colored">
	<?php echo $this->Html->link(__d('billing', 'List Billing Groups'), array('action' => 'index'), array('class' => 'btn btn-primary')); ?>
	<?php echo $this->Form->postLink(__d('billing', 'Delete'), array('action' => 'delete', $this->Form->value('BillingGroup.id')), array('class' => 'btn btn-danger'), __d('billing', 'Are you sure you want to delete # %s?', $this->Form->value('BillingGroup.id'))); ?>
</div>
<?php $this->end(); ?>
