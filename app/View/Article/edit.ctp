<?
	$id = $this->request->data('Article.id');
	$type = $this->request->data('Article.type');
	if(!$type) {
		$type = 'text';
	}
	$group_id = $this->request->data('Article.group_id');
	if( isset($this->params['named']['group_id']) ) {
		$group_id = $this->params['named']['group_id'];
	}
	$published = $this->request->data('Article.published');

	if ($id) {
		$this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
		$viewScripts = array(
			'vendor/jquery/jquery.Jcrop.min',
			'vendor/jquery/jquery.ui.widget',
			'vendor/jquery/jquery.iframe-transport',
			'vendor/jquery/jquery.iframe-transport',
			'vendor/jquery/jquery.fileupload',
			'vendor/exif',
			'/table/js/format',
			'upload'
		);
		$this->Html->script($viewScripts, array('inline' => false));
	}

	$this->Html->css('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css', array('inline' => false));
	$this->Html->css('/Froala/css/froala_editor.min.css', array('inline' => false));
?>



<?=$this->Form->create('Article', array('id' => 'ArticleEditForm'))?>	
	<div class="row projectViewTitle">
		<div class="col-sm-6 col-sm-push-6 controlButtons">
			<button type="submit" id="submit" class="btn btn-primary" type="button"><?=__('Save')?></button>
<?
	if (!$id) {
?>	
			<span id="publish" class="btn btn-default">
				<?=__('Publish')?>
			</span>
<?
	}
	if ($id) {
?>
			<!--a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', $id))?>">
				<?=__('View article')?>
			</a-->

			<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'changePublish', $id))?>">
				<?=($published) ? __('Unpublish') : __('Publish')?>
			</a>
<?
		echo $this->Html->link(
			'<span class="glyphicons bin"></span>',
			array('controller' => 'Article', 'action' => 'delete', $id),
			array('class' => 'btn btn-default smallBtn', 'escape' => false),
			__('Are you sure to delete this record?')
		);
	}
?>
		</div>
		<div class="col-sm-6 col-sm-pull-6">
			<h1><?=($id) ? __('Edit article') : __('Create article')?></h1>
		</div>
	</div>
	<br/>
	<br/>
	
	
	
	
	
	<div class="row" style="position: relative;">	
<?
	$imgSrc = $id ? $this->Media->imageUrl($this->request->data('ArticleMedia'), 'thumb200x200') : "/img/no-article-photo.png";
	$imgId = $id ? 'Article'.$id : 'TempArticle'.$tempArticle;
	$imgType = $id ? 'Article' : 'TempArticle';
	$imgData = $id ? $id : $tempArticle;
	$imgMediaId = $id ? $this->request->data('ArticleMedia.id') : '';
?>
		<div class="baseInfoRow" style="min-width: 200px; float: right;">
			<div class="leftFormBlock" style="max-width: 230px; padding: 0 15px">	
				<label><?=__('Article announce image')?></label>
				<div class="avatar-img-<?=$imgType?>">
					<img id="<?=$imgType.$imgData?>" src="<?=$imgSrc?>" alt="" class="img-responsive"  data-resize="thumb200x200" data-media_id="<?=$imgMediaId?>" style="cursor: pointer"/>
				</div>

				<div class="inputFile">
					<input class="filestyle fileuploader" type="file" data-object_type="<?=$imgType?>" data-object_id="<?=$imgData?>" data-progress_id="progress-Article<?=$imgData?>" accept="image/*"/>
				</div>

				<div class="progress" id="progress-Article<?=$imgData?>" style="height: 0">
					<div class="progress-bar progress-bar-info" role="progressbar">		
						<span id="progress-stats"></span>
					</div>
				</div>
			</div>		
		</div>
		
		<div style="box-sizing: border-box; margin-right: 260px; height: 100%">
			<div class="form-group">
				<?=$this->Form->input('title', array('placeholder' => __('Article title').'...', 'label' => __('Article title'), 'class' => 'form-control'))?>
			</div>
		</div>
		
		<div id="settingBtns" style="box-sizing: border-box; width: 65%; position: absolute; left: 0; margin-bottom: 0;">
			<div class="form-group noBorder" style="padding-bottom: 0;">
	<? if (!$id) { ?>		
				<div class="btn-group pull-left" style="padding-right: 50px;">
					<button type="button" id="textArticle"  class="btn btn-default active"><?=__('Article')?></button>
					<button type="button" id="videoArticle" class="btn btn-default"><?=__('Video-article')?></button>
				</div>
	<? } ?>
				<!--label><?=__('Article section')?></label-->
				<?=$this->Form->input('cat_id', array('options' => $aCategoryOptions, 'placeholder' => __('Article section').'...', 'label' => false, 'class' => 'formstyler'))?>
			</div>
		</div>
	</div>
<?
		if( isset($group_id) )
		{
			echo $this->Form->hidden('group_id', array('value' => $group_id));
		}
?>
	<?=$this->Form->hidden('published')?>
	<?=$this->Form->hidden('type', array('value' => $type))?>

	<div id='textEditor'>
		<?=$this->Form->input('body', array('label' => false, 'id' => 'articleBody', 'class' => 'hide', 'placeholder' => __('your article taxt here...'), 'type' => 'textarea'))?>
	</div>
	
	<div id="urlEditor" class="form-group" >
		<label><?=__('Video')?></label>
		<div class="input-group">
			<div class="input-group-addon glyphicons facetime_video"></div>
			<?=$this->Form->input('video_url', array('label' => false, 'class' => 'form-control', 'placeholder' => 'http://youtube.com...'))?>
		</div>
	</div>

	<br/>
	<br/>
	
<?=$this->Form->end()?>

<style type="text/css">
	.redactor-toolbar { z-index: 1!important; }
</style>

<script>
$(document).ready(function(){
	$('#settingBtns').css('top', 215+$('.baseInfoRow label').height());
	
	// redactor image-uploader ---------
	$('#redactor-toolbar-0').append('<li> <a href="javascript:;" id="imageAddTest" title="Insert Image" tabindex="-1" class="re-icon re-image"></a><input type="file" id="redactorImageUpload" multiple data-object_type="UserMedia" data-object_id="<?= $currUserID ?>" style="display: none"/> </li>');
	
	$('#imageAddTest').click(function () {
		$('#redactorImageUpload').click();
	});
	$('#redactorImageUpload').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			
			$.post(mediaURL.move, file, function (response) {
				var imgUrl = response.data[0].Media.url_download;
				var imgName = response.data[0].Media.orig_fname;
				$('.redactor-editor').append('<p><img src="' + imgUrl + '" alt="' + imgName + '"></p>');
				$('.redactor_box.redactor').redactor('code.sync');
			});
		},
		add: function (e, data) {
			var file = data.files[0];
			var filetype = file.type;
			if(filetype != 'image/gif' && filetype != 'image/png' && filetype != 'image/jpg' && filetype != 'image/jpeg') { 
				alert('WRONG IMAGE FILE: ' + filetype); 
				return false; 
			}
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			console.log(progress + '%');
		}
	});	
	
	var type = '<?=$type?>';
	
	if(type == 'text') {
		$('#urlEditor').hide();
		$('#textEditor').show();
	} else {
		$('#urlEditor').show();
		$('#textEditor').hide();
	}
	
	$('#publish').click(function() {
		$('#ArticlePublished').val('1');
		$('#submit').trigger('click');
	});
	
	$('#submit').on('click', function(e) {
		if($('#ArticleTitle').val().length < 3) {
			$('#ArticlePublished').val('0');
			$('#ArticleTitle').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Title must be at least 3 characters')?>" });
			$('#ArticleTitle').popover('show');
			e.preventDefault();
			return false;
		}
	});
	
	$('#textArticle').click(function() {
		$('#videoArticle').removeClass('active');
		$('#textArticle').removeClass('active').addClass('active');
		$('#urlEditor').hide();
		$('#textEditor').show();
		$('#ArticleType').val('text');
	});
	
	$('#videoArticle').click(function() {
		$('#textArticle').removeClass('active');
		$('#videoArticle').removeClass('active').addClass('active');
		$('#urlEditor').show();
		$('#textEditor').hide();
		$('#ArticleType').val('video');
	});
		
	$('#ArticleEditForm').submit(function() {
		var d = new Date( $('#UserBirthday').val() );	
		var valid = true;
		
		//Валидация ссылки на youtube
		if( (type == 'video') && ( !IsYoutubeUrl($('#ArticleVideoUrl').val()) ) ) {
			$('#ArticleVideoUrl').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Invalid video url. Leave blank or insert valid youtube url')?>" });
			$('#ArticleVideoUrl').popover('show');
			return false;
		}
	});
	
	function IsYoutubeUrl( url ) {
		var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		/*			|	  protocol    |	subdomain |		  domain name		|						URI with video						 |*/		
		return regex.test(url);
	};
	
	$('#ArticleEditForm').click(function(){
		$('#ArticleEditForm').popover('destroy');
	});
	
	$('.leftFormBlock img').on('click', function(){
		$('.fileuploader').trigger('click');
	})
	
	$('input').on('click', function(){
		$(this).popover('destroy');
	})
});	
	
//Text editor
	moveImage = {};
	
	$(function() {
		$('#articleBody').editable({
			inlineMode: false,
			key: '<?=Configure::read('froalaEditorKey')?>',
			imageUploadURL: mediaURL.froalaUpload,
            minHeight: 300
		})		
		
		$('#articleBody').removeClass('hide');
		
		$('#articleBody').on('editable.beforeImageUpload', function (e, editor, images) {
			moveImage = {};
			
			moveImage = {
				type: images[0].type,
				name: images[0].name,
				size: images[0].size,
				object_id: '<?=$currUserID?>',
				object_type: 'UserMedia'
			}
			
		});
		
		$('#articleBody').on('editable.afterImageUpload', function (e, editor, response) {			
			moveImage.url = '//konstrukt.dev' + $.parseJSON(response).link;
			console.log(moveImage);
			var ret = '123';
			$.ajax({
				type: "POST",
				url: mediaURL.move,
				async: false,
				data: moveImage,
				success: function(response) {
					console.log('ajax');
					console.log(response);
					console.log('------------------------');
					if( response.data[0].Media.orig_w < 1000 ) {
						ret = '{"link": "' + response.data[0].Media.url_download + '" }';
					} else {
						var url = response.data[0].Media.url_img;
						url = url.replace('noresize', '1000x');
						ret = '{"link": "' + url + '" }';
					}
				},
				error: function() {
					ret = 'error'
				}
			});
			console.log(ret);
			return ret;			
		});
		
		$('#articleBody').on('editable.imageError', function (e, editor, error) {
			// Custom error message returned from the server.
			if (error.code == 0) { console.log('error 0: Custom error message returned from the server'); }
			// Bad link.
			else if (error.code == 1) { console.log('error 1: Bad link'); }
			// No link in upload response.
			else if (error.code == 2) { console.log('error 2: No link in upload response'); }
			// Error during file upload.
			else if (error.code == 3) { console.log('error 3: Error during file upload'); }
			// Parsing response failed.
			else if (error.code == 4) { console.log('error 4: Parsing response failed'); }
			// File too text-large.
			else if (error.code == 5) { console.log('error 5: File too text-large'); }
			// Invalid file type.
			else if (error.code == 6) { console.log('error 6: Invalid file type'); }
			// File can be uploaded only to same domain in IE 8 and IE 9.
			else if (error.code == 7) { console.log('error 7: File can be uploaded only to same domain in IE 8 and IE 9'); }
      })
	});
	
</script>
