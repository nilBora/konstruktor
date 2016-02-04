<div class="col-sm-5 firstColomn">
	<div class="title"><?=__('Expense')?></div>
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAll1Categories"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['categories'] as $item) {
		$item = $item['FinanceCategory'];
		if ($item['type'] != 1) {
			continue;
		}
	?>
		<div class="bigCheckBox">
			<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-item1Categories" name="shareItems[categories][]" value="<?=$item['id']?>"
				<? if (!empty($userShare['categories']) && in_array($item['id'], $userShare['categories'])) {?>checked="true"<? } ?>
			/>
			<span class="checkboxText"><?=$item['name']?></span>
		</div>
	<? } ?>
</div>
<div class="col-sm-5 firstColomn">
	<div class="title"><?=__('Income')?></div>
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAll2Categories"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['categories'] as $item) {
		$item = $item['FinanceCategory'];
		if ($item['type'] != 0) {
			continue;
		}
		?>
		<div class="bigCheckBox">
			<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-item2Categories" name="shareItems[categories][]" value="<?=$item['id']?>"
				<? if (!empty($userShare['categories']) && in_array($item['id'], $userShare['categories'])) {?>checked="true"<? } ?>
			/>
			<span class="checkboxText"><?=$item['name']?></span>
		</div>
	<? } ?>
</div>

<script>
$(document).ready(function () {
	$('#finance-share-selectAll1Categories').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-item1Categories').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-item1Categories').prop('checked', false).removeClass('checked');
		}
	});
	$('#finance-share-selectAll2Categories').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-item2Categories').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-item2Categories').prop('checked', false).removeClass('checked');
		}
	});
});
</script>