<?
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>

<div class="item controls">
	<form id="finance-report-filter">
		<span class="name">
			<select data-placeholder="<?= __('All accounts')?>" name="accountId">
				<? foreach ($aFinanceAccount as $account) { ?>
					<?
					$selectedAccount = '';
					if (isset($accountId) && $accountId == $account['FinanceAccount']['id']) {
						$selectedAccount = 'selected="true"';
					}
					?>
					<option <?= $selectedAccount ?> value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
				<? } ?>
			</select>
		</span>
		<span class="value1">
			<div class="dateTime date" id="finance-report-filter-month1">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('Month') ?> 1" readonly="readonly">
				<input type="hidden" id="finance-report-filter-month1-mirror" name="month1"
					<? if (@$this->request->query['month1']) { ?> value="<?= $this->request->query['month1']?>"<? } ?>>
			</div>
		</span>
		<span class="value2">
			<div class="dateTime date" id="finance-report-filter-month2">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('Month') ?> 2" readonly="readonly">
				<input type="hidden" id="finance-report-filter-month2-mirror" name="month2"
					<? if (@$this->request->query['month2']) { ?> value="<?= $this->request->query['month2']?>"<? } ?>>
			</div>
		</span>
	</form>
	<!--<div class="addIcons">
		<a class="btn btn-default smallBtn" href="javascript:void(0)"><span class="glyphicons file_import"></span></a>
	</div>-->
</div>

<script type="text/javascript">
$(document).ready(function () {
// Init
	$('select, input.checkboxStyle').styler();
	$('#finance-report-filter-month1').datetimepicker({
		format: 'MM yyyy', //'<?= $dateFormat ?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 3,
		minView: 3,
		language:"<?= $lang ?>",
		linkField: 'finance-report-filter-month1-mirror',
		linkFormat: 'yyyy-mm'
	});
	$('#finance-report-filter-month2').datetimepicker({
		format: 'MM yyyy',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 3,
		minView: 3,
		language:"<?= $lang ?>",
		linkField: 'finance-report-filter-month2-mirror',
		linkFormat: 'yyyy-mm'
	});

	$('#finance-report-filter-month1').datetimepicker('setDate', new Date("<?= $month1 ?>"));
	$('#finance-report-filter-month2').datetimepicker('setDate', new Date("<?= $month2 ?>"));

// Events
	$('#finance-report-filter [name="accountId"]').on('change', function () {
		$('#finance-report-filter').submit();
	});
	$('#finance-report-filter-month1-mirror, #finance-report-filter-month2-mirror').on('change', function () {
		$('#finance-report-filter').submit();
	});
});
</script>