<ul class="group-list" <? if ($id){ ?>style="padding-top: 270px"<? } ?>>
	<? foreach ($aClouds as $cloud) { ?>
		<? if ($cloud['Media']['id'] === null) { ?>
			<!-- Folder -->
			<li class="simple-list-item cloud-item-select" data-id="<?= $cloud['Cloud']['id'] ?>" data-type="folder">
				<a href="javascript:void(0)">
					<div class="user-list-item clearfix">
						<span class="glyphicons folder_closed"></span>
						<div class="articlesInfo">
							<div class="title"><?= $cloud['Cloud']['name'] ?></div>
							<div class="size"><?= $cloud['Cloud']['fileCount'] ?> <?= __('file(s)') ?></div>
						</div>
					</div>
				</a>
			</li>
		<? } else {
			$ext = str_replace('jpeg', 'jpg', strtolower($cloud['Media']['ext']));
			$class = $this->File->hasType($ext) ? 'filetype ' . $ext : 'glyphicons file';
		?>
			<!-- File -->
			<li class="simple-list-item cloud-item-select" data-id="<?= $cloud['Cloud']['id'] ?>" data-type="file" data-url="<?= $cloud['Media']['url_preview'] ?>">
				<a  href="javascript:void(0)">
					<div class="user-list-item clearfix">
						<span class="<?= $class ?>"><!--<span class="glyphicons link"></span>--></span>
						<div class="articlesInfo">
							<div class="title"><?= $cloud['Cloud']['name'] ?></div>
							<div class="size"><?= $this->File->humanFilesize($cloud['Media']['orig_fsize']) ?></div>
						</div>
					</div>
				</a>
			</li>
		<? } ?>
	<? } ?>
</ul>