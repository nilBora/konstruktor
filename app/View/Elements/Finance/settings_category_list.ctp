<? foreach ($aFinanceCategory as $item) { ?>
	<div class="item">
		<a href="<?= $this->Html->url(array('controller' => 'FinanceCategory', 'action' => 'editCategory', $item['FinanceCategory']['id'])) ?>" data-toggle="modal" data-target="#finance-modal-save-category">
			<?= $item['FinanceCategory']['name'] ?>
		</a>
	</div>
<? } ?>