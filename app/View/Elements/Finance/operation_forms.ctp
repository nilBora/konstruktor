<? if (!$isPartAccess || (!empty($currUserShare['operations']) && !empty($currUserShare['accounts']) && !empty($currUserShare['categories']))) { ?>
<div class="planning">
	<div class="top">
		<div class="inner">
			<select class="selectOperation" id="finance-operation-form-selector">
				<? if (!$isPartAccess || in_array('expense', $currUserShare['operations'])) { ?>
				<option value="expense"><?= __('Expense') ?></option>
				<? } ?>
				<? if (!$isPartAccess || in_array('income', $currUserShare['operations'])) { ?>
				<option value="income"><?= __('Income') ?></option>
				<? } ?>
				<? if (!$isPartAccess || in_array('transfer', $currUserShare['operations'])) { ?>
				<option value="transfer"><?= __('Transfer') ?></option>
				<? } ?>
			</select>
		</div>
	</div>
	<div id="finance-operation-forms">
		<div class="section-form section-expense hide">
			<?= $this->element('Finance/form_expense') ?>
		</div>
		<div class="section-form section-income hide">
			<?= $this->element('Finance/form_income') ?>
		</div>
		<div class="section-form section-transfer hide">
			<?= $this->element('Finance/form_transfer') ?>
		</div>
	</div>
</div>
<script type="application/javascript">
$(document).ready(function(){
	$('#finance-operation-forms .section-' + $('#finance-operation-form-selector').find('option:first').val()).removeClass('hide');
	$('#finance-operation-form-selector').on('change', function () {
		var name = $(this).val();
		$('#finance-operation-forms .section-form').addClass('hide');
		$('#finance-operation-forms .section-' + name).removeClass('hide');
	});
});
</script>
<? } ?>