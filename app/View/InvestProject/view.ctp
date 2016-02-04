<?php
	$this->Html->addCrumb(Hash::get($investGroup, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($investGroup, 'Group.id')));
	$this->Html->addCrumb($investProject['InvestProject']['name'], array('controller' => 'InvestProject', 'action' => 'view/'.$id));

	$this->Html->css(array('jquery.fancybox.css?v='.Configure::read('version'), 'fancy-fix.css?v='.Configure::read('version')), array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery-ui-1.10.3.custom.min', 'vendor/jquery/jquery.fancybox.pack'), array('inline' => false));

	$videoId = str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $investProject['InvestProject']['video']);
	$daysToEnd = intval($investProject['InvestProject']['duration'] - ((time() - strtotime($investProject['InvestProject']['created']))/(3600*24)));
	if ($daysToEnd < 0) {
		$daysToEnd = 0;
	}
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

<?php
$user_id = Hash::get($investProject,'InvestProject.user_id');

$aMonths = array( __(' January'), __(' February'), __(' March'), __(' April'), __(' May'), __(' June'),
	__(' July'), __(' August'), __(' September'), __(' October'), __(' November'), __(' December') );

$created = Hash::get($investProject,'InvestProject.created');

$this->Html->css('/Froala/css/froala_editor.min.css', array('inline' => false));
	$month = $aMonths[(int)date('m',strtotime($created))-1];
	$day = date('d',strtotime($created));
	if(!empty($users)){
		$first = reset($users);
	}else{
		$first = $currUser;
	}

	$user_groups = array_keys($userGroups);
	$invite = false;

?>
<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="InvestProject-description-page col-sm-8">
				<div class="row">
					<header class="InvestProject-header-block">
					<style>
					.groupViewInfo{
						display: none;
					}
					.fixedLayout{
						max-width: 1024px;
					}
					#TaskDescr img{
						max-width: 100%;
					}
					.controlButtons a{
						color: #999;
						text-decoration: none;
						font-size: 19px;
						padding: 5px;

					}
					.controlButtons .glyphicons:before{
						position: relative;
					}
					.controlButtons .glyphicons.eye_open:before{
						top: -1px;
					}
					.controlButtons .glyphicons.edit:before{
						top: -3px;
					}
					</style>
						<div class="category-title-block">
							-<?= $investProject['InvestCategory']['title'] ?>-
						</div>
						<h2 id="TaskTitle" class="title" style="text-align: center;" data-chars="12"><?= $investProject['InvestProject']['name'] ?></h2>

						<div class="controlButtons" style="position: absolute; top: 0; right: 0;">
							<?php if($currUserID == $investProject['InvestProject']['user_id']):?>
							<a href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'listSponsors', $investProject['InvestProject']['id'] ))?>" >
								<div style="color: #FFF; background-color: #12d09b;font-size: 13px; border-radius: 15px;width: 23px;padding: 2px 8px;display: inline-block;">$</div>
							</a>
							<?php endif;?>
							<!--a class="" href="<?=$this->Html->url(array('controller' => 'InvestProject', 'action' => 'view', $id ))?>">
								<span class="glyphicons eye_open"></span>
							</a-->
							<?php if($currUserID == $investProject['InvestProject']['user_id']):?>
							<a href="javascript:edit(<?=$investProject['InvestProject']['id']?>)">
								<span class="glyphicons edit"></span>
							</a>
							<?php endif;?>
						</div>
					</header>
				</div>
				<div class="row">
					<article class="col-sm-12 InvestProject-content">
						<? if ($investProject['InvestProject']['video']) {?>
						<div class="userViewVideo">
							<a href="javascript: void(0)" class="showPlayer">
								<span class="glyphicons play_button"></span>
							</a>
						</div>
						<? } ?>
						<div class="userViewVideoPlayer" style="display: none"></div>

						<header class="content-header-panel">
							<div class="left-block" style="margin: 0 auto; display: table;">
								<ul >
									<li><span class="glyphicons calendar"></span><span class="date"><?=$day;?> <?=$month;?></span></li>
									<li>
										<span class="glyphicons eye_open"></span><span class="view-count"><?= $investProject['InvestProject']['hits'] ?></span>
									</li>
									<li><span class="glyphicons user"></span><span class="author"><?= $user['User']['full_name'] ?></span></li>
								</ul>
							</div>
							<div style="clear: both;"></div>
						</header>
						<header class="content-header-panel-descr ">
							<div class="left-block" style="margin: 0 auto; display: table;">
								<ul >
									<li class="active"><?=__('Project Description');?></li>
									<!--li><?=__('Comments');?></li>
									<li><?=__('Updates');?></li-->
								</ul>
							</div>
							<div style="clear: both;"></div>
						</header>
						<div id="TaskDescr" class="content">
							<?= $investProject['InvestProject']['body'] ?>
						</div>
						<div id="TaskComments" class="content hide"></div>
						<div id="TaskUpdates"  class="content hide"></div>
					</article>
				</div>

			</div>
			<script type='text/javascript'>
			$(document).ready(function() {
				$(".showPlayer").click ( function() {
					$('.userViewVideo').slideUp('slow', function(){
						$('.userViewVideoPlayer').slideDown('slow', function(){
							$('.userViewVideoPlayer').append('<iframe width="100%" height="360" src="//www.youtube.com/embed/<?=$videoId?>?rel=0" frameborder="0" allowfullscreen></iframe>');
						});
					});
				});
				$(document).on('click touchstart', function(e) {
					if (!$.contains($(".userViewVideo").get(0), e.target)) {
						$('.userViewVideoPlayer').slideUp('slow', function(){
							$('.userViewVideoPlayer iframe').remove();
							$('.userViewVideo').slideDown();
						});
					}
				});
				$('.fancybox').fancybox({
					padding: 5,
					beforeShow: function () {
						$('body').css("position","fixed");
					},
					afterClose: function () {
						$('body').css("position","static");
					}
				});
			});
			</script>
			<div class="col-sm-4 right-sidebar">
				<div class="statistics">
					<div class="graphics">
						<canvas id="myCanvas"></canvas>
						<div class="des">
							<div class="desc-block">
								<span style="display: block;"><?=__('Target:');?></span>
								<span style="display: block;">$<?= $investProject['InvestProject']['total']?></span>
							</div>
						</div>
					</div>
					<div class="stat">
						<ul>
							<li>
								<div class="statTitle">	<?=__('Assembled:')?>	</div>
								<div class="statPrice">	$<?= $investProject['InvestProject']['funded_total'] ?>	</div>
								<div style="clear: both;"></div>
							</li>
							<li>
								<div class="statTitle">	<?=__('Still needed:')?></div>
								<div class="statPrice">	$<? echo number_format(($investProject['InvestProject']['total'] - $investProject['InvestProject']['funded_total']), 2, '.','')?>	</div>
								<div style="clear: both;"></div>
							</li>
							<li>
								<div class="statTitle">	<?=__('Remains days:')?></div>
								<div class="statPrice">	<?=$daysToEnd?></div>
								<div style="clear: both;"></div>
							</li>
							<li>
								<div class="statTitle">	<?=__('Sponsors:')?></div>
								<div class="statPrice">	<?= $investProject['InvestProject']['funders_total'] ?></div>
								<div style="clear: both;"></div>
							</li>
						</ul>
					</div>
				</div>
				<div class="stat-desc">
					<div class="in-desc">
						<div class="title" style="position: relative;">
							<?php echo $this->Avatar->group($investGroup, array('size' => 'thumb50x50', 'class' => '', 'width' => '70', 'style' => 'margin-right: 10px;')); ?>

		                	<?=$investProject['Group']['title'] ?>
						</div>
						<div class="desc"><?= $investProject['Group']['descr'] ?></div>
					</div>
				</div>
					<div class="sponsors">
						<? foreach ($investProject['Rewards'] as $item) { ?>
							<div class="item">
								<div class="clearfix">
									<span class="value">
										<?= $this->Money->symbolFor($investProject['InvestProject']['currency']) ?> <?= $item['total'] ?>
									</span>
									<?php if($investProject['InvestProject']['user_id'] != $currUserID):?>
									<?php echo $this->Html->link(__('Select'), array('controller' => 'InvestProject', 'action' => 'addFunds', $item['id']), array('class' => 'btn btn-default')); ?>
									<?php endif;?>
								</div>
								<?= $item['name'] ?>
								<div class="size">
									<span class="glyphicons old_man"></span> <?=__('Sponsors')?>: <span class="funders" style="color: #00B6AF;"><?= $item['funders'] ?></span>
								</div>
							</div>
						<? } ?>
					</div>
				<?
				$need = 0;
				if($investProject['InvestProject']['total'] > 0)
					$need = ($investProject['InvestProject']['funded_total']/$investProject['InvestProject']['total'])*100;
				?>
				<script>
					jQuery(document).ready(function(){
					var w = jQuery('#myCanvas').width();
					var h = jQuery('#myCanvas').height();
					jQuery('#myCanvas').attr('width',w);
					jQuery('#myCanvas').attr('height', h);
					 var canvas = document.getElementById('myCanvas');
					 var cx = canvas.getContext('2d');
					 var x = w/ 2;
					 var y = h / 2;
					 var radius = 75;
					 var need = <?=$need?>;
					 var startAngle = 0 * Math.PI;
					 var endAngle = 2 * Math.PI;
					 var ends = (2/100)*need;
					 var endSAngle = ends * Math.PI;
					 var counterClockwise = false;
					 cx.beginPath();
					 cx.lineWidth = 15;
					 cx.strokeStyle = '#D2D2D2';
					 cx.arc(x, y, radius, startAngle, endAngle, counterClockwise);

					 cx.stroke();
					 cx.beginPath();
					 cx.lineWidth = 15;
					 cx.strokeStyle = '#00B6AF';
					 cx.arc(x, y, radius, startAngle, endSAngle, counterClockwise);
					 cx.stroke();
					})
				</script>
			</div>
		</div>
	</div>
</div>
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
		margin-bottom: 48px;
		margin-top: 33px;
		margin-left: -15px;
    	margin-right: -15px;
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
	//console.log(investProjectRewardCounter);
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
