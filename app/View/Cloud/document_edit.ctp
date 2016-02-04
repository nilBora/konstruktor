<style type="text/css">
    .custom-close::before {
        content: url("<?php echo $this->Html->url('/', true) . 'img/close.png' ?>");
    }
    .close {
        opacity: 0.7 !important;
    }
    .close:hover {
        opacity: 1 !important;
    }
</style>
<?
    $id = $this->request->data('Note.id');
    $title = $this->request->data('Note.title');

    $parent_id = isset($this->request->params['named']['Note.parent_id']) ? $this->request->params['named']['Note.parent_id'] : '';
    $parent_name = !empty($parent_name) ? $parent_name : __('Back to Cloud');
    $body = isset($Note['Note']['body']) ?  $Note['Note']['body'] : "";

    $this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
    $this->Html->css('font-awesome.min', null, array('inline' => false));
    echo $this->Html->css('/Froala/css/froala_editor.min.css', null, array('block' => 'css'));

	/* Breadcrumbs */
	$this->Html->addCrumb(__('Cloud'), array('controller' => 'Cloud', 'action' => 'index'));
	if ($id) {
		$this->Html->addCrumb(Hash::get($note, 'Note.title'), array('controller' => 'Cloud', 'action' => 'documentEdit/'.$id));
	} else {
		$this->Html->addCrumb(__('Create'), array('controller' => 'Cloud', 'action' => 'documentEdit'));
	}


?>

<div id="note-<?=$id ? $id : 'new'?>" class="noteEditBlock active">
    <?=$this->Form->create('Note', array('url' => array('controller' => 'NoteAjax', 'action' => 'editPanel')))?>
        <div class="row projectViewTitle">
            <div class="col-sm-5 col-sm-push-7 controlButtons">
                <button type="button" class="btn btn-default formSubmit" <?=$id ? 'data-note_id="'.$id.'"' : ''?>><?=__('Save version')?></button>
            </div>
        </div>
        <br/>
        <div class="pull-right">
            <?php
            if(!empty($last_updated)) {
                $changedById = $last_updated['user_id'];
                $changedByName = $last_updated['user_full_name'];
                $changedDate = $last_updated['last_modified'];
                $changeBy = "<a href='/User/view/" . $changedById ."'>$changedByName</a>";
                $change_date = __(" at ") . $changedDate;
                $full_text = __ ('Last changed by ') . $changeBy . $change_date;
            }
            else {
                $full_text = "No versions available";
            }
            echo __($full_text);
            ?>
            <span>
                <a href="javascript:void(0)" id="versions" class="btn btn-primary">Versions</a>
            </span>
        </div>
<?
        if(!$id && $parent_id) {
?>
            <?=$this->Form->hidden('Note.parent_id', array('value' => $parent_id))?>
<?
        } else {
?>
            <?=$this->Form->hidden('Note.parent_id')?>
<?
        }
?>
        <?=$this->Form->hidden('Note.type', array('value' => 'text'))?>
        <br/>
        <br/>
        <div class="oneFormBlock">
            <div class="form-group">
                <?=$this->Form->input('title', array('placeholder' => __('Document title').'...', 'label' => __('Document title'), 'class' => 'form-control NoteTitle'))?>
            </div>
        </div>

        <div class="wordProcessor">
        </div>
        <br/>
        <br/>
    <?=$this->Form->end()?>
    <div id="eg-basic" class="text-small"></div>
</div>

<?php if(!empty($versions)) :?>

<div class="modal fade" id="doc-versions">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="glyphicons custom-close" style="cursor: pointer;"></span>
                </button>
                <h4 class="modal-title share-title"><?= __('File Sharing') ?></h4>
            </div>
            <div class="modal-body">
            <?php
            $current_day = false;
            $count = 0;
            $colors = array(
                '22b5ae',
                'fbba00',
                'a52a2a',
                '9932CC',
                '00FF00',
            );
            $already_used = [];
            foreach($versions as $version) :
                $new_day = date('d',strtotime($version['DocumentVersion']['modified']));
                $time = date('H:i:s',strtotime($version['DocumentVersion']['modified']));
                if(!isset($already_used[$version['User']['id']])) {
                    $already_used[$version['User']['id']] = reset($colors);
                    $first_key = key($colors);
                    unset($colors[$first_key]);
                }

                if($new_day !== $current_day) : ?>
                    <?php $current_day = $new_day;?>
                    <?php if($count) : ?>
                        </div>
                    <?php else :
                        $count ++;
                    endif;?>

                    <div class="version-date">
                        <div class="version-date-wrap">
                            <?php echo $version['DocumentVersion']['niceday']; ?>
                        </div>
                    <div class="modified-user" style="margin: 10px 0">
                        <div class="square pull-left" style="background: #<?php echo $already_used[$version['User']['id']]; ?> "></div>
                        <input type="hidden" class="version-id" value="<?php echo $version['DocumentVersion']['id'];?>"/>
                        <input type="hidden" class="user-id" value="<?php echo $version['User']['id'];?>"/>
                        <div class="middle-content"><?php echo $version['User']['full_name']; ?></div>
                    </div>
                <?php else: ?>
                    <div class="version-date-wrap">
                        <?php echo $time; ?>
                    </div>
                    <div class="modified-user" style="margin: 10px 0">
                        <div class="square pull-left" style="background: #<?php echo $already_used[$version['User']['id']]; ?>"></div>
                        <input type="hidden" class="version-id" value="<?php echo $version['DocumentVersion']['id'];?>"/>
                        <input type="hidden" class="user-id" value="<?php echo $version['User']['id'];?>"/>
                        <div class="middle-content"><?php echo $version['User']['full_name']; ?></div>
                    </div>
                <?php endif; ?>
                    <div class="clearfix"></div>
            <?php endforeach; ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php endif; ?>

<script>

    function saveData() {
        var url = noteURL.editPanel;
        var id = '';
        var form = $('.formSubmit');
        if (typeof form.data('note_id') !== 'undefined') {
            id = form.data('note_id');
            url += '/' + id;
        }
        var html = $('.wordProcessor').editable('getHTML');

        $.ajax({
            type: "POST",
            url: url,
            async: false,
            data: {
                title: $('.NoteTitle').val(),
                body: html,
                id: id,
                parent_id: $('#NoteParentId').val()
            },
            dataType: "json",

            success: function(response) {
                if(!$.isEmptyObject(response)) {
                    var id = '';
                    if(response.hasOwnProperty('success') && response.success) {
                        if(!$.isEmptyObject(response['data'])) {
                            var data = response['data'];
                            if(data.hasOwnProperty('id')) {
                                id = data['id'];
                                window.location.replace('<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'documentEdit'))?>/'+id);
                            }
                        }

                    }

                }

            }
        });
    }

    $(function() {
        $('#versions').click(function() {
            $('#doc-versions').modal('show');
        });

        $('.middle-content').click(function(e){
            var version_id = $(this).parent().children('.version-id').val();
            $.ajax({
                type: "POST",
                url: '/DocumentVersion/loadVersion',
                async: false,
                data: {version_id: version_id},
                dataType: "JSON",
                success: function(response) {
                    if(!$.isEmptyObject(response)) {

                        if(response.hasOwnProperty('success') && response.success) {
                            if(!$.isEmptyObject(response.data)) {
                                var data = response.data;
                                if(data.hasOwnProperty('doc') && data['doc'].hasOwnProperty('body')) {
                                    $('.wordProcessor').editable('setHTML', data['doc']['body']);
                                }
                                if(data.hasOwnProperty('doc') && data['doc'].hasOwnProperty('title')) {
                                    $('#NoteTitle').val(data['doc']['title']);
                                }
                            }

                        }

                        $('#doc-versions').modal('hide');

                    }
                }
            });
            e.preventDefault();
        });

        var html = '<?php echo preg_replace( "/\r|\n/", "", $body );?>';

        $('.wordProcessor').editable({
            key: '<?=Configure::read('froalaEditorKey')?>',
            inlineMode: false,
            imageUploadURL: mediaURL.froalaUpload,
            minHeight: 250,
            maxHeight: 500,
            saveRequestType: 'POST',
            buttons: ["bold", "italic", "underline", "strikeThrough", "fontSize",
                "fontFamily", "color", "sep", "formatBlock", "blockStyle", "align",
                "insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep",
                "createLink", "insertImage", "insertVideo", "table", "undo", "redo",
                "html","fullscreen"]
        });

        $('.wordProcessor').on('editable.beforeSave', function (e, editor) {
            var html = '<?php echo preg_replace( "/\r|\n/", "", $body );?>';
            var id = '';
            var form = $('.formSubmit');
            if (typeof form.data('note_id') !== 'undefined')
                id = form.data('note_id');
            var data = form.parents('form').serialize();
            var html = $('.wordProcessor').editable('getHTML');
            data += "&Note"+encodeURIComponent('[body]') + "=" + encodeURIComponent(html);
            $('.wordProcessor').editable('option', 'saveParams', {
                title: $('.NoteTitle').val(),
                body: html,
                id: id,
                parent_id: $('#NoteParentId').val()
            });
            var saveURL = noteURL.editPanel + id;
            $('.wordProcessor').editable('option', 'saveURL', noteURL.editPanel + '/' + id);
        });

        $('.wordProcessor').on('editable.beforeImageUpload', function (e, editor, images) {
            moveImage = {};

            moveImage = {
                type: images[0].type,
                name: images[0].name,
                size: images[0].size,
                object_id: '<?=$currUserID?>',
                object_type: 'UserMedia'
            }

        });


        $('.wordProcessor').on('editable.afterImageUpload', function (e, editor, response) {
            var base_url = '<?php echo rtrim($this->Html->url('/',true),'/'); ?>';
            moveImage.url = base_url + $.parseJSON(response).link;
            var ret = null;
            $.ajax({
                type: "POST",
                url: mediaURL.move,
                async: false,
                data: moveImage,
                success: function(response) {

                    if( response.data[0].Media.orig_w < 1000 ) {
                        ret = '{"link": "' + response.data[0].Media.url_download + '" }';
                    } else {
                        var url = response.data[0].Media.url_img;
                        url = url.replace('noresize', '1000px');
                        ret = '{"link": "' + url + '" }';
                    }
                },
                error: function() {
                    ret = 'error'
                }
            });
            return ret;
        });
        $('.wordProcessor').editable('setHTML', html);

        $('.wordProcessor').on('editable.imageError', function (e, editor, error) {
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
        });



        $('.formSubmit').click(function (event) {
            saveData();
        });
    });
</script>
