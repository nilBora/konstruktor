<script src="/js/likely.js"></script>
<link rel="stylesheet" href="/css/likely.css">

<script type="text/javascript">
    window.fbAsyncInit = function () {
        FB.init({
            appId: '<?=Configure::read('fbApiKey')?>', // App ID
            status: true, // check login status
            cookie: true, // enable cookies to allow the server to access the session
            xfbml: true, // parse XFBML
			version: 'v2.5'
        });
		// test without it
//		FB.getLoginStatus(function(response) {
//			statusChangeCallback(response);
//			console.log(response);
//		});
    };

	// Load the SDK asynchronously
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// This is called with the results from from FB.getLoginStatus().
	// test without it
//	function statusChangeCallback(response) {
//		console.log(response);
//		if (response.status === 'connected') {
//			// Logged into your app and Facebook.
//			getUserInfo();
//		}
//	}

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

    var fbUserInfo = null;

    function getUserInfo() {
		FB.api('/me', { fields: 'id, name, email' }, function (response) {
			fbUserInfo = response;
			var cpFbUserInfo = fbUserInfo;
			$.post('<?=$this->Html->url(array('controller' => 'User', 'action' => 'fbAuthCheck'))?>.json', {
				data: response
			}, function (checked) {
				var obj = jQuery.parseJSON( checked );
				try {
					fbUserInfo = $.extend(true, {}, fbUserInfo, initLocation.getLocation());
				} catch (Error) {
					fbUserInfo = cpFbUserInfo;
				}
				loginFacebook(fbUserInfo);
			});
		});
    }

    function loginFacebook(fbData) {
        $.post('<?=$this->Html->url(array('controller' => 'User', 'action' => 'fbAuth'))?>.json', {
            data: fbData
        }, function (response) {
            var obj = jQuery.parseJSON( response );
            if( obj.status == 'LOGIN' ) {
                window.location.replace('<?=$this->Html->url(array('controller' => 'Timeline', 'action' => 'index'))?>');
            } else if( obj.status == 'REGISTER' ) {
                window.location.replace('<?=$this->Html->url(array('controller' => 'User', 'action' => 'interests'))?>');
            } else {
                alert('Error while registering');
            }
        });
    }
</script>

<?=$this->Form->create('User', array('url' => array('controller' => 'User', 'action' => 'login'), 'class' => 'formFields', 'id' => 'authForm'))?>

        <?=$this->Form->hidden('lat', array('id' => 'UserLoginLat', 'autocomplete' => 'false'));?>
        <?=$this->Form->hidden('lng', array('id' => 'UserLoginLng', 'autocomplete' => 'false'));?>

    <div class="login_form-row">
        <?=$this->Form->input('username', array('label' => false, 'placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'authMail'))?>
    </div>

    <div class="login_form-row">
        <?=$this->Form->input('password', array('label' => false, 'placeholder' => __('Password'), 'class' => 'form-control', 'id' => 'authPasswd'))?>
		<a class="remind-pass" href="#remind-popup"><?=__('Password Reminder')?></a>
    </div>

    <div class="login_form-row">
        <button class="" id="authLogin" type="submit"><span class="log_in"></span><?=__('Log In')?></button>
    </div>

<?=$this->Form->end()?>

<div id="fb-root"></div>

<script type="text/javascript">

	$(document).ready(function () {
		$('#authPasswd').on('click', function(){
			$('.remind-pass').css('display', 'inline-block');
		});
	});

    function checkAuthFields() {
        var allow = true;

        if( IsEmail($('#authMail').val()) ) {
            $('#authMail').removeClass('incorrect');
        } else {
            $('#authMail').removeClass('incorrect').addClass('incorrect');
            $('#authMail').popover('destroy');
            allow = false;
        }

        if( $('#authPasswd').val().length > 3 ) {
            $('#authPasswd').removeClass('incorrect');
        } else {
            $('#authPasswd').removeClass('incorrect').addClass('incorrect');
            $('#authPasswd').popover('destroy');
            allow = false;
        }

        if(!allow) {
            setTimeout((function () {
                var placement = ($(window).width() < 768) ? 'bottom' : 'left';
                $('#authMail').popover({ toggle: 'popover', placement: placement, content: "<?=__('Incorrect login or password. Password must be at least 6 characters')?>" });
                $('#authMail').popover('show');
            }), 500);
        }

        return allow;
    }

    $('#authLogin').click ( function(e) {
        var allow = checkAuthFields();
        if(!allow)
        {
            return false;
        }
    });

    $('#authForm input').click( function() {
        $('#authMail').popover('destroy');
        $(this).removeClass('incorrect');
    });

<?
    $error = $this->Session->flash('auth');
    if ($error) {
?>
    var placement = ($(window).width < 768) ? 'bottom' : 'left';
    $('#authMail').addClass('incorrect');
    $('#authMail').popover({ toggle: 'popover', placement: placement, content: "<?=$error?>" });
    $('#authMail').popover('show');
<?
    }
?>

</script>
