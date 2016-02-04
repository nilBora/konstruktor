<?php

/* Breadcrumbs */
$this->Html->addCrumb(__('Chat'), array('controller' => 'Chat', 'action' => 'room/'.$roomID));

$viewScripts = array(
    'photoswipe/photoswipe',
    'photoswipe/photoswipe-ui-default'
);

$viewStyles = [
    'photoswipe/photoswipe',
    'photoswipe/default-skin/default-skin'
];

$this->Html->css($viewStyles, array('inline' => false));
$this->Html->script($viewScripts, array('inline' => false));

?>

<div id="loadMoreTemp" class="dialog">
	<div class="innerDialog">
		<div class="eventsDialog">
		</div>
	</div>
</div>

<style type="text/css">
    .chat-dialogs {
        position: relative;
    }

	#loadMoreTemp { 
        position: absolute; 
        visibility: hidden; 
        z-index: -5000; 
        display: none;
        left: 0;
        right: 0;
    }

	.modal, .modal-backdrop, .modal-backdrop.fade.in {
		background-color: transparent;
	}

    .innerDialog {
        padding-bottom: 20px;
    }

</style>

<div id="editMessage" class="modal fade" tabindex="-1" role="dialog">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<h4><?=__('Edit message')?></h4>
				<form id="msgEditForm">
					<?=$this->Form->hidden('event_id')?>
					<div class="form-group">
						<?=$this->Form->input('message', array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'required' => 'required'))?>
					</div>
					<div class="clearfix">
						<div id="postMessage" class="btn btn-primary loadBtn"><span><?=__('Save')?></span><img src="/img/ajax_loader.gif" style="height: 20px"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- start - photoswipe -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">

        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--share" title="Share"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>
<!-- end photoswipe -->

<script type="text/javascript">
	$('#postMessage.loadBtn').on('click', function() {
		$(this).removeClass('loadBtn');
		$.post( chatURL.editMessage, $( "#msgEditForm" ).serialize(),
			function (response) {

			obj = response;
			if( obj !== null ) {
				if(obj.status == "ERROR") {
					$(this).removeClass('loadBtn').addClass('loadBtn');
					alert( obj.message );
				}
				if(obj.status == "OK") {
					$('#event-'+$('#event_id').val()+" .text").text($('#message').val());
					$('#event-'+$('#event_id').val()+" .pencil").data('msg', $('#message').val());
					$('#editMessage').modal('hide');
					$('#postMessage').removeClass('loadBtn').addClass('loadBtn');
				}
			}

		});
	})

	$('#message').autosize();

	$('#message').on('keyup keydown change copy cut paste', function(){
		if( $(this).val().length == 0 ) {
			$('#postMessage').removeClass('disabled').addClass('disabled');
		} else {
			$('#postMessage').removeClass('disabled');
		}
		$('#message').trigger('autosize.resize');
	});

    $(function() {
        var $pswp = $('.pswp')[0];
        $('body').on('click', '.text a', function(event) {
            if ($(this).find('img[data-type="media"]').length != 0) {
                var img = $(this).find('img[data-type="media"]');
                event.preventDefault();
                var image_list = [];
                $size   = img.data('size').split('x'),
                    $width  = $size[0],
                    $height = $size[1];
                var src = img.data('url');
                var item = {
                    src : src,
                    w   : $width,
                    h   : $height
                };
                image_list.push(item);
                var options = {
                    index: 0,
                    bgOpacity: 0.7,
                    showHideOpacity: true,
                    shareEl: false
                };

                // Initialize PhotoSwipe
                var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, image_list, options);
                lightBox.init();
                $('#header, #chatLink, .planet, .logo').hide();
                lightBox.listen('close', function() {
                    $('#header, #chatLink, .planet, .logo').show();
                });
            }
        });
    });

</script>
