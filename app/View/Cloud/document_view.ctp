<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Cloud'), array('controller' => 'Cloud', 'action' => 'index'));
	$this->Html->addCrumb(Hash::get($note, 'Note.title'), array('controller' => 'Cloud', 'action' => 'documentView/'.$id));
?>
<?php /*
	<h1>
		<?
		if ($id) {
			?>
			<a href="<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'index', !is_null($parent_id) ? $parent_id : ''))?>" class="glyphicons left_arrow"><?=Hash::get($note, 'Note.title')?></a>
			<?
		} else {
			echo __('File Manager');
		}
		?>
	</h1>
*/ ?>
<div class="row projectViewTitle">
	<div class="col-sm-5 col-sm-push-7 controlButtons" style="margin-right: 50px;">
<?
	$id = Hash::get($note, 'Note.id');
?>
        <?php if($edit_doc):?>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default smallBtn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicons pencil"></span>
            </button>
            <ul class="dropdown-menu">
                <?php if($collective_edit) : ?>
                    <li><a href="<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'realTimeEdit', $id))?>">Collective Edit</a></li>
                <?php endif; ?>
                <li><a href="<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'documentEdit', $id))?>">Simple Edit</a></li>
            </ul>
        </div>
        <?php endif; ?>
        <a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'documentDownload', $id))?>"><span class="glyphicons disk_save"></span></a>

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
