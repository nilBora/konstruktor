<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-category">
	<input type="hidden" name="FinanceCategory[project_id]" value="<?= $id ?>" required="true">
	<div class="form-group">
		<label><?= __('Name') ?></label>
		<input type="text" name="FinanceCategory[name]" placeholder="<?= __('Enter category name')?>" class="form-control" required="true">
	</div>
	<div class="clearfix selectBoxLeft">
		<select name="FinanceCategory[type]" required="true" class="pull-left" data-placeholder="<?= __('Select type') ?>">
			<option value=""><?= __('Select type') ?></option>
			<option value="0"><?= __('Income') ?></option>
			<option value="1"><?= __('Expense') ?></option>
			<option value="2"><?= __('Transfer') ?></option>
		</select>
		<button type="submit" class="btn btn-primary pull-left"><?= __('Save') ?></button>
	</div>
</form>

<script type="text/javascript">
// Init
	$('select').styler();

// Create new category
	$('#finance-save-category').on('submit', function () {
		var data = $(this).serialize();
		$.post(financeURL.addCategory + '/<?= $id ?>', data, function () {
			$('#finance-modal-save-category').modal('toggle');
		});
		return false;
	});
</script>