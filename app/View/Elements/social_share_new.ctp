<?
	$scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (
		 (
	  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
	   443 == $_SERVER['SERVER_PORT']
		 ) ? 'https' : 'http'
	 );

    $fbLink = urlencode("$scheme://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    $fbSummary = urlencode($content);
    $fbImgUrl = urlencode($imageUrl);
    $fbTitle = urlencode($title);

    $titleTrimmed = urlencode(mb_substr($title,0,200));
    $titleTwTrimmed = urlencode(mb_substr($title,0,100));
    $contentTrimmed = urlencode(mb_substr($content,0,250));
    $contentvk = mb_substr($content,0,200);
?>
<div class="socLinks">
	<a href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?=$fbTitle?>&amp;p[summary]=<?=$fbSummary?>&amp;p[url]=<?=$fbLink?>&amp;p[images][0]=<?=$fbImgUrl?>" class="lFb" onClick="javascript:window.open(this.href,
	  'sharer', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" >
		<span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 191.4 409.6" enable-background="new 0 0 191.4 409.6" xml:space="preserve">
                    <g>
						<path d="M127.9,409.6H51.2V204.8H0v-70.6l51.2,0l-0.1-41.6C51.1,35,66.7,0,134.6,0H191v70.6h-35.3c-26.4,0-27.7,9.9-27.7,28.3
                            l-0.1,35.3h63.5l-7.5,70.6l-55.9,0L127.9,409.6z M127.9,409.6"/>
					</g>
                    </svg>
		</span>
	</a>

	<a href="http://twitter.com/share?url=<?=$fbLink;?>&text=<?=$titleTwTrimmed;?>&hashtags=konstruktor" onclick="javascript:window.open(this.href, '', 'width=560,height=320,menubar=no,location=no,resizable=no,scrollbars=no,status=no');return false;" class="lTw">
		<span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 409.6 332.9" enable-background="new 0 0 409.6 332.9" xml:space="preserve">
                    <g>
						<path  d="M409.6,39.4c-15.1,6.7-31.3,11.2-48.3,13.2c17.4-10.4,30.7-26.9,36.9-46.5c-16.2,9.6-34.2,16.6-53.4,20.4
                            C329.6,10.2,307.8,0,283.6,0c-46.4,0-84,37.6-84,84c0,6.6,0.7,13,2.2,19.2C131.9,99.7,70,66.2,28.5,15.4
                            c-7.2,12.4-11.4,26.8-11.4,42.2c0,29.2,14.8,54.9,37.4,69.9c-13.8-0.4-26.7-4.2-38.1-10.5c0,0.4,0,0.7,0,1.1
                            c0,40.7,29,74.7,67.4,82.4c-7.1,1.9-14.5,2.9-22.1,2.9c-5.4,0-10.7-0.5-15.8-1.5c10.7,33.4,41.7,57.7,78.5,58.4
                            c-28.8,22.5-65,36-104.4,36c-6.8,0-13.5-0.4-20-1.2c37.2,23.8,81.4,37.8,128.8,37.8c154.6,0,239.1-128,239.1-239.1
                            c0-3.6-0.1-7.3-0.2-10.9C384.1,71.1,398.3,56.3,409.6,39.4L409.6,39.4z M409.6,39.4"/>
					</g>
                </svg></span>
	</a>
	<?php // Плохая идея G+, но нормального решения нет, походу у гугла если ссылкой, то глючит :) ?>
	<a href="https://plus.google.com/share?url=<?=$fbLink;?>" onclick="javascript:window.open(this.href,
	  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="lGg">
		<g:plus action="share" data-href="<?=$fbLink;?>" data-annotation="none" style="display:none;"></g:plus>
		<span><svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				   viewBox="0 0 75.3 77.5" enable-background="new 0 0 75.3 77.5" xml:space="preserve">
                    <g>
						<path d="M49,0H30.5c-8.2,0-13.9,1.8-19.1,6C7.5,9.5,5,14.6,5,19.6c0,7.7,5.9,15.9,16.7,15.9c1,0,2.2-0.1,3.2-0.2l-0.1,0.4
                            c-0.4,1-0.9,2-0.9,3.6c0,3,1.4,4.9,2.8,6.6L27,46l-0.3,0c-4.5,0.3-12.8,0.9-18.9,4.6C0.6,55,0,61.2,0,63c0,7.2,6.7,14.4,21.7,14.4
                            c17.4,0,26.5-9.6,26.5-19.1c0-7-4.1-10.5-8.5-14.2L36,41.3c-1.1-0.9-2.6-2.1-2.6-4.3c0-2.1,1.4-3.5,2.7-4.8l0.1-0.1
                            c4-3.1,8.5-6.7,8.5-14.4c0-7.7-4.8-11.7-7.2-13.6h6.1c0.1,0,0.1,0,0.2-0.1l5.3-3.3c0.1-0.1,0.2-0.3,0.2-0.5C49.4,0.1,49.2,0,49,0
                            L49,0z M26.6,73.2c-10.6,0-17.7-5-17.7-12.3c0-4.8,2.9-8.3,8.7-10.4c4.6-1.5,10.5-1.6,10.6-1.6c1,0,1.5,0,2.3,0.1
                            c7.4,5.3,11,8.1,11,13.3C41.4,69,35.8,73.2,26.6,73.2L26.6,73.2z M26.5,32.6c-8.9,0-12.6-11.7-12.6-18c0-3.2,0.7-5.6,2.2-7.5
                            c1.6-2,4.4-3.3,7.1-3.3c8.2,0,12.7,11,12.7,18.6c0,1.2,0,4.8-2.5,7.4C31.7,31.5,28.9,32.6,26.5,32.6L26.5,32.6z M26.5,32.6"/>
						<path d="M74.9,36.2h-9.7v-9.7c0-0.2-0.2-0.4-0.4-0.4h-4.2c-0.2,0-0.4,0.2-0.4,0.4v9.7h-9.7c-0.2,0-0.4,0.2-0.4,0.4v4.2
                            c0,0.2,0.2,0.4,0.4,0.4h9.7V51c0,0.2,0.2,0.4,0.4,0.4h4.2c0.2,0,0.4-0.2,0.4-0.4v-9.8h9.7c0.2,0,0.4-0.2,0.4-0.4v-4.2
                            C75.3,36.4,75.1,36.2,74.9,36.2L74.9,36.2z M74.9,36.2"/>
					</g>
                </svg></span>
	</a>

	<!--<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?/*=$fbLink;*/?>&title=<?/*=$titleTrimmed;*/?>
	&summary=<?/*=$contentTrimmed;*/?>&source=konstruktor.com" class=" in-share-btn lIn" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;">
		<span></span>
	</a>-->

	<span href="" class="lastM">
		<a href="" class="lMail clickedBtn">
            <span>
                <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 30.5 26.4" enable-background="new 0 0 30.5 26.4" xml:space="preserve">
                <g>
					<path d="M27.7,15.1L26.5,16l1.2,3.9c0.1-0.1,0.2-0.2,0.3-0.4l2.4-7.8c0.1-0.2,0-0.3,0-0.5l-3.2,2.6L27.7,15.1z M27.7,15.1"/>
					<path d="M25.5,9.6c-0.1,0.1-0.2,0.2-0.3,0.4l-2.4,7.8c-0.1,0.2,0,0.3,0,0.5l4.3-3.5L25.5,9.6z M25.5,9.6"/>
					<path d="M23.1,18.7l4.3,1.3l-1.2-3.8L23.1,18.7z M23.1,18.7"/>
					<path d="M27.1,13.4l3.1-2.5l-4.3-1.3L27.1,13.4z M27.1,13.4"/>
				</g>
					<g>
						<g>
							<defs>
								<rect id="SVGID_1_" x="0" y="0" width="24.5" height="26.4"/>
							</defs>
							<clipPath id="SVGID_2_">
								<use xlink:href="#SVGID_1_"  overflow="visible"/>
							</clipPath>
							<path clip-path="url(#SVGID_2_)" d="M24.3,13.9c-0.2-0.1-0.6-0.3-0.8-0.4c0,0-0.1-0.1-0.1-0.2c-0.2-0.5-0.8-1.6-1.9-1.6
                            c-0.2,0-0.4,0-0.5,0.1c-1,0.3-1.5,1-1.9,1.6c-0.2,0.3-0.4,0.5-0.6,0.6c-0.1,0-0.1,0-0.2,0c-0.2,0-0.3-0.1-0.3-0.2
                            c-0.1-0.5-0.1-1.2,0-1.9c0-1.1,0.1-2.4-0.3-3.4c-0.6-1.6-1.9-3-3-4c-0.3-0.3-0.5-0.5-0.7-0.8c-0.1-0.1-0.2-0.2-0.3-0.3
                            c-0.1-0.1-0.2-0.3-0.3-0.4c-0.4-0.6-0.6-1.2-0.8-1.9c0-0.1,0-0.1-0.1-0.2c0-0.1-0.1-0.2-0.1-0.3C12.4,0.3,12.2,0,12,0
                            c0,0-0.1,0-0.1,0c-0.4,0.1-0.4,0.9-0.5,1.6c0,0.4-0.1,0.7-0.1,0.9C11.2,2.7,11,2.9,10.9,3c-0.2,0.2-0.3,0.3-0.4,0.6
                            c-0.1,0.3,0,0.5,0.1,0.7c0.1,0.1,0.1,0.3,0.1,0.5c0,0.2-0.1,0.4-0.3,0.5c-0.1,0.1-0.3,0.3-0.3,0.6c0,0.3,0.1,0.5,0.3,0.7
                            c0.1,0.2,0.2,0.3,0.2,0.5c0,0.2-0.1,0.3-0.2,0.4c-0.2,0.1-0.4,0.3-0.3,0.8c0,0.3,0.2,0.4,0.4,0.6c0.2,0.1,0.3,0.2,0.2,0.4
                            c0,0.1,0,0.2-0.1,0.4c-0.1,0.3-0.2,0.6,0.1,1c0.1,0.1,0.4,0.4,0.7,0.7c-0.9-0.4-2.2-1-3.7-1.2C5.4,9.7,3.2,8.7,2,8.2
                            C1.4,8,1.2,7.9,1.1,7.9C1,7.9,1,7.9,1,8C1,8,1,8.1,1.2,8.2l0.4,0.3C2,9,2.4,9.3,2.6,9.5c-0.1,0-0.2-0.1-0.3-0.1
                            c-0.1,0-0.1,0-0.2,0c0,0,0,0.1,0,0.1c0,0.5,1.3,2.7,3,4.9c1.3,1.6,3.7,4.2,5.7,4.2c0.1,0,0.2,0,0.4,0c-0.8,0.9-3.8,3.2-6.4,4.3
                            c-3.2,1.4-4.6,2.1-4.7,2.6c0,0.1,0,0.2,0.1,0.3C0.3,25.9,0.4,26,0.5,26c0.1,0,0.2,0,0.3-0.1c0.1,0,0.2-0.1,0.3-0.1
                            c0.1,0,0.2,0,0.4,0.1c0.3,0.2,0.7,0.5,1.3,0.5c0.6,0,1.2-0.3,1.9-0.8c0.1-0.1,0.3-0.1,0.4-0.1c0.1,0,0.2,0,0.2,0
                            c0.1,0,0.2,0,0.3,0c0.2,0,0.4-0.1,0.7-0.3c1.7-1.4,3.7-1.7,5.7-1.9c2.4-0.3,4.8-0.7,7.5-2.8c2.3-1.8,2.7-3.4,3-4.4
                            c0-0.1,0.1-0.2,0.1-0.3c0.3-1,0.5-1.2,0.7-1.3c0.1-0.1,0.5-0.2,0.8-0.3c0.2-0.1,0.4-0.2,0.5-0.2c0.1,0,0.1-0.1,0.1-0.1
                            C24.5,14,24.5,13.9,24.3,13.9L24.3,13.9z M24.3,13.9"/>
						</g>
					</g>
                </svg>
            </span>
		</a>
		<div class="hiddenMail" id="shareToEmail">
			<input type="text" class="inpMail" placeholder="<?php echo __('Enter your email friend'); ?>">
			<input type="button" class="inpBtn" value="<?php echo __('Send'); ?>">
			<div class="clear"></div>
		</div>
	</span>

	<div class="clear"></div>
</div>
<script>
	$('#shareToEmail .inpMail').on('focus change', function(){
		$('#shareToEmail .error').remove();
	});
	$('#shareToEmail .inpBtn').on('click', function(){
		var it = $(this);
		it.prop('disabled', true);
		$('#shareToEmail .error').remove();
		var email = $('#shareToEmail .inpMail').val();
		var regEmail = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(!regEmail.test(email)) {
			if(email == '') {
				var errorMsg = '<?php echo __('Enter your Email'); ?>';
			} else {
				var errorMsg = '<?php echo __('Please enter a valid Email');?>';
			}
			$('#shareToEmail').append($('<div class="error">'+errorMsg+'</div>'));
			it.prop('disabled', false);
		} else {
			$.ajax({
				type: "POST",
				url: '/ArticleAjax/shareToEmail',
				dataType: 'JSON',
				data: {id: '<? echo $article['Article']['id']?>', email: email},
				success: function(response) {
					if(response.status == "OK") {
						$('#shareToEmail').html('<div class="success"><?php echo __('Message sent successfully'); ?></div>');
					} else {
						$('#shareToEmail').append($('<div class="error">Error</div>'));
						it.prop('disabled', false);
					}
				},
				error: function() {
					it.prop('disabled', false);
				}
			});
		}
		return false;
	});
	$('.wrappSoc').on('click','.socLinks a',function(){
		$.ajax({
			type: "POST",
			url: '/ArticleAjax/sharedArticle.json',

			data: {id: '<? echo $article['Article']['id']?>'},
			success: function(response) {
				console.log('shared');
			},
			error: function() {
				console.log('error');
			}
		});
	});
	$('.clickedBtn').on('click', function(e){
		e.preventDefault();
		$(this).toggleClass('widthForm');
	});
</script>
