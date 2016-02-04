<!-- Mover modal -->
<div class="modal fade moveFilesFolders" id="cloud-manager-move-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content"></div>
		</div>
	</div>
</div>
<!--/ Mover modal -->

<!-- Share modal -->
<div class="modal fade shareLink" id="cloud-manager-share-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="form-group">
					<label><?= __('Share link') ?></label>
					<div class="link"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/ Share modal -->

<!-- Folder create modal -->
<div class="modal fade" id="createFolderFromManager" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>

				<form action="" method="post" id="cloud-manager-add-folder" data-id="<?= $id ?>">
					<div class="form-group">
						<label><?= __('New Folder') ?></label>
						<input type="text" placeholder="<?= __('Folder name') ?>" class="form-control"
							   name="Cloud[name]" required="true">
						<input type="hidden" class="form-control" name="Cloud[parent_id]" value="<?= $id ?>">
					</div>
					<div class="clearfix">
						<button type="submit" class="btn btn-primary"><?= __('Create') ?></button>
						<button type="button" class="btn btn-default pull-right"
								data-dismiss="modal"><?= __('Close') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/ Folder create modal -->

<!-- Uploader upload file view -->
<div class="hide" id="cloud-manager-tpl-file-upload">
	<a href="#" class="item">
		<span class="filetype"></span>
		<div class="title"></div>
		<div class="progress">
			<div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"><span class="percentage"></span></div>
		</div>
	</a>
</div>
<!-- /Uploader upload file view -->

<!-- Uploader if limit reached -->
<div class="modal fade" id="space-notification-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Storage Notification</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>You have run out space. Please upgrade your subscription.</h4>
                    <p>
                        <?php echo $this->Html->link(__("Buy More Space", true), array('controller' => "StorageLimit", 'action' => "buyMoreSpace"), array('class' => 'btn btn-default textIconBtn'));?>
                    </p>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /Uploader if limit reached  -->
