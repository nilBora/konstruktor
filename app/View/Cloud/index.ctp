<?php
	/* Cloud styles were moved to file css/cloud/cloud-index.css */

   $controller = array(
       'controller' => 'Cloud',
       'action' => 'index'
    );
    $aCloud = $result['files']['aCloud'];
    $aClouds = $result['files']['aClouds'];
    $this->Html->script(array('cloud-manager-mover', 'cloud-manager'), array('inline' => false));
    $this->Html->script('vendor/bootstrap-tokenfield', array('inline' => false));
    $this->Html->css('bootstrap/bootstrap-tokenfield', null, array('inline' => false));
    $this->Html->css('cloud/cloud-index', null, array('inline' => false));
//    $this->Html->css('photoswipe/photoswipe', null, array('inline' => false));
//    $this->Html->css('photoswipe/default-skin/default-skin', null, array('inline' => false));
//   $this->Html->script('photoswipe/photoswipe', array('inline' => false));
//   $this->Html->script('photoswipe/photoswipe-ui-default', array('inline' => false));
    $cloudManagerView = $view ? $view : 'big';
    $cloudIconsClass = $cloudManagerView.'Icons';

    $viewIcon = '';

    switch ($cloudManagerView) {
        case 'big':
            $viewIcon = "show_big_thumbnails";
            break;
        case 'middle':
            $viewIcon = "show_thumbnails";
            break;
        case 'small':
            $viewIcon = "show_thumbnails_with_lines";
            break;
        default:
            $viewIcon = "show_big_thumbnails";
    }
?>
<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Cloud'), array('controller' => 'Cloud', 'action' => 'index'));
   if (__($aCloud['Cloud']['parent_id'])) {
   		$this->Html->addCrumb('...', array('controller' => 'Cloud', 'action' => 'index/'.$aCloud['Cloud']['parent_id']));
   }
   if (__($aCloud['Cloud']['name'])) {
   		$this->Html->addCrumb(__($aCloud['Cloud']['name']), array('controller' => 'Cloud', 'action' => 'index'));
   }
?>
<?php /*
	<h3 style="width: 50%; float: left; margin: 35px 0 11px 0">
	<?
		if ($id) {
			if(isset($result['files']['flag_shared'])) {
				if(isset($result['files']['share_back']))
					$link = array('');
				else
					$link = array('shared');
			}
			else
				$link = array($aCloud['Cloud']['parent_id']);
	?>
		<a href="<?=$this->Html->url(array_merge($controller,$link))?>" class="glyphicons chevron-left"></a><span id="cloud-manager-path"><?=__($aCloud['Cloud']['name'])?></span>
	<?
		} else {
			echo '<span id="cloud-manager-path">'.__('File Manager').'</span>';
		}
	?>
	</h3>
*/ ?>

<a href="<?=$this->Html->url(array('controller' => "cloud", 'action' => "usage"))?>" class="progress btn-group usage-progress" style="float: right; margin-top: 35px;">
    <div class="progress-bar progress-bar-info progress-bar-striped active danger" role="progressbar" aria-valuenow="<?php echo !empty($storage_stats)?$storage_stats['usage_percent']:'100';?>"
         aria-valuemin="0" aria-valuemax="100" style="width: <?php echo !empty($storage_stats)?$storage_stats['usage_percent']:'100';?> %;">
        <?php echo !empty($storage_stats)?$storage_stats['usage_percent']:'100';?>%
    </div>
</a>

<div class="clearfix"></div>
<br>

<div id="create-tooltip-content" style="display: none;">
    <span type="button" class="tooltip-select" data-toggle="modal" data-target="#createFolderFromManager">
        <span class="glyphicons folder_closed"></span><?=__('Create folder')?>
    </span>
    <a class="tooltip-select" href="<?php echo $this->Html->url(array('controller'=>'Cloud','action'=>'documentEdit'))?>">
        <span class="glyphicons file"></span><?=__('Create document')?>
    </a>
</div>

<div id="view-tooltip-content" style="display: none;">
    <span class="tooltip-select<?=$cloudManagerView == 'big' ? ' active' : ''?>" data-view="big">
		<span class="glyphicons show_big_thumbnails"></span><?=__('Big tiles')?></span>
    <span class="tooltip-select<?=$cloudManagerView == 'middle' ? ' active' : ''?>" data-view="middle">
		<span class="glyphicons show_thumbnails"></span><?=__('Small tiles')?></span>
    <span class="tooltip-select<?=$cloudManagerView == 'small' ? ' active' : ''?>" data-view="small">
		<span class="glyphicons show_thumbnails_with_lines"></span><?=__('Table view')?></span>
</div>

<div id="sort-tooltip-content" style="display: none;">
    <span data-sort="" class="tooltip-select<? if(!$sort){ ?> active<? } ?>"><?= __('By default') ?></span>
    <span data-sort="name" class="tooltip-select<? if($sort == 'name'){ ?> active<? } ?>"><?= __('By name') ?></span>
    <span data-sort="created" class="tooltip-select<? if($sort == 'created'){ ?> active<? } ?>"><?= __('By date') ?></span>
    <span data-sort="modified" class="tooltip-select<? if($sort == 'modified'){ ?> active<? } ?>"><?= __('By updated date') ?></span>
    <span data-sort="orig_fsize" class="tooltip-select<? if($sort == 'orig_fsize'){ ?> active<? } ?>"><?= __('By size') ?></span>
    <span data-sort="ext" class="tooltip-select<? if($sort == 'ext'){ ?> active<? } ?>"><?= __('By type') ?></span>
</div>

<div id="path-tooltip-content" style="display: none;">
<?
    // start with an empty $right stack
    $tree = array();

    $icon = '';
    // display each row
    $icon = (isset($id) ? '' : '<span class="glyphicons chevron-right"></span>');
    echo '<a href="/Cloud/index" class="tooltip-select'.(isset($id) ? '' : ' active').'">'.$icon.__('My files')."</a>";
    foreach($folders as $row) {
        $class = $row['Cloud']['id'] == $id ? ' active' : '';
        $icon = ((isset($id) && $row['Cloud']['id'] == $id) ? '<span class="glyphicons chevron-right"></span>' : '');
        if(!$tree) {
            echo '<a href="/Cloud/index/'.$row['Cloud']['id'].'" class="tooltip-select'.$class.'">'.$icon.str_repeat('- ',count($tree)+1).$row['Cloud']['name']."</a>";
            array_push($tree, array('lft' => $row['Cloud']['lft'], 'rght' => $row['Cloud']['rght']));
        } else {
            if($tree[count($tree)-1]['lft'] < $row['Cloud']['lft'] && $tree[count($tree)-1]['rght'] > $row['Cloud']['rght']) {
                echo '<a href="/Cloud/index/'.$row['Cloud']['id'].'" class="tooltip-select'.$class.'">'.$icon.str_repeat('- ',count($tree)+1).$row['Cloud']['name']."</a>";
                array_push($tree, array('lft' => $row['Cloud']['lft'], 'rght' => $row['Cloud']['rght']));
            } else {
                do {
                    array_pop($tree);
                } while (isset($tree[count($tree)-1]['rght']) && ($tree[count($tree)-1]['rght'] < $row['Cloud']['rght']) && (count($tree) != 0));
                echo '<a href="/Cloud/index/'.$row['Cloud']['id'].'" class="tooltip-select'.$class.'">'.$icon.str_repeat('- ',count($tree)+1).$row['Cloud']['name']."</a>";
                array_push($tree, array('lft' => $row['Cloud']['lft'], 'rght' => $row['Cloud']['rght']));
            }
        }
    }
    echo '<hr>';
    $icon = ((strpos($_SERVER['REQUEST_URI'], 'shared') !== false) ? '<span class="glyphicons chevron-right"></span>' : '<span class="glyphicons group"></span>');
    echo '<a href="/Cloud/index/shared" class="tooltip-select'.((strpos($_SERVER['REQUEST_URI'], 'shared') == false) ? '' : ' active').'">'.$icon.__('Shared with me')."</a>";
?>
</div>

<div class="fileStorage clearfix">
    <div class="settings clearfix">
        <span id="create-btn" class="btn btn-default"><?=__('Create')?></span>
        <span class="fileuploader-wrapper btn btn-default"><?=__('Upload')?>
			<input type="file" id="cloud-manager-upload-input" multiple data-object_type="Cloud" data-object_id="<?= $id ?>"/>
		</span>

        <div class="rightButtons">

            <button type="button" class="btn btn-default smallBtn" data-toggle="modal" data-target="#share-selected">
				<span class="glyphicons user_add"></span>
			</button>
            <button type="button" class="btn btn-default smallBtn" id="cloud-manager-upload" data-url="">
				<span class="glyphicons disk_save"></span>
			</button>
            <button type="button" class="btn btn-default smallBtn" id="cloud-manager-move" data-what="" data-where="">
				<span class="glyphicons file_import"></span>
			</button>

            <form method="get" id="cloud-manager-query-form" style="display: inline">
                <div class="btn btn-default smallBtn" role="group" aria-label="..." id="cloud-manager-sort">
					<span class="glyphicons sort-by-alphabet"></span>
				</div>
                <div class="btn btn-default smallBtn" role="group" aria-label="..." id="cloud-manager-view">
					<span class="glyphicons <?=$viewIcon?>"></span>
				</div>

                <input name="view" type="hidden" value="<?= $cloudManagerView ?>">
                <input name="sort" type="hidden" value="<?= $sort ?>">
            </form>

            <button type="button" class="btn btn-default smallBtn" id="cloud-manager-delete" data-id="" data-type="">
				<span class="glyphicons bin"></span>
			</button>
        </div>
    </div>

    <div class="foldersAndFiles <?= $cloudIconsClass ?> <?php echo (!empty($id) && $id == 'shared')?'shared-section':'';?>  clearfix" id="cloud-manager-list">
        <?= $this->element('cloud_manager_list', array('typeView' => $cloudManagerView)) ?>
    </div>
</div>

<div class="show-after-upload">
   <div>
	   <span>X</span>
	   <p class="title"><?= __('Video has been successfully added to the site and soon will be available');?></p>
   </div>
</div>
<script type="text/javascript">
   $('.show-after-upload span').on('click', function(){
	  $(this).closest('.show-after-upload').fadeOut(400);
   });
</script>
<div class="modal fade" id="share-selected">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <span class="glyphicons circle_remove" style="cursor: pointer;" data-dismiss="modal"></span>
                <h4 class="modal-title share-title"><?= __('File Sharing') ?></h4>
            </div>
            <div class="modal-body">

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <?= __('Provide access by link') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <?= $this->Form->create('Share', array('url' => array('controller' => 'CloudAjax', 'action' => 'ShareByLink')))?>
                                <div class="panel-body">
                                    <div id="share-links">
                                    </div>
                                    <div class="hint-wrap">
                                        <span class="hint"><?= __('With this links everyone can access to the files') ?></span>
                                    </div>
                                    <?=$this->Form->hidden('type', array('value' => 0))?>
                                    <?=$this->Form->submit(__('Share'), array('class' => 'btn btn-share pull-right', 'style' => 'margin: 5px 0'))?>
                                    <?=$this->Form->end()?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <?= __('Provide Individual access') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <?=$this->Form->create('Share', array('url' => array('controller' => 'CloudAjax', 'action' => 'ShareIndividual'), 'id' => 'share-individual'));?>
                                    <div class="form-group">
                                        <input type="text" id="userSearch" class="form-control" placeholder="<?php echo __('Select user'); ?>">
                                    </div>
                                    <div class="hint-wrap">
                                        <span class="hint"><?= __('Files will be available only for selected users') ?></span>
                                    </div>
                                    <div class="form-group noBorder empty" id="uList">
                                        <div id="IndividualUserList" class="tokenfield"></div>
                                        <?=$this->Form->hidden('list')?>
                                    </div>
                                    <div class="form-group noBorder">
                                        <img src="/img/ajax_loader.gif" alt="" class="preloader" style="position: absolute; right: 10px; display: none;">
                                        <div class="groupAccess clearfix"></div>
                                    </div>
                                    <?=$this->Form->hidden('type')?>
                                    <?=$this->Form->submit(__('Share'), array('class' => 'btn btn-share pull-right', 'style' => 'margin: 5px 0'))?>
                                    <?=$this->Form->end()?>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <?= __('Provide edit access') ?>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <?=$this->Form->create('Share', array('url' => array('controller' => 'CloudAjax', 'action' => 'ShareEdit'), 'id' => 'share-edit'))?>
                                    <div class="form-group">
                                        <input type="text" id="userSearchEdit" class="form-control" placeholder="<?php echo __('Select user'); ?>">
                                    </div>
                                    <div class="hint-wrap">
                                        <span class="hint"><?= __('Files will be available only for selected users') ?></span>
                                    </div>
                                    <div class="form-group noBorder empty" id="uList_edit">
                                        <div id="editUserList" class="tokenfield"></div>
                                        <?=$this->Form->hidden('list_edit')?>
                                    </div>
                                    <div class="form-group noBorder">
                                        <img src="/img/ajax_loader.gif" alt="" class="preloader" style="position: absolute; right: 10px; display: none;">
                                        <div class="groupAccess clearfix"></div>
                                    </div>
                                    <?=$this->Form->hidden('type')?>
                                    <?=$this->Form->submit(__('Share'), array('class' => 'btn btn-share pull-right', 'style' => 'margin: 5px 0'))?>
                                    <?=$this->Form->end()?>
                                </div>
                            </div>

                        </div>
                    </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?= $this->element('cloud_manager_templates'); ?>
<script type="text/javascript">
    var touch = function() {
        var bool;
        if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
            bool = true;
        } else {
            bool = false;
        }
        return bool;
    };

    if(touch()){
        $('html').addClass('touch');
    } else {
        $('html').addClass('no-touch');
    }

    var observe;
    if (window.attachEvent) {
        observe = function (element, event, handler) {
            element.attachEvent('on'+event, handler);
        };
    }
    else {
        observe = function (element, event, handler) {
            element.addEventListener(event, handler, false);
        };
    }
</script>
<script type="application/javascript">
    var cloudLocale;
    var mediaIds = [];
    var cloudIds = [];
    function encode_sharing_url(selected) {
        mediaIds = [];
        var encoded_links = '';
        selected.each(function(){
            var current_link = $(this).data('url');
            var parts = current_link.split('/');
            var id_link = parts[parts.length - 1];
            parts.pop();
            parts = parts.join('/');
            mediaIds.push(id_link);
            var link = parts + '/' +btoa(id_link);
            encoded_links += '<div class="share-links">' + link +'</div>';

        });
        if(encoded_links)
            encoded_links = encoded_links.slice(0,-1);
        return encoded_links;
    }


;(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3;
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                $el.is(':input') && $el.on('keyup keypress',function(e){
                    if (e.type=='keyup' && e.keyCode!=8) return;
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        doneTyping(el);
                    }, timeout);
                }).on('blur',function(){
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);
    function getIndex(src) {
        var index = 0;
        console.log(src);
        if($('.fileStorage').find('.smallIcons').length > 0) {
            $('.fileStorage .clearfix.item').each(function(){
                if (typeof $(this).data('media') !== "undefined" && $(this).data('media')) {
                    if(src == $(this).data('media'))
                        return false;
                    else
                        index++;
                }
            });
        }
        else {
            $('.fileStorage a.item').each(function(){

                if (typeof $(this).data('media') !== "undefined" && $(this).data('media')) {
                    if(src == $(this).data('media'))
                        return false;
                    else
                        index++;
                }
            });
        }
        return index;
    }
$(document).ready(function(){
    var image_list = [];
    if($('.fileStorage').find('.smallIcons').length > 0) {
        $('.fileStorage .clearfix.item').each(function(){
            if (typeof $(this).data('media') !== "undefined" && $(this).data('media')) {
                $size   = $(this).data('size').split('x'),
                    $width  = $size[0],
                    $height = $size[1];
                var item = {
                    src : $(this).data('media'),
                    w   : $width,
                    h   : $height
                };
                image_list.push(item);
            }
        });
    }
    else {
        $('.fileStorage a.item').each(function(){
            if (typeof $(this).data('media') !== "undefined" && $(this).data('media')) {
                $size   = $(this).data('size').split('x'),
                    $width  = $size[0],
                    $height = $size[1];
                var item = {
                    src : $(this).data('media'),
                    w   : $width,
                    h   : $height
                };
                image_list.push(item);
            }
        });
    }

    var $pswp = $('.pswp')[0];

    /*
     $('.item').on('dblclick', function(event) {
     event.preventDefault();
     var child_span = $(this).children('span');
     if(child_span.hasClass('jpg') || child_span.hasClass('png') || child_span.hasClass('gif')) {
     var src = $(this).data('media');
     var $index = getIndex(src);
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
     */
	$('.item').doubletap(function (event) {
        $this = $(event.currentTarget);

        var child_span = $this.children('span');

        if(child_span.hasClass('jpg') || child_span.hasClass('png') || child_span.hasClass('gif')) {
            var src = $this.data('media');
            var $index = getIndex(src);
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
        else if ($this.data('type') == 'folder') {
            location.href = $this.data('url');
        }
        else if ($this.data('type') == 'file' && !(typeof $this.data('media') !== "undefined" && $this.data('media'))) {
			if ($this.hasClass('video-pop-this')) {
				var href = $this.data('url-down');
				var converted = $this.data('converted');
				if (converted < 12) {
					converted = 0;
				}
				insertVideo(href, converted);
			} else {
				window.open($this.data('url'));
			}
        }
        else if ($this.data('type') == 'doc') {
            window.open($this.attr('href'));
        }
    });

    $('#share-links').on('tap','.share-links',function(e){
        SelectText(e.target);
    });

    var usage_percent = <?php echo $storage_stats['usage_percent'];?>;
    if(usage_percent == 100) {
        $('#space-notification-modal').modal({
            backdrop: 'static'
        });
    }
    cloudLocale = {
        confirm: '<?=__('Are you sure ?')?>'
    }
    $('#cloud-manager-view button[data-view="<?=@$cloudManagerView?>"]').addClass('active');

    $('#userSearch, #userSearchEdit').keypress(function() {
        var parent = $(this).closest('.panel-collapse');
        parent.find('.preloader').show();
    });

    $('#userSearch, #userSearchEdit').donetyping(function() {
        var parent = $(this).closest('.panel-collapse');
        var postData = { q: $(this).val() };
        $.post( "<?=$this->Html->url(array('controller' => 'UserAjax', 'action' => 'userList'))?>", postData, ( function (data) {
            parent.children().find(".groupAccess").html(data);

            var itemCount = parent.find('.item' ).length;
            if( itemCount == 1 ) {
                parent.find('form .groupAccess').removeClass('one').removeClass('more').addClass('one');
            } else if( itemCount > 1 ) {
                parent.find('form .groupAccess').removeClass('one').removeClass('more').addClass('more');
            } else if( itemCount == 0 ) {
                parent.find('form .groupAccess').removeClass('one').removeClass('more');
            }
        }));
        $('#share-selected .preloader').hide();
    });
    function SelectText(element) {
        var doc = document
            , text = element
            , range, selection
            ;
        if (doc.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(text);
            range.select();
        } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
    $(document).on('click', '.user.item', function(e) {
        // ����� ������������� � ������
        $(this).addClass('selected');
        var parent = $(this).closest('.panel-collapse');
        var user_container = $(this).closest('.form-group').prev().children().eq(0);
        if( user_container.find(".token[data-user-id='"+$(this).data('user_id')+"']").length == 0 ) {

            var html = tmpl('user-select', {id: $(this).data('user_id'), name: $(".name", this).text(), url: $('img', this).attr('src')});

            user_container.append(html);
            if( user_container.find('.token').length == 1 ) {
                user_container.removeClass('empty');
            }
            parent.find('form .groupAccess').removeClass('one').removeClass('more');
            parent.find('input.form-control').val('');
            $(this).animate({height: 0, margin: 0}, 300, function() {
                $(this).remove();
            });
        }
    });
    $('.tokenfield').on('click', '.token a.close', function(e) {
        $(this).parents('.token').remove();
        if( $('#uList .token').length == 0 ) {
            $('#uList').addClass('empty');
        }
    });
    $('#share-selected').on('shown.bs.modal', function() {
        var selected = $('.foldersAndFiles .item.active');
        var links = '';
        var encoded_links = encode_sharing_url(selected);
        $('#share-links').empty().html(encoded_links);
    });

    function beforesubmit() {
        var selected = $('.foldersAndFiles .item.active');
        var for_submit = [];
        var cloud_ids = [];
        var document_ids = [];

        selected.each(function(){
            var object_id = $(this).data('id');
            if($(this).data('type') != 'doc') {
                cloud_ids.push(object_id);
            }
            else {
                document_ids.push(object_id);
            }

        });
        for_submit['cloud_ids'] = cloud_ids;
        for_submit['document_ids'] = document_ids;
        return for_submit;
    }
    $('#ShareIndexForm').submit(function(e){
        var for_submit = beforesubmit();
        $.ajax({
            url: '/CloudAjax/ShareByLink',
            type: 'POST',
            data: {cloud_ids: for_submit['cloud_ids'], document_ids: for_submit['document_ids']},
            dataType: 'JSON',
            success: function(response) {
                var flag = false;
                if(!$.isEmptyObject(response)) {
                    if(response.hasOwnProperty('success') && response.success) {
                        flag = true;
                    }
                }
                if(flag)
                    alert("Shared Successfully");
                else
                    alert("Failed to share the selected files and documents");
            }
        });
        $('#share-selected').modal('hide');
        $('.foldersAndFiles .item').removeClass('active');
        e.preventDefault();
        return false;
    });
    $('#share-individual').submit(function(e){
        var user_ids = [];
        $('#IndividualUserList').children('.token').each(function(){
            user_ids.push($(this).data('user-id'));

        });
        var for_submit = beforesubmit();
        $.ajax({
            url: '/CloudAjax/ShareIndividual',
            type: 'POST',
            data: {cloud_ids: for_submit['cloud_ids'], document_ids: for_submit['document_ids'], user_list: user_ids},
            dataType: 'JSON',
            success: function(response) {
                var flag = false;
                if(!$.isEmptyObject(response)) {
                    if(response.hasOwnProperty('success') && response.success) {
                        flag = true;
                    }
                }
                if(flag)
                    alert("Shared Successfully");
                else
                    alert("Failed to share the selected files and documents");
            }
        });
        $('#share-selected').modal('hide');
        $('.foldersAndFiles .item').removeClass('active');
        $('.groupAccess, #IndividualUserList').empty();
        e.preventDefault();
        return false;
    });
    $('#share-edit').submit(function(e){
        var user_ids = [];
        $('#editUserList').children('.token').each(function(){
            user_ids.push($(this).data('user-id'));
        });
        var for_submit = beforesubmit();
        var document_ids = for_submit['document_ids'];
        cloud_ids = for_submit['cloud_ids'];
        if(cloud_ids.length != 0) {
            alert("To share with edit permission, please select only documents");
            e.preventDefault();
            return false;
        }
        $.ajax({
            url: '/CloudAjax/ShareEdit',
            type: 'POST',
            data: {document_ids: for_submit['document_ids'], user_list: user_ids},
            dataType: 'JSON',
            success: function(response) {
                var flag = false;
                if(!$.isEmptyObject(response)) {
                    if(response.hasOwnProperty('success') && response.success) {
                        flag = true;
                    }
                }
                if(flag)
                    alert("Shared Successfully");
                else
                    alert("Failed to share the selected files and documents");
            }
        });
        $('#share-selected').modal('hide');
        $('.foldersAndFiles .item').removeClass('active');
        $('.groupAccess, #editUserList').empty();
        e.preventDefault();
        return false;
    });
    $('.panel-heading a').on('click', function() {
        $('.panel-heading').removeClass('selected');
        $(this).parents('.panel-heading').addClass('selected');
    });

    //$('#create-tooltip-content').html()
});
</script>

<script type="text/x-tmpl" id="user-select">
{%
    var name = o.name;
    var id = o.id;
    var url = o.url;
%}
<div class="token" data-user-id="{%=id%}">
    <span class="name token-label"><img src="{%=url%}" alt="{%=name%}">{%=name%}</span><a href="#" class="close" tabindex="-1">?</a>
</div>
</script>
