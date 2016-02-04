<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-budget">
	<input name="FinanceBudget[project_id]" type="hidden" value="<?= $id ?>"/>
	<input name="FinanceBudget[account_id]" type="hidden" value="<?= $accountId ?>"/>
	<br />
	<div class="form-group noBorder">
		<select name="FinanceBudget[category_id]" required="true" data-placeholder="<?= __('Select category')?>">
			<option value=""><?= __('Select category') ?></option>
			<? foreach ($aFinanceCategory as $category) { ?>
				<option value="<?= $category['FinanceCategory']['id'] ?>"><?= $category['FinanceCategory']['name'] ?></option>
			<? } ?>
		</select>
	</div>
	<div class="form-group">
		<label>План</label>
		<input name="FinanceBudget[plan]" type="number" placeholder="" class="form-control" value="" required="true">
	</div>
	<div class="form-group noBorder">
		<input name="FinanceBudget[is_repeat]" type="checkbox" class="checkboxStyle glyphicons ok_2" />
		<span class="checkboxText"><?= __('Monthly repeat') ?></span>
	</div>
	<div class="clearfix">
		<button type="submit" class="btn btn-primary pull-left"><?= __('Add')?></button>
		<button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?= __('Cancel') ?></button>
	</div>
</form>

<script type="text/javascript">
	// Init
	$('select, [type="checkbox"]').styler();
	// Init currency symbols
	financeSymbolFor = function (code) {
		return <?= json_encode($this->Money->symbols()) ?>[code];
	}
	// Account select
	$('#finance-save-budget [name="FinanceBudget[account_id]"]').on('change', function () {
		var currency = $(this).find(':selected').data('currency');
		if (currency) {
			$('#finance-save-budget .currency').html(financeSymbolFor(currency));
		}
	});
	// Create new budget
	$('#finance-save-budget').on('submit', function () {
		var data = $(this).serialize();
		$.post(financeURL.addBudget + '/<?= $id ?>/<?= $accountId ?>', data, function (response) {
			if (response) {
				alert(response);
				return;
			}
			$('#finance-modal-save-budget').modal('toggle');
			location.reload(false);
		});
		return false;
	});
</script>