<!DOCTYPE html>
<html lang="en">
<head>
    <!--[if lt IE 9]>
		<meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
	<![endif]-->
    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
    <title>Konstruktor: <?=__('Main page')?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="img/favicon/apple-touch-icon-144x144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
    <link href="img/favicon/apple-touch-icon-114x114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
    <link href="img/favicon/apple-touch-icon-72x72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72" />
    <link href="img/favicon/apple-touch-icon-57x57-precomposed.png" rel="apple-touch-icon-precomposed" />
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css' />
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
<?
	//echo $this->Html->meta('icon');

	$vendorCss = array(
		'bootstrap.min',
        'fonts'
	);

	$css = array(
		'index-page-last',
		'magnific-popup',
        'register-popup',
		'jquery.mCustomScrollbar.min',
		'jquery.mmenu.all'
	);

	echo $this->Html->css(array_merge($vendorCss, $css));

	$aScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'vendor/bootstrap.min',
		'timezone_cookie',
		'vendor/jquery/jquery.placeholder.min',
		'vendor/fastclick',
		'vendor/jquery.magnific-popup.min',
		'jquery.mCustomScrollbar.concat.min',
		'//www.google.com/jsapi',
		'jquery.mmenu.min.all'
	);

	echo $this->Html->script($aScripts);

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>


<script type="text/javascript">
$(function() {
	FastClick.attach(document.body);
});

$(document).ready(function(){
	$("#mmenu").clone().removeClass('rightLinks').mmenu({
        "autoHeight": true,
        "navbar": {
            "title": ""
        },
        "offCanvas": {
            "position": "right"
        },
        "extensions": [
            "pageshadow",
            "border-full",
            "effect-menu-slide",
            "effect-listitems-drop"
        ]
    });

    $('.christ-gift').magnificPopup({
        type:'inline',
        midClick: true
    });

	$.ajaxSetup({ cache: false });

	$('form input, form  textarea').placeholder({customClass: 'placeholdr'});
	$('.ui-autocompletea').css('display', 'none');

	$('.change-lang_curr').on('click', function(event) {
		event.preventDefault();

		$('.lang-block').toggleClass('lang-block_show');
	});

	$('.lang-block_link').on('click', function(event) {
		event.preventDefault();

		$('.lang-block').removeClass('lang-block_show');

		var langVal = $(this).data('val');

        $.ajax({
            url: '<?php echo $this->Html->url(array('plugin'=> false, 'controller' => 'UserAjax', 'action' => 'switchLang'), true); ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                lang: langVal
            },
            success: function(resp){
                if(resp.data.success) {
                    window.location.reload();
                }
            }
        });
	});

	$(".lang-block_list").mCustomScrollbar({
		autoDraggerLength: false
	});

	$('body').on('click', function(event) {
		if($(event.target).closest('.lang-block').length == 0 && !$(event.target).hasClass('change-lang_curr')){
			$('.lang-block').removeClass('lang-block_show');
		}
	});

});
</script>

</head>

<body class="front-page">
    <?=$this->element('ga')?>

    <div class="container">
		<?=$this->fetch('content')?>
    </div>

    <div class="footer">
        <div class="container">
            <div class="rightLinks" id="mmenu">
            	<ul>
					<li>
						<a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', 7))?>" target="_blank" class="dottedLine"><?=__('About Us')?></a>
					</li>

					<li><a href="/Terms.pdf" target="_blank" class="dottedLine"><?=__('Terms of Use')?></a></li>
					
					<li><a href="/Privacy.pdf" target="_blank" class="dottedLine"><?=__('Privacy Policy')?></a></li>

					<li class="change-lang">
						<a href="#" class="change-lang_curr"><?php echo $aLangOptions[Configure::read('Config.language')]; ?></a>
					</li>
				</ul>
            </div>

            <div class="footer-copyright">© KONSTRUKTOR Inc. <br><span><?php echo date('2014 - Y'); ?></span>&nbsp;</div>
        </div>
    </div>

    <div class="lang-block">
    	<ul class="lang-block_list">
            <?php foreach( $aLangOptions AS $key => $name ): ?>
                <li class="lang-block_item"><a href="" class="lang-block_link" data-val="<?php echo $key; ?>"><?php echo $name; ?></a></li>
            <?php endforeach; ?>
    	</ul>
    </div>
</body>

</html>
