<div class="outer-modal-dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<span class="glyphicons circle_remove" data-dismiss="modal"></span>
			<h4><?=__('Edit message')?></h4>
			<form id="msgEditForm" class="sendForm" style="width: 100%">
				<?=$this->Form->hidden('event_id')?>
				<div class="form-group">
					<?=$this->Form->input('message', array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'required' => 'required'))?>
				</div>
				<div class="icon_enter btn btn-default" id="sendChatSmileEdit" data-original-title="" title="">
					<span class="smile"></span>
				</div>
				<div class="clearfix">
					<div id="postMessage" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(function(){
		if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
			$('#sendChatSmileEdit').popover({
				html : true,
				placement: 'top',
				class: 'smilesPopover',
				trigger: 'click',
				content: function() {
					return $('#popover_content_wrapper').html();
				}
			});

			$('body').on('touchstart', function(e) {
				if( !($(e.target).is('#sendChatSmileEdit') || $(e.target).is('.smileSelect') || $(e.target).parents('#sendChatSmileEdit').length > 0) ) {
					$('#sendChatSmileEdit').popover('hide');
				}
			});
		} else if((navigator.userAgent.indexOf("Safari") > -1) || (navigator.userAgent.indexOf("Mozilla") > -1)) {
			$('#sendChatSmileEdit').popover({
				html : true,
				placement: 'top',
				class: 'smilesPopover',
				trigger: 'click',
				content: function() {
					return $('#popover_content_wrapper').html();
				}
			});
		} else {
			$('#sendChatSmileEdit').popover({
				html : true,
				placement: 'top',
				class: 'smilesPopover',
				trigger: 'focus',
				content: function() {
					return $('#popover_content_wrapper').html();
				}
			});
			console.log(navigator.userAgent);
		}
	});
</script>
