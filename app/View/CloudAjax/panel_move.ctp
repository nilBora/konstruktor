<span class="glyphicons circle_remove" data-dismiss="modal"></span>
<div class="cloud-move-where folderName  <? if (!$id) { ?>hide<? } ?>" data-where="<?=$id?>">
	<a href="javascript: void(0)" data-id="<?= @$aCloud['Cloud']['parent_id'] ?>" class="cloud-manager-move-back">
		<span class="glyphicons left_arrow"></span><span class="name"><?= $aCloud['Cloud']['name'] ?></span>
	</a>
</div>
<div class="foldersFilesList">
<? foreach ($aClouds as $cloud) { ?>
	<? if ($cloud['Media']['id'] === null) { ?>
		<a href="javascript:void(0)" class="item cloud-manager-move-select" data-id="<?= $cloud['Cloud']['id'] ?>">
			<span class="glyphicons folder_closed"></span><span class="name"><?= $cloud['Cloud']['name'] ?></span>
		</a>
	<? } ?>
<? } ?>
</div>
<div class="clearfix controls">
	<button class="btn btn-default cloudMover" type="button"><?= __('Move') ?></button>
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
	$('.popoverFolderCreate .circle_remove').on('click', function () {
		$('#cloud-move').popover('hide');
	});
});
</script>