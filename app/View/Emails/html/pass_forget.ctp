<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Email</title>
	</head>
	<body>
	<div style="max-width: 440px;">
		<a href="<?='http://'.$_SERVER['SERVER_NAME']?>" style="font: normal 15px Arial; color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0; float: right; margin-top: 43px;">konstruktor.com</a>
		<img src="<?='http://'.$_SERVER['SERVER_NAME'].'/img/email/logo_large.png'?>" style="width: 125px; height: 107px;"/>

		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 35px; padding-bottom: 20px">Hello! We received the request for the new password for your account.</div>
		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 35px; padding-bottom: 20px">If you sent this request, please click the “Reset password” button below. If you did not request the new password, please contact us.</div>
		<a href="<?='http://'.$_SERVER['SERVER_NAME'].$this->Html->url(array('controller' => 'User', 'action' => 'forgetPassword', $userId, $pass))?>" style="text-decoration: none; background: transparent; border: none;" target="_blank">
<img src="<?='http://'.$_SERVER['SERVER_NAME'].'/img/email/reset_pass_btn.png'?>" alt="Reset password"/>
			</a>
		<div style="font: normal 12px/19px Arial; color: #808080; padding-top: 40px;">Please, do not reply on this message.</div>
		<div style="font: normal 12px/19px Arial; color: #808080; padding-top: 19px;">If you did not create any account with your email, please, <a href="mailto:support@konstruktor.com" style="color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0;">contact us</a>.</div>
		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 55px;">With best regards,<br />your Konstruktor</div>
	</div>
	</body>
</html>
