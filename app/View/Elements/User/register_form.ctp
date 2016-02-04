<?php
    $viewStyles = array(
        'bootstrap/bootstrap-tokenfield'
    );

    $this->Html->css($viewStyles, null, array('inline' => false));

    $viewScripts = array(
        'vendor/bootstrap-tokenfield',
        'vendor/jquery/jquery-ui.min',
        'vendor/jquery/jquery.form.min',
        'vendor/jquery/jquery.ui.widget'
    );
    $this->Html->script($viewScripts, array('inline' => false));
    $domain = str_replace(array('www.', 'stg.'), array('', ''), $_SERVER['SERVER_NAME']);

?>

<style type="text/css">
    .likely-light .likely__widget { background: transparent; }
    .likely-light .likely__widget_facebook:hover, .likely-light .likely__widget_facebook:active, .likely-light .likely__widget_facebook:focus { background: transparent; }
    .fbLogin {
        border-radius: 3px;
        background: rgba(236,236,236,.16);
        padding: 0 3px 0 6px;
        color: white;
        line-height: 29px;
        cursor: pointer;
        font-size: 13px;
      -webkit-transition: all .2s linear;
           -moz-transition: all .2s linear;
            -ms-transition: all .2s linear;
             -o-transition: all .2s linear;
                transition: all .2s linear;
    }
    .fbLogin:hover { background: rgba(66,84,151,.7);
        -webkit-transition: all 0s linear;
               -moz-transition: all 0s linear;
              -ms-transition: all 0s linear;
                  -o-transition: all 0s linear;
                  transition: all 0s linear;
    }
<?
    if( Configure::read('Config.language') == 'rus' ) {
?>
    .fbLogin { font-size: 12px; height: 27px; line-height: 27px; }
    #regSubmit { font-size: 13px; }
    .likely-big { margin-top: -3px; }
<?
    }
?>

</style>

<div class="register-title"><?=__('Free registration')?></div>

    <?=$this->Form->create('User', array('url' => array('controller' => 'User', 'action' => 'register'), 'class' => 'formFields', 'id' => 'regForm', 'name' => 'regForm'))?>

    <?=$this->Form->hidden('lat', array('id' => 'UserLat', 'autocomplete' => 'false'));?>
    <?=$this->Form->hidden('lng', array('id' => 'UserLng', 'autocomplete' => 'false'));?>

    <div class="form-group_row clearfix">
        <div class="form-group half left">
            <label for="#UserName"></label>
            <?=$this->Form->input('full_name', array('label' => false, 'placeholder' => __('First name'), 'id' => 'UserName', 'class' => 'form-control', 'autocomplete' => 'false'))?>
        </div>

        <div class="form-group half right">
            <label for="#UserSurname"></label>
            <?=$this->Form->input('surname', array('label' => false, 'placeholder' => __('Last name'), 'id' => 'UserSurname', 'class' => 'form-control', 'autocomplete' => 'false'))?>
        </div>
    </div>

    <div class="form-group_row clearfix">
        <div class="form-group">
            <label for="#regMail"></label>
            <?=$this->Form->input('username', array('label' => false, 'placeholder' => __('Email'), 'id' => 'regMail', 'class' => 'form-control', 'autocomplete' => 'false'))?>
        </div>

        <div class="form-group">
            <label for="#regPasswd"></label>
            <?=$this->Form->input('password', array('label' => false, 'placeholder' => __('Password'), 'id' => 'regPasswd', 'class' => 'form-control', 'autocomplete' => 'false'))?>
        </div>
    </div>

    <div class="register-fb">
        <?=__('or')?> <span class="register-fb_btn" onclick="Login();"></span>
    </div>

    <a href="javascript: void(0)" class="btn btn-primary" id="regSubmit"><?=__('Next')?></a>

    <p class="register-descr">
        <?=__('By clicking on the "Next" button, you are accepting and agreeing with our %s and you are acknowledging that you have read our%s.',
            '<a href="/Terms.pdf" target="_blank" style="text-decoration: underline;">'.__('Terms and Conditions,').'</a>' ,
            ' <a href="/Privacy.pdf" target="_blank" style="text-decoration: underline;">'.__('Privacy Policy').'</a>' ) ?>
    </p>

    <?=$this->Form->end()?>

<script type="text/javascript">
    function checkRegFields() {

        var allow = true;

        if( IsEmail($('#regMail').val()) ) {
            $('#regMail').removeClass('incorrect');
            $('#regMail').closest('.form-group').removeClass('incorrect');
        } else {
            // $('#regMail').val('');
            $('#regMail').removeClass('incorrect').addClass('incorrect');
            $('#regMail').closest('.form-group').removeClass('incorrect').addClass('incorrect');
            $('#regMail').popover('destroy');

            setTimeout((function () {
                if( (/webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (/Chrome|Android|iPad/i.test(navigator.userAgent) && $(window).width()) < $(window).height() ) {
                    $('#regMail').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Enter valid email address')?>" });
                } else {
                    $('#regMail').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Enter valid email address')?>" });
                }
                $('#regMail').popover('show');
            }), 500);

            allow = false;
        }

        if( TestString($('#UserName').val(), 2)) {
            $('#UserName').removeClass('incorrect');
            $('#UserName').closest('.form-group').removeClass('incorrect');
        } else {
            $('#UserName').removeClass('incorrect').addClass('incorrect');
            $('#UserName').closest('.form-group').removeClass('incorrect').addClass('incorrect');
            $('#UserName').popover('destroy');

            setTimeout((function () {
                $('#UserName').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Enter your first name')?>" });
                $('#UserName').popover('show');
            }), 500);

            allow = false;
        }

        if(TestString( $('#UserSurname').val(), 2)) {
            $('#UserSurname').removeClass('incorrect');
            $('#UserSurname').closest('.form-group').removeClass('incorrect');
        } else {
            $('#UserSurname').removeClass('incorrect').addClass('incorrect');
            $('#UserSurname').closest('.form-group').removeClass('incorrect').addClass('incorrect');
            $('#UserSurname').popover('destroy');

            setTimeout((function () {
                $('#UserSurname').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Enter your last name')?>" });
                $('#UserSurname').popover('show');
            }), 500);

            allow = false;
        }

        if( $('#regPasswd').val().length > 5 ) {
            $('#regPasswd').removeClass('incorrect');
            $('#regPasswd').closest('.form-group').removeClass('incorrect');
        } else {
            $('#regPasswd').val('');
            $('#regPasswd').removeClass('incorrect').addClass('incorrect');
            $('#regPasswd').closest('.form-group').removeClass('incorrect').addClass('incorrect');
            $('#regPasswd').popover('destroy');

            setTimeout((function () {
                if( (/webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (/Chrome|Android|iPad/i.test(navigator.userAgent) && $(window).width()) < $(window).height() ) {
                    $('#regPasswd').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Password must be at least 6 characters')?>" });
                } else {
                    $('#regPasswd').popover({ toggle: 'popover', placement: 'bottom', content: "<?=__('Password must be at least 6 characters')?>" });
                }
                $('#regPasswd').popover('show');
            }), 500);

            allow = false;
        }

        setTimeout((function () {
            initRegPopovers();
        }), 500);
        return allow;
    }

    function split( val ) {
        return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }

    function initRegPopovers() {
        if( (/webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (/Chrome|Android|iPad/i.test(navigator.userAgent) && $(window).width()) < $(window).height() ) {
            $('#regMail').popover({ toggle: 'popover', trigger: 'focus', placement: 'bottom', content: "<?=__('You will need email to log in')?>" });
            //$('#UserSkills-tokenfield').popover({ toggle: 'popover', trigger: 'focus', placement: 'top', content: "<?=__('What are your proficiencies?')?>" });
            //$('#regForm .tokenfield.form-control').popover({ toggle: 'popover', trigger: 'focus', placement: 'top', content: "<?=__('What are your proficiencies?')?>" });
            $('#regPasswd').popover({ toggle: 'popover', trigger: 'focus', placement: 'bottom', content: "<?=__('A combination of 6 digits and characters')?>" });
            //$('#UserName').popover({ toggle: 'popover', trigger: 'focus', placement: 'top', content: "<?=__('Your first name?')?>" });
            //$('#UserSurname').popover({ toggle: 'popover', trigger: 'focus', placement: 'top', content: "<?=__('Your last name?')?>" });
        } else {
            $('#regMail').popover({ toggle: 'popover', trigger: 'focus', placement: 'bottom', content: "<?=__('You will need email to log in')?>" });
            //$('#UserSkills-tokenfield').popover({ toggle: 'popover', trigger: 'focus', placement: 'left', content: "<?=__('What are your proficiencies?')?>" });
            //$('#regForm .tokenfield.form-control').popover({ toggle: 'popover', trigger: 'focus', placement: 'left', content: "<?=__('What are your proficiencies?')?>" });
            $('#regPasswd').popover({ toggle: 'popover', trigger: 'focus', placement: 'bottom', content: "<?=__('A combination of 6 digits and characters')?>" });
            //$('#UserName').popover({ toggle: 'popover', trigger: 'focus', placement: 'left', content: "<?=__('Your first name?')?>" });
            //$('#UserSurname').popover({ toggle: 'popover', trigger: 'focus', placement: 'left', content: "<?=__('Your last name?')?>" });
        }
    }

    initRegPopovers();

    $('body').on('click', '#regSubmit', function() {
        var allow = checkRegFields();
        if(allow){
            $.post('<?=$this->Html->url(array('controller' => 'User', 'action' => 'register'))?>', $('#regForm').serialize(),
            function(response){
                var obj = jQuery.parseJSON(response);
                if( obj !== null ) {
                    if(obj.status == "ERROR") {
                        $('#regMail').removeClass('incorrect').addClass('incorrect');
                        $('#regMail').closest('.form-group').removeClass('incorrect').addClass('incorrect');

                        $('#regMail').popover('destroy');
                        setTimeout((function () {
                            if( (/webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || (/Chrome|Android|iPad/i.test(navigator.userAgent) && $(window).width()) < $(window).height() ) {
                                $('#regMail').popover({ toggle: 'popover', placement: 'bottom', content: obj.message });
                            } else {
                                $('#regMail').popover({ toggle: 'popover', placement: 'bottom', content: obj.message });
                            }
                            $('#regMail').popover('show');
                        }), 500);
                    }
                    if(obj.status == "OK") {
                        <?php if ((Configure::read('debug') == 0) && ($domain == 'konstruktor.com')) { ?>
                        ga('send', 'event', 'form', 'registr', 'user registr');
                        //yaCounter33313760.reachGoal('REGISTR');
                        <?php } ?>
                        <?php $_SERVER['REQUEST_URI']!='/' ? $_SESSION['returnTo'] = $_SERVER['REQUEST_URI'] : false;?>
                        window.location.replace('<?=$this->Html->url(array('controller' => 'User', 'action' => 'interests'))?>');
                    }
                } else {
                    <?php if ((Configure::read('debug') == 0) && ($domain == 'konstruktor.com')) { ?>
                    ga('send', 'event', 'form', 'registr', 'user registr');
                    //yaCounter33313760.reachGoal('REGISTR');
                    <?php } ?>
                    <?php $_SERVER['REQUEST_URI']!='/' ? $_SESSION['returnTo'] = $_SERVER['REQUEST_URI'] : false;?>
                    window.location.replace('<?=$this->Html->url(array('controller' => 'User', 'action' => 'interests'))?>');
                }
            });
        }
    });

    $('#regForm input').focus( function() {
        if($(this).hasClass('incorrect')) {
            $(this).popover('destroy');
            $(this).removeClass('incorrect');
            $(this).closest('.form-group').removeClass('incorrect');
            $(this).popover('destroy');
            activeInput = $(this)

            setTimeout((function () {
                initRegPopovers();
                if( activeInput.is(":focus") && !activeInput.hasClass('token-input') ) {
                    activeInput.popover('show');
                }
            }), 600);
        }
    });

    $('#UserSurname').on('focus', function() {
        if( $('#UserName').hasClass('incorrect') ) {
            $('#UserName').removeClass('incorrect');
            $('#UserName').closest('.form-group').removeClass('incorrect');
            $('#UserName').popover('destroy');
        }
    });

    $("#UserSurname, #UserName").on('keypress', function(e) {
        e = e || window.event;
        var charCode = (typeof e.which == "undefined") ? e.keyCode : e.which;
        var charStr = String.fromCharCode(charCode);
        if (/\d/.test(charStr)) {
            return false;
        }
    });

</script>
