<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<link href="/favicon.ico?<?php echo time();?>" type="image/x-icon" rel="shortcut icon">
<?php
	//echo $this->Html->meta('icon');

	echo $this->Html->css(array('admin-bootstrap.min', 'login'));
	echo $this->Html->script(array('jquery-1.10.2.min', 'admin-bootstrap.min'));

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
	<?=$this->element('ga')?>
	<div class="container">
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
