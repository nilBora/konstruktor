<?
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
?>

<form id="finance-add-transfer-operation">
	<input type="hidden" name="FinanceOperation[project_id]" value="<?= $id ?>" required="true">
	<input type="hidden" name="FinanceOperation[type]" value="2" required="true"><!-- Transfer (type == 2) -->
	<div class="dateTime date">
		<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
		<input type="text"  readonly="readonly"  placeholder="<?= __('Select date') ?>" class="form-control">
		<input name="FinanceOperation[created]" type="hidden" id="finance-transfer-created">
	</div>
	<select name="FinanceOperation[account_id]" required="true" data-placeholder="<?= __('From account')?>" class="tick">
		<option value=""><?= __('Select transfer account') ?></option>
		<? foreach ($aFinanceAccount as $account) { ?>
			<option data-currency="<?= $account['FinanceAccount']['currency'] ?>" value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
		<? } ?>
	</select>
	<select name="FinanceOperation[account_id_2]" required="true" data-placeholder="<?= __('To account')?>" class="tick">
		<option value=""><?= __('Select income account') ?></option>
		<? foreach ($aFinanceAccount as $account) { ?>
			<option data-currency="<?= $account['FinanceAccount']['currency'] ?>" value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
		<? } ?>
	</select>
	<div class="form-group summ">
		<label><?= __('Transfer amount') ?></label>
		<input type="number" step="0.01" name="FinanceOperation[amount]" required="true" value="" placeholder="" class="form-control">
		<strong class="currency currency1"></strong>
	</div>
	<div class="form-group summ">
		<label><?= __('Income amount') ?></label>
		<input name="FinanceOperation[amount_2]" required="true" type="text" value="" placeholder="" class="form-control">
		<strong class="currency currency2"></strong>
	</div>
	<div class="form-group noBorder">
		<input name="FinanceOperation[is_planned]" type="checkbox" class="checkboxStyle glyphicons ok_2" />
		<span class="checkboxText"><?= __('Scheduled transaction') ?></span>
	</div>
	<div class="form-group">
		<? if ($isOwner) { ?>
			<label><?= __('Type new category name and hit Enter') ?></label>
		<? } else { ?>
			<label><?= __('Click on the field to select a category') ?></label>
		<? } ?>
		<input name="FinanceOperationHasCategory[category_id]" id="finance-transfer-tokenfield" type="text" value="" class="form-control">
	</div>
	<div class="form-group">
		<label><?= __('Comments') ?></label>
		<input name="FinanceOperation[comment]" type="text" value="" placeholder="" class="form-control">
	</div>
	<div class="bottomButton">
		<div class="inner">
			<button type="submit" class="btn btn-default"><?= __('Make operation') ?></button>
		</div>
	</div>
</form>

<script>
$(document).ready(function () {
// Init category input's
	var financeCategories = <?= json_encode($aFinanceCategory) ?>;
	var financeTransferCategoriesSource = [];
	for(var i in financeCategories) {
		var item = financeCategories[i].FinanceCategory;
		if (item.type == 2) {
			financeTransferCategoriesSource.push({value: item.id, label: item.name});
		}
	}
// Date time picker
<?php 
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
	$('#finance-add-transfer-operation .dateTime').datetimepicker({
		format: '<?= $dateFormat?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?=$lang?>",
		linkField: 'finance-transfer-created',
		linkFormat: 'yyyy-mm-dd hh:ii:ss'
	});
	$('#finance-add-transfer-operation .dateTime').datetimepicker('setDate', new Date());

// Transfer account select
	$('#finance-add-transfer-operation [name="FinanceOperation[account_id]"]').on('change', function () {
		var currency = $(this).find(':selected').data('currency');
		if (currency) {
			$('#finance-add-transfer-operation .currency1').html(financeSymbolFor(currency));
		}
	});
	$('#finance-add-transfer-operation [name="FinanceOperation[account_id_2]"]').on('change', function () {
		var currency = $(this).find(':selected').data('currency');
		if (currency) {
			$('#finance-add-transfer-operation .currency2').html(financeSymbolFor(currency));
		}
	});
// Transfer token field
	$('#finance-transfer-tokenfield').tokenfield({
		autocomplete: {
			source: financeTransferCategoriesSource,
			delay: 100
		},
		showAutocompleteOnFocus: true
	});
// Transfer operation
	$('#finance-add-transfer-operation').on('submit', function () {
		if (!$('#finance-transfer-tokenfield').val()) {
			alert("<?= __('Categories is required')?>");
			return false;
		}
		var data = $(this).serialize();
		$.post(financeURL.addOperation, data, function (response) {
			if (response) {
				alert(response);
				return;
			}
			location.reload(false);
		});
		return false;
	});
});
</script>