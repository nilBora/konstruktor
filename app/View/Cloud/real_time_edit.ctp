<!-- Firebase -->
    <?php
    $this->Html->script('firebase', array('inline' => false));
    $this->Html->script('codemirror', array('inline' => false));
    $this->Html->css('codemirror', null, array('inline' => false));
    $this->Html->css('firepad', null, array('inline' => false));
    $this->Html->script('firepad', array('inline' => false));
    $this->Html->script('user-list', array('inline' => false));
    $this->Html->css('user-list', null, array('inline' => false));

    $id = $this->request->data('Note.id');
    $title = $this->request->data('Note.title');

    $parent_id = isset($this->request->params['named']['Note.parent_id']) ? $this->request->params['named']['Note.parent_id'] : '';
    $parent_name = !empty($parent_name) ? $parent_name : __('Back to Cloud');
    $body = isset($Note['Note']['body']) ?  $Note['Note']['body'] : "";
    ?>
    <style>
        #firepad-container {
            width: 100%;
            height: 100%;
        }
        .firepad-with-toolbar .CodeMirror {
            top: 10px;
        }
        .firepad .CodeMirror {
            position: relative;
        }
        .powered-by-firepad {
            display: none;
        }
        .custom-close::before {
            content: url("<?php echo $this->Html->url('/', true) . 'img/close.png' ?>");
        }
        .close {
            opacity: 0.7 !important;
        }
        .close:hover {
            opacity: 1 !important;
        }
        #userlist {
            display: none; /* need to remove in future and modify the style */
            position: fixed; right: 0; bottom: 100px; height: auto;
            width: 175px;
            z-index: 1000;
        }
        .firepad-userlist {
            height: auto;
        }
        .firepad-userlist-heading {
            margin: 10px 0;
            padding: 5px;
        }
        .firepad-userlist-users {
            position: inherit;
        }
    </style>


<h3>
    <a href="<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'index', $parent_id))?>" class="glyphicons chevron-left"><?=$parent_name?></a>
</h3>
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
    <div id="userlist"></div>
<div id="firepad-container"></div>

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
    var firepad;
    var img_response = null;
    function init(html) {
        var userId = '<?php echo $user_id; ?>';
        var displayName = '<?php echo isset($user_name) ? $user_name : "Guest" ;?>';
        var id = '<?php echo isset($id) ? $id : "" ;?>';
         var firepadRef = new Firebase('https://multiedit.firebaseio.com/firepads/' + id);

        //// Create CodeMirror (with lineWrapping on).
        var codeMirror = CodeMirror(document.getElementById('firepad-container'), { lineWrapping: true });

        //// Create Firepad (with rich text toolbar and shortcuts enabled).
        firepad = Firepad.fromCodeMirror(firepadRef, codeMirror,
            { richTextToolbar: true, richTextShortcuts: true, userId: userId });

        var firepadUserList = FirepadUserList.fromDiv(firepadRef.child('users'),
            document.getElementById('userlist'), userId, displayName);

        //// Initialize contents.
        firepad.on('ready', function() {
            firepad.setHtml(html);
        });

        // An example of a complex custom entity.
        firepad.registerEntity('checkbox', {
            render: function (info, entityHandler) {
                var inputElement = document.createElement('input');
                inputElement.setAttribute('type', 'checkbox');
                if(info.checked) {
                    inputElement.checked = 'checked';
                }
                inputElement.addEventListener('click', function () {
                    entityHandler.replace({checked:this.checked});
                });
                return inputElement;
            }.bind(this),
            fromElement: function (element) {
                var info = {};
                if(element.hasAttribute('checked')) {
                    info.checked = true;
                }
                return info;
            },
            update: function (info, element) {
                if (info.checked) {
                    element.checked = 'checked';
                } else {
                    element.checked = null;
                }
            },
            export: function (info) {
                var inputElement = document.createElement('checkbox');
                if(info.checked) {
                    inputElement.setAttribute('checked', true);
                }
                return inputElement;
            }
        });
    }

    function saveData() {
        var url = noteURL.editPanel;
        var id = '';
        var form = $('.formSubmit');
        if (typeof form.data('note_id') !== 'undefined') {
            id = form.data('note_id');
            url += '/' + id;
        }
        var html = firepad.getHtml();

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
                                window.location.replace('<?=$this->Html->url(array('controller' => 'Cloud', 'action' => 'realTimeEdit'))?>/'+id);
                            }
                        }

                    }

                }

            }
        });
    }
    function uploadImage() {
        $('#file-input').fileupload({
            url: mediaURL.upload,
            dataType: 'json',
            done: function (e, data) {

                var file = data.result.files[0];
                if(file.hasOwnProperty('error') && file['error'] == 'File Storage limit exceeded') {
                    var temp = $('#notification-img-upload');
                    temp.empty().html('<div style="color: red;">' + file['error'] + '</div>');
                    setTimeout(function () {
                        temp.fadeOut('slow', function () {
                            temp.remove();
                        })
                    }, 3000);
                    return false;

                }
                file.object_type = $(data.fileInput).data('object_type');
                file.object_id = $(data.fileInput).data('object_id');
                $.post(mediaURL.move, file, function (response) {
                    var newFile = {
                        Media: {
                            media_id: response.data[0].Media.id,
                            name: response.data[0].Media.orig_fname,
                            src: response.data[0].Media.url_download,
                            parent_id: file.object_id
                        }
                    };
                    img_response = newFile['Media']['src'];
                });
            },
            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                data.context.find('.percentage').html(progress + '%');
                data.context.find('.progress-bar').attr('style', 'width: ' + progress + '%');
            }
        });
    }

    $(function(){
        var html = '<?php echo preg_replace( "/\r|\n/", "", $body );?>';

        init(html);

        $('#versions').click(function() {
            $('#doc-versions').modal('show');
        });

        jQuery(window).bind('beforeunload', function(e) {
            saveData();
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
                                    firepad.setHtml(data['doc']['body']);
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


        $('.formSubmit').click(function (event) {
            saveData('clicked');
        });


    })
</script>
