<?
	$id = $this->request->data('Note.id');
	$table_id = $this->request->data('Note.table_id');
		
	$title = $this->request->data('Note.title');
	$parent_id = isset($this->request->params['named']['Note.parent_id']) ? $this->request->params['named']['Note.parent_id'] : '';

	$codebasePath = $id ? '../../../' : '../' ;
	
	$this->Html->css('jquery.Jcrop.min', null, array('inline' => false));
	$viewScripts = array(
		'redactor/redactor',
		'vendor/jquery/jquery.Jcrop.min',
		'vendor/jquery/jquery.ui.widget',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/exif',
		'/table/js/format',
		'upload'
	);
	$this->Html->script($viewScripts, array('inline' => false));
?>

<script src="<?=$codebasePath?>codebase/spreadsheet.php?load=js"></script>
<link rel="stylesheet" type="text/css" href="<?=$codebasePath?>codebase/dhtmlx_core.css" />
<link rel="stylesheet" type="text/css" href="<?=$codebasePath?>codebase/dhtmlxspreadsheet.css" />
<link rel="stylesheet" type="text/css" href="<?=$codebasePath?>codebase/dhtmlxspreadsheet_dhx_web.css">
<link rel="stylesheet" type="text/css" href="<?=$codebasePath?>codebase/skins/dhtmlxgrid_dhx_web.css">
<link rel="stylesheet" type="text/css" href="<?=$codebasePath?>codebase/skins/dhtmlxtoolbar_dhx_web.css">
<link rel="STYLESHEET" type="text/css" href="<?=$codebasePath?>skins/dhx_web/ocean/dhtmlx_custom.css" />

<div id="note-<?=$id ? $id : 'new'?>" class="noteEditBlock active">
	<?=$this->Form->create()?>		
		<div class="row projectViewTitle">
			<div class="col-sm-5 col-sm-push-7 controlButtons">
				<button type="submit" class="btn btn-default formSubmit" <?=$id ? 'data-note_id="'.$id.'"' : ''?>><?=__('Save')?></button>
<?
	if ($id) {
?>
				<!--a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'view', $id))?>"><?=__('View')?></a-->
				<button type="button" class="btn btn-default smallBtn" id="note-manager-move" data-what="<?=$id?>" data-where=""><span class="glyphicons move"></span></button>
				<!--a class="btn btn-default smallBtn" href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons disk_save"></span></a-->
				<!--button type="button" class="btn btn-default smallBtn" id="note-share" data-link="<?=$_SERVER['HTTP_HOST'].$this->Html->url(array('controller' => 'Note', 'action' => 'download', $id))?>"><span class="glyphicons link"></span></button-->
				<a href="<?=$this->Html->url(array('controller' => 'Note', 'action' => 'delete', $id))?>" class="btn btn-default smallBtn noteDelete"><span class="glyphicons bin"></span></a>
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
		<?=$this->Form->hidden('Note.type', array('value' => 'table'))?>
		<?=$this->Form->hidden('Note.table_id', array('value' => $table_id))?>
		<br/>
		<div class="oneFormBlock">
			<div class="form-group">
				<?=$this->Form->input('title', array('placeholder' => __('Document title').'...', 'label' => __('Document title'), 'class' => 'form-control NoteTitle'))?>
			</div>
		</div>

		<div class="spreadsheet" id="table" style="width: 100%; height: 550px"></div>
		<br/>
		<br/>
	<?=$this->Form->end()?>
</div>

<!-- Mover modal -->
<div class="modal fade moveFilesFolders" id="note-manager-move-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content"></div>
		</div>
	</div>
</div>
<!--/ Mover modal -->

<!-- Share modal -->
<div class="modal fade shareLink" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="form-group">
					<label><?= __('Share link') ?></label>
					<div class="link"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/ Share modal -->

<script type="text/javascript">
	
var NoteProcessor = {
	initHandlers: function() {
		$('.noteDelete').click( function () {			
			if (!confirm("<?=__('Are you sure to delete this record?')?>")) {
				return false;
			}
		});
				
		$('#note-manager-move').on('click', function () {
			if (!$(this).data('what')) {
				return;
			}
			$('#note-manager-move-modal').modal('show');
		});
		
		// share
		$('#note-share').on('click', function () {
			$('#share-modal .link').text($(this).data('link'));
			$('#share-modal').modal('show');
		});

		$('.noteEditBlock').height = $(window).height - $('.bookmarks').height;
	}
};
	
var NoteManagerMover = {
	render: function ($this, params) {
		if (!$this.data('what')) {
			return;
		}
		params = params == 'undefined' ? {} : params;
		$.post(noteURL.panelMove, params, function (response) {
			$('#note-manager-move-modal .modal-content').html(response);

			$('#note-manager-move-modal .circle_remove').removeClass('hide');

			$('.note-manager-move-back').on('click', function () {
				NoteManagerMover.render($this, {id: $(this).data('id')});
			});
			$('.note-manager-move-select').on('click', function () {
				$('.note-manager-move-select').removeClass('active');
				$(this).toggleClass('active');
				$this.data('where', $(this).data('id'));
			});
			$('.note-manager-move-select').doubletap(
				function (event) {
					$target = $(event.currentTarget);
					NoteManagerMover.render($this, {id: $target.data('id')});
				}
			);
			// mover
			$('#note-manager-move-modal .noteMover').on('click', function () {
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
					location.reload(false);			
				});
			});
			// init current folder as 'where'
			$this.data('where', $('.note-move-where').data('where'))
		});
	}
};
	
$(document).ready(function () {
	
// css
	$('select').styler();

// note move	
	$('#note-manager-move-modal').on('shown.bs.modal', function () {
		NoteManagerMover.render($('#note-manager-move'));
		$('body').css("position","fixed");
	});
	
	$('#note-manager-move-modal').on('hide.bs.modal', function () {
		$('#note-manager-move-modal .modal-content').html('');
		$('.foldersFilesList').getNiceScroll().hide();
		$('body').css("position","static");
	});
	
	$('#share-modal').on('hide.bs.modal', function () {
		$('#share-modal .link').text('');
	});
	
	NoteProcessor.initHandlers();
});
	
	
window.onload = function() {
	var dhx_sh2 = new dhtmlxSpreadSheet({
		load: "<?=$codebasePath?>codebase/php/data.php",
		save: "<?=$codebasePath?>codebase/php/data.php",
		parent: "table",
		maths: true,
		icons_path: "<?=$codebasePath?>codebase/imgs/icons/",
		autowidth: false,
		autoheight: false,
		skin: 'dhx_web'
	}); 
	dhx_sh2.load("<?=$table_id?>");
};
	
</script>