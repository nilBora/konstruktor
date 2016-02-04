<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Operations'), array('controller' => 'FinanceOperation', 'action' => 'index/'.$id));

$this->Html->script(array(
	'vendor/bootstrap-tokenfield',
	'vendor/bootstrap-datetimepicker.min',
	'vendor/bootstrap-datetimepicker.ru.js',
), array('inline' => false));

echo $this->Html->css(array(
	'bootstrap/bootstrap-tokenfield'
));
?>

<style>
	.finance-operation-item {cursor: pointer}
</style>

<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
	<?= $this->element('Finance/project_nav') ?>
	<?= $this->element('Finance/operations_filter') ?>
	<div class="clearfix">
		<div class="operations">
			<div id="finance-operation-list-container">
				<?= $this->element('Finance/operations_list') ?>
			</div>
			<? if ($page < $countPages-1) { ?>
			<span class="showMore" id="finance-operations-showMore">
				<span class="text"><?= __('Show more') ?></span>
				<span class="glyphicons repeat"></span>
			</span>
			<? } ?>
		</div>
		<?= $this->element('Finance/operation_forms') ?>
	</div>
</div>

<script type="application/javascript">
$(document).ready(function(){
// Init page
	var financeOperationCurrentPage = 0;
	$('select, input.checkboxStyle').styler();
// Init currency symbols
	financeSymbolFor = function (code) {
		return <?= json_encode($this->Money->symbols()) ?>[code];
	}
// Show more
	$('#finance-operations-showMore').on('click', function () {
		financeOperationCurrentPage++;
		$.post(financeURL.operationShowMore + '/' + '<?= $id ?>' + '/' + financeOperationCurrentPage + location.search, function (response) {
			if (!response) { // no more
				$('#finance-operations-showMore').hide();
				return;
			}
			var currHtml = $('#finance-operation-list-container').html();
			$('#finance-operation-list-container').html(currHtml + response);
		});
	});
	$('.ui-helper-hidden-accessible').hide();
});
</script>
