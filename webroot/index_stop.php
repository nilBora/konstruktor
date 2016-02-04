<!DOCTYPE html>
<html lang="en">
<head>
	<!--[if lt IE 9]>
		<meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
	<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konstruktor: Main page</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="img/favicon/apple-touch-icon-144x144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
    <link href="img/favicon/apple-touch-icon-114x114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
    <link href="img/favicon/apple-touch-icon-72x72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72" />
    <link href="img/favicon/apple-touch-icon-57x57-precomposed.png" rel="apple-touch-icon-precomposed" />

<link href="/device/favicon.ico" type="image/x-icon" rel="icon" /><link href="/device/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<link rel="stylesheet" type="text/css" href="/device/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="/device/css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="/device/css/bootstrap/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/device/css/main-panel.css" />
	<link rel="stylesheet" type="text/css" href="/device/css/content.css" />
	<link rel="stylesheet" type="text/css" href="/device/css/index-page.css" />

	<script type="text/javascript" src="/device/js/vendor/jquery/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/device/js/vendor/easing.1.3.js"></script>
	<script type="text/javascript" src="/device/js/vendor/bootstrap.min.js"></script>
	<script type="text/javascript" src="/device/js/index-page.js"></script>
	<script type="text/javascript" src="/device/js/timezone_cookie.js"></script>

</head>
<body>
<script type="text/javascript">
    /*<![CDATA[*/
    switchIndexForms = function () {
        $("#login_form_block").toggle(300);
        $("#register_form_block").toggle(300);
    };
    /*]]>*/
</script>

<div class="index-wrapper">
    <div class="container">
		<div class="row">
    <div class="col-md-4">
        <a href="/" class="logo-company">
            <img class="logo-image" src="/img/logo.png" alt=""><br>
            <span class="logo-text">Creative Environment</span>
        </a>
    </div>
    <div class="col-md-6 col-md-offset-2">
		<div class="row" id="login_form_block">
		    <div class="col-md-4 func-link-box">
		        <a class="func-link" href="javascript:void(0);" onclick="switchIndexForms();">Register</a>
		    </div>
		    <div class="col-md-7 col-md-offset-1">
		        <div class="input-box">
		            <div class="form">
		                <form action="/device/" class="form" id="loginForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>    <div class="input-box-item">
    	<div class="input text required"><input name="data[User][username]" placeholder="Email" maxlength="50" type="text" value="fyr@tut.by" id="UserUsername" required="required"/></div>    </div>
    <div class="input-box-item">
<?
	$password = $_POST['data']['User']['password'];
?>
        <div class="input password required"><input name="data[User][password]" placeholder="Password" type="password" value="<?=$password?>" id="UserPassword" required="required"/></div>
<?
	if (!$password) {
?>
	<div class="error-message">Your authorization has been expired</div>
<?
	} else
?>
	<div class="error-message">Invalid username or password, try again</div>
<?
	}
?>
    </div>
    <div class="login-box">
        <button type="submit" class="enter-link"><span class="halflings log_in"></span>Log In</button>
        <a class="func-link fright" href="#">Password Reminder</a>
    </div>
</form>		            </div>
		        </div>
		    </div>
		</div>
		<div class="row" id="register_form_block" style="display: none;">
		    <div class="col-md-4 func-link-box">
		        <a class="func-link" href="javascript:void(0);" onclick="switchIndexForms();">Sign In</a>
		    </div>
		    <div class="col-md-7 col-md-offset-1">
		        <div class="input-box">
		            <div class="form">
		            	<form action="/device/User/register" class="form" id="registerForm" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>    <div class="input-box-item">
        <div class="input text required"><input name="data[User][username]" placeholder="Email" id="UserRegisterName" maxlength="50" type="text" value="fyr@tut.by" required="required"/></div>    </div>
    <div class="input-box-item">
    	<div class="input password required"><input name="data[User][password]" placeholder="Password" required="required" type="password" value="q" id="UserPassword"/></div>    </div>
    <div class="login-box">
        <label for="terms-of-use" class="terms-of-use">
            <span class="glyphicons ok_2"></span>
            <input id="terms-of-use" type="checkbox">
            <span>I agree to</span>
        </label>
        <a class="terms-link" href="/Terms.pdf" target="_blank">Terms of Use</a>
        <br>
        <button type="submit" class="enter-link save-button">
            <span class="halflings log_in"></span> Register
        </button>
    </div>
</form><script type="text/javascript">
function updateSubmit() {
	var enabled = $('#UserRegisterName').val() && $('label.terms-of-use').hasClass('checkedIn');
	$('.save-button').prop('disabled', !enabled);
	$('.save-button').removeClass('disabled');
	if (!enabled) {
		$('.save-button').addClass('disabled');
	}
}

$(document).ready(function(){
	$('#UserRegisterName').keyup(function(){
		updateSubmit();
	});
	$('.terms-of-use input[type="checkbox"]').change(function(){
		updateSubmit();
	});

	$('#registerForm .save-button').click(function(){
		updateSubmit();
		return !$('.save-button').prop('disabled');
	});
	updateSubmit();
});
</script>		            </div>
		        </div>
		    </div>
		</div>
    </div>
</div>    </div>
</div>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="copyright-wrap">
                    <div class="copyright">
                        © KONSTRUKTOR US lab LLC
                        <div class="copyright-year">2002-2015</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 taright footer-menu-block">
                <div class="footer-menu">
                    <ul>
                        <!--<li><a href="#">О проекте</a></li>-->
                        <li><a href="/Terms.pdf" target="_blank">Terms of Use</a></li>
                        <li><a href="/Privacy.pdf" target="_blank">Privacy Policy</a></li>
                        <!--<li><a href="#">Помощь</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
