<div class="subHead"><?= __('Accounts') ?></div>
<? foreach ($aFinanceAccount as $item) { ?>
	<div class="item">
		<a href="<?= $this->Html->url(array('controller' => 'FinanceAccount', 'action' => 'editAccount', $item['FinanceAccount']['id'])) ?>" data-toggle="modal" data-target="#finance-modal-save-account">
			<?= $item['FinanceAccount']['name'] ?>
		</a>
	</div>
<? } ?>