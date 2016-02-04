<?php  ?>
<div class="front-header">
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
</div>

<style type="text/css">
    .placeholdr { color: #c0d0de!important; }
</style>

<script type="text/javascript">

    $('.register-btn').magnificPopup({
        type:'inline',
        midClick: true,
        callbacks: {
        open: function() {
            // $('.front-page').addClass('blur-bg');
			$('#register_form_block').show();
        },
        close: function() {
            // $('.front-page').removeClass('blur-bg');

        }
      }
    });
    $('.remind-pass').magnificPopup({
        type:'inline',
        midClick: true,
        callbacks: {
        open: function() {
			$('#register_form_block').hide();
            $('#password_form_block').show();
			$('#passwdMail').show();
			$('#passwdReset').show();
			$('.recovery-message').hide();
        },
        close: function() {
			$('#register_form_block').hide();
			$('#password_form_block').hide();
        }
      }
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

<div id="register-popup" class="mfp-hide" >
    <div id="password_form_block" class="hidable" style="display: none;">
        <?=$this->element('User/pass_forget_form')?>
    </div>

    <div id="register_form_block" >
        <?=$this->element('User/register_form')?>
    </div>
</div>
