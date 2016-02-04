<ul class="nav navbar-top-links navbar-right">
	<li>Welcome, <b><?php echo $currUser['User']['username'] ?></b>!</li>
	<!--li>
		<a href="<?=$this->Html->url(array('admin' => true, 'plugin' => false, 'controller' => 'dashboard', 'action' => 'index'))?>" rel="tooltip-bottom" title="<?=__('Admin Home Page')?>" class="navbar-link"><i class="icon-home"></i><span><?=__('Dashboard')?></span></a>
	</li-->
	<li>
		<a href="/" rel="tooltip-bottom" title="<?=__('Open Home page of Front-end in a new tab')?>" target="_blank" class="navbar-link"><i class="icon-globe"></i><span><?=__('Go to Site')?></span></a>
	</li>
	<li>
		<a href="<?=$this->Html->url(array('admin' => true, 'plugin' => false, 'controller' => 'user', 'action' => 'logout'))?>" rel="tooltip-bottom" title="<?=__('Log out')?>" class="navbar-link"><i class="icon-off"></i><span><?=__('Log out')?></span></a>
	</li>
</ul>
