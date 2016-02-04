<!DOCTYPE html>
<html lang="en">
<head>
    <!--[if lt IE 9]>
		<meta http-equiv="Refresh" content="0; URL=/ie6/ie6.html" />
	<![endif]-->
    <?=$this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
    <title>Konstruktor: <?php echo $title_for_layout ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="img/favicon/apple-touch-icon-144x144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
    <link href="img/favicon/apple-touch-icon-114x114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
    <link href="img/favicon/apple-touch-icon-72x72-precomposed.png" rel="apple-touch-icon-precomposed" sizes="72x72" />
    <link href="img/favicon/apple-touch-icon-57x57-precomposed.png" rel="apple-touch-icon-precomposed" />

<?php
	echo $this->Html->meta('icon');

	$vendorCss = array(
		'fonts'
	);
	echo $this->Html->css($vendorCss);

	echo $this->fetch('meta');
	echo $this->fetch('css');
?>

<style type="text/css">
	html {
		background: url(<?php echo $this->Html->url('/'); ?>img/404.jpg) no-repeat center center fixed;
        -moz-background-size: cover;
        -webkit-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
	}

	div.text {color: white; padding:50px 60px; font-size: 15px; line-height: 24px; font-family: 'Open Sans', sans-serif; }
	div.text a {border-bottom: 1px solid rgba(255, 255, 255, .5); text-decoration: none; color: white;}
</style>

</head>
<body>
    <div class="container">
		<?php echo $this->fetch('content'); ?>
    </div>
</body>
</html>
