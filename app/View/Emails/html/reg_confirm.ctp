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

		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 35px;">Hello! This email address was used for registering on the Konstruktor website.</div>
		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 20px;">In order to complete your registration, please confirm your email address.</div>
		
		<a href="<?='http://'.$_SERVER['SERVER_NAME'].$this->Html->url(array('controller' => 'User', 'action' => 'confirm', $userId, $token))?>" style="text-decoration: none" target="_blank">
		
<img src="<?='http://'.$_SERVER['SERVER_NAME'].'/img/email/confirm_reg_btn.png'?>" alt="Confirm"/>
			</a>
		<div style="font: normal 12px/19px Arial; color: #808080; padding-top: 40px;">Please, do not reply on this message.</div>
		<div style="font: normal 12px/19px Arial; color: #808080; padding-top: 19px;">If you did not create any account with your email, please, <a href="mailto:support@konstruktor.com" style="color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0;">contact us</a>.</div>
		<div style="font: normal 15px/25px Arial; color: #212121; padding-top: 55px;">With best regards,<br />your Konstruktor</div>
	</div>
	</body>
</html>


