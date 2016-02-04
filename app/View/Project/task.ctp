<?php
    $viewScripts = array(
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru',
        'vendor/jquery/jquery.fileupload',
        'vendor/jquery/jquery.linkify.min',
        'upload',
    );
    $viewStyles = [
        'bootstrap/bootstrap-tokenfield',
    ];
    $this->Html->css($viewStyles, array('inline' => false));
    $this->Html->script($viewScripts, array('inline' => false));

    $taskID = Hash::get($task, 'Task.id');
    $taskTitle = Hash::get($task, 'Task.title');
    $closed = Hash::get($task, 'Task.closed');
    $isManager = Hash::get($task, 'Task.manager_id') == $currUserID;
    $isAssignee = Hash::get($task, 'Task.user_id') == $currUserID;
    $isCRM = Hash::get($task, 'Task.crm') == $currUserID;

	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'view/'.Hash::get($project, 'Project.group_id')));
	$this->Html->addCrumb(Hash::get($project, 'Project.title'), array('controller' => 'Project', 'action' => 'view/'.$projectID));
	$this->Html->addCrumb($taskTitle, array('controller' => 'Project', 'action' => 'task/'.$taskID));
?>

<div class="row taskViewTitle fixedLayout">
    <div class="col-sm-3 col-sm-push-9 controlButtons">
		<?php if (!$closed && ($isProjectAdmin || $isProjectResponsible)) : ?>
			<a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'closeTask', $taskID))?>" onclick="return confirm('<?=__('Are you sure ?')?>')">
				<div class="glyphicons remove_2"></div>
				<div class="caption"><?=__('Close')?></div>
			</a>
		<?php endif; ?>
    </div>
</div>

<div class="taskViewInfo">
    <div class="deadline">
<?
    $deadline = Hash::get($task, 'Task.deadline');
?>
        <span class="glyphicons anchor"></span>
        <?=__('Deadline')?>: <?=$this->LocalDate->date($deadline)?> <?=($closed) ? __('(Сlosed)') : ''?>
        <span style="padding-left: 20px"><?=__('Project')?>: <a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', Hash::get($project, 'Project.id')))?>" class="underlink"><?=Hash::get($project, 'Project.title')?></a></span>
    </div>
</div>

<?
    if($operationsCount > 1) {
        $incomeBalance = 0;
        if($lastMonthBalance != 0) {
            $incomeBalance = ( $actualBalance / $lastMonthBalance - 1 ) * 100;
            $incomeBalance = round($incomeBalance, 2);
        }

        $currSymbol = $task['CrmTask']['currency'];
        $currSymbol = $this->Money->symbols()[$currSymbol];

        $timeStr = '';

        $timeStr = $timeInterval['days'] ? $timeStr.$timeInterval['days'].' '.__('days').' ' : '';
        $timeStr = $timeInterval['hours'] ? $timeStr.$timeInterval['hours'].' '.__('hours').' ' : '';
        $timeStr = $timeInterval['minutes'] ? $timeStr.$timeInterval['minutes'].' '.__('minutes').' ' : '';
?>
<div class="fixedLayout taskViewBalance clearfix">
    <div class="leftContent">
        <div class="item"><span class="name"><?=__('Time spent')?></span><span class="value"><?=$timeStr?></span></div>
        <div class="item"><span class="name"><?=__('Expense')?></span><span class="value"><?=$fullExpense?> <?=$currSymbol?></span></div>
        <div class="item"><span class="name"><?=__('Income')?></span><span class="value"><?=$fullIncome?> <?=$currSymbol?></span></div>
    </div>
    <div class="rightContent">
        <span class="profit"><?=__('Net Income')?>
            <span class="value"><?= ($lastMonthBalance == 0) ? '---' : $incomeBalance.'%' ?></span>
        </span>
        <span class="text"><?=__('Income & expense')?></span>
        <span class="collapseGraphic"><?=__('Show chart')?></span>
    </div>
</div>

<div class="taskViewGraphic clearfix" style="display: none">
    <!--div class="btn-group">
        <button type="button" class="btn btn-default">Расход</button>
        <button type="button" class="btn btn-default active">Доход</button>
    </div>
    <div class="rightButtons">
        <div class="btn-group">
            <button type="button" class="btn btn-default active">Месяц</button>
            <button type="button" class="btn btn-default">Квартал</button>
            <button type="button" class="btn btn-default">Год</button>
        </div>
        <div class="calandarPeriod">
            <div id="selectPeriod" class="dateTime date">
                <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
                <input type="text" class="form-control" placeholder="C" readonly="readonly">
                <input type="hidden" value="" id="selectPeriod_mirror">
            </div>
            <div id="selectPeriod1" class="dateTime date">
                <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
                <input type="text" class="form-control" placeholder="По" readonly="readonly">
                <input type="hidden" value="" id="selectPeriod_mirror1">
            </div>
        </div>
    </div>
    <img src="img/temp/graphic.jpg" alt="" /-->

    <div class="rightButtons">
        <div id="chartMonth" class="dateTime date">
            <span class="add-on"><i class="icon-th glyphicons calendar"></i></span>
            <input type="text" class="form-control" placeholder="<?=__('From')?>" readonly="readonly">
            <input type="hidden" value="" id="selectPeriod_mirror">
        </div>
    </div>

    <iframe id="chartIframe" class="chartIframe" src="/FinanceBudget/chart/<?=$taskAccount['FinanceAccount']['project_id']?>?&accountId=<?=$taskAccount['FinanceAccount']['id']?>">
    </iframe>

</div>
<?
    }
?>
<div class="taskViewInfo">
<?
    if($contractor) {
?>
    <div class="performers">
        <span class="name"><?=__('Contractor')?></span>
        <span class="info">
            <div class="contractor clearfix">
                <?php echo $this->Avatar->user($contractor, array(
                    'size' => 'thumb50x50'
                )); ?>
                <div class="description">
                    <span class="title"><?=Hash::get($contractor, 'User.full_name')?></span>
                    <!--span class="position">ЗАО «Газпром»</span-->
                </div>
            </div>
        </span>
    </div>
<?
    } else {
        // Кто назначает контрагента?
        if( $isManager || $isProjectAdmin || $isProjectResponsible ) {
?>
    <div class="performers">
        <span class="name"><?=__('Contractor')?></span>
        <span class="info">
            <?=$this->Form->input('user_id', array('options' => $aUserOptions, 'empty' => __('-- Select contractor --'), 'class' => 'formstyler', 'label' => false, 'div' => false, 'id' => 'contractorList', 'data-placeholder' => __('Contractor')))?>
        </span>
    </div>
<?
        }
    }
?>
    <div class="performers">
        <span class="name"><?=__('Manager')?>:</span>
        <span class="info">
<?
    $user = $aUsers[Hash::get($task, 'Task.manager_id')];
?>
            <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="ava">
                <?php echo $this->Avatar->user($user, array(
                    'size' => 'thumb50x50'
                )); ?>
            </a>
            <a class="underlink" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>">
                <?=$user['User']['full_name']?>
            </a>
        </span>
    </div>
    <div class="performers">
        <span class="name"><?=__('Assigned to')?>:</span>
        <span class="info">
<?
    $user = $aUsers[Hash::get($task, 'Task.user_id')];
?>
            <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="ava">
                <?php echo $this->Avatar->user($user, array(
                    'size' => 'thumb50x50'
                )); ?>
            </a>
            <a class="underlink" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>">
                <?=$user['User']['full_name']?>
            </a>
        </span>
    </div>
</div>
<?
    if( ($isManager || $isAssignee || $isProjectAdmin || $isProjectResponsible) && !$closed )    {
?>
<div class="taskViewControls">
    <button class="btn btn-default" data-toggle="modal" data-target="#userEventModal"><?=__('Add event')?></button>
<?
        if( $isManager || $isProjectAdmin )    {
?>
    <button class="btn btn-default" data-toggle="modal" data-target="#ww"><?=__('Add financial data')?></button>
<?
        }
?>
</div>
<?
    }
?>
<br />
<?=$this->element('Project/crm_task_popups', compact('task'))?>

<!-- Send message -->
<div class="submitMessage">
<?
    $user = $aUsers[$currUserID];
?>
    <?php echo $this->Avatar->user($user, array(
        'class' => 'ava',
        'size' => 'thumb100x100'
    )); ?>

    <?=$this->Form->create('ProjectEvent', array('class' => 'message'))?>
        <span id="message-files"></span>
        <div class="form-group">
            <label><?=__('Send message')?></label>
            <textarea id="message-title" class="form-control" name="data[message]"></textarea>
            <button type="button" class="btn btn-default submitBtn"><span class="submitArrow"></span></button>
        </div>
        <div class="clearfix">
            <span class="fileuploader-wrapper btn btn-default smallBtn halflings uni-paperclip">
                <input type="file" id="message-attach-upload-input" multiple data-object_type="TaskComment" data-object_id=""/>
            </span>
            <div id="progress-bar">
                <div id="progress-stats"></div>
            </div>
        </div>
    <div class="foldersAndFiles middleIcons clearfix" id="message-attach-list"></div>
    <?=$this->Form->end()?>
</div>
<!--/ Send message -->

<!-- Uploader upload file view -->
<div class="hide" id="task-tpl-file-upload">
    <a href="javascript:void(0)" class="item">
        <span class="filetype"></span>
        <div class="title"></div>
        <div class="progress">
            <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"><span class="percentage"></span></div>
        </div>
    </a>
</div>
<!-- /Uploader upload file view -->

<h3><?=__('Discussion')?></h3>
<div class="fixedLayout taskDiscussion">

<?
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
                <?php foreach ($commentsMedia[$msg_id] as $commentMedia) : ?>
					<?php
						$ext = str_replace('jpeg', 'jpg', strtolower(substr($commentMedia['ext'], 1)));
						$class = $this->File->hasType($ext) ? 'filetype ' . $ext : 'glyphicons file';
					?>
                    <?php if ($this->File->isImage($ext)) : ?>
						<a href="<?=$commentMedia['url_preview']?>" class="item" target="_blank">
							<img src="<?=$this->Media->imageUrl($commentMedia, 'thumb85x85')?>" alt="<?=$commentMedia['orig_fname']?>" class="img-responsive" data-size="<?php echo $commentMedia['size'] ?>" data-url="<?php echo $commentMedia['url_download']; ?>"/>
							<div class="title"><?= $commentMedia['orig_fname'] ?></div>
						</a>
                    <? elseif($this->File->isVideo($ext)) : ?>
							<a href="<?= $commentMedia['url_preview'] ?>" class="item video-pop-this" data-url-down="<?=$commentMedia['url_download']?>" data-converted="<?=$commentMedia['converted'];?>">
								<span class="<?= $class ?>"></span>
								<div class="title"><?= $commentMedia['orig_fname'] ?></div>
							</a>
                    <? else: ?>
                        <a href="<?= $commentMedia['url_preview'] ?>" class="item" target="_blank">
                            <span class="<?= $class ?>"></span>
                            <div class="title"><?= $commentMedia['orig_fname'] ?></div>
                        </a>
                    <? endif; ?>
                <? endforeach; ?>
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
//            if($event['ProjectEvent']['event_type'] == ProjectEvent::TASK_COMMENT && !isset($commentsMedia[$msg_id]) && $user['User']['id'] == $currUser['User']['id']) {
            if($event['ProjectEvent']['event_type'] == ProjectEvent::TASK_COMMENT && $user['User']['id'] == $currUser['User']['id']) {
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

<div id="editComment" class="modal fade" tabindex="-1" role="dialog">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="glyphicons circle_remove" data-dismiss="modal"></span>
                <h4><?=__('Edit message')?></h4>
                <form id="msgEditForm">
                    <?=$this->Form->hidden('event_id')?>
                    <div class="form-group">
                        <?=$this->Form->input('message', array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'required' => 'required'))?>
                    </div>
                    <div class="clearfix">
                        <div id="postEditComment" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function getCommentMediaIndex(src) {
    var index = 0;
    $('.taskDiscussion .foldersAndFiles img').each(function(){
        if(src == $(this).attr('src'))
            return false;
        else
            index++;
    });
    return index;
}
$(document).ready(function(){
    var existsAttachFiles = false;
    $('.item .msgText').linkify({
        tagName: 'a',
        target: '_blank',
        newLine: '\n',
        linkClass: 'underlink',
        linkAttributes: null
    });

    var image_list = [];
    var comment_media_src;
    $('.taskDiscussion .foldersAndFiles img').each(function(){
        var comment_media_src =  $(this).data('url');
        $size   = $(this).data('size').split('x'),
            $width  = $size[0],
            $height = $size[1];
        var item = {
            src : comment_media_src,
            w   : $width,
            h   : $height
        };
        image_list.push(item);
    });
    var $pswp = $('.pswp')[0];

    $('.foldersAndFiles  a.item').click( function(event) {
        if($(this).children('img').length > 0) {
            event.preventDefault();
            var src = $(this).children('img').attr('src');
            var $index = getCommentMediaIndex(src);
            var options = {
                index: $index,
                bgOpacity: 0.7,
                showHideOpacity: true,
                shareEl: false
            };

            // Initialize PhotoSwipe
            var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, image_list, options);
            lightBox.init();
            $('#header, #chatLink, .planet, .logo, .chat-window-content.user-list-scroll').hide();
            lightBox.listen('close', function() {
                $('#header, #chatLink, .planet, .logo, .chat-window-content.user-list-scroll').show();
            });
        }
    });

    $('#ProjectEventTaskForm textarea').bind('keypress', function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $('#ProjectEventTaskForm').submit();
        }
    });

    $('#message-title').bind('keypress', function(event) {
        if (event.ctrlKey && event.keyCode == 13) {
            var value = $('#ProjectEventTaskForm textarea').val();
            $('#ProjectEventTaskForm textarea').val(value+'\n');
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

    $('.submitBtn').on('click', function(event) {
        var parent = $(this).parents('.submitMessage');
        if(!existsAttachFiles && $('textarea', parent).val().length < 1 ) {
            alert("<?=__('Message can not be empty')?>");
            return false;
        }
        parent = $(this).parents('form').submit();
    });

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

    $("#chartMonth").on("change.dp",function (e) {
        var time = $(e.target).val();
        $('#chartIframe').attr('src', '/FinanceBudget/chart/<?=$taskAccount['FinanceAccount']['project_id']?>?fromMonth='+time+'&accountId=<?=$taskAccount['FinanceAccount']['id']?>');
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
