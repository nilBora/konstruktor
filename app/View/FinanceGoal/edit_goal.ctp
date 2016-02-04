<?
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<form id="finance-save-goal">
	<input type="hidden" name="FinanceGoal[project_id]" value="<?= $projectId ?>" required="true">
	<div class="form-group">
		<label><?= __('Goal name') ?></label>
		<input type="text" name="FinanceGoal[name]" value="<?= $aFinanceGoal['FinanceGoal']['name'] ?>" placeholder="<?= __('Goal') ?>" class="form-control" required="true">
	</div>
	<div class="clearfix form-group bankOrders noBorder">
		<select name="FinanceGoal[account_id]" required="true" data-placeholder="<?= __('Select account')?>" class="pull-left">
			<option value=""><?= __('Select account') ?></option>
			<? foreach ($aFinanceAccount as $account) { ?>
				<option data-currency="<?= $account['FinanceAccount']['currency'] ?>" value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
			<? } ?>
		</select>
		<div class="dateTime date pull-right" id="finance-add-goal-finish">
			<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
			<input type="text"  readonly="readonly"  placeholder="<?= __('Select date') ?>" class="form-control">
			<input name="FinanceGoal[finish]" type="hidden" id="finance-add-goal-finish-mirror">
		</div>
	</div>
	<div class="form-group">
		<label><?= __('Amount required') ?></label>
		<input type="number" step="0.01" name="FinanceGoal[final_sum]" value="<?= $aFinanceGoal['FinanceGoal']['final_sum'] ?>" class="form-control" placeholder="<?= __('Amount required') ?>" required="true">
	</div>
	<div class="clearfix">
		<button type="submit" class="btn btn-primary pull-left" style="margin-right: 10px;"><?= __('Save')?></button>
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= __('Close')?></button>
	</div>
</form>

<script type="text/javascript">
	// Init
	$('#finance-save-goal select[name="FinanceGoal[account_id]"]').val("<?= $aFinanceGoal['FinanceGoal']['account_id'] ?>");
	$('select').styler();
	// Init currency symbols
	financeSymbolFor = function (code) {
		return <?= json_encode($this->Money->symbols()) ?>[code];
	}
	$('#finance-add-goal-finish').datetimepicker({
		format: '<?= $dateFormat?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?=$lang?>",
		linkField: 'finance-add-goal-finish-mirror',
		linkFormat: 'yyyy-mm-dd'
	});
	$('#finance-add-goal-finish').datetimepicker('setDate', new Date("<?= $aFinanceGoal['FinanceGoal']['finish'] ?>"));

	// Account select
	$('#finance-save-goal [name="FinanceGoal[account_id]"]').on('change', function () {
		var currency = $(this).find(':selected').data('currency');
		if (currency) {
			$('#finance-save-goal .currency').html(financeSymbolFor(currency));
		}
	});

	// Edit goal
	$('#finance-save-goal').on('submit', function () {
		var data = $(this).serialize();
		$.post(financeURL.editGoal + '/<?= $id ?>', data, function (response) {
			if (response) {
				alert(response);
				return;
			}
			$('#finance-modal-save-goal').modal('toggle');
			location.reload(false);
		});
		return false;
	});
</script>