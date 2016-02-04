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

<a href="https://www.facebook.com/sharer.php?s=100&amp;p[title]=<?=$fbTitle?>&amp;p[summary]=<?=$fbSummary?>&amp;p[url]=<?=$fbLink?>&amp;p[images][0]=<?=$fbImgUrl?>" class="social fb-share-btn" onClick="javascript:window.open(this.href,
  'sharer', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;" >
	<svg width="16" height="16" viewBox="0 0 16 16"><path d="M13 0H3C1 0 0 1 0 3v10c0 2 1 3 3 3h5V9H6V7h2V5c0-2 2-2 2-2h3v2h-3v2h3l-.5 2H10v7h3c2 0 3-1 3-3V3c0-2-1-3-3-3z"></path></svg>
</a>

<a href="http://twitter.com/share?url=<?=$fbLink;?>&text=<?=$titleTwTrimmed;?>&hashtags=konstruktor" onclick="javascript:window.open(this.href, '', 'width=560,height=320,menubar=no,location=no,resizable=no,scrollbars=no,status=no');return false;" class="social tw-share-btn">
	<svg viewBox="0 0 33 33" width="16" height="16"><g><path d="M 32,6.076c-1.177,0.522-2.443,0.875-3.771,1.034c 1.355-0.813, 2.396-2.099, 2.887-3.632 c-1.269,0.752-2.674,1.299-4.169,1.593c-1.198-1.276-2.904-2.073-4.792-2.073c-3.626,0-6.565,2.939-6.565,6.565 c0,0.515, 0.058,1.016, 0.17,1.496c-5.456-0.274-10.294-2.888-13.532-6.86c-0.565,0.97-0.889,2.097-0.889,3.301 c0,2.278, 1.159,4.287, 2.921,5.465c-1.076-0.034-2.088-0.329-2.974-0.821c-0.001,0.027-0.001,0.055-0.001,0.083 c0,3.181, 2.263,5.834, 5.266,6.438c-0.551,0.15-1.131,0.23-1.73,0.23c-0.423,0-0.834-0.041-1.235-0.118 c 0.836,2.608, 3.26,4.506, 6.133,4.559c-2.247,1.761-5.078,2.81-8.154,2.81c-0.53,0-1.052-0.031-1.566-0.092 c 2.905,1.863, 6.356,2.95, 10.064,2.95c 12.076,0, 18.679-10.004, 18.679-18.68c0-0.285-0.006-0.568-0.019-0.849 C 30.007,8.548, 31.12,7.392, 32,6.076z"></path></g></svg>
</a>
<?php // Плохая идея G+, но нормального решения нет, походу у гугла если ссылкой, то глючит :) ?>
<a href="https://plus.google.com/share?url=<?=$fbLink;?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="social g-share-btn">
	<g:plus action="share" data-href="<?=$fbLink;?>" data-annotation="none" style="display:none;"></g:plus>
	<svg viewBox="0 0 33 33" width="16" height="16"><g><path d="M 17.471,2c0,0-6.28,0-8.373,0C 5.344,2, 1.811,4.844, 1.811,8.138c0,3.366, 2.559,6.083, 6.378,6.083 c 0.266,0, 0.524-0.005, 0.776-0.024c-0.248,0.475-0.425,1.009-0.425,1.564c0,0.936, 0.503,1.694, 1.14,2.313 c-0.481,0-0.945,0.014-1.452,0.014C 3.579,18.089,0,21.050,0,24.121c0,3.024, 3.923,4.916, 8.573,4.916 c 5.301,0, 8.228-3.008, 8.228-6.032c0-2.425-0.716-3.877-2.928-5.442c-0.757-0.536-2.204-1.839-2.204-2.604 c0-0.897, 0.256-1.34, 1.607-2.395c 1.385-1.082, 2.365-2.603, 2.365-4.372c0-2.106-0.938-4.159-2.699-4.837l 2.655,0 L 17.471,2z M 14.546,22.483c 0.066,0.28, 0.103,0.569, 0.103,0.863c0,2.444-1.575,4.353-6.093,4.353 c-3.214,0-5.535-2.034-5.535-4.478c0-2.395, 2.879-4.389, 6.093-4.354c 0.75,0.008, 1.449,0.129, 2.083,0.334 C 12.942,20.415, 14.193,21.101, 14.546,22.483z M 9.401,13.368c-2.157-0.065-4.207-2.413-4.58-5.246 c-0.372-2.833, 1.074-5.001, 3.231-4.937c 2.157,0.065, 4.207,2.338, 4.58,5.171 C 13.004,11.189, 11.557,13.433, 9.401,13.368zM 26,8L 26,2L 24,2L 24,8L 18,8L 18,10L 24,10L 24,16L 26,16L 26,10L 32,10L 32,8 z"></path></g></svg>

</a>

<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?=$fbLink;?>&title=<?=$titleTrimmed;?>
&summary=<?=$contentTrimmed;?>&source=konstruktor.com" class="social in-share-btn" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');return false;">
	<svg viewBox="0 0 100 100" width="16" height="16">
		<path class="inner-shape" d="M82.539,1H17.461C8.408,1,1,8.408,1,17.461v65.078C1,91.592,8.408,99,17.461,99h65.078C91.592,99,99,91.592,99,82.539 V17.461C99,8.408,91.592,1,82.539,1z M37.75,80.625H25.5V37.75h12.25V80.625z M31.625,31.625c-3.383,0-6.125-2.742-6.125-6.125 s2.742-6.125,6.125-6.125s6.125,2.742,6.125,6.125S35.008,31.625,31.625,31.625z M80.625,80.625h-12.25v-24.5 c0-3.383-2.742-6.125-6.125-6.125s-6.125,2.742-6.125,6.125v24.5h-12.25V37.75h12.25v7.606c2.526-3.47,6.389-7.606,10.719-7.606 c7.612,0,13.782,6.856,13.782,15.312L80.625,80.625L80.625,80.625z"></path>
	</svg>
</a>


<script type="text/javascript">
	document.write(VK.Share.button({
		url: '<?=$fbLink;?>',
		title: '<?=$title;?>',
		description: '<?=$contentvk;?>',
		image: '<?=$imageUrl?>',
		noparse: true
	}, {
		type: 'custom',

		text: '<i class="social vk-share-btn"><svg width="16" height="16" viewBox="0 0 548 548" ><path d="M546 400c-1-1-1-3-2-4 -9-17-28-38-54-63l-1-1 0 0 0 0h0c-12-12-20-19-23-23 -6-8-7-15-4-23 2-6 11-18 26-37 8-10 14-18 19-24 33-44 47-72 43-84l-2-3c-1-2-4-3-9-5 -5-1-11-2-18-1l-82 1c-1 0-3 0-6 0 -2 1-4 1-4 1l-1 1 -1 1c-1 1-2 2-3 3 -1 1-2 3-3 5 -9 23-19 44-31 64 -7 12-13 22-19 31 -6 9-11 15-15 19 -4 4-8 7-11 10 -3 3-6 4-7 3 -2 0-3-1-5-1 -3-2-5-4-6-7 -2-3-3-7-3-11 -1-4-1-8-1-12 0-3 0-8 0-14 0-6 0-10 0-12 0-7 0-15 0-24 0-8 1-15 1-20 0-5 0-10 0-16s0-10-1-13c-1-3-2-6-3-9 -1-3-3-5-6-7 -3-2-6-3-10-4 -10-2-23-3-38-4 -35 0-58 2-68 7 -4 2-8 5-11 9 -3 4-4 7-1 7 11 2 20 6 24 12l2 3c1 3 3 7 4 13 1 6 2 13 3 21 1 14 1 26 0 36 -1 10-2 18-3 23 -1 6-2 10-4 13 -2 3-3 6-3 6 -1 1-1 1-1 1 -2 1-5 1-8 1 -3 0-6-1-10-4 -4-3-8-6-12-11 -4-5-9-11-14-20 -5-8-10-18-16-30l-5-8c-3-5-7-13-12-23 -5-10-9-20-13-30 -1-4-4-7-7-9l-1-1c-1-1-2-2-5-2 -2-1-4-1-7-2l-78 1c-8 0-13 2-16 5l-1 2c-1 1-1 3-1 5 0 2 1 5 2 8 11 27 24 53 37 78s25 45 35 60c10 15 20 30 30 43 10 14 17 22 20 26 3 4 6 7 8 9l7 7c5 5 11 10 20 16 9 6 19 13 29 19 11 6 23 11 38 15 14 4 28 6 42 5h33c7-1 12-3 15-6l1-1c1-1 2-3 2-5 1-2 1-5 1-8 0-8 0-16 2-22 1-7 3-11 5-15 2-3 4-6 6-8 2-2 4-4 5-4 1 0 2-1 2-1 5-1 10 0 16 4 6 5 12 10 17 17 5 7 12 14 20 22 8 8 14 14 20 18l6 3c4 2 9 4 15 6 6 2 11 2 16 1l73-1c7 0 13-1 17-4 4-2 6-5 7-8 1-3 1-6 0-10C547 404 546 402 546 400z"></path></svg></i>'
	}));
</script>
