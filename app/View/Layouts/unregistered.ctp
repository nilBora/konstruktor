<!doctype html>
<html lang="ru">
<head>
    <!--[if lt IE 9]>
        <meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
    <![endif]-->
    <?=$this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?= ((isset($title)) ? (is_array($title) ? $this->fetch('title', $title).' | Konstruktor.com' : $title.' | Konstruktor.com') : 'Konstruktor.com | '.__('Main page')) ?></title>

    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css' />
    <link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
    <meta name="description" content="<?=isset($title) ? $title : '';?>">
<?php
    //echo $this->Html->meta('icon');

    $vendorCss = array(
        'fonts',
        'bootstrap.min',
    );

    $css = array(
        'main-panel-new',
        'magnific-popup',
        'register-popup',
        'style'
    );

    echo $this->Html->css(array_merge($vendorCss, $css));


    $vendorScripts = array(
        'vendor/jquery/jquery-1.10.2.min',
        'vendor/jquery/jquery-ui.min',
        'vendor/jquery/jquery.form.min',
        'vendor/jquery/jquery.ui.widget',
        //'vendor/easing.1.3',
        'vendor/jquery/jquery.formstyler.min',
        'vendor/jquery.magnific-popup.min',
        //'vendor/moment',
        'vendor/bootstrap.min',
        'vendor/autosize.min',
        'vendor/tmpl.min',
    );

    $scripts = array(
        '/core/js/json_handler',
        '/table/js/format',
        'struct',
        'login',
        'xdate'
    );
    echo $this->Html->script(array_merge($vendorScripts, $scripts));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
?>

<script type="text/javascript">

var fbUserInfo = null;
$(function() {
    $.ajaxSetup({ cache: false });

    $('select.formstyler, input.filestyle').styler({
        fileBrowse: '<?=__('Upload image')?>'
    });
});
window.fbAsyncInit = function () {
    FB.init({
        appId: '<?=Configure::read('fbApiKey')?>', // App ID
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true // parse XFBML
    });
};
(function (d) {
    var js, id = 'facebook-jssdk',
        ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));
function getUserInfo() {
    var obj;
    FB.api('/me', function (response) {

        fbUserInfo = response;

        $.post('<?=$this->Html->url(array('controller' => 'User', 'action' => 'fbAuthCheck'))?>.json', {
            data: response
        }, function (checked) {
            var obj = jQuery.parseJSON( checked );
            loginFacebook(fbUserInfo);
        });
    });
}
function loginFacebook(fbData) {
    $.post('<?=$this->Html->url(array('controller' => 'User', 'action' => 'fbAuth'))?>.json', {
        data: fbData
    }, function (response) {
        var obj = jQuery.parseJSON( response );
        if( obj.status == 'LOGIN' || obj.status == 'REGISTER' ) {
            window.location.replace('<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index'))?>');
        } else {
            alert('Error while registering');
        }
    });
}
function Login() {
    FB.login(function (response) {
        if (response.authResponse) {
            var data = getUserInfo(); // Get User Information.
        } else {
            alert('<?=__('Facebook authorization failed.')?>');
        }
    }, {
        scope: 'email'
    });
}

</script>

    <style type="text/css">
        .register { width: 100%; max-width: 915px; box-sizing: border-box; padding: 15px; background: #333; min-height: 52px; margin: 0; }
        .register * { font-size: 12px!important; }
            .register .logo { height: 26px; }
                .register .logo img { float: left; height: 26px; }
            .register .formInput { border-bottom: 1px solid white; height: 26px; }
            .register .menuGroup { float: right; }
                .register .menuGroup .topInput input { height: 30px; border: none; background: transparent; color: white; }
                .register .menuGroup .topInput input:focus { box-shadow: none; }

                .register .menuGroup .topInput,
                .register .menuGroup .btnGroup { display: inline-block; height: 26px; box-sizing: border-box; margin: 0 5px;}

                .register .menuGroup .topInput { width: 160px;}
                .register .menuGroup .btnGroup { width: 280px; padding-right: 20px;}
                .register .menuGroup .btnGroup .btn { position: relative; border: 1px solid #aaa; padding 4px 12px; margin-right: 1px; height: 26px; color: #777; background: transparent;
                                -webkit-transition: all .2s; -moz-transition: all .2s; -ms-transition: all .2s; -o-transition: all .2s; transition: all .2s; }
                .register .menuGroup .btnGroup .btn:hover { color: white; border-color: white; }
                .register .menuGroup .btnGroup .fbLogin {
                    height: 26px;
                    display: inline-block;
                    position: relative;
                    text-decoration: none;
                    color: white;
                    cursor: pointer;
                }
                .register .menuGroup .btnGroup .text {
                    display: inline-block;
                    position: relative;
                    height: 20px;
                    line-height: 20px
                }
                .register .menuGroup .btnGroup .fbLogin .fbIcon {
                    display: inline-block;
                    position: absolute;
                    top: 0;
                    right: -30px;
                    padding: 2px;
                    border-radius: 4px;
                    background-color: rgba(255, 255, 255, .15);
                    height: 20px;
                    width: 20px;
                    -webkit-transition: all .4s;
                    -moz-transition: all .4s;
                    -ms-transition: all .4s;
                    -o-transition: all .4s;
                    transition: all .4s;
                }

                .register .menuGroup .btnGroup .fbLogin .fbIcon svg {
                    fill  : #ffffff;
                    -webkit-transition: all .4s;
                    -moz-transition: all .4s;
                    -ms-transition: all .4s;
                    -o-transition: all .4s;
                    transition: all .4s;
                }
                .register .menuGroup .btnGroup .fbLogin:hover .fbIcon svg {
                    fill  : #4C66A4;
                    -webkit-transition-duration: 0;
                    -moz-transition-duration: 0s;
                    -ms-transition-duration: 0s;
                    -o-transition-duration: 0s;
                    transition-duration: 0s;
                }

                .register .menuGroup .btnGroup .fbLogin:hover .fbIcon {
                    background-color: rgba(255, 255, 255, .85);
                    -webkit-transition-duration: 0s;
                    -moz-transition-duration: 0s;
                    -ms-transition-duration: 0s;
                    -o-transition-duration: 0s;
                    transition-duration: 0s;
                }

        @media (max-width: 919px) {
            .register .menuGroup .topInput { width: 120px;}
        }
        @media (min-width: 768px) {
            .register .menuGroup { max-width: 640px; }
            .register .menuGroup .col-sm-4{ padding: 0 5px; }
        }
        @media (max-width: 767px) {
            .register .logo { width: 23%; padding-bottom: 16px; }
            .register .menuGroup { width: 100%; margin: 5px 0; text-align: center; }
            .register .menuGroup .topInput { width: 100%; margin: 5px 0;}
            .register .menuGroup .btnGroup { width: 280px; margin-top: 15px; display: inline-block;}
        }

    </style>

</head>
<body>
<?=$this->element('ga')?>
<div style="width: 100%; background: #333">
    <div class="register row fixedLayout" style="margin: 0 auto;">
        <div class="col-md-3 col-sm-3 logo">
            <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'login'))?>" class="logo pull-left"><img alt="" src="/img/logo.png" class="logo-image"></a>
        </div>

        <div class="menuGroup pull-right row">
            <?=$this->Form->create('User', array('url' => array('controller' => 'User', 'action' => 'login'), 'class' => 'formFields', 'id' => 'authForm'))?>
            <div class="topInput">
                <div class="formInput">
                    <?=$this->Form->input('username', array('label' => false, 'placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'authMail'))?>
                </div>
            </div>
            <div class="topInput">
                <div class="formInput">
                    <?=$this->Form->input('password', array('label' => false, 'placeholder' => __('Password'), 'class' => 'form-control passwd', 'id' => 'authPasswd'))?>
					<?=$this->Form->hidden('url',['value'=>Router::url( $this->here, true )]);?>
                </div>
            </div>
            <div class="btnGroup">
                <!--a class="btn" href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'login'))?>"><?=__('Register')?></a-->
                <button class="btn" id="authLogin" type="submit"><?=__('Log In')?></button>
                <a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'login'))?>" class="btn" id="authRegister"><?=__('Register')?></a>
                <a class="fbLogin" onclick="Login();">
                    <span class="text"><?=__('Log in using')?></span>
                    <span class="fbIcon">
                        <svg viewBox="0 0 16 16" style="width: 16px; height: 16px;">
                            <path d="M13 0H3C1 0 0 1 0 3v10c0 2 1 3 3 3h5V9H6V7h2V5c0-2 2-2 2-2h3v2h-3v2h3l-.5 2H10v7h3c2 0 3-1 3-3V3c0-2-1-3-3-3z"></path>
                        </svg>
                    </span>
                </a>
            </div>
            <?=$this->Form->end()?>
            <div class="clearfix"></div>
        </div>

    </div>
</div>
<?php
if(isset($page) && $page == 'file/preview'):
    $style = "padding: 0 60px 0 75px";
    $fixed = "";
else:
    $style = "padding: 0;";
    $fixed = " fixedLayout";
endif;
?>
<div class="wrapper-container" style="<?php echo $style?>">
    <div class="container-fluid<?php echo $fixed;?>">
        <?=$this->fetch('content')?>
    </div>
</div>
</body>
</html>
