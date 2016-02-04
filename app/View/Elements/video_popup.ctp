<!-- start - video pop up -->
<link href="/js/vendor/video-js/video-js.css" rel="stylesheet">
<link href="/js/vendor/video-js/lib/videojs-resolution-switcher.css" rel="stylesheet">
<script src="/js/vendor/video-js/video.js"></script>
<script src="/js/vendor/video-js/lib/videojs-resolution-switcher.js"></script>

<div class="video-pop-up">
	<span class="glyphicons remove_2 close-it"></span>
	<div class="preview-video">	</div>
</div>

<script type="text/javascript">
	//Show pop up and init player
	$('body').on('click', '.video-pop-this', function(event){
		event.preventDefault();
		var href = $(this).data('url-down');
		var converted = $(this).data('converted') ? $(this).data('converted') : 0;
		if (converted < 12) {
			converted = 0;
		}
		insertVideo(href, converted);
	});
	/** Insert video tag with init video-js */
	function insertVideo(href, converted) {
		var regex = /\..+$/;
		var regexType = /^(.+\.)/;
		var videoInContainer = $('.preview-video');
		var ifConverted = converted;
//		console.log(ifConverted);

		//Video template
		var tmpl = '';
		$('.video-pop-up').fadeIn();
		tmpl += '<video id="preview-video" class="video-js vjs-default-skin vjs-big-play-centered" controls autoplay preload="auto">';
		if (ifConverted > 0) {
			tmpl += '<source src="' + href.replace(regex , '_360p.mp4') + '" type="video/mp4" label="360p"/>';
			tmpl += '<source src="' + href.replace(regex , '_360p.webm') + '" type="video/webm" label="360p"/>';
			tmpl += '<source src="' + href.replace(regex , '_360p.ogg') + '" type="video/ogg" label="360p"/>';
			tmpl += '<source src="' + href.replace(regex , '_480p.mp4') + '" type="video/mp4" label="480p"/>';
			tmpl += '<source src="' + href.replace(regex , '_480p.webm') + '" type="video/webm" label="480p"/>';
			tmpl += '<source src="' + href.replace(regex , '_480p.ogg') + '" type="video/ogg" label="480p"/>';
			tmpl += '<source src="' + href.replace(regex , '_720p.mp4') + '" type="video/mp4" label="720p"/>';
			tmpl += '<source src="' + href.replace(regex , '_720p.webm') + '" type="video/webm" label="720p"/>';
			tmpl += '<source src="' + href.replace(regex , '_720p.ogg') + '" type="video/ogg" label="720p"/>';
			tmpl += '<source src="' + href.replace(regex , '_1080p.mp4') + '" type="video/mp4" label="1080p"/>';
			tmpl += '<source src="' + href.replace(regex , '_1080p.webm') + '" type="video/webm" label="1080p"/>';
			tmpl += '<source src="' + href.replace(regex , '_1080p.ogg') + '" type="video/ogg" label="1080p"/>';
		} else {
			tmpl += '<source src="' + href + '" type="video/'+href.replace(regexType , '')+'"/>';
		}

		tmpl += '</video>';
		videoInContainer.html(tmpl);

		//Video.js player init and some setup
		videojs(document.getElementById("preview-video")).videoJsResolutionSwitcher();
		videojs(document.getElementById("preview-video")).ready(function(){
			resizeVideoFrame();
			var myPlayer = this;
			if (ifConverted) {
				myPlayer.currentResolution('360p')
			}
		});
	}
	//Resize video function
	resizeVideoFrame = function() {
		$('#preview-video').css('width', '100%');
		var allowedHeight = $(window).height() - $('#preview-header').height() - 170;

		var vWidth = $('#preview-video').width();
		var vHeight = vWidth/16*10;

		if(allowedHeight < vHeight) {
			vHeight = allowedHeight;
			vWidth = vHeight / 10 * 16;
			$('#preview-video').css('width', vWidth);
		}
		$('#preview-video').css('height', vHeight);
	}

	//Close popup and remove player
	$('.close-it').on('click', function(){
		var popup = $(this).closest('.video-pop-up');
		popup.fadeOut();
		popup.find('#preview-video').remove();
	});
	$('.video-pop-up').on('click','#preview-video', function(event) {
		event.stopPropagation();
	});
	$('.video-pop-up').on('click', function() {
		$(this).fadeOut();
		$(this).find('#preview-video').remove();
	});
	//Resize video on change window size
	$(window).on('resize orientationchange', function() {
		resizeVideoFrame();
	});
</script>
<!-- end - video pop up -->
