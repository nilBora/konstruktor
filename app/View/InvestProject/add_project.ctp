<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Investments'), array('controller' => 'InvestProject', 'action' => 'listProjects'));
	$this->Html->addCrumb(__('Create project'), array('controller' => 'InvestProject', 'action' => 'addProject'));

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

<h1><?= __('New investment project') ?></h1>
<div class="row investmentsCreate">
<form id="invest-project-create" method="post">
	<input type="hidden" name="InvestProjectAvatar[id]" value="" id="invest-project-avatar"/>
	<span id="invest-project-video-inputs"></span>
	<span id="invest-project-image-inputs"></span>
	<span id="invest-project-body-inputs"></span>
	<div class="col-sm-3 leftFormBlock">
		<label><?= __('Picture, for the announcement project') ?></label>
		<img src="/img/no-article-photo.png" alt="" class="img-responsive" id="invest-project-avatar-img"/>
		<input type="file" class="filestyle" id="invest-project-avatar-uploader"
		   data-progress_id="invest-project-avatarProgress"
		   data-object_type="InvestProjectAvatar"
		   data-object_id=""
		   accept="image/*"
		/>
		<div class="progress" id="invest-project-avatarProgress" style="height: 0">
			<div class="progress-bar progress-bar-info" role="progressbar">
				<span id="progress-stats"></span>
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		<div class="form-group">
			<label><?= __('Project name') ?></label>
			<input name="InvestProject[name]" data-label="<?= __('Project name') ?>" required="true" type="text" class="form-control" placeholder="" value="" />
		</div>
		<div class="form-group">
			<label><?= __('Announcement project') ?></label>
			<input name="InvestProject[note]" data-label="<?= __('Announcement project') ?>" required="true" type="text" class="form-control" placeholder="" value="" />
		</div>
		<select name="InvestProject[category_id]" class="category" data-placeholder="<?= __('Select category') ?>">
			<? foreach ($aInvestCategory as $item) { ?>
				<option value="<?= $item['InvestCategory']['id'] ?>"><?= $item['InvestCategory']['title'] ?></option>
			<? } ?>
		</select>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<label><?= __('Video') ?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons facetime_video"></div>
				<input name="InvestProject[video]" class="form-control" placeholder="" type="text">
			</div>
		</div>
		<div class="form-group noBorder">
			<label><?=__('Photo or video gallery')?></label>
            <input type="file" class="filestyle uploadPhoto" id="invest-project-gallery-uploadImage"
				data-object_type="InvestProjectGallery"
				data-object_id=""
				accept="image/*"
            />
            <button class="btn btn-default" type="button" style="width: 200px"
                data-toggle="modal"
                data-target="#invest-project-addVideo-modal"
                ><?=__('Upload video')?></button>

			<ul class="photoCollection clearfix" id="invest-project-gallery" style="min-height: 122px"></ul>
		</div>
		<?=$this->Redactor->redactor('body')?>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label><?= __('Necessary sum') ?></label>
			<div class="needSum">
				<input name="InvestProject[total]" type="number" step="0.01" data-label="<?= __('Necessary sum') ?>" required="true" class="form-control" placeholder="" value="" />
                <select name="InvestProject[currency]" class="currency">
                    <option value="USD"><?= $this->Money->symbolFor('USD') ?></option>
                    <option value="EUR"><?= $this->Money->symbolFor('EUR') ?></option>
                    <option value="RUB"><?= $this->Money->symbolFor('RUB') ?></option>
                </select>
			</div>
		</div>
	</div>
	<div class="col-sm-4 col-sm-offset-1">
		<div class="form-group">
			<label><?= __('Project duration (60 days maximum)') ?></label>
			<div class="input-group">
				<div class="input-group-addon glyphicons stopwatch"></div>
				<input name="InvestProject[duration]" data-label="<?= __('Project duration') ?>" required="true" type="number" min="1" max="60" step="1" class="form-control" placeholder="" value="" />
			</div>
		</div>
	</div>
	<div class="clearfix hidden-xs"></div>
	<!-- Add reward -->
	<div class="col-sm-3 leftFormBlock">
		<a href="javascript:void(0)" class="addNewInfo" id="invest-project-reward-add-button">
			<span class="glyphicons circle_plus"></span>
			<span class="title"><?= __('Add reward') ?></span>
		</a>
	</div>
	<div class="col-sm-9 reward" id="invest-project-reward-list"></div>
	<!--/ Add reward -->

	<div class="col-sm-12 paymentsAndTerms">
		<label>Системы оплаты</label>
		<div class="paymentSystems">
			<a href="javascript: void(0)" class="paypal"></a>
			<a href="javascript: void(0)" class="visa"></a>
		</div>
		<div class="navigation">
			<!--<a class="underlink" href="#"><?= __('Terms of service') ?></a>
			<a class="underlink" href="#"><?= __('Agreement') ?></a>
			<a class="underlink" href="#"><?= __('Payment') ?></a>-->
		</div>
		<button class="btn btn-primary save" type="submit"><?= __('Save') ?></button>
	</div>
</form>
</div>
<br /><br /><br />

<!-- Modal for video gallery -->
<div id="invest-project-addVideo-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="form-group">
					<label><?=__('Youtube.com URL')?></label>
					<input class="form-control" type="text"/>
				</div>
				<div class="clearfix">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Add')?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/ Modal for video gallery -->

<!-- Templates -->
<span id="tmpl-invest-redactorUploadButton" class="hide">
	<li>
		<a href="javascript:void(0)" id="imperaviImageUploadButton" title="Insert Image" tabindex="-1" class="re-icon re-image"></a>
		<input type="file" multiple style="display: none"
			id="imperaviImageUpload"
			data-object_type="InvestProjectBody"
			data-object_id=""
		/>
	</li>
</span>
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
<div class="back">
	<span class="reward-remove glyphicons remove"></span>
	<div class="form-group">
		<label><?= __('Title') ?></label>
		<input name="InvestReward[{%=o.index%}][name]" type="text" class="form-control" placeholder="" value="" data-label="<?= __('Title') ?>" required="true" />
	</div>
	<div class="form-group">
		<label><?= __('Description') ?></label>
		<input name="InvestReward[{%=o.index%}][description]" type="text" class="form-control" placeholder="" value="" data-label="<?= __('Description') ?>" required="true" />
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label><?= __('Amount') ?></label>
				<div class="input-group">
					<input name="InvestReward[{%=o.index%}][total]" type="number" step="0.01" class="form-control" placeholder="" value="" data-label="<?= __('Amount') ?>" data-label="<?= __('Amount') ?>" required="true"/>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label><?= __('Delivery date') ?></label>
				<div class="input-group">
					<div class="input-group-addon glyphicons calendar"></div>
					<input type="text" class="form-control" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}" readonly/>
					<input name="InvestReward[{%=o.index%}][created]" type="hidden" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-{%=o.index%}-mirror"/>
				</div>
			</div>
		</div>
	</div>
</div>
</script>
<!--/ Templates -->

<script type="text/javascript">
$(document).ready(function () {
// Init
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
	var investProjectRewardCounter = 0;
	$('#invest-project-reward-add-button').on('click', function () {
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
		$('#invest-project-reward-list').prepend($newReward);
		investProjectRewardCounter++;
	});
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
