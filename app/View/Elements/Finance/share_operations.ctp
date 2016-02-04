<div class="col-sm-6 firstColomn">
	<div class="bigCheckBox selectAll">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" id="finance-share-selectAllOperations"/>
		<span class="checkboxText"><?=__('Select all')?></span>
	</div>
	<div class="bigCheckBox">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-itemOperations" name="shareItems[operations][]" value="income"
			<? if (!empty($userShare['operations']) && in_array('income', $userShare['operations'])) {?>checked="true"<? } ?>
		/>
		<span class="checkboxText"><?=__('Income')?></span>
	</div>
	<div class="bigCheckBox">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-itemOperations" name="shareItems[operations][]" value="expense"
			<? if (!empty($userShare['operations']) && in_array('expense', $userShare['operations'])) {?>checked="true"<? } ?>
		/>
		<span class="checkboxText"><?=__('Expense')?></span>
	</div>
	<div class="bigCheckBox">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2 finance-share-itemOperations" name="shareItems[operations][]" value="transfer"
			<? if (!empty($userShare['operations']) && in_array('transfer', $userShare['operations'])) {?>checked="true"<? } ?>
		/>
		<span class="checkboxText"><?=__('Transfer')?></span>
	</div>
</div>

<script>
$(document).ready(function () {
	$('#finance-share-selectAllOperations').on('change', function () {
		if ($(this).prop('checked')) {
			$('.finance-share-itemOperations').prop('checked', true).addClass('checked');
		} else {
			$('.finance-share-itemOperations').prop('checked', false).removeClass('checked');
		}
	});
});
</script>