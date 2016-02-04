<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Reports'), array('controller' => 'FinanceOperation', 'action' => 'index/'.$id));

$this->Html->script(array(
	'vendor/bootstrap-tokenfield',
	'vendor/bootstrap-datetimepicker.min',
	'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));

echo $this->Html->css(array(
	'bootstrap/bootstrap-tokenfield'
));
?>

<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
	<?= $this->element('Finance/project_nav') ?>
	<div class="reports">
		<?= $this->element('Finance/reports_filter') ?>
		<?= $this->element('Finance/reports_list') ?>
	</div>
</div>
