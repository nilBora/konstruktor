<?php  ?>
<div class="front-header">
    <a href="#mmenu" class="mobile-nav">
        <span></span>
    </a>

    <a class="logo-company">
        <img alt="" src="/img/logo2.png" class="logo-image">
    </a>

    <div id="login_form_block" class="hidable">
        <div class="login_form_fb">
            <div class="fb_login" onclick="Login();"></div> <?=__('or')?>
        </div>

        <?=$this->element('User/login_form')?>
    </div>
</div>

<div class="front-content">
    <div class="front-content_title">
        <?=__('Fulfill your dream')?>
    </div>

    <div class="front-content_body">
        <?=__('Join our community of creative individuals from all around the globe.').' '.__('Create and Collaborate')?>
    </div>

    <!-- <a href="#" class="play-btn"></a> -->

    <a href="#register-popup" class="register-btn"><?=__('Fulfill dream now')?></a>

    <a href="#" class="login-btn">Войти в свой аккаунт</a>
</div>

<div class="login-content unvisible hide">
    <?=$this->element('User/login_form')?>

    <div class="login_form_fb">
        <?=__('or')?>

        <div class="fb_login" onclick="Login();">С помощью Facebook</div> 
    </div>

    <a href="#" class="close-login"><?=__('Backward')?></a>
</div>

<style type="text/css">
    .placeholdr { color: #c0d0de!important; }
</style>

<script type="text/javascript">

    $('.register-btn').magnificPopup({
        type:'inline',
        midClick: true
    });

    $('.remind-pass').magnificPopup({
        type:'inline',
        midClick: true
    });

    $('.login-btn').on('click', function(event) {
        event.preventDefault();

        $('.front-content').addClass('unvisible');

        window.setTimeout(function(){
            $('.front-content').addClass('hide');

            $('.login-content').removeClass('hide');

            window.setTimeout(function(){
                $('.login-content').removeClass('unvisible');
            }, 100);
        }, 600);
    });

    $('.close-login').on('click', function(event) {
        event.preventDefault();

        $('.login-content').addClass('unvisible');

        window.setTimeout(function(){
            $('.login-content').addClass('hide');

            $('.front-content').removeClass('hide');

            window.setTimeout(function(){
                $('.front-content').removeClass('unvisible');
            }, 100);
        }, 600);
    });
</script>

<div class="modal fade" id="regTermsAgree" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="outer-modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content small-size">
                <span id="responseMsg"><?=__('A confirmation mail was sent to your email address.')?></span><br /><br />
                <a href="javascript: void(0)" class="btn btn-primary" data-dismiss="modal" id="responseBtn"><?=__('Thanks')?></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#termsAgree').click ( function() {
        if( $('#fb-root').hasClass('login') ) {
            window.location.replace('<?=$this->Html->url(array('controller' => 'User', 'action' => 'interests'))?>');
        } else {

        }
    });
</script>


<div id="remind-popup" class="mfp-hide">
    <div id="password_form_block" class="hidable">
        <?=$this->element('User/pass_forget_form')?>
    </div>
</div>

<div id="register-popup" class="mfp-hide">
    <div id="register_form_block" >
        <?=$this->element('User/register_form')?>
    </div>
</div>
