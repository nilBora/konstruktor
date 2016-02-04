<!-- Folder -->
<?php
	function checkFullUrl($url) {
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$baseHref = $protocol.$_SERVER['HTTP_HOST'];
		if( strpos($url, $baseHref) === false ) {
			$url = $baseHref . '/' . ltrim($url, '/');
		}
		return $url;
	}
?>
<?php if(empty($id)) : ?>
	<a href="javascript:void(0)" class="item shared-folder" data-url="<?= checkFullUrl($this->Html->url(array(
		'controller' => 'Cloud',
		'action' => $this->action=='fortiny' ? 'fortiny' : 'index',
		'shared'

	))) ?>"  data-type="folder">
					<span class="glyphicons folder_closed">
					</span>

		<div class="title"><?= __('Shared with me') ?></div>

	</a>
<?php else: ?>
	<script>
	  jQuery.post(cloudURL.deActive, {id: '<?=$id;?>'}, function (response) {
		  $('#cloudCount').html('');
	  });
	</script>
<?php endif; ?>

<?php
	$aCloud = $result['files']['aCloud'];
	$aClouds = $result['files']['aClouds'];
	$flag_shared = isset($result['files']['flag_shared']) ? $result['files']['flag_shared'] : null;
	$aNote = $result['docs']['aNote'];
	$aNotes = $result['docs']['aNotes'];
?>

<?php if ($typeView == 'small') : ?>
	<? foreach ($aClouds as $cloud) : ?>
		<? if ($cloud['Media']['id'] === null) : ?>
            <?php
				if (isset($flag_shared)) {
					$link = array('shared', $cloud['Cloud']['id']);
				} else {
					$link = array($cloud['Cloud']['id']);
				}
				$controller = array(
					'controller' => 'Cloud',
					'action' => $this->action=='fortiny' ? 'fortiny' : 'index');
            ?>
            <!-- Folder -->
			<div class="clearfix item"
                 data-url="<?= checkFullUrl($this->Html->url(array_merge($controller, $link), true)) ?>"
                 data-id="<?= $cloud['Cloud']['id'] ?>" data-type="folder">
				<div class="size"><?= $cloud['Cloud']['fileCount'] ?> <?= __('file(s)') ?></div>
				<div class="date"><?=$this->LocalDate->dateTime($cloud['Cloud']['modified'])?></div>
				<div class="name">
					<span class="glyphicons folder_closed">
                        <?php if(isset($cloud['Cloud']['is_shared'])) : ?>
                            <img src="<?php echo $this->Html->url('/', true) . 'img/share-small.png' ?>" class="shared shared-folder" />
                        <?php endif; ?>
                    </span>
					<div class="title"><?= $cloud['Cloud']['name'] ?></div>
				</div>
			</div>
		<? else : ?>
			<?php
				$ext = str_replace('jpeg', 'jpg', strtolower($cloud['Media']['ext']));
				$class = $this->File->hasType($ext) ? 'filetype ' . $ext : 'glyphicons file';
			?>
			<!-- File -->
			<?php if( $cloud['Media']['media_type'] == 'video') :?>
				<div class="clearfix item video-pop-this" data-video="<?php echo trim($cloud['Media']['ext'], '.'); ?>" data-url="<?= checkFullUrl($cloud['Media']['url_preview']) ?>" data-id="<?= $cloud['Cloud']['id'] ?>" data-type="file" data-media="<?php echo ($cloud['Media']['media_type'] == 'image') ? $cloud['Media']['url_download'] : 'false' ;?>" data-size="<?php echo $cloud['Media']['size'] ?>" data-url-down="<?=$cloud['Media']['url_download'];?>" data-converted="<?=$cloud['Media']['converted'];?>">
					<div class="size"><?= $this->File->humanFilesize($cloud['Media']['orig_fsize']) ?></div>
					<div class="date"><?=$this->LocalDate->dateTime($cloud['Cloud']['modified'])?></div>
					<div class="name">
						<span class="<?= $class ?>">
							<?php if(isset($cloud['Cloud']['is_shared'])) : ?>
								<img src="<?php echo $this->Html->url('/', true) . 'img/share-small.png' ?>" class="shared shared-file" />
							<?php endif; ?>
						</span>
						<div class="title"><?= $cloud['Cloud']['name'] ?></div>
					</div>
				</div>
			<?php elseif( $cloud['Media']['media_type'] != 'video') :?>
				<div class="clearfix item" data-url="<?= checkFullUrl($cloud['Media']['url_preview']) ?>" data-id="<?= $cloud['Cloud']['id'] ?>" data-type="file" data-media="<?php echo ($cloud['Media']['media_type'] == 'image') ? $cloud['Media']['url_download'] : 'false' ;?>" data-size="<?php echo $cloud['Media']['size'] ?>">
					<div class="size"><?= $this->File->humanFilesize($cloud['Media']['orig_fsize']) ?></div>
					<div class="date"><?=$this->LocalDate->dateTime($cloud['Cloud']['modified'])?></div>
					<div class="name">
										<span class="<?= $class ?>">
											<?php if(isset($cloud['Cloud']['is_shared'])) : ?>
											<img src="<?php echo $this->Html->url('/', true) . 'img/share-small.png' ?>" class="shared shared-file" />
											<?php endif; ?>
										</span>
						<div class="title"><?= $cloud['Cloud']['name'] ?></div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

    <?php foreach($aNotes as $note) :  ?>
		<?php if(!$note['Note']['is_folder']) : ?>
            <div class="clearfix item">
            <?php
				$link = 'Cloud/documentView/' . $note['Note']['id'];
				if(isset($note[0]['max_priv'])) {
					$type = $note[0]['max_priv'];
					if($type == 3)
						$link = 'Cloud/documentEdit/' . $note['Note']['id'];
            	}
            ?>
            <a href="/<?php echo $link;?>" class="item"  data-url="<?= checkFullUrl($this->Html->url('/',true) . $link); ?>" data-id="<?= $note['Note']['id'] ?>" data-type="doc">
                <span class="glyphicons file pull-left">
                    <?php if(isset($note['Note']['is_shared'])) : ?>
                        <img src="<?php echo $this->Html->url('/', true) . 'img/share-small.png' ?>" class="shared shared-file" />
                    <?php endif; ?>
                </span>
                <div class="title"><?=$note['Note']['title']?></div>
            </a>
            </div>
        <?php endif; ?>
	<?php endforeach; ?>

<?php else : ?>

	<?php foreach ($aClouds as $cloud) : ?>
		<?php if (is_null($cloud['Media']['id'])) :
            if(isset($flag_shared)) {
                $link = array('shared', $cloud['Cloud']['id']);
            } else {
                $link = array($cloud['Cloud']['id']);
			}
            $controller = array(
                'controller' => 'Cloud',
				'action' => $this->action=='fortiny' ? 'fortiny' : 'index');
            ?>
            <!-- Folder -->
			<a href="javascript:void(0)" class="item" data-url="<?= checkFullUrl($this->Html->url(array_merge($controller, $link), true)) ?>" data-id="<?= $cloud['Cloud']['id'] ?>" data-type="folder">
				<span class="glyphicons folder_closed">
                    <?php if(isset($cloud['Cloud']['is_shared'])) : ?>
                        <img src="<?php echo $this->Html->url('/', true) . 'img/share.png' ?>" class="shared shared-folder" />
                    <?php endif; ?>
                </span>
				<div class="title"><?= $cloud['Cloud']['name'] ?></div>
			</a>
		<?php else :

			$ext = str_replace('jpeg', 'jpg', strtolower($cloud['Media']['ext']));
			$class = $this->File->hasType($ext) ? 'filetype ' . $ext : 'glyphicons file';
            if($cloud['Media']['media_type'] == 'image') {
                $class .= ' preview';
            }

		?>
			<!-- File -->
			<?php if( $cloud['Media']['media_type'] == 'video') :?>
				<a href="javascript:void(0)" class="item video-pop-this" data-video="<?php echo trim($cloud['Media']['ext'], '.'); ?>" data-url="<?= checkFullUrl($cloud['Media']['url_preview']) ?>"
				   data-id="<?= $cloud['Cloud']['id'] ?>" data-type="file" data-url-down="<?=$cloud['Media']['url_download'];?>" data-media="<?php echo ($cloud['Media']['media_type'] == 'image') ? $cloud['Media']['url_download'] : 'false';?>" data-size="<?php echo $cloud['Media']['size'] ?>" data-converted="<?=$cloud['Media']['converted'];?>">
					<span class="<?= $class ?>"></span>
					<div class="title"><?= $cloud['Cloud']['name'] ?></div>
				</a>
			<?php elseif( $cloud['Media']['media_type'] != 'video') :?>
					<a href="javascript:void(0)" class="item" data-url="<?= checkFullUrl($cloud['Media']['url_preview']) ?>"
				   data-id="<?= $cloud['Cloud']['id'] ?>" data-type="file" data-media="<?php echo ($cloud['Media']['media_type'] == 'image') ? $cloud['Media']['url_download'] : 'false';?>" data-size="<?php echo $cloud['Media']['size'] ?>">
						<span class="<?= $class ?>">
							<?php if( $cloud['Media']['media_type'] == 'image' ): ?>
								<img src="<?php echo $cloud['Media']['url_download']; ?>" class="shared shared-file" />
							<?php elseif(isset($cloud['Cloud']['is_shared'])) : ?>
								<img src="<?php echo $this->Html->url('/', true) . 'img/share.png' ?>" class="shared shared-file" />
							<?php endif; ?>
						</span>
						<div class="title"><?= $cloud['Cloud']['name'] ?></div>
					</a>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php foreach($aNotes as $note) : ?>
		<?php if(!$note['Note']['is_folder']) : ?>
            <?php
				$link = 'Cloud/documentView/' . $note['Note']['id'];
				if(isset($note[0]['max_priv'])) {
					$type = $note[0]['max_priv'];
					if($type == 3) {
						$link = 'Cloud/realTimeEdit/' . $note['Note']['id'];
					}
				}
            ?>
            <a href="/<?php echo $link;?>" class="item" data-type="doc" data-url="<?= checkFullUrl($this->Html->url('/',true) . $link); ?>" data-id="<?= $note['Note']['id'] ?>">
                <span class="glyphicons file">
                    <?php if(isset($note['Note']['is_shared'])) : ?>
                        <img src="<?php echo $this->Html->url('/', true) . 'img/share.png' ?>" class="shared shared-file" />
                    <?php endif; ?>
                </span>
                <div class="title"><?=$note['Note']['title']?></div>
            </a>
        <?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
