<!doctype html>
<html lang="ru">
<head>
	<!--[if lt IE 9]>
		<meta http-equiv="Refresh" content="0; URL=/html/include/ie.html" />
	<![endif]-->
	<?=$this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Konstruktor: <?=__('Main page')?></title>

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
    <meta name="robots" content="noindex, nofollow"/>
<?php
	//echo $this->Html->meta('icon');

	$vendorCss = array(
		'fonts',
		'bootstrap/bootstrap',
		'bootstrap/bootstrap-datepicker',
		'bootstrap/bootstrap-tokenfield',
	);

	$css = array(
		'main-panel-new',
		'content',
		'd_custom',
		'search-page',
		'settings-page',
		'group-page',
		'project-page',
		'konstruktor'
	);

	echo $this->Html->css(array_merge($vendorCss, $css));

	echo $this->Websockets->init();

	$vendorScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/jquery/jquery.backgroundSize',
		'vendor/jquery/jquery-ui.min',
		'vendor/jquery/jquery.form.min',
		'vendor/jquery/jquery.ui.widget',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/easing.1.3',
		'vendor/formstyler',
		'vendor/bootstrap.min',
		'vendor/moment',
		'vendor/bootstrap-datetimepicker',
		'vendor/bootstrap-datetimepicker.ru',
		'vendor/bootstrap-tokenfield',
		'vendor/autosize.min',
		'vendor/tmpl.min',
		'vendor/jquery.nicescroll.min',
		'vendor/double-tap',
	);

	$scripts = array(
		'/core/js/json_handler',
		'/table/js/format',
		'main-panel',
		'chat', 'chat_panel', 'chat_room',
		'struct',
		'search',
		'note',
		'article',
		'settings-script',
		'group-script',
		'group',
		'project-page',
		'upload',
		'xdate',
		//'notify_profile',
		'cloud',
		'cloud-mover',
		'finance',
		'invest_category',
	);

	echo $this->Html->script(array_merge($vendorScripts, $scripts));

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
<script type="text/javascript" src="<?=$this->Html->url(array('plugin' => false, 'controller' => 'js', 'action' => 'settings'), true)?>?<?= time(); ?>"></script>
<script type="text/javascript">
$(function() {
	$.ajaxSetup({ cache: false });

	$('select.formstyler, input.filestyle').styler({
		fileBrowse: '<?=__('Upload image')?>'
	});
	$('.textarea-auto').autosize();

	Search.initPanel($('.dropdown-searchPanel .dropdown-panel-wrapper').get(0));
	//Chat.initPanel($('.dropdown-chatPanel .dropdown-panel-wrapper').get(0));
	Struct.initPanel($('.dropdown-ipadPanel .dropdown-panel-wrapper').get(0));
	Group.initPanel($('.dropdown-groupPanel .dropdown-panel-wrapper').get(0));
	//Note.initPanel($('.dropdown-filePanel .dropdown-panel-wrapper').get(0));
	Article.initPanel($('.dropdown-notesPanel .dropdown-panel-wrapper').get(0));
	Cloud.initPanel($('.dropdown-cloudPanel .dropdown-panel-wrapper').get(0));
	Finance.initPanel($('.dropdown-financePanel .dropdown-panel-wrapper').get(0));
	InvestCategory.initPanel($('.dropdown-investPanel .dropdown-panel-wrapper').get(0));
});
</script>
</head>
<body>
<?=$this->element('ga')?>
<?=$this->element('panel_menu')?>

<div class="wrapper-container">
    <div class="settings-page search-page group-page project-page">
        <div class="container-fluid">
            <?=$this->fetch('content')?>
        </div>
    </div>
</div>
<?=$this->element('js_templates')?>
</body>
</html>
