var noteURL = {
	panel: '<?=$this->Html->url(array('controller' => 'NoteAjax', 'action' => 'panel'))?>',
	panelMove: '<?= $this->Html->url(array('controller' => 'NoteAjax', 'action' => 'panelMove')) ?>',
	editPanel: '<?= $this->Html->url(array('controller' => 'NoteAjax', 'action' => 'editPanel')) ?>',
	remove: '<?= $this->Html->url(array('controller' => 'NoteAjax', 'action' => 'delete')) ?>',
	addFolder: '<?=$this->Html->url(array('controller' => 'NoteAjax', 'action' => 'addFolder'))?>.json',
	delFolder: '<?=$this->Html->url(array('controller' => 'NoteAjax', 'action' => 'delFolder'))?>.json',
	move: '<?= $this->Html->url(array('controller' => 'NoteAjax', 'action' => 'move')) ?>.json'
}