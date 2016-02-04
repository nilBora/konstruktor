var investURL = {
	panel: '<?= $this->Html->url(array('controller' => 'InvestAjax', 'action' => 'panel')) ?>',
	removeMedia: '<?= $this->Html->url(array('controller' => 'InvestProject', 'action' => 'removeMedia')) ?>',
	removeReward: '<?= $this->Html->url(array('controller' => 'InvestReward', 'action' => 'delete')) ?>'
}