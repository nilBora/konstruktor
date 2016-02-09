<?php
	$user_id = Hash::get($task, 'User.id');

	$this->Html->script(array(
		'vendor/bootstrap-datetimepicker.min',
		'vendor/bootstrap-datetimepicker.ru.js',
		'youtube.js',
		'jquery.mCustomScrollbar.concat.min.js',
		'dropzone.js',
		'fresco.js',
		'tinymce.min.js',
		'theme.min.js',
		'tinyPlugins/advlist/plugin.min.js',
		'tinyPlugins/autolink/plugin.min.js',
		'tinyPlugins/lists/plugin.min.js',
		'tinyPlugins/link/plugin.min.js',
		'tinyPlugins/image/plugin.min.js',
		'tinyPlugins/charmap/plugin.min.js',
		'tinyPlugins/print/plugin.min.js',
		'tinyPlugins/cloudfilemanager/plugin.js',
		'tinyPlugins/preview/plugin.min.js',
		'tinyPlugins/responsivefilemanager/plugin.min.js',
		'tinyPlugins/anchor/plugin.min.js',
		'tinyPlugins/searchreplace/plugin.min.js',
		'tinyPlugins/visualblocks/plugin.min.js',
		'tinyPlugins/code/plugin.min.js',
		'tinyPlugins/fullscreen/plugin.min.js',
		'tinyPlugins/insertdatetime/plugin.min.js',
		'tinyPlugins/media/plugin.js',
		'tinyPlugins/table/plugin.min.js',
		'tinyPlugins/contextmenu/plugin.min.js',
		'tinyPlugins/paste/plugin.min.js',
		'tinyPlugins/code/plugin.min.js'
	), array('inline' => false));

	$allowEdit = $user_id == $currUserID && $currUserID;

	$image_link = 'https://'.$_SERVER['HTTP_HOST'].$this->Media->imageUrl($currUser['UserMedia'], 'thumb200x197');

	$title = Hash::get($currUser, 'User.full_name');
	$description = strlen(Hash::get($currUser, 'User.skills')) > 0 ? Hash::get($currUser, 'User.skills') : '';
	$aMonths = array( __(' January'), __(' February'), __(' March'), __(' April'), __(' May'), __(' June'),
	__(' July'), __(' August'), __(' September'), __(' October'), __(' November'), __(' December') );

	$created = Hash::get($task,'UserEvent.created');
	$starttime = strtotime(Hash::get($task,'UserEvent.event_time'));
	$endtime = strtotime(Hash::get($task,'UserEvent.event_end_time'));
	$period = ($endtime - $starttime);
    $task_dur = $taskDurM = '';
	if($period>0){
		$hours = $period/(60*60);
		if($hours >= (7*24)){
			$p = round($hours/(7*24)).' '.__('weeks');
		}elseif($hours >= (24)){
			$p = round($hours/(24)).' '.__('days');
		}elseif($hours >= 1){
			$p = round($hours).' '.__('hours');
		}elseif($hours <= 0){
			$p = '0 '.__('minutes');
		}else{
			$p = round($hours*60).' '.__('minutes');
		}

		$task_dur = $taskDurM = $p;
	}

	$dateFormat = (Hash::get($currUser, 'User.lang') == 'rus') ? 'dd.mm.yyyy' : 'mm/dd/yyyy';

	if(Configure::read('Config.language') == 'rus'){
		$lang = 'ru';
	} else {
		$lang = 'en';
	}

	$css = array(
		//'vendor/editor.min.css',
		'/css/task.css',
		'jquery.mCustomScrollbar.min.css',
		'dropzone.css',
		'fresco.css',
		'content.min.css',
		'skin.min.css',
        '../skins_tiny/lightgray/content.min.css',
        '../skins_tiny/lightgray/skin.min.css',
        '../skins_tiny/lightgray/content.inline.min.css'
	);

	$this->Html->css($css, array('inline' => false));

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($currUser, 'User.full_name'), array('controller' => 'User', 'action' => 'view/'.$user_id));
	$this->Html->addCrumb(__('Task management'), array('controller' => 'User', 'action' => 'timeManagement'));
	if (Hash::get($task, 'UserEvent.title')) {
		$breadcrumbsTitle = Hash::get($task, 'UserEvent.title');
		if ( iconv_strlen($breadcrumbsTitle) > 40 ) {
			$breadcrumbsTitle = mb_substr($breadcrumbsTitle, 0,39).'…';
		}
		$this->Html->addCrumb($breadcrumbsTitle, array('controller' => 'User', 'action' => 'task/'.Hash::get($task, 'UserEvent.id')));
	}

	$month = $aMonths[(int)date('m',strtotime($created)) - 1];
	$day = date('d',strtotime($created));
	if(!empty($users)){
		$first = reset($users);
	}else{
		$first = $currUser;
	}

	$user_groups = array_keys($userGroups);
	$share = $task['UserEvent']['recipient_id'];
	$share  = array_keys($aEventShare);
	$invite = false;
	if( (!empty($task['UserEvent']['object_id']) && in_array( $task['UserEvent']['object_id'], $user_groups)) || in_array($currUserID, $share)){
		$invite = true;
	}

	$UserEventRequests = Hash::combine($task, 'UserEventRequest.{n}.user_id', 'UserEventRequest.{n}');
?>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<script>
	    jQuery(document).ready(function($) {
			tinymce.init({
				selector: '.UserEventDescrTextarea',
				plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table contextmenu paste code cloudfilemanager'
				],
				relative_urls: false,
				external_filemanager_path:"/Cloud/index",
				filemanager_title:"Менеджер файлов" ,
				toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link cloudfilemanager'
			});
			var myDropzone = new Dropzone('div#myDropzone',{
				url: "/media/ajax/upload",
				autoProcessQueue: true,
				acceptedFiles: ".jpg, .png, .gif, .jpeg, .bmp, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .psd, .rar, .zip",
				paramName : 'files',
				parallelUploads: 1,
				addRemoveLinks: true
			});
			myDropzone.on("removedfile", function(file) {
				var imageId = file.previewElement.dataId;
				$.ajax({
					type: 'POST',
					url: '/media/ajax/deleteObjectId',
					dataType: 'JSON',
					data:{
						id: imageId
					},
					success: function(data){

					}
				});
			});
			myDropzone.on("success", function(file,response) {
				if( typeof response != 'object' ) {
					response = $.parseJSON(response);
				}
				var moveImage = response.files[0];
				moveImage.object_id = "<?php echo $task['UserEvent']['id'];?>";
				moveImage.object_type = 'Cloud';
				var ret = '';
				$.ajax({
					type: "POST",
					url: "/media/ajax/move.json",
					async: false,
					data: moveImage,
					dataType: 'JSON',
					success: function(response) {
						if( response.data[0].Media.orig_w < 1000 ) {
							ret = '{"link": "' + response.data[0].Media.url_download + '" }';
						} else {
							var url = response.data[0].Media.url_img;
							url = url.replace('noresize', '1000x');
							ret = '{"link": "' + url + '" }';
							file.previewElement.dataId = response.data[0].Media.id;
						}
						$.ajax({
							type: "POST",
							url: "/CloudAjax/addFolder.json",
							data: {"Cloud":{"media_id":response.data[0].Media.id,"name":response.data[0].Media.orig_fname,"parent_id":""}}
						});
					},
					error: function() {
						ret = 'error'
					}
				});
			});

			myDropzone.on("addedfile", function(file) {
				var fileName = file.name;
				var fileExt = fileName.split('.');
				fileExt = fileExt[fileExt.length - 1].toLowerCase();
				$(file.previewElement).addClass('file-' + fileExt);
			});

			if( typeof $('#myDropzone').attr('data-file') != 'undefined' ) {
				var dataFileJson = $.parseJSON($('#myDropzone').attr('data-file'));
				for(var i in dataFileJson) {
					var mockFile = {name: dataFileJson[i].name, size: dataFileJson[i].size, accepted: true};
					myDropzone.emit("addedfile", mockFile);
					if(dataFileJson[i].media_type == 'image') {
						myDropzone.emit("thumbnail", mockFile, dataFileJson[i].path);
					}
					myDropzone.emit("complete", mockFile);
					mockFile.previewElement.dataId = dataFileJson[i].id;
					myDropzone.files.push(mockFile);
					myDropzone._updateMaxFilesReachedClass();
				}
			}

	    });
</script>

<style>
	.task-description-page{
		display: table-cell;
		/*width: 100%;*/
	}
	.task-header-block h1.title{
		text-transform: uppercase;
	}
	.task-description-page .task-content .content-header-panel{
		border: none;
		border-top: 1px solid #CCC;
		border-bottom: 1px solid #CCC;
		padding: 10px 0;
	}

	.task-description-page .task-content .left-block{
		float: left;
		margin: 7px 0;
	}
	.task-description-page .task-content .right-block{
		float: right;
		color: #12d09b;
		font-size: 25px;
	}
	.task-description-page .task-content .left-block ul{
		margin: 0;
		padding: 0;
		list-style: none;
	}
	.task-description-page .task-content .left-block ul li{
		display: inline-block;
		vertical-align: middle;
		color: #818181;
	}
	.task-description-page .task-content .left-block ul li span{
		vertical-align: text-bottom;
	}
	.task-description-page .task-content .left-block ul li span:last-child{
		vertical-align: baseline;
		font-size: 10px;
		margin-right: 20px;
	}
	.task-description-page .task-content .content{
		padding: 20px 0;
	}

	.task-content .content ul,
	.task-content .content ol {
		list-style-position: inside;
		margin-bottom: 20px;
	}

	.task-content .content ul {
		list-style-type: disc;
	}

	.task-description-page .task-content .button-bar{
		padding: 10px 0;
		border: none;
		border-bottom: 1px solid #CCC;
		border-top: 1px solid #CCC;
	}
	.task-description-page .task-content .button-bar .social-icons{
		float: right;
		margin: 3px 0;
	}
	.task-description-page .task-content .button-bar .social-icons a{
		color: #CCC;
	}
	.task-description-page .task-content .button-bar .btn{
		background-color: #FFBA4B;
		color: #FFF;
		border: none;
	}
	.task-description-page .task-content .comment-block{
		border: none;
		border-top: 1px solid #ccc;
		margin-top: 5px;
	}
	.task-description-page .task-content .comment-block .author{
		padding: 0;
		height: 85px;
	}
	.task-description-page .task-content .comment-block .author img{
		height: 100%;
	}
	.task-description-page .task-content .comment-block .textarea textarea{
		height: 85px;
		border: 1px solid #CCC;
	}
	.task-description-page .task-content .comment-block .textarea{
		padding: 0;

	}
	.task-description-page .task-content .comment-block .comment-title{
		text-transform: uppercase;
		margin: 25px 0 20px;
	}
	.task-description-page .task-content .comment-block .send-coment{
		margin-top: 10px;
	}
	.task-description-page .task-content .comment-block .send-coment .btn{
		float: right;
	}
/*	.fixedLayout{
		max-width: 1024px;
	}*/
	.right-sidebar{
		border: 1px solid #CCC;
		height: 100%;
		float: none;
		display: table-cell;
		border-top: none;
		vertical-align: top;
		padding: 0;
	}
	.right-sidebar .category-list{
		border: none;
		border-top: 1px solid #CCC;
		border-bottom: 1px solid #CCC;
		padding: 10px 0;
	}
	.right-sidebar .category-list ul{
		margin: 0;
		padding: 0;
		list-style: none;
	}
	.right-sidebar .category-list ul li{
		text-align: center;
		padding: 10px 0;
		margin: 0;
		list-style: none;
	}
	.right-sidebar .category-list ul li:first-child{
		text-transform: uppercase;
		padding: 15px 0;
		border-bottom: none;
	}
	.right-sidebar .category-list ul li{
		border-bottom: 1px solid #CCC;

	}
	.right-sidebar .category-list ul li:last-child{
		padding: 10px 0;
		border-bottom: none;
	}
	.right-sidebar .category-list ul li a{
		text-decoration: none;
		color: #999;
	}
	.new-categorys-title{
		text-align: center;
		text-transform: uppercase;
		margin: 20px 0;
	}
	.new-categorys-list .categorys-list{
		margin: 0;
		padding: 0;
		list-style: none;
	}
		.new-categorys-list .categorys-list a{
			color: #666666;
			text-decoration: none;
		}
	.new-categorys-list .categorys-list > li{
		margin: 0;
		padding: 0 0 5px 30px;
		list-style: none;
		border-top: 1px solid #CCC;

	}
	.new-categorys-list .categorys-list > li .content-header-panel{
		margin: 5px 0;
	}
	.new-categorys-list .categorys-list > li .content-header-panel ul{
		margin: 0;
		padding: 0;
		list-style: none;
	}
	.new-categorys-list .categorys-list > li .content-header-panel ul li{
		margin: 0;
		padding: 0;
		list-style: none;
		display: inline-block;
		vertical-align: middle;
		color: #818181;
	}
	.new-categorys-list .categorys-list > li .content-header-panel ul li.price{
		color: #12d09b;
	}
	.new-categorys-list .categorys-list > li .content-header-panel ul li span{
		vertical-align: sub;
	}
	.new-categorys-list .categorys-list > li .content-header-panel ul li span:last-child{
		vertical-align: -webkit-baseline-middle;
		font-size: 10px;
		margin-right: 20px;
	}
	.popup-content-tasks .redactor-toolbar, .popup-edit-content-tasks .redactor-toolbar{
		background-color: #F1F1F1;
	}
	.duration-icon{
	  background-image: url(/img/duration_icon.png);
		width: 15px;
	  background-size: 100%;
	  height: 15px;
	  display: inline-block;
	  margin-right: 5px;
	}

	.file-psd .dz-image,.task-file_item-preview.psd{
		background:url(../../html/img/psd7.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-doc .dz-image,.task-file_item-preview.doc{
		background:url(../../html/img/doc8.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-docx .dz-image,.task-file_item-preview.docx{
		background:url(../../html/img/docx6.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-xlsx .dz-image,.task-file_item-preview.xlsx{
		background:url(../../html/img/xlsx5.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-xls .dz-image,.task-file_item-preview.xls{
		background:url(../../html/img/xls8.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-pdf .dz-image,.task-file_item-preview.pdf{
		background:url(../../html/img/pdf28.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-bmp .dz-image,.task-file_item-preview.bmp{
		background:url(../../html/img/bmp4.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.file-zip .dz-image,.task-file_item-preview.zip{
		background:url(../../html/img/zip17.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}
	.file-rar .dz-image,.task-file_item-preview.rar{
		background:url(../../html/img/rar4.svg)no-repeat center center !important;
		background-size:90px 90px !important;
	}

	.dropzone .dz-preview .dz-details .dz-filename:not(:hover) span{
		border:none !important;
	}

	ol{
		padding-left:0;
	}

	.clear{
		clear:both;
	}
/*
	.filetype.zip{
		background:url(../../html/img/zip17.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.doc{
		background:url(../../html/img/doc8.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.pdf{
		background:url(../../html/img/pdf28.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.rar{
		background:url(../../html/img/rar4.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.docx{
		background:url(../../html/img/docx6.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.xls{
		background:url(../../html/img/xls8.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.xlsx{
		background:url(../../html/img/xlsx5.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.bmp{
		background:url(../../html/img/bmp4.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}


	.filetype.psd{
		background:url(../../html/img/psd7.svg)no-repeat center center !important;
		background-size:17px 17px !important;
	}*/

/*.filetype.zip:before, .filetype.doc:before, .filetype.pdf:before, .filetype.rar:before, .filetype.docx:before, .filetype.xls:before, .filetype.xlsx:before, .filetype.bmp:before, .filetype.psd:before{
	content:'';
}*/

	.similar-task_item-image img {
		max-width: 100px;
	}

	.padTop{
		padding-top:55px !important;
	}

	.posSC{
		position: relative !important;
	}
</style>
<script>
	jQuery(document).ready(function(){



		$(".similar-task_list").mCustomScrollbar({
			autoDraggerLength: false
		});

		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/getEventsComment',
			async: false,
			data: { event_id: <?=$task['UserEvent']['id']?>, recepient_id: <?=$first['User']['id']?> }
		}).done(function(respnse){
			jQuery('.commentBlock').html(respnse);
		});
		jQuery('#UserEventEventTaskForm').on('submit',function(event){
			event.preventDefault();
			var thise = jQuery('#UserEventEventRecepientId').val();
			jQuery.ajax({
				method: "POST",
				url: '/UserAjax/addComment',
				async: false,
				data: jQuery(this).serialize(),
			}).done(function(respnse){
				selectUserChat(<?=$task['UserEvent']['id']?>, thise);
			});
		});
	});

	function chat(user){
		if($('.user-list-item[data-val-id="'+user+'"]').length > 0){
			$('.user-list-item[data-val-id="'+user+'"]').click();
		}else{
			window.location.href = "/Chat/index/"+user;
		}
	}

	function selectUserChat(event_id, recepient_id){
		if(!jQuery('#user'+recepient_id).hasClass('active')){
			jQuery('.comment-user-list .active').addClass('active');
			jQuery('#user'+recepient_id).addClass('active');
			jQuery('#UserEventEventRecepientId').val(recepient_id);
			jQuery.ajax({
			  method: "POST",
				url: '/UserAjax/getEventsComment',
				async: false,
			  data: { event_id: event_id, recepient_id: recepient_id }
			}).done(function(respnse){
				jQuery('.commentBlock').html(respnse);
			});
		}
	}

	function sendUserInvite(event_id, recepient_id){
		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/setInvites',
			async: false,
			data: {event_id: event_id, user_id: recepient_id},
		}).done(function(respnse){
			jQuery('.button-bar .btn').remove();
		});
	}

	function accept(request, user_id, event_id){
		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/acceptInvites',
			async: false,
			data: {id: request, event_id: event_id, user_id: user_id},
		}).done(function(respnse){
			jQuery('.accept-'+event_id+'-'+user_id+' .task-followers_item-accept').hide();
			// jQuery('.accept-'+event_id+'-'+user_id+' .task-followers_item-reject').remove();
		});
	}

	function discard(request, user_id, event_id){
		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/discartInvites',
			async: false,
			data: {id: request, event_id: event_id, user_id: user_id},
		}).done(function(response){
			jQuery('.accept-'+event_id+'-'+user_id+' .task-followers_item-accept').show();
			// jQuery(this).parent().remove();
		});
	}

	function acceptShare(request, user_id, event_id){
		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/acceptShareInvites',
			async: false,
			data: {id: request, event_id: event_id, user_id: user_id},
		}).done(function(respnse){
			jQuery('.accept-'+event_id+'-'+user_id+' .task-followers_item-accept').hide();
		});
	}

	function deleteFromShare(request, user_id, event_id){
		jQuery.ajax({
			method: "POST",
			url: '/UserAjax/deleteShareInvites',
			async: false,
			data: {id: request, event_id: event_id},
		}).done(function(response){
			jQuery('.accept-'+event_id+'-'+user_id).remove();
		});
	}

	</script>

<div class="col-sm-12" style="display: table;">
	<div class="row">
		<div class="task-description-page col-sm-8">
			<header class="task-header-block">

				<a href="/User/view/<?=Hash::get($task, 'User.id')?>" class="community-logo" id="user<?=Hash::get($task, 'User.id')?>">
					<?php echo $this->Avatar->user($users[Hash::get($task, 'User.id')], array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
				</a>

				<div class="community-title"><?php echo __('Task from'); ?> <span><?=Hash::get($task, 'User.full_name')?></span>:</div>

				<h2 id="TaskTitle" class="title" data-chars="12">
					«<?=Hash::get($task, 'UserEvent.title')?>»
				</h2>
				<div class="community-title" style="color: #12d09b; text-align: center; margin: 10px auto;">
					<?php
					if(!empty(Hash::get($task, 'UserEventCategory.title'))){
						echo ' - ' . Hash::get($task, 'UserEventCategory.title') . ' - ';
					}
					?>
				</div>
				<?php if($allowEdit) { ?>
				    <div class="user-btn-block" id="editMode">
				        <span class=""></span>
				    </div>
				<?php } ?>
			</header>

			<article class="task-content">
				<header class="content-header-panel">
					<?php if(Hash::get($task, 'UserEvent.price')>0): ?>
						<div class="task-meta_price">
							$<span id="TaskPrice"><?=Hash::get($task, 'UserEvent.price')?></span>
						</div>
					<?php endif ?>
					<div class="task-meta_date"><?=$day;?> <?=$month;?></div>
					<?php if($task_dur): ?>
						<div class="task-meta_time"><?=$task_dur?></div>
					<?php endif ?>
				</header>

				<?php if( count($mediaFiles) ): ?>
					<div class="task-file_list">
						<?php
							$mediaFilesImages = [];
							foreach($mediaFiles as $mFile) {
								if($mFile['Media']['media_type'] == 'image') {
									$mediaFilesImages[] = $mFile;
								}
							}
						?>

						<?php if( count($mediaFilesImages) ): ?>
						<div class="task-file_coll">
							<div class="task-file_item">
								<a href="<?=$this->Media->imageUrl($mediaFilesImages[0]['Media'], 'noresize')?>" class="task-file_item-preview fresco" data-fresco-group="picTask">
									<img src="<?=$this->Media->imageUrl($mediaFilesImages[0]['Media'], '135x')?>" alt="">
								</a>
								<?php for( $i = 1; $i < count($mediaFilesImages); $i++ ): ?>
									<a href="<?=$this->Media->imageUrl($mediaFilesImages[$i]['Media'], 'noresize')?>" class="fresco" data-fresco-group="picTask"></a>
								<?php endfor; ?>
								<div class="task-file_item-title"><span class="image"><?php echo count($mediaFilesImages); ?></span><?php echo __('Images'); ?></div>
							</div>
						</div>
						<?php endif; ?>
						<?php if( count($mediaFiles) > count($mediaFilesImages) ): ?>
							<?php foreach( $mediaFiles as $mFile ): ?>
								<?php if( $mFile['Media']['media_type'] != 'image' ): ?>
								<div class="task-file_coll">
									<div class="task-file_item">
										<a href="/File/preview/<?php echo $mFile['Media']['id']; ?>" class="task-file_item-preview <?php echo trim($mFile['Media']['ext'], '.') ?>">

										</a>
										<div class="task-file_item-title"><span class="filetype <?php echo trim($mFile['Media']['ext'], '.') ?>"></span><?php echo $mFile['Media']['orig_fname']; ?></div>
									</div>
								</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<div id="TaskDescr" class="content">
					<?=Hash::get($task, 'UserEvent.descr')?>
				</div>
					<?php if($currUserID):?>
						<?php if($user_id !== $currUserID) :?>
							<div class="task-subscribe">
								<?php echo __('Get the first task and news from') ?> <span><?=Hash::get($task, 'User.full_name')?></span>:
								<?php if($subscription) :?>
									<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'deleteSubscription', $subscription['Subscription']['id']))?>" class="btn btn-default task-subscribe_link"><?=__('Unsubscribe')?></a>
								<?php else:?>
									<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'addSubscription', $user_id))?>" class="btn btn-default task-subscribe_link"><?=__('Subscribe')?></a>
								<?php endif;?>
							</div>
						<?php endif;?>
					<?php endif;?>
				<?php if(($task['UserEvent']['user_id'] != $currUserID) && !$invite && empty($UserEventRequests[$currUserID])):?>
					<div class="task-request">
						<a class="btn btn-default task-request_link" href="javascript:add();" id="user<?=$currUserID;?>">
							<?=__('Send request')?>
						</a>
					</div>
				<?php endif;?>

				<?php if($task['UserEvent']['user_id'] == $currUserID): ?>
				<div class="task-followers">
					<?php if(!empty($UserEventRequests)): ?>
						<?php foreach ($UserEventRequests as $userRequest):?>
							<div class="task-followers_item accept-<?php echo $userRequest['event_id']; ?>-<?php echo $userRequest['user_id']; ?>">
								<a href="/User/view/<?=$userRequest['user_id']?>" id="user<?=$userRequest['user_id']?>" class="task-followers_item-image">
									<?php echo $this->Avatar->user($users[$userRequest['user_id']], array('size' => 'thumb100x100', 'class' => 'rounded', 'style' => "width:70px;")); ?>
								</a>
								<a href="#" onclick="accept(<?=$userRequest['id']?>,<?=$userRequest['user_id']?>,<?=$userRequest['event_id']?>);return false;" class="task-followers_item-accept" style="display: <?php echo (int) $userRequest['status'] == 1 ? 'none' : 'block'; ?>;"><?php echo __('Accept') ?></a>
								<a href="#" onclick="discard(<?=$userRequest['id']?>,<?=$userRequest['user_id']?>,<?=$userRequest['event_id']?>);return false;" class="task-followers_item-reject"><?php echo __('Decline') ?></a>
							</div>
						<?php endforeach; ?>
					<?php endif ?>
					<?php if(!empty($aEventShare)): ?>
						<?php foreach ($aEventShare as $userRequest):?>
							<?php if($userRequest['UserEventShare']['user_id'] != $currUserID): ?>
								<div class="task-followers_item accept-<?php echo $userRequest['UserEventShare']['user_event_id']; ?>-<?php echo $userRequest['UserEventShare']['user_id']; ?>">
									<a href="/User/view/<?=$userRequest['UserEventShare']['user_id']?>" id="user<?=$userRequest['UserEventShare']['user_id']?>" class="task-followers_item-image">
										<?php echo $this->Avatar->user($users[$userRequest['UserEventShare']['user_id']], array('size' => 'thumb100x100', 'class' => 'rounded', 'style' => "width:70px;")); ?>
									</a>
									<?php if($userRequest['UserEventShare']['acceptance']==0) :?>
										<a href="#" onclick="acceptShare(<?=$userRequest['UserEventShare']['id']?>,<?=$userRequest['UserEventShare']['user_id']?>,<?=$userRequest['UserEventShare']['user_event_id']?>);return false;" class="task-followers_item-accept"><?php echo __('Accept') ?></a>
									<?php endif; ?>
									<a href="#" onclick="deleteFromShare(<?=$userRequest['UserEventShare']['id']?>,<?=$userRequest['UserEventShare']['user_id']?>,<?=$userRequest['UserEventShare']['user_event_id']?>);return false;" class="task-followers_item-reject"><?php echo __('Decline') ?></a>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif ?>
				</div>
				<?php endif; ?>
			</article>
		</div>
		<div class="col-sm-4">

			<?php if(!empty($newCatTasks)):?>
				<div class="similar-task_list">
					<div class="similar-task_title"><?php echo __('Similar tasks') ?></div>
					<?php foreach ($newCatTasks as $newCatTask):?>
						<?php
							$created = Hash::get($newCatTask,'UserEvent.created');
							$month = $aMonths[date('m',strtotime($created)) - 1];
							$day = date('d',strtotime($created));

							$created = Hash::get($newCatTask,'UserEvent.created');
							$starttime = strtotime(Hash::get($newCatTask,'UserEvent.event_time'));
							$endtime = strtotime(Hash::get($newCatTask,'UserEvent.event_end_time'));
							$period = ($endtime - $starttime);
						    $task_dur = '';
							if($period>0){
								$hours = $period/(60*60);
								if($hours >= (7*24)){
									$p = round($hours/(7*24)).' '.__('weeks',array(round($hours/(7*24))));
								}elseif($hours >= (24)){
									$p = round($hours/(24)).' '.__('days');
								}elseif($hours >= 1){
									$p = round($hours).' '.__('hours');
								}elseif($hours <= 0){
									$p = '0 '.__('minutes');
								}else{
									$p = round($hours*60).' '.__('minutes');
								}
								$task_dur = $p;
							}

						?>
						<div class="similar-task_item">
							<a href="/User/task/<?=Hash::get($newCatTask, 'UserEvent.id')?>" class="similar-task_item-image">
								<img src="<?php echo $this->Media->imageUrl($newCatTask['Media'], '135x') ?>" alt="">
							</a>

							<div class="similar-task_item-body">
								<?php echo __('%s was created task',array(Hash::get($newCatTask, 'User.full_name'))); ?>
								<a href="/User/task/<?=Hash::get($newCatTask, 'UserEvent.id')?>" class="similar-task_item-title">«<?=Hash::get($newCatTask, 'UserEvent.title')?>»</a>
							</div>

							<div class="clear"></div>

							<div class="similar-task_item-meta">
								<?php if(Hash::get($newCatTask, 'UserEvent.price')>0): ?>
									<div class="similar-task_item-price">$<?=Hash::get($newCatTask, 'UserEvent.price')?></div>
								<?php endif ?>
								<div class="similar-task_item-date"><?=$day?> <?=$month?></div>
								<?php if($task_dur): ?>
									<div class="similar-task_item-time"><?php echo $task_dur ?></div>
								<?php endif ?>
							</div>
							<a href="/User/task/<?=Hash::get($newCatTask, 'UserEvent.id')?>" class="similar-task_item-logo">
								<?php echo $this->Avatar->user($users[Hash::get($newCatTask, 'User.id')], array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
							</a>
						</div>
					<?php endforeach;?>
				</div>
			<?php endif; ?>

			<?php if(!empty($otherTasks)):?>
				<div class="similar-task_list">
					<div class="similar-task_title"><?php echo __('More interesting tasks') ?></div>
					<?php foreach ($otherTasks as $otherTask):?>
						<?php
						$created = Hash::get($otherTask,'UserEvent.created');
						$month = $aMonths[date('m',strtotime($created)) - 1];
						$day = date('d',strtotime($created));

						$created = Hash::get($otherTask,'UserEvent.created');
						$starttime = strtotime(Hash::get($otherTask,'UserEvent.event_time'));
						$endtime = strtotime(Hash::get($otherTask,'UserEvent.event_end_time'));
						$period = ($endtime - $starttime);
						$task_dur = '';
						if($period>0){
							$hours = $period/(60*60);
							if($hours >= (7*24)){
								$p = round($hours/(7*24)).' '.__('weeks');
							}elseif($hours >= (24)){
								$p = round($hours/(24)).' '.__('days');
							}elseif($hours >= 1){
								$p = round($hours).' '.__('hours');
							}elseif($hours <= 0){
								$p = '0 '.__('minutes');
							}else{
								$p = round($hours*60).' '.__('minutes');
							}
							$task_dur = $p;
						}
						?>
						<div class="similar-task_item">
							<a href="/User/task/<?=Hash::get($otherTask, 'UserEvent.id')?>" class="similar-task_item-image">
								<img src="<?php echo $this->Media->imageUrl($otherTask['Media'], '135x') ?>" alt="">
							</a>

							<div class="similar-task_item-body">
								<?php echo __('%s was created task',array(Hash::get($otherTask, 'User.full_name'))); ?>
								<a href="/User/task/<?=Hash::get($otherTask, 'UserEvent.id')?>" class="similar-task_item-title">«<?=Hash::get($otherTask, 'UserEvent.title')?>»</a>
							</div>

							<div class="similar-task_item-meta">
								<?php if(Hash::get($otherTask, 'UserEvent.price')>0): ?>
									<div class="similar-task_item-price">$<?=Hash::get($otherTask, 'UserEvent.price')?></div>
								<?php endif ?>
								<div class="similar-task_item-date"><?=$day?> <?=$month?></div>
								<?php if($task_dur): ?>
									<div class="similar-task_item-time"><?php echo $task_dur ?></div>
								<?php endif ?>
							</div>

							<a href="/User/task/<?=Hash::get($otherTask, 'UserEvent.id')?>" class="similar-task_item-logo">
								<?php echo $this->Avatar->user($users[Hash::get($otherTask, 'User.id')], array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
							</a>
						</div>
					<?php endforeach;?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<!-- / content -->


<!-- закрывющий тег для container-fluid fixedLayout -->
</div>

<!-- закрывющий тег для wrapper-container -->
</div>

<?php if(!empty($lastTaskID) && !empty($viewedTasks)): ?>
<div class="viewed-task">
	<div class="container-fluid fixedLayout">
		<div class="row">
			<div class="col-sm-12">
				<div class="viewed-task_title"><?php echo __('Last viewed tasks') ?></div>
			</div>
		</div>
		<div class="row">
		<?php foreach($lastTaskID as $k=>$v): ?>
			<?php if(isset($viewedTasks[$k])): ?>
				<?php
				$created = Hash::get($viewedTasks[$k],'UserEvent.created');
				$month = $aMonths[date('m',strtotime($created)) - 1];
				$day = date('d',strtotime($created));

				$created = Hash::get($viewedTasks[$k],'UserEvent.created');
				$starttime = strtotime(Hash::get($viewedTasks[$k],'UserEvent.event_time'));
				$endtime = strtotime(Hash::get($viewedTasks[$k],'UserEvent.event_end_time'));
				$period = ($endtime - $starttime);
				$task_dur = '';
				if($period>0){
					$hours = $period/(60*60);
					if($hours >= (7*24)){
						$p = round($hours/(7*24)).' '.__('weeks');
					}elseif($hours >= (24)){
						$p = round($hours/(24)).' '.__('days');
					}elseif($hours >= 1){
						$p = round($hours).' '.__('hours');
					}elseif($hours <= 0){
						$p = '0 '.__('minutes');
					}else{
						$p = round($hours*60).' '.__('minutes');
					}
					$task_dur = $p;
				}
				?>

					<div class="col-sm-3">
						<div class="viewed-task_item">
							<a href="/User/task/<?=Hash::get($viewedTasks[$k], 'UserEvent.id')?>" class="viewed-task_item-image">
								<img src="<?php echo $this->Media->imageUrl($viewedTasks[$k]['Media'], '170x') ?>" alt="">
							</a>

							<div class="viewed-task_item-meta">
								<div class="viewed-task_item-data"><?=$day?> <?=$month?></div>
								<?php if($task_dur): ?>
									<div class="viewed-task_item-time"><?php echo $task_dur ?></div>
								<?php endif ?>
							</div>
							<p class="viewed-task_item-subtitle"><?php echo __('%s was created task',array(Hash::get($viewedTasks[$k], 'User.full_name'))); ?></p>
							<a href="/User/task/<?=Hash::get($viewedTasks[$k], 'UserEvent.id')?>" class="viewed-task_item-title">«<?=Hash::get($viewedTasks[$k], 'UserEvent.title')?>»</a>
							<?php if(Hash::get($viewedTasks[$k], 'UserEvent.price')>0): ?>
								<span class="viewed-task_item-price">$<?=Hash::get($viewedTasks[$k], 'UserEvent.price')?></span>
							<?php endif ?>

							<div class="viewed-task_item-logo">
								<?php echo $this->Avatar->user($users[Hash::get($viewedTasks[$k], 'User.id')], array('size' => 'thumb100x100', 'class' => 'rounded')); ?>
							</div>
						</div>
					</div>
			<?php endif ?>
		<?php endforeach; ?>
		</div>
	</div>
</div>
<?php endif ?>
<!-- modal -->
<div class="modal fade" id="approveMember" tabindex="-1" role="dialog">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
		        <button type="button" class="close" ><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title"><?=__('Proposals Limit Notification')?></h4>

			    </div>
					<div class="modal-body">
			    </div>
			</div>
		</div>
	</div>
</div>

<!-- popup-back-tasks -->
<div class="popup-back-tasks" style="display: none; position: fixed; top: 0px; z-index: 9999; left: 0px; width: 100%; bottom: 0px; background: rgba(204, 204, 204,0.8);"></div>

<!-- popup-content-tasks -->
<div class="popup-content-tasks" style="z-index: 9999;  position: absolute; top:50%; left:50%;  display: none; max-width:830px; margin:0px auto 40px; transform:translate(-50%,-50%);" >
	<div class="" style="background: #fff;  position:static; height:100%;">
		<div class="popup-header">
			<h2 class="desc"><?=__('Your comment')?></h2>
			<div class="close-button">
				<span class="glyphicons remove_2 i2"></span>
			</div>
		</div>

		<form id="invest-project-create" action="/User/addEventRequest" method="post">
			<div class="col-sm-12 " style="padding: 0 35px;">
				<input type="hidden" name="UserEvent[event_id]" value="<?=Hash::get($task, 'UserEvent.id')?>" id="invest-project-avatar"/>
				<input type="hidden" name="UserEvent[user_id]" value="<?=Hash::get($task, 'UserEvent.user_id')?>" id="invest-project-avatar"/>
				<input type="hidden" name="UserEvent[status]" value="0" id="invest-project-avatar"/>
				<?=$this->Redactor->redactor('UserEventRequest.description')?>

				<div class="row InvestProject-totals">
					<div class="col-sm-4">
						<div class="form-group">
							<div class="needSum">
								<div class="input-group">
									<input type="number" step="1" min="0" class="form-control" style="border-radius: 5px 0px 0px 5px; border-right: 0;" name="UserEvent[price]" data-label="<?= __('Necessary sum') ?>" required="true" class="form-control" placeholder="<?= __('Necessary sum') ?>" value="" >

									<div class="input-group-btn">
										<button type="button" class="btn btn-secondary dropdown-toggle" style="padding: 7px 5px 5px;background: #fff;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;border-top: 1px solid #ccc;border-radius: 0 5px 5px 0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										  <?= $this->Money->symbolFor('USD') ?>
										</button>
									</div>
							    </div>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<div class="">
								<input name="UserEvent[duration]" type="text" class="form-control " data-label="<?= __('Delivery date') ?>" placeholder="<?= __('Delivery date') ?>" required="true" id="datetimepicker-0"/>
								<input  type="hidden" placeholder="<?= __('Delivery date') ?>" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-0-mirror"/>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<button class="btn save col-sm-12" style="padding: 6px 12px;background-color: #FFBA4B;color: #FFF;border: none;font-weight: normal;" type="submit"><?= __('Save') ?></button>
					</div>
				</div>
			</div>
		</form>

		<div style="clear: both;"></div>
	</div>
</div>

<!-- popup-edit-content-tasks -->
<style>
	.dropzoneWrapper {
		padding: 0 35px;
		margin: -20px 0 30px 0;
	}
</style>
<div class="popup-edit-content-tasks" style="z-index: 9999;  position: relative;  display: none; max-width:830px; margin:0px auto 40px; min-height:100%;">
	<div class="" style="background: #fff;  position:static; height:100%;">
		<div class="popup-header">
			<h2 class="desc" style="font-size: 14px;color: #666666;"><?=__('Edit task')?></h2>

			<div class="close-button">
				<span class="glyphicons remove_2 i2"></span>
			</div>
		</div>

		<div class="dropzoneWrapper col-sm-12">
			<div class="dropzone" id="myDropzone" <?php if( count($mediaFiles)>0): ?>data-file='[<?php foreach($mediaFiles as $k => $mFile): ?><?php echo $k > 0 ? ',' : ''; ?>{"name":"<?php echo Hash::get($mFile, 'Media.orig_fname'); ?>", "size":"<?php echo Hash::get($mFile, 'Media.orig_fsize'); ?>", "media_type":"<?php echo Hash::get($mFile, 'Media.media_type'); ?>", "path":"<?php echo Hash::get($mFile, 'Media.url_img'); ?>", "id":"<?php echo Hash::get($mFile, 'Media.id'); ?>"}<?php endforeach; ?>]'<?php endif; ?>></div>
		</div>

		<form id="userevent-task-edit" data-cat="<?=Hash::get($task, 'UserEventCategory.id')?>"  style="margin-bottom: 20px;padding-bottom: 10px;" action="/User/editTaskEvent" method="post">
			<div class="col-sm-12 " style="padding: 0 35px;border-bottom: 2px solid #ccc;margin-bottom: 10px;">
				<input type="hidden" name="UserEvent[event_id]" value="<?=Hash::get($task, 'UserEvent.id')?>" id="invest-project-avatar"/>
				<input type="hidden" name="UserEvent[user_id]" value="<?=Hash::get($task, 'UserEvent.user_id')?>" id="invest-project-avatar"/>

				<textarea id="UserEventDescr" name="data[UserEvent][descr]" class="UserEventDescrTextarea"><?php echo Hash::get($task, 'UserEvent.descr') ?></textarea>

				<div class="row InvestProject-totals" style="margin-top:20px;">
					<div class="col-sm-4">
						<div class="form-group">
							<div class="needSum">
								<div class="input-group">
							    	<input type="number" step="0.01" min="0" class="form-control" style="border-radius: 5px 0px 0px 5px; border-right: 0;" name="UserEvent[price]" data-label="<?= __('Necessary sum') ?>" required="true" class="form-control" placeholder="<?= __('Necessary sum') ?>" value="<?=Hash::get($task, 'UserEvent.price')?>" >

								    <div class="input-group-btn">
								        <button type="button" class="btn btn-secondary dropdown-toggle" style="padding: 7px 5px 5px;background: #fff;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;border-top: 1px solid #ccc;border-radius: 0 5px 5px 0;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								          <?= $this->Money->symbolFor('USD') ?>
													<!--span class="halflings chevron-down"></span-->
								        </button>
								    </div>
							    </div>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<div class="">
								<input name="UserEvent[event_end_time]"  type="text" value="<?php echo date('Y-m-d H:i', strtotime(Hash::get($task, 'UserEvent.event_end_time'))) ?>" class="form-control " data-label="<?= __('Delivery date') ?>" placeholder="<?=$taskDurM?>" id="datetimepicker-taskevent-0"/>

								<input  name="UserEvent[duration_old]" type="hidden" value="<?=$taskDurM?>" data-label="<?= __('Delivery date') ?>" required="true" id="invest-project-reward-0-mirror"/>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<div class="">
								<select name="UserEvent[category]" style="border: 1px solid #ccc;" class="form-control " id="category-select">
									<option>
										stydffs
									</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			</div>

			<div class="col-sm-12 " style="padding: 10px 35px;">
				<div class="row">
					<!--<div class="col-sm-8" style="margin: 4px 0;">
						<div class="row" style="font-size: 11px;color: #666666;">
							<div class="col-sm-4">
								<input type="checkbox" value="1" <?php /*echo Hash::get($task,'UserEvent.external') ? 'checked' : ''; */?> name="UserEvent[external]" style="margin-right: 5px;"><label><?/*=__('Set external')*/?></label>
							</div>
						</div>
					</div>-->
					<div class="col-sm-4">
						<button class="btn edit-save col-sm-12" style="padding: 6px 12px;background-color: #FFBA4B;color: #FFF;border: none;font-weight: normal;" type="submit"><?= __('Save') ?></button>
					</div>
				</div>
			</div>

			<div style="clear: both;"></div>
		</form>
	</div>
</div>
<!-- два открытых тега. так нужно. не удалять -->
<div>
<div>

<script>
$('.popup-content-tasks .close-button').on('click',function(){
	var popup = $('.popup-back-tasks');
	var content = $('.popup-content-tasks');
	$('.container-fluid').show();
	$('body').removeClass('posSC');
	$('.wrapper-container').show().removeClass('padTop');
	popup.hide();
	content.hide();
});

var investProjectRewardCounter = 0;

$('#datetimepicker-0').datetimepicker().on('focus', function () {
	var dts = $('.datetimepicker');
	for(var i = 0; i <= (dts.length - 1);i++) {
		var dt = $(dts[i]);
		//dt.css('left', parseInt(dt.css('left')) - 50);
	}
});

$('#datetimepicker-taskevent-0').datetimepicker().on('focus', function () {
	var dts = $('.datetimepicker');
	for(var i = 0; i <= (dts.length - 1);i++) {
		var dt = $(dts[i]);
		//dt.css('left', parseInt(dt.css('left')) - 50);
	}
});
$('#approveMember .modal-header .close').on('click', function(){
	$('#approveMember').addClass('fade').hide();
});
</script>
<script>
var catArray = '';

$('.popup-edit-content-tasks .close-button').on('click',function(){
	var popup = $('.popup-back-tasks');
	var content = $('.popup-edit-content-tasks');
	$('.container-fluid').show();
	$('body').removeClass('posSC');
	$('.wrapper-container').removeClass('padTop');
	popup.hide();
	content.hide();
});

$('#editMode').on('click', function() {
	var body = $('body');
	var popup = $('.popup-back-tasks');
	var content = $('.popup-edit-content-tasks');
	var task_c = $('#userevent-task-edit').attr('data-cat');
	$('.container-fluid').hide();
	$('body').addClass('posSC');
	$('.wrapper-container').addClass('padTop');
	if(!catArray){
		$.get('/userAjax/getCategories',{},function(data){
			var data = $.parseJSON(data);
			popup.show();
			content.show();
			catArray = data;

			var category_option = '';
			for (x in catArray) {
					var cat = catArray[x].UserEventCategory;
					var act = '';
					if(cat.id == task_c){
						act = 'selected';
					}
			    category_option += '<option '+act+' value="'+cat.id+'">'+cat.title+'</option>';
			}
			$('#category-select').html(category_option);
		});
	} else {
		popup.show();
		content.show();
	}

});

add = function(){
	//   popup-content
	var body = $('body');
	var popup = $('.popup-back-tasks');
	var content = $('.popup-content-tasks');
	$.get('/userAjax/checkUserEventRequest',{},function(data){
		var data = $.parseJSON(data);
		if(data.allowed == true){
			body.append(popup);
			popup.show();
			body.append(content);
			content.show();
			$('.container-fluid').hide();
			$('.wrapper-container').hide();
		}else{
			$('#approveMember .modal-content .modal-body').html(tmpl('subscribe-for-proposals'));
			$('#approveMember').removeClass('fade').show();
		}
	});
}

var editMode = false;
var user_name = '';
var user_surname = '';
var skills = '';
var city = '';
var country = '';
var birth_day = '';
var birth_month = '';
var birth_year = '';
var phone = '';
var university = '';
var speciality = '';
var timezone = '';
var language = '';
var videourl = '';

// переключение режима редактирования
switchEditor = function() {
	if( !editMode ) {
		enableEdit();
		$('#editMode').addClass('active');
		saveLeave = false;
	} else {
		if( sameCheck() ) {
			if(confirm("<?=__('Save changes?')?>")) {
				saveSettings();
				disableEdit();
			} else {
				disableEdit();
			}
		} else {
			disableEdit();
		}
		$('#editMode').removeClass('active');
	}
};

// включение режима редактирования
enableEdit = function() {
	$('.needHide').hide();
	$('.needShow').show();
	$('#TaskTitle, #TaskDescr, #TaskPrice, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').addClass('contentEditable').addClass('needsclick');
	$('#TaskTitle, #TaskDescr, #TaskPrice, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').prop('contentEditable', true);

	user_name = $('#TaskTitle').text();
	user_surname = $('#TaskDescr').text();
	skills = $('#TaskPrice').text();

	editMode = true;

	// заморочка с классами для грёбаного iOS-а
	$('#TaskTitle, #TaskDescr, #TaskPrice, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').each( function() {
		if( $(this).text().length == 0 ) {
			$(this).removeClass('noPlaceholder');
		} else {
			$(this).addClass('noPlaceholder');
		}
	});

	$('.contentEditable').on('input', function() {
		if( $(this).text().length == 0 ) {
			$(this).removeClass('noPlaceholder');
		} else {
			$(this).addClass('noPlaceholder');
		}
	});
};

// отключение режима редактирования
disableEdit = function() {
	$('#TaskTitle, #TaskDescr, #TaskPrice, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').removeClass('contentEditable').removeClass('needsclick');
	$('#TaskTitle, #TaskDescr, #TaskPrice, .city .caption, #UniversityName, #Speciality, #phone, #YoutubeUrl').prop('contentEditable', false);
	$('.needHide').show();
	$('.needShow').hide();
	editMode = false;
	/*$('.contentEditable').off('input');*/
};

// проверка на одинаковость старой и новой версии изменений
sameCheck = function() {
	if(user_name !== $('#TaskTitle').text()) { return true; };
	if(user_surname !== $('#TaskDescr').text()) { return true; };
	if(skills !== $('#TaskPrice').text()) { return true; };

	return false;
};

//сохранение
saveSettings = function() {
	var save_user_name = $('#TaskTitle').text();
	var save_user_surname = $('#TaskDescr').text();
	var save_skills = $('#TaskPrice').text();

	var today = new Date();
	var dob = '';

	//dob = save_birth_year+'-'+save_birth_month+'-'+save_birth_day;

	var sendData = {
			UserEvent: {
				id: <?=$task['UserEvent']['id'];?>,
				title: save_user_name,
				descr: save_user_surname,
				price: save_skills
			}
		};

	$.post('/UserAjax/saveTask', {
			data: sendData,
		}, function (response) {
			location.reload();
			//disableEdit();
		}
	);
}

$('#userAvatarUpload').click(function(){
	$(this).data().submit();
});
</script>

<script type="text/x-tmpl" id="subscribe-for-proposals">
<div class="text-center">
	<h4> <?php echo __('You must upgrade your subscription to add new proposals')?> </h4>
	<p>
		<?php
			echo $this->Html->link(__("Buy More Proposals", true),
			array(
				'plugin' => 'billing',
				'controller' => 'billing_subscriptions',
				'action' => 'plans',
				'proposals'
			),
					array('class' => 'btn btn-default textIconBtn')
			);
		?>
	</p>
</div>
</script>
