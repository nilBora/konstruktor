<?php

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

<div class="row taskViewTitle fixedLayout" style="">
<style>
.groupViewInfo{
	border-bottom: 3px solid #eee;
	margin-bottom: 5px;
	padding-bottom: 40px;
}
.userArticles .edit-bar li{
	display: inline-block;
	vertical-align: middle;
	color: #818181;
	font-size: 11px;
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

.fixedLayout{
	max-width: none;
}
#sorted-styler{
	float: right;
}
.edit-bar a{
	color: #999;
	text-decoration: none;
	font-size: 19px;
	padding: 5px;

}
.edit-bar .glyphicons:before{
	position: relative;
}
.edit-bar .glyphicons.eye_open:before{
	top: 	2px;
}
.edit-bar .glyphicons.edit:before{
	top: 0px;
}
</style>
<div class="row">
	<div class="col-sm-4 col-sm-push-8 controlButtons" style="margin-top: 30px;">
			<div style="display: inline-block;float: left;font-size: 18px;">
				<?=__('Sort');?>
			</div>
			<?php
			$options = array('date' => __('Create date'));
			echo $this->Form->select('sorted', $options);
			?>
			<?php //echo $this->Html->link(__('Create project'), 'javascript:add()', array('class' => 'btn btn-default pull-left'))?>
	</div>
	    <div class="col-sm-8 col-sm-pull-4">
	    <? if ($aSearch) { ?>
	        <h1 style="float: left;"><?= __('Search result') ?></h1>
	    <? } elseif (isset($aInvestCategory)) { ?>
	        <h1 style="float: left;"><?= $aInvestCategory['InvestCategory']['title'] ?></h1>
	    <? } else {?>
	        <h1 style="float: left;"><?= __('My projects') ?></h1>
	    <? } ?>
				<div class="col-sm-3 controlButtons" style="margin-top: 30px;">
						<?php echo $this->Html->link(__('Create project'), 'javascript:add()', array('class' => 'btn btn-default pull-left','style'=>'background-color: #FFBA4C;color: #fff;border: none;'))?>
				</div>
	    </div>
	</div>
</div>


<div class="row fixedLayout userArticles">
<? foreach($aInvestProject as $item) { ?>
	<?
	$daysToEnd = intval($item['InvestProject']['duration'] - ((time() - strtotime($item['InvestProject']['created']))/(3600*24)));
	if ($daysToEnd < 0) {
		$daysToEnd = 0;
	}
	?>
    <div class="col-sm-4">
			<ul class="edit-bar" style="margin-top: 10px;margin-right: 10px;z-index: 109;position: relative;top: 25px;text-align: right;">
				<li>
					<?php if($currUserID == $item['InvestProject']['user_id']):?>
					<a class=""  href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listSponsors', $item['InvestProject']['id'] ))?>">
						<div style="color: #FFF; background-color: #12d09b;font-size: 9px; border-radius: 15px;width: 17px;padding: 2px 6px;display: inline-block;">$</div>
					</a>
					<?php endif;?>
				</li>
				<li>
					<a class="" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $item['InvestProject']['id'] ))?>">
						<span class="glyphicons eye_open"></span>
					</a>

				</li>
				<li onclick="javascript:edit(<?=$item['InvestProject']['id']?>)"><span style="    font-size: 16px;" class="glyphicons edit"></span><span class="author"></span></li>
			</ul>
        <a href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $item['InvestProject']['id']))?>" class="item good" style="border-width: 1px 1px 1px;box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.1);">
            <div class="title" style="position: relative;">

                <img src="<?= $this->Media->imageUrl($item['Avatar'], 'thumb70x70') ?>" alt="" width="70" onerror="this.src='/img/no-photo.jpg'">
                <?= $item['InvestProject']['name'] ?>
            </div>
            <div style="padding-left: 13px; padding-right: 13px; font-weight: 600; font-size: 14px"><?= $item['InvestProject']['note'] ?></div>
						<div  style="padding-left: 13px; padding-right: 13px;">
							<div class="left-block">
								<ul style="margin-bottom: 10px;">
									<li><span class="glyphicons calendar"></span><span class="date"><?= $daysToEnd ?></span></li>
									<!--li>
										<span class="glyphicons eye_open"></span><span class="view-count"><?= $investProject['InvestProject']['hits'] ?></span>
									</li-->
									<li><span class="glyphicons user"></span><span class="author"><?= $currUser['User']['full_name'] ?></span></li>
								</ul>
								<div id="progressbar-<?=$item['InvestProject']['id']?>"></div>
							</div>

						<p class="collected" style="left: 88px; top: 55px; font-size: 14px; font-weight: 600; vertical-align: center;"><?= $item['InvestProject']['funded_total'] ?> <?= __('from') ?> <?= $item['InvestProject']['total'] ?></p>
						</div>
        </a>
				<?
					$summ = ($item['InvestProject']['funded_total']/$item['InvestProject']['total'])*100 ;
				?>
				<script>
					 jQuery(function() {
						 var summ = <?=$summ?>;
						 jQuery("#progressbar-<?=$item['InvestProject']['id']?>").progressbar({
							 value: parseInt(summ),
						 });
					 });
			 </script>
    </div>
<? } ?>
<? if (empty($aInvestProject)) { ?>
    <?= __('Nothing not found') ?>
<? } ?>
</div>

<?= $this->element('Invest/project_add')?>

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
<!--/ Templates -->

<script type="text/javascript">
add = function(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back');
	var content = $('.popup-content');
	body.append(popup);
	popup.show();
	body.append(content);
	content.show();
}
edit = function(id){
	var body = $('body');
	var popup = $('.popup-back');
	$.ajax({
		url: '/investAjax/editForm',
		data: {id: id},

	}).done(function(response){
		body.append(popup);
		popup.show();
		body.append(response);
		$('.popup-edit-content').show();
		$('.popup-edit-content .redactor_box').redactor({
			focus: true,
			phpTags: false,
			plugins: ['table', 'video'],
			imageUpload: '/redactor/mediafiles/upload/image',
			fileUpload: '/redactor/mediafiles/upload/file',
			imageGetJson: '/redactor/mediafiles/getmediaimages.json',
			minHeight: 200,
			buttons: ['html', '|', 'formatting', '|', 'bold', 'italic', 'underline', 'deleted', '|',
	                'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
	                'link', '|',
	                'fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule']
		});
		$('.popup-edit-content .close-button').on('click',function(){
			var popup = $('body>.popup-back');
			var content = $('.popup-edit-content');
			popup.hide();
			content.remove();
		});
		var investProjectRewardEditCounter = 0;
		$('.popup-edit-content #invest-project-reward-edit-add-button').on('click', function () {

			investProjectRewardEditCounter = $('#reward-counter').attr('data-counter');
			var $newReward = $(tmpl('tmpl-invest-project-reward-form', {index: investProjectRewardEditCounter}));
			$newReward.find('#invest-project-reward-' + investProjectRewardEditCounter).datetimepicker({
				format: '<?= $dateFormat?>',
				weekStart: 1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				language:"<?=$lang?>",
				linkField: 'invest-project-reward-' + investProjectRewardEditCounter + '-mirror',
				linkFormat: 'yyyy-mm-dd hh:ii:ss'
			});
			$newReward.find('#invest-project-reward-' + investProjectRewardEditCounter).datetimepicker().on('focus', function () {
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
			investProjectRewardEditCounter++;
		});
		$('select').styler();
	});
}

$(document).ready(function () {
// Init

$('.popup-content .close-button').on('click',function(){
	var popup = $('body>.popup-back');
	var content = $('body>.popup-content');
	popup.hide();
	content.hide();
})

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
