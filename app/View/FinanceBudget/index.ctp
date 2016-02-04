<?php

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Budgets'), array('controller' => 'FinanceBudget', 'action' => 'index/'.$id));

$this->Html->script(array(
	'vendor/bootstrap-tokenfield',
	'https://www.google.com/jsapi',
	'vendor/bootstrap-datetimepicker.min',
	'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));

echo $this->Html->css(array(
	'bootstrap/bootstrap-tokenfield',
	'main-panel-new',
));
?>

<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
	<?= $this->element('Finance/project_nav') ?>
	<?= $this->element('Finance/budgets_list_new') ?>
</div>
<?= $this->element('Finance/modal_save_budget') ?>

<script type="application/javascript">
$(document).ready(function () {
	$('select, input.checkboxStyle').styler();
// Close modal
	$('#finance-modal-save-budget').on('hidden.bs.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>
