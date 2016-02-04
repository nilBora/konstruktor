<div class="row taskViewTitle" style="margin-bottom: 15px;">
	<div class="col-sm-3 col-sm-push-9 controlButtons" style="margin-top: 25px;">
		<a href="<?=$pdf['Media']['url_download']?>" class="btn btn-default" id="pdfDownload" download="<?=$pdf['Media']['orig_fname']?>"><?=__('Download')?></a>
		<a href="javascript: void(0)" class="btn btn-default" id="pdfShare" onclick="shareLink('<?="http://".$_SERVER["HTTP_HOST"].$pdf["Media"]["url_download"]?>')"><?=__('Share')?></a>
	</div>
	<div class="col-sm-9 col-sm-pull-3">
		<h3 style="margin-top: 25px;"><?=$pdf['Media']['orig_fname']?></h3>
	</div>
</div>

<iframe id="pdfIframe" src="/pdf/web/viewer.html?file=<?=$pdf['Media']['url_download']?>" style="width: 100%; height: 600px; border: none;"></iframe>

<div class="modal fade eventTypeModal" id="shareLinkModal">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content" style="max-width: 640px;">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="form-group">
					<label><?=__('Link to file')?></label>
					<input type="text" id="shareLink" class="form-control" onFocus="this.selectionStart=0; this.selectionEnd=this.value.length;" onTouchEnd="this.selectionStart=0; this.selectionEnd=this.value.length;" onMouseUp="return false">
				</div>
				<!--button type="button" class="btn btn-primary saveButton" data-dismiss="modal"><?=__('OK')?></button-->
			</div>
		</div>
	</div>
</div>
	

<script type="text/javascript">
	var windows_height = $(window).height() - $('.taskViewTitle').height() - 45;
	$('#pdfIframe').css('height', windows_height);
	
	$( window ).resize(function() {
		windows_height = $(window).height() - $('.taskViewTitle').outerHeight() - 45;
		$('#pdfIframe').css('height', windows_height);
	});
	
	shareLink = function(link) {		
		$('#shareLinkModal #shareLink').val( link );
		$('#shareLinkModal').modal('show');
	}
	
	if( !(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ) {
		$('.myModal').css('right', ($('.wrapper-container').width() - $('.myModal').width())/2 );
		//$('.wrapper-container').niceScroll({cursorwidth:"7px",cursorcolor:"#23b5ae", cursorborder:"none", autohidemode:"false", background: "#f1f1f1"});
		//$('.wrapper-container').getNiceScroll().show();
	} else {
		$('.modal .glyphicons.circle_remove').css('font-size', '20px');
		//$('.myModal').css('right', ($(document).width() - $('.myModal').width())/2 );	
	}
</script>