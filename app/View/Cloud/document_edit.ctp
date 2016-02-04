<?php
	$this->Html->script(array(
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
		'tinyPlugins/media/plugin.min.js',
		'tinyPlugins/table/plugin.min.js',
		'tinyPlugins/contextmenu/plugin.min.js',
		'tinyPlugins/paste/plugin.min.js',
		'tinyPlugins/code/plugin.min.js',
	), array('inline' => false));

	$css = array(
		'content.min.css',
		'skin.min.css',
		'../skins_tiny/lightgray/content.min.css',
		'../skins_tiny/lightgray/skin.min.css',
		'../skins_tiny/lightgray/content.inline.min.css'
	);

	$this->Html->css($css, array('inline' => false));
?>
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
                $full_text = __("No versions available");
            }
            echo __($full_text);
            ?>
            <span>
                <a href="javascript:void(0)" id="versions" class="btn btn-primary"><?php echo __("Versions"); ?></a>
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
			<textarea name="wordProcessor" id="wordProcessor"></textarea>
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
        var html = tinyMCE.get('wordProcessor').getContent();

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
									tinyMCE.get('wordProcessor').setContent(data['doc']['body']);
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

		tinymce.init({
			selector: '#wordProcessor',
			plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code cloudfilemanager'
			],
			relative_urls: false,
			image_advtab: true,
			external_filemanager_path:"/Cloud/index",
			filemanager_title: '<?php echo __('File manager');?>',
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link cloudfilemanager',
			init_instance_callback : function(editor) {
				editor.setContent('<?php echo Hash::get($note,'Note.body')?>');
			}
		});




        $('.formSubmit').click(function (event) {
            saveData();
        });
    });
</script>
