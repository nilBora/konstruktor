var cloudURL = {
	panel: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'panel')) ?>',
	panelMove: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'panelMove')) ?>',
	addFolder: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'addFolder')) ?>.json',
	delFolder: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'delFolder')) ?>.json',
	deActive: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'deActive')) ?>.json',
	move: '<?= $this->Html->url(array('controller' => 'CloudAjax', 'action' => 'move')) ?>.json'
}
