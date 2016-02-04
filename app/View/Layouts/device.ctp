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

<?php
    echo $this->Html->meta('icon');

    $vendorCss = array(
        'reset',
        'fonts',
        'bootstrap/bootstrap',
		'vendor/ChatJs/jquery.chatjs.css',
		'vendor/animate',
    );
    $css = array(
        'main-panel-new',
        'content',
        // 'chat-page',
        'chat-old',
        'konstruktor',
        'device-page',
        'style',
        'progress-bar'
    );
    echo $this->Html->css(array_merge($vendorCss, $css));

    $vendorScripts = array(
        'vendor/jquery/jquery-1.10.2.min',
		'vendor/jquery/jquery-ui.min',
        'vendor/jquery/jquery.easing.1.3',
        'vendor/jquery/jquery.backgroundSize',
        'vendor/jquery/jquery.form.min',
        'vendor/jquery/jquery.ui.widget',
        'vendor/jquery/jquery.iframe-transport',
        'vendor/jquery/jquery.fileupload',
        'vendor/jquery/jquery.maskedinput',
        'vendor/formstyler',
        'vendor/bootstrap.min',
        'vendor/tmpl.min',
        'vendor/jquery.nicescroll.min',
        'vendor/double-tap',

		'vendor/tappy',
		'vendor/fastclick',
		'vendor/autosize.min',


		'vendor/ChatJs/jquery.chatjs.utils.js',
		'vendor/ChatJs/jquery.chatjs.adapter.servertypes.js',
		'vendor/ChatJs/jquery.chatjs.adapter.js',
		'vendor/ChatJs/jquery.chatjs.adapter.konstruktor.js',
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
        'xdate', 'xarray',
        'chat', 'chat_panel', 'chat_room',
        'struct',
        'search',
        'note',
        'article',
        'group',
        'upload',
        //'notify_profile',
        'cloud',
        'cloud-mover',
        'device-script',
        'finance',
        'invest_category',
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
<script type='text/javascript'>
    $(document).ready(function() {
        $('#addFundsButton').on('click', function(e){
            $(this).hide();
            $('#addFundsBlock').show().css("display", "inline-block");;
        });
        $('#addFundsForm').submit(function(e){
            e.preventDefault();
            amount = $('#addFundsAmount').val();
            if(amount.match(/^[0-9\.]+$/)){
                this.submit();
            } else {
                alert('<?php echo __('Only numbers allowed for balance amount') ?>');
            }
        });
        $('#cancelFundsForm').on('click', function(e){
            $('#addFundsBlock ').hide();
            $('#addFundsButton').show();
        });
    });
</script>
</head>
<body>
<?=$this->element('ga')?>
<?=$this->element('panel_menu')?>
<div class="wrapper-container">
    <div class="container-fluid fixedLayout">
        <div class="device-page">
            <div class="header-device-page clearfix">
                <div class="state-of-account col-md-12 col-sm-5 col-xs-5 t-a-right clearfix">
                    <a href="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'orders'))?>" class="device-my-orders"><?=__('My orders')?></a>
                    <a href="<?=$this->Html->url(array('controller' => 'Device', 'action' => 'checkout'))?>" class="device-my-orders" style="margin-left: 20px;"><?=__('New order')?></a>
                    <div class="pull-left account-money text-left" style="margin-left:20px;min-width:500px;">
                        <div class="accountBalance-container">
                            <span class="accountBalance bold"><?php echo __('Current balance') ?>: <strong><?php echo $currUser['User']['balance']; ?> USD</strong></span>
                            <a id="addFundsButton" class="linkBlock" style="width:auto;margin-left:10px;" href="javascript:void(0)">
                                <i class="glyphicons plus"></i><span><?php echo __('Add funds') ?></span>
                            </a>
                            <div id="addFundsBlock" class="linkBlock" style="width:350px;margin-left:10px;display:none;">
                                <form id="addFundsForm" method="post" action="<?php echo $this->Html->url(array('plugin' => 'billing', 'controller' => 'billing_user', 'action' => 'payment')) ?>">
                                    <input type="text" id="addFundsAmount" name="amount" value="10">
                                    <button id="sendFundsForm" type="submit"><?php echo __('Pay') ?>
                                    <button id="cancelFundsForm" type="button"><?php echo __('Cancel') ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="device-page-wrapper">
                <?=$this->fetch('content')?>
            </div>
        </div>
    </div>
</div>

<?=$this->element('js_templates')?>
</body>
</html>
