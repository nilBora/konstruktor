<?
$currDate = isset($lastDate) ? $lastDate : '';
$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
<? if (!empty($aFinanceOperations)) {
	$firstDate = $aFinanceOperations[0]['FinanceOperation']['created'];
	foreach ($aFinanceOperations as $operation) {
		if ($operation['FinanceOperation']['amount'] < 0) {
			$operation['FinanceOperation']['amount'] *= -1;
		}
		$lastDate = $operation['FinanceOperation']['created'];
		$checkDate = substr($operation['FinanceOperation']['created'], 0, 10);

		if ($operation['FinanceOperation']['type'] == -1) { // Account create operation
			continue;
		}

		$date = date('j', strtotime($operation['FinanceOperation']['created']));
		$month = __(date('M', strtotime($operation['FinanceOperation']['created'])));
		$day = __(date('D', strtotime($operation['FinanceOperation']['created'])));
	?>
		<? if ($currDate !== $checkDate) { ?>
			<? if ($currDate) { ?>
				</div>
			<? } ?>
		<div class="dateItem">
			<!-- Small Calendar -->
			<div class="calendarBack">
				<div class="date"><?= $date ?></div>
				<div class="month"><?= $month ?></div>
				<div class="day"><?= $day ?></div>
			</div>
			<!--/ Small Calendar -->
			<? $currDate = $checkDate ?>
		<? } ?>

			<!-- Item -->
			<div class="categoryItem clearfix finance-operation-item" data-id="<?= $operation['FinanceOperation']['id'] ?>">
				<div class="spending">
					<? if ($operation['FinanceOperation']['type'] == 0) { ?>
						+
					<? } else if ($operation['FinanceOperation']['type'] == 1) {?>
						-
					<? } ?>
					<?= $this->Money->symbolFor($operation['FinanceOperation']['currency']) ?> <?= $this->Money->format($operation['FinanceOperation']['amount']) ?>
					<div class="account">
					<? if (isset($aFinanceAccount[$operation['FinanceOperation']['account_id']])) { ?>
						<?= $aFinanceAccount[$operation['FinanceOperation']['account_id']]['FinanceAccount']['name'] ?>
					<? } ?>
					</div>
				</div>
				<div class="category">
					<? foreach ($operation['Categories'] as $category) { ?>
						<?= $category['name'] ?><br>
					<? } ?>
					<div class="description">
						<?= $operation['FinanceOperation']['comment'] ?>
					</div>
				</div>
			</div>
			<!--/ Item -->

			<!-- Controls -->
			<div class="chooseOperation finance-operation-choice-action hide" data-id="<?= $operation['FinanceOperation']['id'] ?>">
			<? if (in_array($operation['FinanceOperation']['type'], array(0, 1))) { ?>
				<a data-id="<?= $operation['FinanceOperation']['id'] ?>" href="javascript: void(0)" class="glyphicons pencil action-update"></a>
			<? } ?>
				<? if ($isOwner) {?>
					<a data-id="<?= $operation['FinanceOperation']['id'] ?>" href="javascript: void(0)" class="glyphicons bin action-delete"></a>
				<? } ?>
			</div>
			<!--/ Controls -->

			<!-- Delete Confirm -->
			<div class="deleteOperation finance-operation-delete-confirm hide" data-id="<?= $operation['FinanceOperation']['id'] ?>">
				<div class="delete">
					<div class="text"><?= __('Do you really want to delete transaction')?>?</div>
					<div class="buttons">
						<a class="btn btn-default action-yes"><?= __('Yes')?></a>
						<a class="btn btn-default action-no"><?= __('No')?></a>
					</div>
				</div>
			</div>
			<!--/ Delete Confirm -->

			<?
				if (!in_array($operation['FinanceOperation']['type'], array(0, 1))) {
					continue;
				}
			?>
			<!-- Form Update -->
			<?
				$categoryValue = array();
				$categoryNames = array();
				$categoryIds = array();
				foreach ($operation['Categories'] as $category) {
					$categoryNames[] = $category['name'];
					$categoryIds[] = $category['id'];
					$categoryValue[] = $category['name'];
				}
			?>
			<div class="editOperation finance-operation-update-form hide"
				data-id="<?= $operation['FinanceOperation']['id'] ?>"
				data-created="<?= $operation['FinanceOperation']['created'] ?>"
				data-type="<?= $operation['FinanceOperation']['type'] ?>"
				data-categorynames="<?= implode('|', $categoryNames) ?>"
				data-categoryids="<?= implode('|', $categoryIds) ?>"
			>
			<form>
				<input type="hidden" name="FinanceOperation[id]" value="<?= $operation['FinanceOperation']['id'] ?>">
				<input type="hidden" name="FinanceOperation[is_planned]" value="<?= $operation['FinanceOperation']['is_planned'] ?>">
				<div class="clearfix">
					<div class="leftColomn">
						<div class="dateTime date" id="selectDate">
							<span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
							<input type="text"  readonly="readonly"  placeholder="<?= __('Select date') ?>" class="form-control">
							<input name="FinanceOperation[created]" type="hidden" id="finance-operation-update-created-"<?= $operation['FinanceOperation']['id'] ?>>
						</div>
						<div class="form-group summ">
							<label>
							<? if ($operation['FinanceOperation']['type'] == 0) { ?>
								<?= __('Income amount') ?>
							<? } else if ($operation['FinanceOperation']['type'] == 1) {?>
								<?= __('Expense amount') ?>
							<? } ?>
							</label>
							<input type="number" step="0.01" name="FinanceOperation[amount]" required="true" value="<?= $operation['FinanceOperation']['amount'] ?>" placeholder="" class="form-control">
							<strong class="currency"><?= $this->Money->symbolFor($operation['FinanceOperation']['currency']) ?></strong>
						</div>
					</div>
					<div class="rightColomn">
						<select name="FinanceOperation[account_id]" required="true" data-placeholder="<?= __('Select account')?>" class="tick">
							<option value=""><?= __('Select account') ?></option>
							<? foreach ($aFinanceAccount as $account) {
								$selected = '';
								if ($account['FinanceAccount']['id'] == $operation['FinanceOperation']['account_id']) {
									$selected = 'selected="true"';
								}
							?>
								<option <?= $selected ?> data-currency="<?= $account['FinanceAccount']['currency'] ?>" value="<?= $account['FinanceAccount']['id'] ?>"><?= $account['FinanceAccount']['name'] ?></option>
							<? } ?>
						</select>
						<div class="form-group">
							<label><?= __('Type new category name and hit Enter') ?></label>
							<input value="<?= implode(',', $categoryValue) ?>" class="form-control tokenfield" name="FinanceOperationHasCategory[category_id_fake]"  type="text">
							<input  type="hidden" name="FinanceOperationHasCategory[category_id]">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label><?=__('Comments')?></label>
					<input name="FinanceOperation[comment]" type="text" class="form-control" placeholder="" value="<?= $operation['FinanceOperation']['comment'] ?>">
				</div>
				<button type="submit" class="btn btn-primary action-submit"><?=__('Save')?></button>
				<button type="button" class="btn btn-default action-cancel"><?=__('Cancel')?></button>
			</form>
			</div>
			<!-- Form Update -->
			<!-- !!! Below this there should be nothing in 'foreach' !!! -->
	<? } ?>
		</div>

<script>
// Init category input's
var allFinanceCategories = <?= json_encode($aFinanceCategory) ?>;
var financeExpenseCategoriesList = [];
var financeIncomeCategoriesList = [];
for(var i in allFinanceCategories) {
	var item = allFinanceCategories[i].FinanceCategory;
	if (item.type == 1) {
		financeExpenseCategoriesList.push({value: item.id, label: item.name});
	} else if (item.type == 0) {
		financeIncomeCategoriesList.push({value: item.id, label: item.name});
	}
}
// Actions panel
$('.finance-operation-item').on('click', function () {
	var id = $(this).data('id');
	var actionsPanel = $('.finance-operation-choice-action[data-id="' + id + '"]');
	// hidden others
	$('.finance-operation-choice-action').addClass('hide');
	$('.finance-operation-delete-confirm').addClass('hide');
	$('.finance-operation-update-form').addClass('hide');
	// show this
	actionsPanel.removeClass('hide');
});

// Delete confirm
$('.finance-operation-choice-action .action-delete').on('click', function () {
	var id = $(this).data('id');
	// hidden others
	$('.finance-operation-choice-action[data-id="' + id + '"]').addClass('hide');
	// show this
	var deleteConfirm = $('.finance-operation-delete-confirm[data-id="' + id + '"]');
	deleteConfirm.removeClass('hide');
	// click No
	deleteConfirm.find('.action-no').on('click', function () {
		deleteConfirm.addClass('hide');
	});
	// click Yes
	deleteConfirm.find('.action-yes').on('click', function () {
		$.post(financeURL.delOperation, {id: id}, function () {
			location.reload(false);
		});
	});
});

// Update form
$('.finance-operation-choice-action .action-update').on('click', function () {
	var id = $(this).data('id');
// hidden others
	$('.finance-operation-choice-action[data-id="' + id + '"]').addClass('hide');
	// show this
	var updateContainer = $('.finance-operation-update-form[data-id="' + id + '"]');
	updateContainer.removeClass('hide');
// init Form
	// Datetimepicker for created
	updateContainer.find('.dateTime').datetimepicker({
		format: '<?= $dateFormat?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?=$lang?>",
		linkField: 'finance-operation-update-created-' + id,
		linkFormat: 'yyyy-mm-dd hh:ii:ss'
	});
	updateContainer.find('.dateTime').datetimepicker('setDate', new Date(updateContainer.data('created')));
	// Categories
	updateContainer.find('.tokenfield').tokenfield({
		autocomplete: {
			source: updateContainer.data('type') ? financeExpenseCategoriesList : financeIncomeCategoriesList,
			delay: 100
		},
		showAutocompleteOnFocus: true
	});

// click Cancel
	updateContainer.find('.action-cancel').on('click', function () {
		updateContainer.addClass('hide');
	});
// Account select
	updateContainer.find('[name="FinanceOperation[account_id]"]').on('change', function () {
		var currency = $(this).find(':selected').data('currency');
		if (currency) {
			updateContainer.find('.currency').html(financeSymbolFor(currency));
		}
	});
// click Submit
	updateContainer.find('form').on('submit', function () {
		var category_id = $(this).find('[name="FinanceOperationHasCategory[category_id_fake]"]').val();
		var names = updateContainer.data('categorynames');
		var ids = updateContainer.data('categoryids');
		if (names.indexOf('|') === -1) {
			category_id = category_id.replace(names, ids);
		} else {
			names = updateContainer.data('categorynames').split('|');
			ids = updateContainer.data('categoryids').split('|');
			for (var i = 0; i < names.length; i++) {
				category_id = category_id.replace(names[i], ids[i]);
			}
		}
		if (!category_id) {
			alert("<?= __('Categories is required')?>");
			return false;
		}
		$(this).find('[name="FinanceOperationHasCategory[category_id]"]').val(category_id);
		var data = $(this).serialize();
		$.post(financeURL.editOperation, data, function () {
			location.reload(false);
		});
		return false;
	});
});
</script>
<? } ?>