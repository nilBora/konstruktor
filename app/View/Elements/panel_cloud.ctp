<div class="create-group clearfix">
    <button class="btn btn-default textIconBtn pull-left" type="button" id="cloud-create-folder-popup"><?= __('Create folder') ?></button>
    <span class="fileuploader-wrapper btn btn-default smallBtn pull-left glyphicons disk_open">
        <input type="file" id="cloud-upload-input" multiple data-object_type="Cloud" data-object_id="<?= $id ?>"/>
    </span>
    <a href="<?= $this->Html->url(array('controller' => 'Cloud', 'action' => 'index', $aCloud['Cloud']['id'])) ?>" data-id="<?= $aCloud['Cloud']['id'] ?>" class="btn btn-default smallBtn pull-right" type="button"><span class="glyphicons cloud"></span></a>
</div>

<div class="create-group folderMain clearfix <? if (!$id) { ?>hide<? } ?>">
    <div class="folderName">
        <a href="javascript:void(0)" class="glyphicons left_arrow cloud-folder-select" data-id="<?= @$aCloud['Cloud']['parent_id'] ?>" data-current="<?= @$aCloud['Cloud']['id'] ?>"></a>
        <span class="title cloud-folder-select" data-id="<?= @$aCloud['Cloud']['parent_id'] ?>" style="cursor: pointer"><?= $aCloud['Cloud']['name'] ?></span>
    </div>
    <button class="btn btn-default pull-left" type="button" id="cloud-move" data-what="" data-where=""><?= __('Move') ?></button>
    <!--<button class="btn btn-default smallBtn pull-left" type="button"><span class="glyphicons link"></span></button>-->
    <button class="btn btn-default smallBtn pull-right" type="button" id="cloud-delete-folder" data-id="<?= $id ?>" data-parent="<?= @$aCloud['Cloud']['parent_id'] ?>"><span class="glyphicons bin"></span></button>
</div>

<div class="dropdown-panel-scroll">
    <?= $this->element('cloud_list') ?>
</div>

<div class="hide" id="cloud-tpl-popover">
    <div class="popover popoverFolderCreate cloud-create-folder" role="tooltip" style="margin-left: 53px;">
        <div class="popover-content"></div>
    </div>
</div>

<div class="hide" id="cloud-tpl-popover-content">
    <span class="glyphicons circle_remove"></span>
    <form id="cloud-add-folder" data-id="<?=$id?>">
        <div class="form-group">
            <label><?=__('New Folder')?></label>
            <div class="input-group">
                <input name="Cloud[name]" required="true" type="text" placeholder="" class="form-control">
                <div class="input-group-addon">
                    <button class="btn btn-default submitButtonArrow" type="submit"><span class="submitArrow"></span></button>
                    <input type="hidden" class="form-control" name="Cloud[parent_id]" value="<?=$id?>">
                </div>
            </div>
        </div>
    </form>
</div>

<div class="hide" id="cloud-tpl-file-upload">
    <li class="simple-list-item">
        <div class="user-list-item clearfix">
            <span class="filetype"></span>
            <div class="articlesInfo">
                <div class="title"></div>
                <div class="clearfix">
                    <span class="percentage"></span>
                    <div class="progress">
                        <div style="width: 0%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-info"></div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</div>
<script type="text/javascript">
$(document).ready(function () {
//init
    Cloud.uploadCounter = 0;

// search
    $('#searchCloudForm').ajaxForm({url: cloudURL.panel, target: Cloud.panel});

// create folder
    $('#cloud-create-folder-popup').popover({
        content: $('#cloud-tpl-popover-content').html(),
        html: true,
        placement: "bottom",
        template: $('#cloud-tpl-popover').html()
    });

    $('.cloud-create-folder .circle_remove').on('click', function () {
        $('#cloud-create-folder-popup').popover('hide');
    });

    $('#cloud-add-folder').on('submit', function () {
        $form = $(this);
        $.post(cloudURL.addFolder, $form.serialize(), function (response) {
            Cloud.initPanel(null, $form.data('id'));
        });
        return false;
    });

// delete folder
    $('#cloud-delete-folder').on('click', function () {
        if (!confirm('<?=__('Are you sure ?')?>')) {
            return;
        }
        $control = $(this);
        $.post(cloudURL.delFolder, {id: $control.data('id')}, function (response) {
            Cloud.initPanel(null, $control.data('parent'));
        });
    });

// navigation
    $('.cloud-folder-select').on('click', function () {
        Cloud.initPanel(null, $(this).data('id'));
    });
    $('.cloud-item-select').on('click', function () {
        $('.cloud-item-select').removeClass('active');
        $(this).toggleClass('active');
        $('#cloud-move').data('what', $(this).data('id'));
    });
    $('.cloud-item-select').doubletap(
        function (event) {
            $target = $(event.currentTarget);
            if ($target.data('type') == 'folder') {
                Cloud.initPanel(null, $target.data('id'));
            } else if ($target.data('type') == 'file') {
                window.open($target.data('url'));
            }
        }
    );

//uploader
    $('#cloud-upload-btn').click(function () {
        $('#cloud-upload-input').click();
    });
    $('#cloud-upload-input').fileupload({
        url: mediaURL.upload,
        dataType: 'json',
        done: function (e, data) {
            var file = data.result.files[0];
            file.object_type = $(data.fileInput).data('object_type');
            file.object_id = $(data.fileInput).data('object_id');
            $.post(mediaURL.move, file, function (response) {
                var newFile = {
                    Cloud: {
                        media_id: response.data[0].Media.id,
                        name: response.data[0].Media.orig_fname,
                        parent_id: file.object_id
                    }
                };
                $.post(cloudURL.addFolder, newFile, function () {
                    if (--Cloud.uploadCounter == 0) {
                        Cloud.initPanel(null, file.object_id);
                    }
                    document.location.reload();
                });
            });
        },
        add: function (e, data) {
            if( !!window.preventUpload ) {
                return false;
            }
            window.preventUpload = true;
            var ul = $('.dropdown-cloudPanel .dropdown-panel-scroll .group-list');
            var fileType = data.files[0].name.split('.').pop().toLowerCase().replace('jpeg', 'jpg');
            var $tpl = $('#cloud-tpl-file-upload .simple-list-item').clone();
            var fileClass = Cloud.hasType(fileType) ? 'filetype ' + fileType : 'glyphicons file';
            $tpl.find('.filetype').attr('class', fileClass);
            $tpl.find('.title').text(data.files[0].name);
            data.context = $tpl.prependTo(ul);
            Cloud.uploadCounter++;
            data.submit();
        },
        progress: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            data.context.find('.percentage').html(progress + '%');
            data.context.find('.progress-bar').attr('style', 'width: ' + progress + '%');
        }
    });

// move
    $('#cloud-move').popover({
        content: ' ',
        html: true,
        placement: "bottom",
        template: '<div class="popover popoverFolderCreate moveFolders" style="border: none; margin-left: 50px" role="tooltip"><div class="cloud-manager popover-content"></div></div>'
    });
    $('#cloud-move').on('shown.bs.popover', function () {
        CloudMover.afterMove = function () {
             Cloud.initPanel(null, $('.cloud-folder-select').data('current'));
        };
        CloudMover.render($(this));

        $('body').on('click', function (e) {
            $('.foldersFilesList').getNiceScroll().hide();
            $('#cloud-move').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    });
    $('#cloud-move').on('hide.bs.popover', function () {
        $('.foldersFilesList').getNiceScroll().hide();
        $('.popoverFolderCreate').css({border: 'none'});
    });
});
</script>
