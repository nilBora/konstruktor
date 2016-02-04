<!doctype html>
<html lang="ru">
<head>
	<!--[if lt IE 9]>
		<meta http-equiv="Refresh" content="0; URL=/html/include/ie.html" />
	<![endif]-->
	<?=$this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=0.7, initial-scale=0.7, minimum-scale=0.7">
	<meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="user-scalable=no" />
	<title>Konstruktor: <?=__('My time')?></title>

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

<?php
	echo $this->Html->meta('icon');

	$vendorCss = array(
		'fonts',
		'bootstrap/bootstrap',
		'bootstrap-datetimepicker',
	);

	$css = array(
		'main-panel-new',
		'content',
		'style',
		'user-profile'
	);
	echo $this->Html->css(array_merge($vendorCss, $css));

	echo $this->Websockets->init();

	$vendorScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/jquery/jquery.form.min',
		'vendor/jquery/jquery.ui.widget',
		'vendor/formstyler',
		'vendor/bootstrap.min',
		'vendor/bootstrap-datetimepicker.min',
		'vendor/bootstrap-datetimepicker.ru',
		'vendor/tmpl.min',
		'vendor/jquery/jquery.iframe-transport',
		'vendor/jquery/jquery.fileupload',
		'vendor/jquery.nicescroll.min',
		'vendor/double-tap',
		'vendor/jquery.autocomplete',
	);

	$scripts = array(
		'/core/js/json_handler',
		'main-panel',
		'chat', 'chat_panel',
		'struct',
		'search',
		'note',
		'article',
		'group',
		'cloud',
		'cloud-mover',
		'timeline',
		'xdate',
		//'notify_profile',
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
	// $('input.clock-mask').setMask('time');

	Search.initPanel($('.dropdown-searchPanel .dropdown-panel-wrapper').get(0));
	//Chat.initPanel($('.dropdown-chatPanel .dropdown-panel-wrapper').get(0));
	Struct.initPanel($('.dropdown-ipadPanel .dropdown-panel-wrapper').get(0));
	Group.initPanel($('.dropdown-groupPanel .dropdown-panel-wrapper').get(0));
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
<div class="wrapper-container time-line-bg">
    <div class="settings-page">
        <div class="container-fluid user-page-header">
            <div class="row user-page-header-inner">
                <div class="col-md-12 col-sm-12 col-xs-12 ">
                    <div class="col-md-5 col-sm-5 col-xs-5">
						<div class="group-menu page-menu">
							<div class="editUserLink">
								<span class="halflings list-alt"></span>
								<a href="<?=$this->html->url(array('controller' => 'Article', 'action' => 'myArticles'))?>" class="underlink"><?=__('My articles')?></a>
							</div>
							<div class="editUserLink">
								<span class="glyphicons parents"></span>
								<a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'favourites'))?>" class="underlink"><?=__('Users')?></a>
							<!--a href="#" class="underlink"><?=__('Statistics')?></a-->
							</div>
							<div class="editUserLink">
								<span class="glyphicons credit_card"></span>
								<a href="<?=$this->html->url(array('controller' => 'User', 'action' => 'mySells'))?>" class="underlink"><?=__('My sales')?></a>
							</div>
						</div>
					</div>
                    <div class="col-md-2 col-sm-2 col-xs-2 text-center group-menu">
                    	<?=$this->element('ajax_loader')?>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <div class="group-menu page-menu t-a-right">
                            <div class="btn-group btn-group-sm">
                                <button id="showDay" type="button" class="btn btn-default save-button"><?=__('Day')?></button>
                                <button id="showWeek" type="button" class="btn btn-default"><?=__('Week')?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<div class="container-fluid user-page-wrapp">
			<?=$this->fetch('content')?>
        </div>
    </div>
</div>

<?=$this->element('js_templates')?>
</body>
</html>
