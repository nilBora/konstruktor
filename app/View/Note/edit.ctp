<?
	$id = $this->request->data('Note.id');
	$title = $this->request->data('Note.title');

	$parent_id = isset($this->request->params['named']['Note.parent_id']) ? $this->request->params['named']['Note.parent_id'] : '';
	$body = isset($Note['Note']['body']) ?  $Note['Note']['body'] : "";

	$this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
    $this->Html->css('font-awesome.min', null, array('inline' => false));
    echo $this->Html->css('/Froala/css/froala_editor.min.css', null, array('block' => 'css'));
?>
<div id="note-<?=$id ? $id : 'new'?>" class="noteEditBlock active">
	<?=$this->Form->create('Note', array('url' => array('controller' => 'NoteAjax', 'action' => 'editPanel')))?>		
		<div class="row projectViewTitle">
			<div class="col-sm-5 col-sm-push-7 controlButtons">
				<button type="button" class="btn btn-default formSubmit" <?=$id ? 'data-note_id="'.$id.'"' : ''?>><?=__('Save')?></button>
<?
	if ($id) {
?>
				<a class="btn btn-default noteView"><?=__('View')?></a>
				<a id="view-note-<?=$id?>" target="_blank" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'view', $id))?>" style="display: none;"><?=__('View')?></a>
				<button type="button" class="btn btn-default smallBtn" id="note-manager-move" data-what="<?=$id?>" data-where=""><span class="glyphicons move"></span></button>
				<a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons disk_save"></span></a>
				<button type="button" class="btn btn-default smallBtn" id="note-share" data-link="<?='https://'.$_SERVER['HTTP_HOST'].$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons link"></span></button>
				<button type="button" class="btn btn-default smallBtn noteDelete" data-note_id="<?=$id?>"><span class="glyphicons bin"></span></button>
<?
	}
?>
			</div>
			<div class="col-sm-5 col-sm-pull-5">
				<h1><?=($id) ? __('Edit document') : __('Create document')?></h1>
			</div>
		</div>
		<br/>
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
<script>

    $(function() {

        var html = '<?php echo preg_replace( "/\r|\n/", "", $body );?>';

        $('.wordProcessor').editable({
            key: '<?=Configure::read('froalaEditorKey')?>',
            inlineMode: false,
            imageUploadURL: mediaURL.froalaUpload,
            buttons: ["bold", "italic", "underline", "strikeThrough", "fontSize",
                "fontFamily", "color", "sep", "formatBlock", "blockStyle", "align",
                "insertOrderedList", "insertUnorderedList", "outdent", "indent", "sep",
                "createLink", "insertImage", "insertVideo", "table", "undo", "redo",
                "html","fullscreen"]
        });

//        $('.wordProcessor').on('editable.focus', function (e, editor) {
//            console.log("asdasdassadsadasd");
//        });
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
            console.log(moveImage);
            var ret = null;
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
                        url = url.replace('noresize', '1000px');
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
        $('.wordProcessor').editable('setHTML', html);
        console.log(mediaURL,'<?php echo rtrim($this->Html->url('/',true),'/'); ?>');
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

            var id = '';
            if (typeof $(this).data('note_id') !== 'undefined')
                id = '/' + $(this).data('note_id');
            var data = $(this).parents('form').serialize();
            var html = $('.wordProcessor').editable('getHTML');
            data += "&Note"+encodeURIComponent('[body]') + "=" + encodeURIComponent(html);
            var item = $('.bookmarks .item.active');
            var itemName = $('.bookmarks .item.active .name');
            var block = $('.noteEditBlock.active');

            $.post( noteURL.editPanel + id, data, function (response) {

                var addedID = $(response).prop('id');
                var id = $('.formSubmit', $(response)).data('note_id');
                window.open('<?=$this->Html->url(array('controller' => 'Note', 'action' => 'view'))?>/'+id);
                block.remove();
                console.log(item.data('note_id'));
                if($('#note-new').length > 0) {
                    var newID = 'note-' + id;
                    $('#note-new').attr("id", newID);
                }

            });
        })
    });
</script>