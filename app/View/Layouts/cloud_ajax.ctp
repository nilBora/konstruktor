<!doctype html>
<html lang="ru" prefix="og: http://ogp.me/ns#">
<head>
	<!--[if lt IE 9]>
	<meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
	<![endif]-->
	<?=$this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="user-scalable=no, maximum-scale=0.7, initial-scale=0.7, minimum-scale=0.7">

	<title><?= isset($title) ? $this->fetch('title', $title).' | Konstruktor.com' : 'Konstruktor.com | '.__('Main page') ?></title>

	<?php
	echo $this->Html->meta('icon');

	$vendorCss = array(
		'fonts',
		'bootstrap.min',
		'bootstrap-datetimepicker',
		'vendor/ChatJs/jquery.chatjs.css',
		'vendor/animate',
	);

	$css = array(
		'main-panel-new',
		'style',
		'photoswipe/photoswipe',
		'photoswipe/default-skin/default-skin'
	);
	echo $this->Html->css(array_merge($vendorCss, $css));


	$vendorScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/jquery/jquery-ui.min',
		'vendor/jquery/jquery.form.min',
		'vendor/jquery/jquery.ui.widget',
		'vendor/jquery/jquery-ui-1.10.3.custom.min',
		'vendor/jquery/jquery.fancybox.pack',
		'vendor/jquery.ui.touch-punch.js',
		'vendor/jquery/jquery.formstyler.min',
		'vendor/noty/packaged/jquery.noty.packaged.min',
		'vendor/bootstrap.min',
		'vendor/autosize.min',
		'vendor/tmpl.min',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/jquery.nicescroll.min',
		'vendor/double-tap',
		'vendor/tappy',
		'vendor/fastclick',
		'vendor/iscroll.js',
		'upload.js'
	);

	$scripts = array(
		'/core/js/json_handler',
		'/table/js/format',
		'struct',
		'search',
		'note',
		'article', 'article_category',
		'group',
		'cloud',
		'cloud-mover',
		'project-page',
		'xdate',
		'upload',
		'finance',
		'modal_conf',
		'invest_category',
		'highcharts',
		'exporting'
	);

	echo $this->Html->script(array_merge($vendorScripts, $scripts));

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');

	?>
	<script type="text/javascript" src="<?=$this->Html->url(array('plugin' => false, 'controller' => 'js', 'action' => 'settings'), true)?>?<?= time(); ?>"></script>
	<script type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</head>
<body>
<script type="text/javascript">
	$(window).resize();
	$.ajax({
		url: '<?=$this->Html->url(array('controller' => 'CloudAjax', 'action' => 'getCount'))?>',
		async: true
	}).done(function(responses){
		if(responses > 0)
			$('#cloudCount').html(responses);
	});
</script>
<div class="container-fluid fixedLayout wider" >
	<?=$this->fetch('content')?>
</div>
<?=$this->element('js_templates')?>
<?=$this->element('photo_swipe')?>
<?=$this->element('video_popup')?>
</body>
</html>
