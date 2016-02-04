<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-account">
	<div class="form-group">
		<label><?= __('Name') ?></label>
		<input type="text" name="FinanceAccount[name]" value="<?= $aFinanceAccount['FinanceAccount']['name'] ?>" placeholder="<?= __('Account') ?>..." class="form-control" required="true">
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
		<input type="number" step="0.01" name="FinanceAccount[balance]" value="<?= $aFinanceAccount['FinanceAccount']['balance'] ?>" placeholder="<?= __('Balance') ?>"class="form-control" required="true">
	</div>
	<div class="clearfix">
		<button type="submit" class="btn btn-primary pull-left" style="margin-right: 10px;"><?= __('Save')?></button>
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= __('Close')?></button>
		<button type="button" id="finance-del-account" class="btn btn-default smallBtn pull-right"><span class="glyphicons bin"></span></button>
	</div>
</form>

<script type="text/javascript">
// Init
	$('#finance-save-account select[name="FinanceAccount[type]"]').val("<?= $aFinanceAccount['FinanceAccount']['type'] ?>");
	$('#finance-save-account select[name="FinanceAccount[currency]"]').val("<?= $aFinanceAccount['FinanceAccount']['currency'] ?>");
	$('select').styler();
// Edit account
	$('#finance-save-account').on('submit', function () {
		var data = $(this).serialize();
		$.post(financeURL.editAccount + '/<?= $id ?>', data, function () {
			$('#finance-modal-save-account').modal('toggle');
		});
		return false;
	});
// Delete account
	$('#finance-del-account').on('click', function() {
		$.post(financeURL.delAccount, {id: <?= $id ?>}, function () {
			$('#finance-modal-save-account').modal('toggle');
		});
	});
</script>