<div class="row projectViewTitle">
	<div class="col-sm-5 col-sm-push-7 controlButtons">
		<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', Hash::get($note, 'Note.user_id')))?>">
			<?=__('Back to profile')?>
		</a>
<?
	$id = Hash::get($note, 'Note.id');
?>
		<a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons disk_save"></span></a>
		<a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'edit', $id))?>">
			<span class="glyphicons pencil"></span>
		</a>
	</div>
	<div class="col-sm-5 col-sm-pull-5">
		<h1><?=Hash::get($note, 'Note.title')?></h1>
	</div>
</div>

<div class="projectLastUpdates">
	<div class="title">
		<span class="glyphicons clock"></span><?=$this->LocalDate->dateTime($note['Note']['created'])?>
	</div>
</div>

<div class="articleView">
	<?=Hash::get($note, 'Note.body')?>
</div>	
<br />
<br />
<br />