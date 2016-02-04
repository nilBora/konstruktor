<div class="note-move-where folderName  <? if (!$id) { ?>hide<? } ?>" data-where="<?=$id?>">
	<a href="javascript: void(0)" data-id="<?= @$aNote['Note']['parent_id'] ?>" class="note-manager-move-back">
		<span class="glyphicons left_arrow"></span><span class="name"><?= $aNote['Note']['title'] ?></span>
	</a>
</div>
<div class="foldersFilesList">
<? foreach ($aNotes as $note) { ?>
	<? if ($note['Note']['is_folder'] == 1) { ?>
		<a href="javascript:void(0)" class="item note-manager-move-select" data-id="<?= $note['Note']['id'] ?>">
			<span class="glyphicons folder_closed"></span><span class="name"><?= $note['Note']['title'] ?></span>
		</a>
	<? } ?>
<? } ?>
</div>
<div class="clearfix controls">
	<button class="btn btn-primary noteMover" type="button"><?= __('Move') ?></button>
	<button data-dismiss="modal" class="btn btn-default pull-right closePopup" type="button"><?= __('Close') ?></button>
</div>
<script type="application/javascript">
$(document).ready(function(){
	$('.foldersFilesList').niceScroll({
		cursorwidth: "7px",
		cursorcolor: "#23b5ae",
		cursorborder: "none",
		autohidemode: "false",
		background: "#f1f1f1",
		zindex: 9999999
	});
	$('.foldersFilesList').getNiceScroll().show();
});
</script>