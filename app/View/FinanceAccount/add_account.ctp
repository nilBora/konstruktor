<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-account">
	<input type="hidden" name="FinanceAccount[project_id]" value="<?= $id ?>" required="true">
	<div class="form-group">
		<label><?= __('Name') ?></label>
		<input type="text" name="FinanceAccount[name]" value="" placeholder="<?= __('Account') ?>..." class="form-control" required="true">
	</div>
	<div class="clearfix form-group bankOrders noBorder">
		<select class="pull-left"  name="FinanceAccount[type]" required="true" data-placeholder="<?= __('Select type') ?>">
			<option value=""><?= __('Select type') ?></option>
			<option value="0"><?= __('Credit card') ?></option>
			<option value="1"><?= __('Debit card') ?></option>
			<option value="2"><?= __('Bank account') ?></option>
			<option value="3"><?= __('Cash') ?></option>
		</select>
		<select class="pull-right" name="FinanceAccount[currency]" required="true" data-placeholder="<?= __('Select currency') ?>">
			<option value=""><?= __('Select currency') ?></option>
			<option value="USD">$</option>
			<option value="EUR">&#8364;</option>
			<option value="RUB">P</option>
		</select>
	</div>
	<div class="form-group">
		<label><?= __('Balance') ?></label>
		<input type="number" step="0.01" name="FinanceAccount[balance]" placeholder="<?= __('Balance') ?>" class="form-control">
	</div>
	<div class="clearfix">
		<button type="submit" class="btn btn-primary pull-left" style="margin-right: 10px;"><?= __('Save')?></button>
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= __('Close')?></button>
	</div>
</form>

<script type="text/javascript">
// Init
	$('select').styler();

// Create new account
	$('#finance-save-account').on('submit', function () {
		if (!$(this).find('[name="FinanceAccount[currency]"]').val()) {
			alert("<?=__('Currency is required')?>");
			return false;
		}
		$('#finance-modal-save-account').data('status', 1);
		var data = $(this).serialize();
		$.post(financeURL.addAccount + '/<?= $id ?>', data, function () {
			$('#finance-modal-save-account').modal('toggle');
		});
		return false;
	});
</script>