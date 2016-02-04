<br>
<div id="taskMsg-<?= $task['Task']['id'] ?>" class="submitMessage">
<?
//echo '<pre>';

//var_dump($task);
//var_dump($subproject);
//var_dump($project);
//var_dump($group);
//var_dump($messages);
//var_dump($files);
//var_dump($members);
//echo '<BR>';
//var_dump($aEvents);

//echo '</pre>';
	$user = $aUsers[$currUserID];
?>
	<?php echo $this->Avatar->user($user, array(
		'class' => 'ava',
		'size' => 'thumb100x100'
	)); ?>
	<?=$this->Form->create('ProjectEvent', array('class' => 'message'))?>
		<span id="message-files"></span>
		<div class="form-group">
			<textarea id="message-title" class="form-control" name="data[message]"></textarea>
			<input type="hidden" name="data[task_id]" value="<?= $task['Task']['id'] ?>">
			<button type="button" class="btn btn-default submitBtn"><span class="submitArrow"></span></button>
		</div>
		<div class="clearfix">
			<input type="file" id="message-attach-upload-input" multiple data-object_type="TaskComment" data-object_id="" style="display: none"/>
			<label class="btn btn-default smallBtn" style="float: left;" for="projectFileChoose" id="message-attach-upload-btn"><span class="halflings uni-paperclip"></span></label>

			<label class="labelMessage"><?=__('To send message press Enter')?></label>
			<div id="progress-bar">
				<div id="progress-stats"></div>
			</div>
		</div>
		<div class="foldersAndFiles middleIcons clearfix" id="message-attach-list"></div>
		<div class="hide" id="task-tpl-file-upload">
			<a href="javascript:void(0)" class="item">
				<span class="filetype"></span>
				<div class="title"></div>
				<div class="progress">
					<div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"><span class="percentage"></span></div>
				</div>
			</a>
		</div>
	<?=$this->Form->end()?>
</div>

<div class="fixedLayout taskDiscussion">
<?
	$aEvents = array_reverse($aEvents);
	foreach($aEvents as $event) {
		$user = $aUsers[$event['ProjectEvent']['user_id']];
		if (in_array($event['ProjectEvent']['event_type'], array(ProjectEvent::TASK_CREATED, ProjectEvent::TASK_CLOSED, ProjectEvent::TASK_COMMENT, ProjectEvent::FILE_ATTACHED))) {
?>

	<div class="item" id="event-<?=$event['ProjectEvent']['id']?>">
		<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="rate-0 ava">
			<?php echo $this->Avatar->user($user, array(
				'size' => 'thumb100x100'
			)); ?>
		</a>
		<div class="description">
<?
			switch ($event['ProjectEvent']['event_type']) {
				case ProjectEvent::TASK_CREATED:
					echo __('Task was created');
					break;

				case ProjectEvent::TASK_CLOSED:
					echo __('Task was closed');
					break;

				case ProjectEvent::TASK_COMMENT:
					$msg_id = $event['ProjectEvent']['msg_id'];
?>
			<span class="msgText"><?=$messages[$msg_id]['message']?></span>
			<? if (isset($commentsMedia[$msg_id])) { ?>
			<div class="foldersAndFiles middleIcons clearfix">
				<? foreach ($commentsMedia[$msg_id] as $commentMedia) {
					$ext = str_replace('jpeg', 'jpg', strtolower(substr($commentMedia['ext'], 1)));
					$class = $this->File->hasType($ext) ? 'filetype ' . $ext : 'glyphicons file';
				?>
					<? if ($this->File->isImage($ext)) {?>
					<a href="<?=$commentMedia['url_preview']?>" class="item" target="_blank">
						<img src="<?=$this->Media->imageUrl($commentMedia, 'thumb85x85')?>" alt="<?=$commentMedia['orig_fname']?>" class="img-responsive" />
						<div class="title"><?= $commentMedia['orig_fname'] ?></div>
					</a>
					<? } else { ?>
					<a href="<?= $commentMedia['url_preview'] ?>" class="item" target="_blank">
						<span class="<?= $class ?>"></span>
						<div class="title"><?= $commentMedia['orig_fname'] ?></div>
					</a>
					<? } ?>
				<? } ?>
			</div>
			<? } ?>
<?
					break;

				case ProjectEvent::FILE_ATTACHED:
					$file = $files[$event['ProjectEvent']['file_id']];
					$fType = Hash::get($file, 'file');
					if( $fType == 'image' ) {
?>
							<a href="<?=$file['url_preview']?>">
								<img src="<?=$this->Media->imageUrl($file, 'thumb85x85')?>" alt="<?=Hash::get($file, 'orig_fname')?>" class="img-responsive" />
							</a>
<?
					} else {
?>
							<a href="<?=$file['url_preview']?>">
								<figure>
									<div style="font-size:80px; display:block" class="filetype <?=str_replace('.', '', $file['ext'])?>"></div><span><?=$file['orig_fname']?></span>
								</figure>
							</a>
<?
					}
					break;
			}
?>
			<div class="clearfix">
				<div class="time"><?=$this->LocalDate->dateTime($event['ProjectEvent']['created'])?></div>
<?
			if($event['ProjectEvent']['event_type'] == ProjectEvent::TASK_COMMENT && !isset($commentsMedia[$msg_id]) && $user['User']['id'] == $currUser['User']['id']) {
?>
				<a href="javascript: void(0)" class="reply remove" data-id="<?=$event['ProjectEvent']['id']?>"><?=__('Remove')?></a>
				<a href="javascript: void(0)" class="reply edit" style="margin-right: 20px;" data-id="<?=$event['ProjectEvent']['id']?>"><?=__('Edit')?></a>
<?
			}
?>
			</div>
		</div>
	</div>
<?
		}
	}
?>
</div>
<br /><br />
<!-- Send message -->


<script>
$('#taskMsg-<?= $task['Task']['id'] ?> .submitBtn').off('click');
$('#taskMsg-<?= $task['Task']['id'] ?> .submitBtn').on('click', function(event) {
	Timeline.lEnableUpdate = false;
	var parent = $(this).parents('.submitMessage');
	/*if( $('textarea', parent).val().length < 1 ) {
		alert("Message can not be empty");
		Timeline.lEnableUpdate = true;
		return false;
	}*/
	$.post( "/ProjectAjax/addComment.json", $(this).parents('form').serialize(), function(response) {
		$.get( "/ProjectAjax/comments/<?= $task['Task']['id'] ?>", function(response) {
			$('#taskCommentsMsg-<?= $task['Task']['id'] ?> .comment-block').html(response);
			Timeline.lEnableUpdate = true;
		});
	});
});


$('textarea').autosize();

$(document).ready(function(){
	var existsAttachFiles = false;

	$('#ProjectEventTaskForm textarea').bind('keypress', function(event) {
		if (event.which == 13) {
			event.preventDefault();
			$('#ProjectEventTaskForm').submit();
		}
	});

	(function($) {
		$(function() {
			$('input.attachFile').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});
		});
	})(jQuery);

	$('#message-title').autosize();

	$('#message-title').on('keyup copy cut paste change', function() {
		$('#message-title').trigger('autosize.resize');
		Chat.fixPanelHeight();
	});

	/*$('.submitBtn').on('click', function(event) {
		var parent = $(this).parents('.submitMessage');
		if(!existsAttachFiles && $('textarea', parent).val().length < 1 ) {
			alert("<?=__('Message can not be empty')?>");
			return false;
		}
		parent = $(this).parents('form').submit();
	});
*/
	$('.collapseGraphic').on('click', function(event) {
		$('.taskViewGraphic').toggle();

		if( $('.taskViewGraphic:visible').length > 0 ) {
			$(this).text('<?=__('Hide chart')?>');
		} else {
			$(this).text('<?=__('Show chart')?>');
		}
	});

	$('#contractorList').change(function() {
		$.post('<?=$this->Html->url(array('controller' => 'Project', 'action' => 'addTaskContractor'))?>', {data: {
					 user_id: $(this).val(),
					 curr_user_id: '<?=$currUserID?>',
					 task_id: '<?=$task['Task']['id']?>'
		}
		}, function(response){
			location.reload();
		});
	});
<?
if(Configure::read('Config.language') == 'rus'){
	$lang = 'ru';
}else{
	$lang = 'en';
}
?>;
	$('#chartMonth').datetimepicker({
		language:"<?=$lang?>",
		format:"yyyy-mm",
		weekStart: 1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 3,
		minView: 3
	});


// task message attach file uploader
	var messageAttachCounter = 0;
	$('#message-attach-upload-btn').click(function () {
		$('#message-attach-upload-input').click();
	});
	$('#message-attach-upload-input').fileupload({
		url: mediaURL.upload,
		dataType: 'json',
		done: function (e, data) {
			var file = data.result.files[0];
            if(file.hasOwnProperty('error') && file['error'] == 'File Storage limit exceeded') {
                var temp = $('#message-attach-list');
                temp.empty().html('<div style="color: red;">' + file['error'] + '</div>');
                setTimeout(function() {
                    temp.fadeOut('slow', function() {
                        temp.remove();
                    })
                }, 3000);
                return false;

            }
			file.object_type = $(data.fileInput).data('object_type');
			file.object_id = $(data.fileInput).data('object_id');
			$.post(mediaURL.move, file, function (response) {
				console.log(response);
				var mediaId = response.data[0].Media.id;
				$('#message-files').append('<input type="hidden" name="data[media][]" value="' + mediaId + '" />');
				existsAttachFiles = true;
				if (--messageAttachCounter == 0) {
					$('#message-attach-list').find('.progress').hide();
				}
			});
		},
		add: function (e, data) {
			var ul = $('#message-attach-list');
			var fileType = data.files[0].name.split('.').pop().toLowerCase().replace('jpeg', 'jpg');
			var $tpl = $('#task-tpl-file-upload .item').clone();
			var fileClass = Cloud.hasType(fileType) ? 'filetype ' + fileType : 'glyphicons file';
			$tpl.find('.filetype').attr('class', fileClass);
			$tpl.find('.title').text(data.files[0].name);
			data.context = $tpl.prependTo(ul);
			messageAttachCounter++;
			data.submit();
		},
		progress: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			data.context.find('.percentage').html(progress + '%');
			data.context.find('.progress-bar').attr('style', 'width: ' + progress + '%');
		}
	});

	$('.taskDiscussion .edit').on('click', function(){
		$('#event_id').val( $(this).data('id') );

		var msg = $('#event-'+$(this).data('id')+' .msgText').text();
		console.log(msg);

		$('#message').val( msg );
		$('#editComment').modal('show');
	})

	$('#postEditComment.loadBtn').on('click', function() {
		$(this).removeClass('loadBtn');
		$.post( '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'editComment'))?>.json', $( "#msgEditForm" ).serialize(),
			function (response) {
				console.log(response);

				obj = $.parseJSON(response);
				if( obj !== null ) {
					if(obj.status == "ERROR") {
						$(this).removeClass('loadBtn').addClass('loadBtn');
						alert( obj.data );
					}
					if(obj.status == "OK") {
						$('#event-'+$('#event_id').val()+" .msgText").text($('#message').val());
						$('#editComment').modal('hide');
						$('#postEditComment').removeClass('loadBtn').addClass('loadBtn');
					}
				}
			}
		);
	})

	$('.taskDiscussion .remove').on('click', function() {
		if(!confirm('Are you sure ?')) {
			return;
		}
		var eid = $(this).data('id');
		$.post( '<?=$this->Html->url(array('controller' => 'Project', 'action' => 'removeComment'))?>.json', {data: {event_id: eid} },
			function (response) {
				console.log(response);

				obj = $.parseJSON(response);
				if( obj !== null ) {
					if(obj.status == "ERROR") {
						alert( obj.data );
					}
					if(obj.status == "OK") {
						$('#event-'+eid).remove();
					}
				}

			}
	    );
	})

	$('#message').on('keyup keydown change', function(){
		if( $(this).val().length == 0 ) {
			$('#postEditComment').removeClass('disabled').addClass('disabled');
		} else {
			$('#postEditComment').removeClass('disabled');
		}
	})
});
</script>
