<?
  $useragent = $_SERVER['HTTP_USER_AGENT'];
  /*
  //for full spectrum of mobile devices
  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
  */
  //for ios only
  if(preg_match('/ip(hone|od|ad)/i',$useragent)) {
    $ext = strtolower($file['ext']);
    if( $fileType == 'google-view' ) {
      $fileType = '';
    } else if( in_array($ext, array('.tif', '.tiff')) ) {
      //оказывается ipad умеет показывать tif в теге img!
      $fileType = 'image-view';
    }
    $downloadText = __('Preview');
  } else {
    $downloadText = __('Download');
  }
?>

<div id="preview-header" class="row">
  <div class="preview-btns">
        <a id="download" class="btn btn-default"
            href="<?php echo Router::url('/', true)  ?>File/download/<?= $cloud['Media']['id'] ?>" ><?=$downloadText?></a>
<?php if( !isset($fileOwner) && $loggedIn) { ?>
        <a id="save" class="btn btn-default"
           href="<?php echo Router::url('/', true)  ?>File/save_copy/<?= $cloud['Media']['id'] ?>"><?=__('Save to my cloud')?></a>
<?php } ?>
  </div>
</div>

    <?php if( $fileType == 'google-view' ) : ?>
        <div class="preview-doc">
            <iframe src="<?php echo Router::url($file_url, true)  ?>" style="position:absolute; width:90%; height:90%;" frameborder="0"></iframe>
        </div>
    <?php elseif ( $fileType == 'image-view' ) : ?>
        <div id="over">
            <img class="preview-img" src="<?php echo $file_url ?>" alt="" />
        </div>
    <?php elseif ( $fileType == 'thumbnailed' ) : ?>
        <div id="over">
            <img class="preview-img"  src="<?php echo $file_url ?>" alt="" />
        </div>
    <?php elseif ( $fileType == 'video-view' ) : ?>
		<link href="/js/vendor/video-js/video-js.css" rel="stylesheet">
		<link href="/js/vendor/video-js/lib/videojs-resolution-switcher.css" rel="stylesheet">
		<script src="/js/vendor/video-js/video.js"></script>
		<script src="/js/vendor/video-js/lib/videojs-resolution-switcher.js"></script>

        <div class="preview-video">
            <video id="preview-video" class="video-js vjs-default-skin vjs-big-play-centered"
				   controls autoplay preload="auto" data-setup='{"playbackRates": [1, 1.5, 2]}'>
				<source src="<?php echo preg_replace('/\..+$/','_360p.mp4', $file_url) ?>" type='video/mp4' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_360p.webm', $file_url) ?>" type='video/webm' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_360p.ogg', $file_url) ?>" type='video/ogg' label="360p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.mp4', $file_url) ?>" type='video/mp4' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.webm', $file_url) ?>" type='video/webm' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_480p.ogg', $file_url) ?>" type='video/ogg' label="480p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.mp4', $file_url) ?>" type='video/mp4' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.webm', $file_url) ?>" type='video/webm' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_720p.ogg', $file_url) ?>" type='video/ogg' label="720p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.mp4', $file_url) ?>" type='video/mp4' label="1080p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.webm', $file_url) ?>" type='video/webm' label="1080p"/>
				<source src="<?php echo preg_replace('/\..+$/','_1080p.ogg', $file_url) ?>" type='video/ogg' label="1080p"/>
				<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
            </video>
        </div>

    <script type="text/javascript">
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
		//Video.js player init and some setup
		videojs("preview-video").videoJsResolutionSwitcher();
		videojs("preview-video").ready(function(){
		  resizeVideoFrame();
		  var myPlayer = this;
		  myPlayer.currentResolution('360p')
		  // EXAMPLE: Start playing the video.
		//          myPlayer.play();
		});

		$(window).on('resize orientationchange', function() {
		resizeVideoFrame();
		});
    </script>
    <?php else :
      $ext = substr($cloud['Media']['ext'], 1);
      $class = $this->File->hasType($ext) ? 'glyphicons filetype ' . $ext : 'glyphicons file';
    ?>
        <div class="preview bigIcons clearfix">
            <h2><?=$cloud['Media']['orig_fname']?></h2>
            <span class="<?=$class?>">
            </span>
        </div>
    <?php endif; ?>

<style>
  .col-centered{ float: none; margin: 0 auto; padding: 20px 0; }
  .col-centered a { text-decoration: none; }

  /* Easy Zoom styles*/
  .easyzoom { float: left; }
  .easyzoom img { display: block; }
  .easyzoom.is-ready img { cursor: crosshair; }
  .preview.bigIcons .glyphicons, .preview.bigIcons .filetype { font-size: 400px; color: #337ab7; }
  @media ( max-width: 767px ) {
    .preview.bigIcons .glyphicons, .preview.bigIcons .filetype { font-size: 200px; }
  }
  .preview { text-align: center; }
  .preview-img { max-width: 95%; margin-top: 20px; display: block; margin-left: auto; margin-right: auto; }
  .preview-btns { width: 100%; text-align: center; margin-top: 20px; }
  .preview-video { margin: 20px auto; }
  .video-js { margin: 0 auto; }
  .preview-doc { margin: 20px auto; }
</style>
