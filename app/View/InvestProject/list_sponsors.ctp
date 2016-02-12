<?php
	$this->Html->addCrumb(Hash::get($investGroup, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($investGroup, 'Group.id')));
	$this->Html->addCrumb($investProject['InvestProject']['name'], array('controller' => 'InvestProject', 'action' => 'view/'.$id));
	$this->Html->addCrumb(__('Sponsors'), array('controller' => 'InvestProject', 'action' => 'listSponsors/'.$id));

$group = $groupHeader;
//$this->element('Invest/project_top') ?>
<?
$this->Html->script(array(
	'vendor/bootstrap-datetimepicker.min',
	'vendor/bootstrap-datetimepicker.ru.js',
	'youtube.js',
), array('inline' => false));

$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>
<span></span>
<style>
	.container-fluid>.groupViewInfo{
		display: none;
	}
	.sponsors-list-page .header{

	}

	ul.edit-bar li{
	  display: inline-block;
	  vertical-align: middle;
	  color: #818181;
	  font-size: 11px;
	}
	.sponsors-list-page .header .strong{
		font-weight: bold;
	}
	.groupViewInfo .table-striped>thead>tr{
		background-color: #eeeeee;
	}
	.groupViewInfo .table-striped>tbody tr{

	}
	.InvestProject-totals .form-group{
		border: none;
		margin-bottom: 10px;
	}
	.InvestProject-totals .form-group input{
	    font-size: 12px;
	    border: 1px solid #ccc;
	    border-radius: 5px;
	    padding: 0 5px;
	}

	.needSum .jq-selectbox{
	    width: 50px;
	    float: right;
	    position: absolute !important;
	    top: 2px;
	    right: 16px;
	}
	.needSum .jq-selectbox .jq-selectbox__select{
	    border: none;
	    height: 30px;
	}
	.userArticles .edit-bar li, .userArticles .edit-bar li{
		display: inline-block;
		vertical-align: middle;
		color: #818181;
		font-size: 11px;
	}

	.groupViewInfo .table-striped >tbody tr:nth-child(even){
		background-color: #f9f9f9;
	}
	.table-striped>tbody>tr:nth-child(odd) {
	    background-color: #FFF;
	}
	.groupViewInfo .table>thead>tr>th,
	.groupViewInfo .table>tbody>tr>th,
	.groupViewInfo .table>tfoot>tr>th,
	.groupViewInfo .table>thead>tr>td,
	.groupViewInfo .table>tbody>tr>td,
	.groupViewInfo	 .table>tfoot>tr>td {
	    padding: 8px;
	    line-height: 1.42857143;
	    vertical-align: top;
	    border: 1px solid #ddd;
	    color: #999;
	}
	.fixedLayout {
    	max-width: 1024px;
	}
	.refound{
		color: #999;
		text-decoration: none;
	}
	.refound:before{
		content: 'x';
	    font-size: 13px;
	    line-height: 13px;
	    position: absolute;
	    padding: 0 4px;
	    color: #ee8176;
	    border: 1px solid #ee8176;
	    border-radius: 15px;
	    width: 18px;
	    height: 18px;
	    background-color: #fff;
	    left: -25px;
	}

</style>
<div class="">
	<div class="row groupViewInfo">
	    <div class="col-sm-8">
	        <div class="thumb">
				<?php echo $this->Avatar->group($group, array(
					'size' => 'thumb200x200'
				)); ?>
			</div>
	        <h1><?=Hash::get($group, 'Group.title')?></h1>
	    </div>
	    <div class="col-sm-4">
	        <?= $this->element('Invest/project_nav')?>
	    </div>
	</div>
</div>
<div class="sponsors-list-page">
	<div class="row header">
		<div class="col-sm-1">
			<span class="strong"><?=__('Total:')?></span>
		</div>
		<div class="col-sm-2">
			<?=__('%s sponsors',count($sponsors))?>
		</div>
		<div class="col-sm-3">
			<?=__('$%s collected', $investProject['InvestProject']['funded_total'])?>
		</div>
		<div class="col-sm-2">
			<?=__('1% commissions')?>
		</div>
	</div>
	<div class="row groupViewInfo">
		<?php if(!empty($sponsors)): ?>
			<div class="col-sm-8">
				<table class="table table-striped">
					<thead>
						<tr div="col-sm-12">
							<th div="col-sm-4"><?= __('Sponsor') ?></th>
							<th div="col-sm-2"><?= __('Created') ?></th>
							<!--th><?= __('Reward') ?></th-->
							<th div="col-sm-3"><?= __('Amount') ?></th>

							<th div="col-sm-3"><?= __('Status') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($sponsors as $sponsor): ?>
							<tr>
								<td>
									<div class="thumb">
										<?php echo $this->Avatar->user($users[$sponsor['User']['id']], array(
															'size' => 'thumb50x50', 'class' => 'rounded'
														)); ?>
									</div>

									<div style="display: table-cell; padding-left: 10px; vertical-align: middle;">
										<?php echo $sponsor['User']['full_name'] ?>
									</div>
								</td>
								<td style="    vertical-align: middle;"><?php echo $sponsor['InvestSponsor']['created'] ?></td>
								<!--td><?php echo $sponsor['InvestReward']['name'] ?></td-->
								<td style="    vertical-align: middle;"><?php echo $sponsor['InvestSponsor']['amount'] ?></td>

								<td style="position: relative;vertical-align: middle;">
									<div class="">
										<?=__('Open');?>
									</div>
									<?if($currUserID == $sponsor['InvestSponsor']['user_id'] || $currUserID == $investProject['InvestProject']['user_id'] ):?>
									<div class="" style="position: absolute; top: 30px; right: -100%;">
										<a class="refound"  href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'refundReward', $sponsor['InvestSponsor']['id'] ))?>">
											<?=__('Refund')?>
										</a>
										<?php
											/*if(!$sponsor['InvestSponsor']['canceled']):?>

											<?	echo $this->Form->postLink(__('Refund'),
													array('controller' => 'InvestProject', 'action' => 'refundReward', $sponsor['InvestSponsor']['id']),
													array(
														'class' => 'refound',
														//'onclick' => '',
													)
												);
											else:
												echo $this->Html->tag('strong', __('Canceled'));
											endif*/
										?>
									</div>
								<?php endif;?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>

				</table>
			</div>

		<?php else: ?>
			<div class="col-sm-12 investmentsMoney">
			<?= __('The project has no sponsors') ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="popup-back" style="display: none; position: fixed; top: 0px; z-index: 500; left: 0px; width: 100%; bottom: 0px; background: rgba(204, 204, 204,0.8);"></div>
<?= $this->element('Invest/project_edit')?>


<script type="text/x-tmpl" id="tmpl-invest-project-gallery">
{% for (var i=0; i<o.length; i++) { %}
<li>
	<a class="remove" href="javascript: void(0)" data-index="{%=i%}"><span class="glyphicons circle_remove"></span></a>
	{% if (o[i].type == 'video') { %}
		<span class="glyphicons play"></span>
	{% } %}
	<img src="{%=o[i].image%}" alt="" />
</li>
{% } %}
</ul>
</script>
<script type="text/x-tmpl" id="tmpl-invest-project-video-inputs">
{% for (var i=0; i<o.length; i++) { %}
	{% if (o[i].type == 'video') { %}
		<input type="hidden" name="InvestVideo[]" value="{%=o[i].videoId%}" />
	{% } %}
{% } %}
</script>
<script type="text/x-tmpl" id="tmpl-invest-project-image-inputs">
{% for (var i=0; i<o.length; i++) { %}
	{% if (o[i].type == 'image') { %}
		<input type="hidden" name="InvestProjectGallery[]" value="{%=o[i].id%}" />
	{% } %}
{% } %}
</script>
<script type="text/x-tmpl" id="tmpl-invest-project-body-inputs">
{% for (var i=0; i<o.length; i++) { %}
	<input type="hidden" name="InvestProjectBody[]" value="{%=o[i]%}" />
{% } %}
</script>
<script type="text/x-tmpl" id="tmpl-invest-project-reward-form">
<div class="back" style="margin-top: 20px;">
	<span style="position: absolute;top: 0;right: 10px;" class="reward-remove glyphicons remove"></span>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group"  style="margin-bottom: 10px;">
				<input name="InvestReward[{%=o.index%}][name]" type="text" class="form-control" placeholder="<?= __('Title') ?>" value="" data-label="<?= __('Title') ?>" required="true" />
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group"  style="margin-bottom: 10px;">
						<div class="input-group">
							<input name="InvestReward[{%=o.index%}][total]" type="number" min="0" step="0.01" class="form-control" placeholder="<?= __('Amount') ?>" value="" data-label="<?= __('Amount') ?>" data-label="<?= __('Amount') ?>" required="true"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group" style="margin-bottom: 10px;">
						<div class="input-group">
							<!--div class="input-group-addon glyphicons calendar"></div-->
							<input type="text" class="form-control" data-label="<?= __('Delivery date') ?>" placeholder="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}" readonly/>
							<input name="InvestReward[{%=o.index%}][created]" type="hidden" placeholder="<?= __('Delivery date') ?>" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}-mirror"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
		<div class="form-group"  style="margin-bottom: 10px;">
			<textarea name="InvestReward[{%=o.index%}][description]" type="text" class="form-control" placeholder="<?= __('Description') ?>" value="" data-label="<?= __('Description') ?>" required="true"  rows="5" cols="40"></textarea>
		</div>
		</div>
	</div>
</div>
</script>

<script type="text/javascript">
function edit(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-edit-content');
	body.append(popup);
	popup.show();
	body.append(content);
	content.show();


}
$(document).ready(function () {
// Init
$('.close-button').on('click',function(){
	var popup = $('.popup-back');
	var content = $('.popup-edit-content');
	popup.hide();
	content.hide();
});

var investProjectRewardCounter = 0;
$('#invest-project-reward-edit-add-button').on('click', function () {
	investProjectRewardCounter = $('#reward-counter').attr('data-counter');
	var $newReward = $(tmpl('tmpl-invest-project-reward-form', {index: investProjectRewardCounter}));
	$newReward.find('#invest-project-reward-' + investProjectRewardCounter).datetimepicker({
		format: '<?= $dateFormat?>',
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		language:"<?=$lang?>",
		linkField: 'invest-project-reward-' + investProjectRewardCounter + '-mirror',
		linkFormat: 'yyyy-mm-dd hh:ii:ss'
	});

	$newReward.find('#invest-project-reward-' + investProjectRewardCounter).datetimepicker().on('focus', function () {
		var dts = $('.datetimepicker');
		for(var i = 0; i <= (dts.length - 1);i++) {
			var dt = $(dts[i]);
			dt.css('left', parseInt(dt.css('left')) - 50);
		}
	});

	$newReward.find('.reward-remove').on('click', function () {
		$newReward.remove();
	});
	$('#invest-project-edit-reward-list').prepend($newReward);
	investProjectRewardCounter++;
});
	$('select, input.filestyle, input.checkboxStyle').styler({fileBrowse: 'Загрузить фото'});
	var investRemoveMedia = function (id) {
		$.post(investURL.removeMedia + '/' + id);
	};
// Avatar Uploader
	$('#invest-project-avatar-uploader').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var src = response.data[0].Media.url_download;
				var oldAvatarId = $('#invest-project-avatar').val();
				if (oldAvatarId) {
					investRemoveMedia(oldAvatarId);
				}
				$('#invest-project-avatar').val(id);
				$('#invest-project-avatar-img').attr('src', src);
			});
            // progress
            $('#invest-project-avatarProgress').height(0);
            $('#invest-project-avatarProgress #progress-stats').html('');
            $('#invest-project-avatarProgress .progress-bar').attr('style', 'width: 0%');
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#invest-project-avatarProgress').height(20);
            $('#invest-project-avatarProgress #progress-stats').html(progress + '%');
            $('#invest-project-avatarProgress .progress-bar').attr('style', 'width: ' + progress + '%');
		}
	});
// Gallery
	var investProjectGallery = {
		templates: {
			gallery: 'tmpl-invest-project-gallery',
			videoInputs: 'tmpl-invest-project-video-inputs',
			imageInputs: 'tmpl-invest-project-image-inputs'
		},
		containers: {
			gallery: '#invest-project-gallery',
			videoInputs: '#invest-project-video-inputs',
			imageInputs: '#invest-project-image-inputs'
		},
		images: [],
		addItem: function (item) {
			this.images.push(item);
		},
		addVideo: function (link) {
			if (!link) {
				return;
			}
			if (!Youtube.isValidLink(link)) {
				alert('<?= __('Invalid youtube video') ?>');
				return;
			}
			var image = this.videoImage(link);
			if (!image) {
				return;
			}
			var videoId = this.videoId(link);
			this.addItem({
				type: 'video',
				image: image,
				videoId: videoId
			});
			this.render();
		},
		addImage: function (image, id) {
			if (!image || !id) {
				return;
			}
			this.addItem({
				type: 'image',
				image: image,
				id: id
			});
			this.render();
		},
		deleteItem: function (index) {
			if (!this.images[index]) {
				return;
			}
			if (this.images[index].type == 'image') {
				investRemoveMedia(this.images[index].id);
			}
			this.images.splice(index, 1);
			this.render();
		},
		render: function () {
			$(this.containers.gallery).html(tmpl(this.templates.gallery, this.images));
			$(this.containers.videoInputs).html(tmpl(this.templates.videoInputs, this.images));
			$(this.containers.imageInputs).html(tmpl(this.templates.imageInputs, this.images));
			// events for gallery element's
			$(this.containers.gallery).find('.remove').on('click', function () {
				investProjectGallery.deleteItem($(this).data('index'));
			});
		},
		videoId: function (url) {
			var videoId = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
			if(videoId == null || !videoId[1]) {
				return;
			}
			videoId = videoId[1];
			return videoId;
		},
		videoImage: function (url) {
			var videoId = this.videoId(url);
			if (videoId) {
				return 'http://img.youtube.com/vi/' + videoId + '/default.jpg';
			}
		}
	}
	$('#invest-project-addVideo-modal button').on('click', function () {
		var $input = $('#invest-project-addVideo-modal input');
		investProjectGallery.addVideo($input.val());
		$input.val('');

	});
	$('#invest-project-gallery-uploadImage').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var image = response.data[0].Media.image;
				var id = response.data[0].Media.id;
				investProjectGallery.addImage(image, id);
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Imperavi-redactor image-uploader
	// button to redactor panel
	$('#redactor-toolbar-0').append($('#tmpl-invest-redactorUploadButton').html());
	$('#imperaviImageUploadButton').on('click', function () {
		$('#imperaviImageUpload').trigger('click');
	});
	var investProjectBodyImages = [];
	$('#imperaviImageUpload').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				var id = response.data[0].Media.id;
				var imgUrl = response.data[0].Media.url_download;
				var imgName = response.data[0].Media.orig_fname;
				$('.redactor-editor').append('<p><img src="' + imgUrl + '" alt="' + imgName + '"></p>');
				$('.redactor_box.redactor').redactor('code.sync');
				investProjectBodyImages.push(id);
				$('#invest-project-body-inputs').html(tmpl('tmpl-invest-project-body-inputs', investProjectBodyImages));
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') {
				return false;
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
		}
	});
// Reward's

	//$('#invest-project-reward-add-button').trigger('click');
// Video
	$('[name="InvestProject[video]"]').on('blur', function () {
		if ($(this).val()) {
			if (!Youtube.isValidLink($(this).val())) {
				alert('<?= __('Invalid youtube video') ?>');
			}
		}
	});
// Submit
	$('#invest-project-create').on('submit', function () {
        // ipad validation
        var required = $('#invest-project-create').find('[required="true"]');
        for(var i = 0; i <= (required.length - 1);i++)
        {
            if($(required[i]).val() == '')
            {
                alert("'" + $(required[i]).data('label') + "'" + ' <?= __('is required') ?>');
                return false;
            }
        }

		// duration
		if ($('[name="InvestProject[duration]"]').val() != parseInt($('[name="InvestProject[duration]"]').val())) {
			alert("<?= __('Enter a integer number to Project duration') ?>");
			return false;
		}
		if ($('[name="InvestProject[duration]"]').val() > 60) {
			alert('<?= __('Project duration (60 days maximum)') ?>');
			return false;
		}

        // youtube link
        if ($('[name="InvestProject[video]"]').val()) {
            if (!Youtube.isValidLink($('[name="InvestProject[video]"]').val())) {
                alert('<?= __('Invalid youtube video') ?>');
                return false;
            }
        }
	});
// fix select
	$('.jq-selectbox.jqselect.category ul').height(300);
});
</script>
