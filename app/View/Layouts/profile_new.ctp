<!doctype html>
<html lang="ru" prefix="og: http://ogp.me/ns#">
<head>
    <!--[if lt IE 9]>
        <meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
    <![endif]-->
    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=0.7, initial-scale=0.7, minimum-scale=0.7">
    <title><?= ((isset($title)) ? (is_array($title) ? $this->fetch('title', $title).' | Konstruktor.com' : $title.' | Konstruktor.com') : 'Konstruktor.com | '.__('Main page')) ?></title>
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
    <meta name="description" content="<?=isset($title) ? $title : '';?>">
    <meta name="robots" content="noindex, nofollow"/>
<?php
    //echo $this->Html->meta('icon');
    //$this->fetch('title', $title)
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

    echo $this->Websockets->init();

    $vendorScripts = array(
        'vendor/jquery/jquery-1.10.2.min',

        'vendor/jquery/jquery-ui.min',
        'vendor/jquery/jquery.form.min',
        'vendor/jquery/jquery.ui.widget',
        'vendor/jquery/jquery-ui-1.10.3.custom.min',
        'vendor/jquery/jquery.fancybox.pack',
        'vendor/jquery.ui.touch-punch.js',
        //'vendor/easing.1.3',
        'vendor/jquery/jquery.formstyler.min',
        'vendor/noty/packaged/jquery.noty.packaged.min',
        'vendor/moment',
        'vendor/bootstrap.min',
        'vendor/autosize.min',
        'vendor/tmpl.min',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/jquery/jquery.fileupload',
        'vendor/jquery.nicescroll.min',
        'vendor/double-tap',
        'vendor/tappy',
        'vendor/fastclick',


        'vendor/ChatJs/jquery.chatjs.utils.js',
        'vendor/ChatJs/jquery.chatjs.adapter.servertypes.js',
        'vendor/ChatJs/jquery.chatjs.adapter.js',
        //'vendor/ChatJs/jquery.chatjs.adapter.konstruktor.js',
        'vendor/ChatJs/jquery.chatjs.adapter.konstruktor_hw.js',
        'vendor/ChatJs/jquery.chatjs.window.js',
        'vendor/ChatJs/jquery.chatjs.messageboard.js',
        'vendor/ChatJs/jquery.chatjs.userlist.js',
        'vendor/ChatJs/jquery.chatjs.pmwindow.js',
        'vendor/ChatJs/jquery.chatjs.friendswindow.js',
        'vendor/ChatJs/jquery.chatjs.controller.js',

        'vendor/iscroll.js',
        'upload.js',
        'upload_chat.js'
    );

    $scripts = array(
        '/core/js/json_handler',
        '/table/js/format',
        'main-panel',
        //'main-panel_new',
        'chat', 'chat_panel', 'chat_room',
        'struct',
        'search',
        'note',
        'article', 'article_category',
        'group',
        'cloud',
        'cloud-mover',
        'project-page',
        'xdate',
        //'notify_profile',
        'upload',
        'finance',
        'modal_conf',
        'invest_category',
        'highcharts',
        'exporting',
        'photoswipe/photoswipe',
        'photoswipe/photoswipe-ui-default'
    );

    echo $this->Html->script(array_merge($vendorScripts, $scripts));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

?>
<script type="text/javascript" src="<?=$this->Html->url(array('plugin' => false, 'controller' => 'js', 'action' => 'settings'), true)?>?<?= time(); ?>"></script>
<script type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
<script type="text/javascript">
var minichat = null;
$(document).ready(function() {
    FastClick.attach(document.body);

    $.ajaxSetup({ cache: false });

    $('select.formstyler, input.filestyle').styler({
        fileBrowse: '<?=__('Upload image')?>'
    });
    $('.textarea-auto').autosize();

    $(function () {
        minichat = $.chat({
            // your user information
            userId: <?=Hash::get($currUser, 'User.id')?>,
            // id of the room. The friends list is based on the room Id
            roomId: 1,
            // text displayed when the other user is typing
            typingText: ' is typing...',
            // text displayed when there's no other users in the room
            emptyRoomText: "There's no one around here. You can still open a session in another browser and chat with yourself :)",
            // the adapter you are using
            chatJsContentPath: 'js/vendor/ChatJs/',
            adapter: new KonstruktorAdapter(<?=Hash::get($currUser, 'User.id')?>)
        });

        $('[data-toggle="tooltip"]').tooltip();
    });
});
</script>
	<script type="text/javascript" src="https://vk.com/js/api/share.js?90" charset="windows-1251"></script>
	<script src="https://apis.google.com/js/client:platform.js" async defer></script>
</head>
<body>
<?=$this->element('ga')?>
<?=$this->element('panel_menu')?>

<div class="wrapper-container">
<?
        $style = '';
        $class = '';

        if($this->params['controller'] == 'Cloud') {
            $class = 'fixedLayout wider';
        } else if($this->params['controller'] == 'Timeline' || $this->params['controller'] == 'File'){
            $class = '';
        } else {
            $class = 'fixedLayout';
        }
?>
    <div class="container-fluid <?=$class?>" <?=$style?>>
        <?php /*
            if( isset($groupHeader) && (
                $this->params['controller'] == 'FinanceAccount' ||
                $this->params['controller'] == 'FinanceBudget' ||
                $this->params['controller'] == 'FinanceCalendar' ||
                $this->params['controller'] == 'FinanceCategory' ||
                $this->params['controller'] == 'FinanceGoal' ||
                $this->params['controller'] == 'FinanceOperation' ||
                $this->params['controller'] == 'FinanceProject' ||
                $this->params['controller'] == 'FinanceReport' ||
                $this->params['controller'] == 'FinanceShare' ||
                $this->params['controller'] == 'InvestCategory' ||
                $this->params['controller'] == 'InvestProject' ||
                $this->params['controller'] == 'InvestReward' ||
                $this->params['controller'] == 'InvestSponsor' ||
                $this->params['controller'] == 'InvestVideo'
            )) {
                echo $this->element('group_header', array( 'group' => $groupHeader));
            }
       */ ?>
        <?=$this->fetch('content')?>
    </div>
</div>
<?=$this->element('js_templates')?>
<?=$this->element('photo_swipe')?>
<?=$this->element('video_popup')?>
</body>
</html>
