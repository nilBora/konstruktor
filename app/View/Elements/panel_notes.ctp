<?
//echo $id;die;
	$noteUrl = $id ? $this->Html->url(array('controller' => 'Note', 'action' => 'edit', 'Note.parent_id' => $id)) : $this->Html->url(array('controller' => 'Note', 'action' => 'edit'));
	$sheetUrl = $id ? $this->Html->url(array('controller' => 'Note', 'action' => 'spreadsheet', 'Note.parent_id' => $id)) : $this->Html->url(array('controller' => 'Note', 'action' => 'spreadsheet'));
?>
<div class="create-group clearfix">
    <button class="btn btn-default textIconBtn pull-left" type="button"id="createNoteFolderPopup"><?=__('Create folder')?></button>
	<!--button class="btn btn-default smallBtn pull-left" type="button" id="note-create"><span class="glyphicons pencil"></span></button-->
	<a href="<?=$noteUrl?>"  class="btn btn-default smallBtn pull-left" type="button" id="note-create"><span class="glyphicons pencil"></span></a>
    <!--a href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'index', $aNote['Note']['id']))?>"  data-id="<?=$aNote['Note']['id']?>" class="btn btn-default smallBtn pull-right" type="button"><span class="glyphicons note"></span></a-->
</div>

<div class="create-group folderMain clearfix <? if(!$id){ ?>hide<?}?>">
    <div class="folderName">
        <a href="javascript:void(0)" class="glyphicons left_arrow note-folder-select" data-id="<?=@$aNote['Note']['parent_id']?>"></a>
        <span class="title note-folder-select" data-id="<?=@$aNote['Note']['parent_id']?>" style="cursor: pointer"><?=$aNote['Note']['title']?></span>
    </div>
    <button class="btn btn-default pull-left" id="note-move" type="button" data-what="" data-where=""><?=__('Move')?></button>
    <!--button class="btn btn-default smallBtn pull-left" type="button"><span class="glyphicons link"></span></button-->
    <button class="btn btn-default smallBtn pull-right" type="button" id="note-delete-folder" data-id="<?=$id?>" data-parent="<?=@$aNote['Note']['parent_id']?>"><span class="glyphicons bin"></span></button>
</div>

<div class="dropdown-panel-scroll">
    <?=$this->element('user_notes_list')?>
</div>

<div id="create-note-content" style="display: none">
	<div class="foldersFilesList" style="overflow: hidden; outline: none; height: 93px" tabindex="5000">	
		<a href="<?=$noteUrl?>" class="item" id="newDocument">	
			<span class="filetype doc"></span><span class="name"><?=__('Document')?></span>	
		</a>	
		<a href="<?=$sheetUrl?>" class="item" id="newSheet">	
			<span class="filetype xls"></span><span class="name"><?=__('Table')?></span>	
		</a>	
	</div>
</div>

<script>
    // search
    $('#searchNoteForm').ajaxForm({url: noteURL.panel, target: Note.panel});

    // create folder
    $('#createNoteFolderPopup').popover({
        content: '<span class="glyphicons circle_remove"></span><form action="" method="post" id="note-add-folder" data-id="<?=$id?>"><div class="form-group"><label><?=__('New Folder')?></label><div class="input-group"><input name="Note[title]" required="true" type="text" placeholder="" class="form-control"><div class="input-group-addon"><button class="btn btn-default submitButtonArrow" type="submit"><span class="submitArrow"></span></button><input type="hidden" class="form-control" name="Note[parent_id]" value="<?=$id?>"></div></div></form>',
        html: true,
        placement: "bottom",
        template: '<div class="popover popoverFolderCreate" style="margin-left: 53px;" role="tooltip"><div class="popover-content"></div></div>'
    });

    $(document).on('click', '.popoverFolderCreate .circle_remove', function(){
        $('#createNoteFolderPopup').popover('hide');
    });

    $(document).off('submit', '#note-add-folder');
    $(document).on('submit', '#note-add-folder', function() {
        $form = $(this);
        $.post(noteURL.addFolder, $form.serialize(), function(response) {
            Note.initPanel(null, $form.data('id'));
        });
        return false;
    });

    // delete folder
    $('#note-delete-folder').on('click', function() {
        if(!confirm('<?=__('Are you sure ?')?>')) {
            return;
        }
        $control = $(this);
        $.post(noteURL.delFolder, {id: $control.data('id')}, function(response) {
            Note.initPanel(null, $control.data('parent'));
        });
    });
    
    // navigation
    $('.note-folder-select').on('click', function() {
        Note.initPanel(null, $(this).data('id'));
    });
	$('.simple-list-item').on('click', function (e) {
		$('.simple-list-item.active').removeClass('active');
		$(this).toggleClass('active');
		$('#note-move').data('what', $(this).data('id'));
	});
	
	$('.simple-list-item').doubletap(
		function (event) {
			$target = $(event.currentTarget);
			if ($target.data('type') == 'folder') {
				Note.initPanel(null, $target.data('id'));
			} else if ($target.data('type') == 'text') {
				if (document.location.pathname.indexOf("/Note/edit") == 0) {
                    var base_url = '<?php echo rtrim($this->Html->url('/',true),'/'); ?>';
                    var link = base_url + $target.data('url');
                    window.location.replace(link);
					$('.dropdown-panel.dropdown-open').removeClass('dropdown-open');
					$('.main-panel-list li.open').removeClass('open');
				} else {
					window.location.replace($target.data('url'));
				}
			} else if ($target.data('type') == 'table') {
				window.location.replace($target.data('url'));
			}
		}
	);
	
	// create
	//если не табличного редактора
	$('#note-create').on('click', function(event) {
		if (document.location.pathname.indexOf("/Note/edit") == 0) {
			NoteProcessor.openDocument("Note.parent_id:<?=$id?>");
			event.preventDefault();
		}	
	});
	
	// если будет табличный редактор
	/*
	$('#note-create').popover({
		content: $('#create-note-content').html(),
		html: true,
		placement: "bottom",
		template: '<div class="popover moveFolders" style="border: none; margin-left: -18px" role="tooltip"><div class="cloud-manager popover-content"></div></div>'
	});
	
	$('#note-create').on('shown.bs.popover', function () {
		$('#newDocument').unbind('click');
		$('#newDocument').on('click', function(event) {
			if (document.location.pathname.indexOf("/Note/edit") == 0) {
				NoteProcessor.openDocument("Note.parent_id:<?=$id?>");
				event.preventDefault();
			}	
		});

		$('body').on('click', function (e) {
			$('.foldersFilesList').getNiceScroll().hide();
			$('#note-create').each(function () {
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					$(this).popover('hide');
				}
			});
		});
	});
	
	$('#note-create').on('hide.bs.popover', function () {
		$('.foldersFilesList').getNiceScroll().hide();
		$('.popoverFolderCreate').css({border: 'none'});
	});
	*/

    //uploader
    var ul = $('.dropdown-notePanel .dropdown-panel-scroll .group-list');
	
	// move
	$('#note-move').popover({
		content: ' ',
		html: true,
		placement: "bottom",
		template: '<div class="popover popoverFolderCreate moveFolders" style="border: none; margin-left: 50px" role="tooltip"><div class="note-manager popover-content"></div></div>'
	});
	$('#note-move').on('shown.bs.popover', function () {
		NoteMover.afterMove = function () {
			Note.initPanel(null, $('.note-folder-select').data('current'));
		};
		NoteMover.render($(this));
		$('body').on('click', function (e) {
			$('.foldersFilesList').getNiceScroll().hide();
			$('#note-move').each(function () {
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					$(this).popover('hide');
				}
			});
		});
	});
	
	$('#note-move').on('hide.bs.popover', function () {
		$('.foldersFilesList').getNiceScroll().hide();
		$('.popoverFolderCreate').css({border: 'none'});
	});
		
	var NoteMover = {
		afterMove: function () {},
		render: function ($this, params) {
			
			if (!$this.data('what')) {
				return;
			}
			
			params = params == 'undefined' ? {} : params;
			$.post(noteURL.panelMove, params, function (response) {
				$('.note-manager.popover-content').html(response);
				
				$('.popoverFolderCreate').css({border: '1px solid #ccc'});
				$('.note-manager-move-back').on('click', function () {
					NoteMover.render($this, {id: $(this).data('id')});
				});
				$('.note-manager-move-select').on('click', function () {
					$('.note-manager-move-select').removeClass('active');
					$(this).toggleClass('active');
					$this.data('where', $(this).data('id'));
				});
				$('.note-manager-move-select').doubletap(
					function (event) {
						$target = $(event.currentTarget);
						NoteMover.render($this, {id: $target.data('id')});
					}
				);
				// mover
				$('.popoverFolderCreate .noteMover').on('click', function () {
					if (!$this.data('what')) {
						return;
					}
					if ($this.data('what') == $this.data('where')) {
						return;
					}
					$.post(noteURL.move, {id: $this.data('what'), parentId: $this.data('where')}, function (data) {
						var status = data.status;
						if(status == "ERROR") {
							console.log(data.status);
							console.log(data.errMsg);
							alert(data.errMsg);
							return;
						}
						NoteMover.afterMove();
					});
				});
				// close
				$('.popoverFolderCreate .closePopup').on('click', function () {
					$this.popover('hide');
				});
				// init
				$this.data('where', $('.note-move-where').data('where'));
			});
		}
	};
</script>