<!doctype html>
<html lang="ru">
<head>
    <!--[if lt IE 9]>
        <meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
    <![endif]-->
    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=0.7, initial-scale=0.7, minimum-scale=0.7">
    <title>Konstruktor.com - <?=__('Messenger')?></title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
<?php
    //echo $this->Html->meta('icon');

    $vendorCss = array(
        'fonts',
        'bootstrap/bootstrap'
    );
    $css = array(
        'main-panel-new',
        'content',
        'style',
        'chat-old',
        'device-page',
        'progress-bar',
    );
    echo $this->Html->css(array_merge($vendorCss, $css));

    echo $this->Websockets->init();

    $vendorScripts = array(
        'vendor/jquery/jquery-1.10.2.min',
        'vendor/jquery/jquery.easing.1.3',
        'vendor/jquery/jquery.backgroundSize',
        'vendor/jquery/jquery.form.min',
        'vendor/jquery/jquery.ui.widget',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/jquery/jquery.fileupload',
        'vendor/jquery/jquery.linkify.min',
        'vendor/moment',
        'vendor/formstyler',
        'vendor/bootstrap.min',
        'vendor/tmpl.min',
        'vendor/jquery.nicescroll.min',
        'vendor/double-tap',
        'vendor/autosize.min',
        'vendor/tappy',
        'vendor/fastclick',
    );

    $scripts = array(
        '/core/js/json_handler',
        '/table/js/format',
        'main-panel',
        'xdate', 'xarray',
        'chat', 'chat_panel', 'chat_room',
        'struct',
        'search',
        'note',
        'article',
        'group',
        'upload_chat',
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
var objectType = 'Chat', objectID = null;

$(function() {
    FastClick.attach(document.body);
});

$(document).ready(function () {
    $.ajaxSetup({ cache: false });

    $(window).resize(function() {
        Chat.fixPanelHeight();
    });

    $('select.formstyler').styler();

    $('input.attachFile').styler({fileBrowse: '<span class="glyphicons paperclip"></span>'});

    $('#sendChatMsg, #sendChatMsg .submitArrow').on('click mouseup', function(e) {
        Chat.Panel.rooms[Chat.Panel.activeRoom].sendMsg();
        setTimeout(function(){
            $('.sendForm textarea').trigger('autosize.resize');
            Chat.fixPanelHeight();
        }, 20);
        e.stopPropagation();
    });

    if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'click',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });

        $('body').on('touchstart', function(e) {
            if( !($(e.target).is('#sendChatSmile') || $(e.target).is('.smileSelect') || $(e.target).parents('#sendChatSmile').length > 0) ) {
                $('#sendChatSmile').popover('hide');
            }
        });
    } else if((navigator.userAgent.indexOf("Safari") > -1) || (navigator.userAgent.indexOf("Mozilla") > -1)) {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'click',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });
    } else {
        $('#sendChatSmile').popover({
            html : true,
            placement: 'top',
            class: 'smilesPopover',
            trigger: 'focus',
            content: function() {
              return $('#popover_content_wrapper').html();
            }
        });
        console.log(navigator.userAgent);
    }

    $('.sendForm textarea').autosize();
    $('.sendForm textarea').on('keyup copy cut paste change', function() {
        $('.sendForm textarea').trigger('autosize.resize');
        Chat.fixPanelHeight();
    });
    $('.sendForm textarea').bind('keydown', function(event) {
        if (event.ctrlKey && event.keyCode == 13) {
            var value = $(this).val();
            $(this).val(value+'\n');
        } else {
            if ( event.keyCode == 13 ) {
                event.preventDefault();
                Chat.Panel.rooms[Chat.Panel.activeRoom].sendMsg();
            }
        }
    });

    $(document).on('click', '.smileSelect', function() {
        console.log($(this).text());
        text = $('.sendForm textarea').val().length > 0 ? ' '+$(this).text() : ''+$(this).text();
        $('.sendForm textarea').val( $('.sendForm textarea').val() + text );

        if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (navigator.userAgent.indexOf("Safari") > -1)) {
            $('#sendChatSmile').popover('hide');
        }
    });

    $('.abortload-chat-file').click(function (){
        var $e = $('.preloadThumb img');
        if (!$e.length) {
            $e = $('.preloadFile span');
        }
        $e.data().abort();
        $e.remove();
        $('.preloadArea .circle_remove').hide();
        $('.preloadArea .process').hide();
        $('.preloadArea').hide();
        Chat.fixPanelHeight();
    });
});
</script>
</head>
<body>
<?=$this->element('ga')?>
<?php if($this->params['controller'] == 'Chat') { ?>

<? echo $this->element('panel_menu')?>
<div class="wrapper-container chat-page">

    <div class="dropdown-chatPanel">
        <div class="dropdown-panel-wrapper">

        </div>
    </div>

    <div class="usersInChat chat-members">
        <!--a href="javascript: void(0)" class="addUser glyphicons plus text-center"></a-->
    </div>
    <div class="bottom">
        <div class="openChats chat-tabs clearfix"></div>
        <?=$this->element('/Chat/send_message')?>
    </div>
    <div class="chat-dialogs">
        <?=$this->fetch('content')?>
    </div>
</div>

<?php } ?>

<?=$this->element('js_templates')?>
<?=$this->element('video_popup')?>
</body>
</html>
