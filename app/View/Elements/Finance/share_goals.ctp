<div class="col-sm-6 firstColomn">
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAllGoals"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<? foreach ($shareItems['goals'] as $item) { ?>
		<? $item = $item['FinanceGoal'] ?>
		<div class="bigCheckBox">
			<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-itemGoals" name="shareItems[goals][]" value="<?=$item['id']?>"
				<? if (!empty($userShare['goals']) && in_array($item['id'], $userShare['goals'])) {?>checked="true"<? } ?>
			/>
			<span class="checkboxText"><?=$item['name']?></span>
		</div>
	<? } ?>
</div>

<script>
$(document).ready(function () {
	$('#finance-share-selectAllGoals').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-itemGoals').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-itemGoals').prop('checked', false).removeClass('checked');
		}
	});
});
</script>