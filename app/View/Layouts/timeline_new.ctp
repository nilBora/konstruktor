<!doctype html>
<html lang="ru">
<head>
    <!--[if lt IE 9]>
        <meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
    <![endif]-->

    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=0.7, initial-scale=0.7, minimum-scale=0.7">
    <meta name="format-detection" content="telephone=no">
    <title><?=(isset($title) ?  $title.' | Konstruktor.com' : 'Konstruktor: '.__('My time'));?></title>
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
    <meta name="description" content="<?=isset($title) ? $title : '';?>">
    <meta name="robots" content="noindex, nofollow"/>
<?php
    //echo $this->Html->meta('icon');

    $vendorCss = array(
        'fonts',
        'bootstrap.min',
        'bootstrap-datetimepicker',
        'bootstrap/bootstrap-tokenfield',
        'vendor/ChatJs/jquery.chatjs.css',
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
        'vendor/jquery.ui.touch-punch.js',
        'vendor/formstyler',
        'vendor/moment',
        'vendor/easing.1.3',
        'vendor/bootstrap.min',
        'vendor/bootstrap-datetimepicker.min',
        'vendor/bootstrap-datetimepicker.ru',
        'vendor/tmpl.min',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/jquery/jquery.fileupload',
        'vendor/jquery.nicescroll.min',
        'vendor/double-tap',
        'vendor/jquery.autocomplete',
        'vendor/autosize.min',
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
        'main-panel',
        'chat', 'chat_panel',
        'struct',
        'search',
        'note',
        'article',
        'group',
        'cloud',
        'cloud-mover',
        'timeline_new',
        'xdate',
        //'notify_profile',
        'finance',
        'upload',
        'invest_category',
        'photoswipe/photoswipe',
        'photoswipe/photoswipe-ui-default'
    );

    echo $this->Html->script(array_merge($vendorScripts, $scripts));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

?>
<script type="text/javascript" src="<?=$this->Html->url(array('plugin' => false, 'controller' => 'js', 'action' => 'settings'), true)?>?<?= time(); ?>"></script>
<script type="text/javascript">
var minichat = null;
$(function() {

    FastClick.attach(document.body);

    $.ajaxSetup({ cache: false });

    $('select.formstyler, input.filestyle, .bigCheckBox input').styler({
        fileBrowse: '<?=__('Upload image')?>'
    });
    // $('input.clock-mask').setMask('time');

    FastClick.attach(document.body);

    $.ajaxSetup({ cache: false });

    $('select.formstyler, input.filestyle, .bigCheckBox input').styler({
        fileBrowse: '<?=__('Upload image')?>'
    });
    // $('input.clock-mask').setMask('time');

    Search.initPanel($('.dropdown-searchPanel .dropdown-panel-wrapper').get(0));
    //Disabled due minichat update timeline
    //Chat.initPanel($('.dropdown-chatPanel .dropdown-panel-wrapper').get(0));
    Struct.initPanel($('.dropdown-ipadPanel .dropdown-panel-wrapper').get(0));
    Group.initPanel($('.dropdown-groupPanel .dropdown-panel-wrapper').get(0));
    Article.initPanel($('.dropdown-notesPanel .dropdown-panel-wrapper').get(0));
    Cloud.initPanel($('.dropdown-cloudPanel .dropdown-panel-wrapper').get(0));
    Finance.initPanel($('.dropdown-financePanel .dropdown-panel-wrapper').get(0));
    InvestCategory.initPanel($('.dropdown-investPanel .dropdown-panel-wrapper').get(0));

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
    });
});

</script>
</head>
<body>
<?=$this->element('ga')?>
<div class="absoluteWrap clearfix" style="position: fixed; top: 0; left: 0;">
    <?=$this->element('panel_menu')?>
	<!-- moved headerTimeline in panel_menu.ctp -->
    <!--  <div class="headerTimeline clearfix row" style="margin-top: 136px;">
        <span class="ajax-loader" style="display: none;"><img src="/img/ajax_loader.gif" alt="" style="width: 20px; height: 20px;"> <?=__('Loading...')?></span>
        <div class="col-sm-6">
            <div id="breadcrumb" style="display: none;">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Library</a></li>
                    <li class="active">Data</li>
                </ol>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="btn-group">
                <button id="showDay" class="btn btn-default active" type="button"><?=__('Day')?></button>
                <button id="showWeek" class="btn btn-default" type="button"><?=__('Week')?></button>
                <button id="showMonth" class="btn btn-default" type="button"><?=__('Month')?></button>
                <button id="showYear" class="btn btn-default" type="button"><?=__('Year')?></button>
            </div>
        </div>
    </div> -->

    <div class="wrapper-container">
        <div class="container-fluid fixedLayout wider timeline-page">
            <?=$this->fetch('content')?>
        </div>
    </div>

</div>

<?=$this->element('js_templates')?>
<?=$this->element('photo_swipe')?>
<?=$this->element('video_popup')?>
</body>
</html>
