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
    <a href="<?=FULL_BASE_URL;?>" style="font: normal 15px Arial; color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0; float: right; margin-top: 43px;">Konstruktor.com</a>
    <img src="<?=FULL_BASE_URL;?>/img/email/logo_large.png" style="width: 125px; height: 107px;"/>

    <div style="font: normal 15px/25px Arial; color: #212121; padding-top: 35px;">Hello! This is the invitation to join to the event on the Konstruktor website.</div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 5px;">Event type: <span><?php echo $eventType;?></span></div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 5px;">Event Title: <span><?php echo $eventTitle;?></span></div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 5px;">Event date: <span><?php echo $date;?></span></div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 5px;">Event Period: <span><?php echo $start_time;?> - </span><span><?php echo $end_time;?></span></div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 5px;">View Event: <span><?php echo FULL_BASE_URL;?></span></div>


    <a href="<?=FULL_BASE_URL;?>" style="text-decoration: none" target="_blank">
        Join Us
    </a>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 40px;">Please, do not reply on this message.</div>
    <div style="font: normal 12px/19px Arial; color: #808080; padding-top: 19px;">If you did not create any account with your email, please, <a href="mailto:support@konstruktor.com" style="color: #1580be; text-decoration: none; border-bottom: 1px solid #cae2f0;">Contact us</a>.</div>
    <div style="font: normal 15px/25px Arial; color: #212121; padding-top: 55px;">With best regards,<br />your Konstruktor</div>
</div>
</body>
</html>