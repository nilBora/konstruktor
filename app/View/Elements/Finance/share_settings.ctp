<form method="post" action="<?=$this->Html->url(array('controller' => 'FinanceShare', 'action' => 'setShareItems'))?>" id="finance-share-form">
	<div class="bigCheckBox">
		<input type="checkbox" class="checkboxStyle glyphicons ok_2" name="full_access" value="1"
		    id="finance-share-fullAccess"
			<? if ($userShare && $userShare['full_access']) {?>checked="true"<? } ?>
		/>
		<span class="checkboxText big"><?=__('Provide full access')?></span>
		<button class="btn btn-default" type="submit" style="margin-left: 167px"><?=__('Save')?></button>
	</div>
	<div id="finance-share-provideAccess" <? if ($userShare && $userShare['full_access']) {?>style="display: none"<? } ?>>
		<div class="subTitle"><?=__('Select elements to provide access')?></div>
		<div class="btn-group" id="finance-share-navigation">
			<button class="btn btn-default active" data-tab="finance-share-tabAccount"  type="button"><?=__('Accounts')?></button>
			<button class="btn btn-default" data-tab="finance-share-tabOperations" type="button"><?=__('Operations')?></button>
			<button class="btn btn-default" data-tab="finance-share-tabCategories" type="button"><?=__('Categories')?></button>
			<button class="btn btn-default" data-tab="finance-share-tabBudgets" type="button"><?=__('Budgets')?></button>
			<button class="btn btn-default" data-tab="finance-share-tabGoals" type="button"><?=__('Goals')?></button>
		</div>
		<div class="row categoryName" id="finance-share-tabs">
			<input type="hidden" name="projectId" value="<?=$id?>">
			<input type="hidden" name="userId" value="<?=$user?>" id="finance-share-inputUserId">
			<div id="finance-share-tabAccount">
				<?=$this->element('Finance/share_accounts')?>
			</div>
			<div id="finance-share-tabOperations" class="hide">
				<?=$this->element('Finance/share_operations')?>
			</div>
			<div id="finance-share-tabCategories"  class="hide">
				<?=$this->element('Finance/share_categories')?>
			</div>
			<div id="finance-share-tabBudgets"  class="hide">
				<?=$this->element('Finance/share_budgets')?>
			</div>
			<div id="finance-share-tabGoals"  class="hide">
				<?=$this->element('Finance/share_goals')?>
			</div>
		</div>
	</div>
</form>

<script>
$(document).ready(function () {
	$('#finance-share-fullAccess').on('change', function () {
		if($(this).prop('checked')) {
			$('#finance-share-provideAccess').hide();
		} else {
			$('#finance-share-provideAccess').show();
		}
	});
	$('#finance-share-navigation button').on('click', function () {
		$('#finance-share-navigation button').removeClass('active');
		$(this).addClass('active');
		var tabId = $(this).data('tab');
		$('#finance-share-tabs > div').addClass('hide');
		$('#' + tabId).removeClass('hide');
	});
	$('#finance-share-form').on('submit', function () {
		var data = $(this).serialize();
		var url = $(this).attr('action');
		$.post(url, data, function (response) {
			if (response) {
				alert(response);
			} else {
				location.href = '/FinanceShare/index/<?=$id?>?user=<?=$user?>';
			}
		});
		return false;
	});
});
</script>