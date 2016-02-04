<?
/**
 * @property MoneyHelper Money
 */
?>
<div class="balance"><?= __('Balance') ?></div>
<? foreach ($aFinanceAccount as $account) {
	$accBalance = $account['FinanceAccount']['balance'];
	$style = 'style=""';
	if ($accBalance < 0) {
		$style = 'style="color: red"';
	}
?>
	<div class="value" <?= $style ?>><?= $this->Money->symbolFor($account['FinanceAccount']['currency'])?> <?= $this->Money->format($accBalance) ?></div>
	<div class="type"><?= $account['FinanceAccount']['name'] ?></div>
<? } ?>
<? if ($isOwner) {?>
<a class="underlink" data-toggle="modal" data-target="#finance-modal-save-account" href="<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'addAccount', $id)) ?>">
	<?=__('Add account')?>
</a>
<? } ?>
<?= $this->element('Finance/modal_save_account') ?>

<script type="application/javascript">
$(document).ready(function(){
	
	$('#finance-modal-save-account').on('shown.bs.modal', function (e) {
		$('body').css("position","fixed");
	});
	
	// Close account modal
	$('#finance-modal-save-account').on('hidden.bs.modal', function () {
		if ($(this).data('status')) {
			location.reload(false);
		}
		$(this).removeData('bs.modal');
		$('body').css("position","static");
	});
});
</script>