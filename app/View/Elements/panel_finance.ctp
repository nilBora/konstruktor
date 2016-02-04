<div class="create-group clearfix">
	<button class="btn btn-default pull-left" type="button" id="finance-create-project-popup"><?= __('Create project') ?></button>
</div>

<div class="dropdown-panel-scroll">
	<?= $this->element('Finance/project_list') ?>
</div>

<div class="hide" id="finance-tpl-popover">
	<div class="popover popoverFolderCreate finance-create-project" role="tooltip" style="margin-left: 63px">
		<div class="popover-content"></div>
	</div>
</div>

<div class="hide" id="finance-tpl-popover-content">
	<span class="glyphicons circle_remove"></span>
	<form action="" method="post" id="finance-add-project">
		<div class="form-group">
			<label><?=__('New Project')?></label>
			<div class="input-group">
				<input name="FinanceProject[name]" required="true" type="text" placeholder="" class="form-control">
				<div class="input-group-addon">
					<button class="btn btn-default submitButtonArrow" type="submit"><span class="submitArrow"></span></button>
				</div>
			</div>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
// search
	$('#searchFinanceForm').ajaxForm({url: financeURL.panel, target: Finance.panel});

// create project
	$('#finance-create-project-popup').popover({
		content: $('#finance-tpl-popover-content').html(),
		html: true,
		placement: "bottom",
		template: $('#finance-tpl-popover').html()
	});
	$('.finance-create-project .circle_remove').on('click', function () {
		$('#finance-create-project-popup').popover('hide');
	});
	$('#finance-add-project').on('submit', function () {
		$form = $(this);
		$.post(financeURL.addProject, $form.serialize(), function (response) {
			if (response) {
				alert(response);
			}
			Finance.initPanel(null);
		});
		return false;
	});

// delete project
	$('#finance-delete-project').on('click', function () {
		if (!confirm('<?=__('Are you sure ?')?>')) {
			return;
		}
		$control = $(this);
		$.post(financeURL.delProject, {id: $control.data('id')}, function (response) {
			Finance.initPanel(null);
		});
	});
// share
<? if(count($aInvites)) {?>
	$('.financePanel').parent('a').append('<div class="count"><?=count($aInvites)?></div>');
<? } ?>
});
</script>