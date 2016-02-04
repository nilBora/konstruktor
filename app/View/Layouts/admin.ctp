<!DOCTYPE html>
<html>
<head>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
	<?=$this->Html->charset(); ?>
	<title><?=$title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');

		$css = array(
			'bootstrap.min',
			'jquery-ui-1.10.3.custom',
			'font-awesome.min.css',
			'vendor/metisMenu/metisMenu.min.css',
			'admin',
			//'/table/css/grid',
			//'/icons/css/icons'
		);
		echo $this->Html->css($css);
		echo $this->fetch('css');

		$aScripts = array(
			'vendor/jquery/jquery-1.10.2.min',
			'vendor/jquery/jquery.cookie',
			'vendor/jquery/jquery-ui-1.10.3.custom.min',
			'vendor/bootstrap.min.js',
			'vendor/metisMenu/metisMenu.min.js',
			//'vendor/bootstrap-multiselect',
			//'vendor/meiomask',
			'admin',
			//'/core/js/json_handler',
			//'/table/js/grid'
		);
		echo $this->Html->script($aScripts);
		echo $this->fetch('script');
	?>
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
				<?php echo $this->Html->link('CMS '.DOMAIN_TITLE, '/admin', array('class' => 'navbar-brand')); ?>
            </div>

			<?php echo $this->element('/AdminUI/admin_shortcuts')?>

			<div class="navbar-default sidebar" role="navigation">
			    <div class="sidebar-nav navbar-collapse">
					<?php echo $this->element('AdminUI/admin_menu')?>
				</div>
			</div>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
					<?php echo $this->fetch('content'); ?>
                </div>
                <!-- /.col-lg-12 -->
            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
</body>
</html>
