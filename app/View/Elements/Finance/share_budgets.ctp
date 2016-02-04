<div class="col-sm-5 firstColomn">
	<div class="title"><?=__('Expense')?></div>
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAll1Budgets"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['budgets'] as $item) {
		$itemId = $item['FinanceBudget']['id'];
		$item = $item['Category'];
		if ($item['type'] != 1) {
			continue;
		}
		?>
		<div class="bigCheckBox">
			<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-item1Budgets" name="shareItems[budgets][]" value="<?=$itemId?>"
				<? if (!empty($userShare['budgets']) && in_array($itemId, $userShare['budgets'])) {?>checked="true"<? } ?>
			/>
			<span class="checkboxText"><?=$item['name']?></span>
		</div>
	<? } ?>
</div>
<div class="col-sm-5 firstColomn">
	<div class="title"><?=__('Income')?></div>
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAll2Budgets"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['budgets'] as $item) {
		$itemId = $item['FinanceBudget']['id'];
		$item = $item['Category'];
		if ($item['type'] != 0) {
			continue;
		}
		?>
		<div class="bigCheckBox">
			<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-item2Budgets" name="shareItems[budgets][]" value="<?=$itemId?>"
				<? if (!empty($userShare['budgets']) && in_array($itemId, $userShare['budgets'])) {?>checked="true"<? } ?>
			/>
			<span class="checkboxText"><?=$item['name']?></span>
		</div>
	<? } ?>
</div>

<script>
$(document).ready(function () {
	$('#finance-share-selectAll1Budgets').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-item1Budgets').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-item1Budgets').prop('checked', false).removeClass('checked');
		}
	});
	$('#finance-share-selectAll2Budgets').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-item2Budgets').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-item2Budgets').prop('checked', false).removeClass('checked');
		}
	});
});
</script>