<div class="col-sm-6 firstColomn">
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAllAccounts"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['accounts'] as $item) { ?>
		<? $item = $item['FinanceAccount'] ?>
	<div class="bigCheckBox">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-itemAccounts" name="shareItems[accounts][]" value="<?=$item['id']?>"
			<? if (!empty($userShare['accounts']) && in_array($item['id'], $userShare['accounts'])) {?>checked="true"<? } ?>
		/>
		<span class="checkboxText"><?=$item['name']?></span>
	</div>
	<? } ?>
</div>

<script>
$(document).ready(function () {
	$('#finance-share-selectAllAccounts').on('change', function () {
		if ($(this).prop('checked')) {
			$(this).addClass('checked')
			$('.finance-share-itemAccounts').prop('checked', true).addClass('checked');
		} else {
			$(this).removeClass('checked')
			$('.finance-share-itemAccounts').prop('checked', false).removeClass('checked');
		}
	});
});
</script>