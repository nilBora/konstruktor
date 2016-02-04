<?
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
<div class="addButtons">
	<form id="finance-operation-filter">
		<select data-placeholder="<?= __('All accounts')?>" name="accountId">
			<option value=""><?= __('All accounts') ?></option>
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

		<select data-placeholder="<?= __('All categories')?>" name="categoryId">
			<option value=""><?= __('All categories') ?></option>
			<? foreach ($aFinanceCategory as $category) { ?>
				<?
				$selectedCategory = '';
				if (isset($categoryId) && $categoryId == $category['FinanceCategory']['id']) {
					$selectedCategory = 'selected="true"';
				}
				?>
				<option <?= $selectedCategory ?> value="<?= $category['FinanceCategory']['id'] ?>"><?= $category['FinanceCategory']['name'] ?></option>
			<? } ?>
		</select>
		<div class="calandarPeriod">
			<div class="dateTime date" id="finance-operation-filter-from">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('From') ?>" readonly="readonly">
				<input type="hidden" id="finance-operation-filter-from-mirror" name="from"
					<? if (@$this->request->query['from']) { ?> value="<?= $this->request->query['from']?>"<? } ?>>
			</div>
			<div class="dateTime date" id="finance-operation-filter-to">
				<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
				<input type="text" class="form-control" placeholder="<?= __('To') ?>" readonly="readonly">
				<input type="hidden" id="finance-operation-filter-to-mirror" name="to"
					<? if (@$this->request->query['to']) { ?> value="<?= $this->request->query['to']?>"<? } ?>>
			</div>
		</div>
	<!--<div class="addIcons">
			<a href="javascript:void(0)" class="btn btn-default smallBtn"><span class="glyphicons alarm"></span></a>
			<a href="javascript:void(0)" class="btn btn-default smallBtn"><span class="glyphicons file_import"></span></a>
			<a href="javascript:void(0)" class="btn btn-default smallBtn"><span class="glyphicons print"></span></a>
		</div> -->
	</form>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('#finance-operation-filter [name="accountId"], #finance-operation-filter [name="categoryId"]').on('change', function () {
		$('#finance-operation-filter').submit();
	});
	$('#finance-operation-filter-from').datetimepicker({
		format: '<?= $dateFormat ?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?= $lang ?>",
		linkField: 'finance-operation-filter-from-mirror',
		linkFormat: 'yyyy-mm-dd'
	});
	$('#finance-operation-filter-to').datetimepicker({
		format: '<?= $dateFormat ?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?= $lang ?>",
		linkField: 'finance-operation-filter-to-mirror',
		linkFormat: 'yyyy-mm-dd'
	});
	<? if (@$this->request->query['from']) { ?>
		$('#finance-operation-filter-from').datetimepicker('setDate', new Date("<?=$this->request->query['from']?>"));
	<? } ?>
	<? if (@$this->request->query['to']) { ?>
		$('#finance-operation-filter-to').datetimepicker('setDate', new Date("<?=$this->request->query['to']?>"));
	<? } ?>

	$('#finance-operation-filter-from-mirror, #finance-operation-filter-to-mirror').on('change', function () {
		$('#finance-operation-filter').submit();
	});
});
</script>