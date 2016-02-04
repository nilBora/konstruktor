<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($group, 'Group.id')));
	$this->Html->addCrumb(__('Finances'), array('controller' => 'FinanceProject', 'action' => 'index/'.$id));
	$this->Html->addCrumb(__('Settings'), array('controller' => 'FinanceSetting', 'action' => 'index/'.$id));
?>
<?= $this->element('Finance/project_top') ?>
<div class="financeSettings">
	<?= $this->element('Finance/project_nav') ?>
	<div class="addButtons">
		<a class="btn btn-default"  data-toggle="modal" data-target="#finance-modal-save-account" href="<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'addAccount', $id)) ?>">
			<?= __('New Account') ?>
		</a>
		<a class="btn btn-default"  data-toggle="modal" data-target="#finance-modal-save-category" href="<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'addCategory', $id)) ?>">
			<?= __('New Category') ?>
		</a>
	</div>
	<div class="row categoryName">
		<div class="col-sm-6 firstColomn">
			<div class="subHead"><?= __('Categories') ?></div>
			<div class="btn-group" id="finance-filter-category">
				<button class="btn btn-default all active" type="button"><?= __('All') ?></button>
				<button class="btn btn-default expense" type="button"><?= __('Expense') ?></button>
				<button class="btn btn-default income" type="button"><?= __('Income') ?></button>
				<button class="btn btn-default transfer" type="button"><?= __('Transfer') ?></button>
			</div>
			<div id="finance-list-category">
				<?= $this->element('Finance/settings_category_list') ?>
			</div>
		</div>
		<div class="col-sm-6" id="finance-list-account">
			<?= $this->element('Finance/settings_account_list') ?>
		</div>
	</div>
</div>
<?= $this->element('Finance/modal_save_account') ?>
<?= $this->element('Finance/modal_save_category') ?>

<script type="application/javascript">
$(document).ready(function(){
// Close category modal
	$('#finance-modal-save-category').on('hidden.bs.modal', function () {
		$('#finance-list-category').load(financeURL.listCategory + '/<?= $id ?>');
		$(this).removeData('bs.modal');
	});

// Close account modal
	$('#finance-modal-save-account').on('hidden.bs.modal', function () {
		$('#finance-list-account').load(financeURL.listAccount + '/<?= $id ?>');
		$(this).removeData('bs.modal');
	});
});

// Filter Category
$('#finance-filter-category button').on('click', function() {
	if ($(this).hasClass('all')) {
		$('#finance-list-category').load(financeURL.listCategory + '/<?= $id ?>');
	} else if ($(this).hasClass('income')) {
		$('#finance-list-category').load(financeURL.listCategory + '/<?= $id ?>', {type: 0});
	} else if ($(this).hasClass('expense')) {
		$('#finance-list-category').load(financeURL.listCategory + '/<?= $id ?>', {type: 1});
	} else if ($(this).hasClass('transfer')) {
		$('#finance-list-category').load(financeURL.listCategory + '/<?= $id ?>', {type: 2});
	}
	$('#finance-filter-category button').removeClass('active');
	$(this).addClass('active');
});
</script>
