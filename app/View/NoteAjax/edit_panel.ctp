<?
	$id = $this->request->data('Note.id');
	$parent_id = isset($this->request->params['named']['Note.parent_id']) ? $this->request->params['named']['Note.parent_id'] : '';
?>
<div id="note-<?=$id ? $id : 'new'?>" class="noteEditBlock active">
	<?=$this->Form->create('Note')?>		
		<div class="row projectViewTitle">
			<div class="col-sm-5 col-sm-push-7 controlButtons">
				<button class="btn btn-default formSubmit" type="button" <?=$id ? 'data-note_id="'.$id.'"' : ''?>><?=__('Save')?></button>
<?
	if ($id) {
?>
				<a class="btn btn-default noteView"><?=__('View')?></a>
				<a id="view-note-<?=$id?>" target="_blank" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'view', $id))?>" style="display: none;"><?=__('View')?></a>
				<button type="button" class="btn btn-default smallBtn" id="note-manager-move" data-what="<?=$id?>" data-where=""><span class="glyphicons move"></span></button>
				<a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons disk_save"></span></a>
				<button type="button" class="btn btn-default smallBtn" id="note-share" data-link="<?='https://'.$_SERVER['HTTP_HOST'].$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons link"></span></button>
				<button type="button" class="btn btn-default smallBtn noteDelete" data-note_id="<?=$id?>"><span class="glyphicons bin"></span></button>
<?
	}
?>
			</div>
			<div class="col-sm-5 col-sm-pull-5">
				<h1><?=($id) ? __('Edit document') : __('Create document')?></h1>
			</div>
		</div>
		<br/>
<?		
		if(!$id && $parent_id) {
?>		
			<?=$this->Form->hidden('Note.parent_id', array('value' => $parent_id))?>
<?
		} else {
?>
			<?=$this->Form->hidden('Note.parent_id')?>
<?
		}
?>
		<?=$this->Form->hidden('Note.type', array('value' => 'text'))?>
		<br/>
		<br/>
		<div class="oneFormBlock">
			<div class="form-group">
				<?=$this->Form->input('title', array('placeholder' => __('Document title').'...', 'label' => __('Document title'), 'class' => 'form-control NoteTitle'))?>
			</div>
		</div>

		<div class="wordProcessor">
			<?=$this->Redactor->redactor('body')?>
		</div>
		<br/>
		<br/>
	<?=$this->Form->end()?>
</div>