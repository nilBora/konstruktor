<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-category">
	<div class="form-group">
		<label><?= __('Name') ?></label>
		<input type="text" name="FinanceCategory[name]" value="<?= $aFinanceCategory['FinanceCategory']['name'] ?>" placeholder="<?= __('Enter category name')?>" class="form-control" required="true">
	</div>
	<div class="clearfix selectBoxLeft">
		<select name="FinanceCategory[type]" required="true" class="pull-left" data-placeholder="<?= __('Select type') ?>">
			<option value=""><?= __('Select type') ?></option>
			<option value="0"><?= __('Income') ?></option>
			<option value="1"><?= __('Expense') ?></option>
			<option value="2"><?= __('Transfer') ?></option>
		</select>
		<button type="submit" class="btn btn-primary pull-left"><?= __('Save') ?></button>
		<button type="button" id="finance-del-category" class="btn btn-default smallBtn pull-right"><span class="glyphicons bin"></span></button>
	</div>
</form>

<script type="text/javascript">
// Init
	$('#finance-save-category select[name="FinanceCategory[type]"]').val("<?= $aFinanceCategory['FinanceCategory']['type'] ?>");
	$('select').styler();
// Edit category
	$('#finance-save-category').on('submit', function () {
		var data = $(this).serialize();
		$.post(financeURL.editCategory + '/<?= $id ?>', data, function () {
			$('#finance-modal-save-category').modal('toggle');
		});
		return false;
	});
// Delete category
	$('#finance-del-category').on('click', function() {
		$.post(financeURL.delCategory, {id: <?= $id ?>}, function () {
			$('#finance-modal-save-category').modal('toggle');
		});
	});
</script>