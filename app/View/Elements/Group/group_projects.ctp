<?
	$title = Hash::get($project, 'Project.title');
	//$src = $this->Media->imageUrl($project, 'thumb50x50');
	//$src = ($src) ? $src : '/img/group-create-pl-image.jpg';
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : ''
?>

<a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'view', Hash::get($project, 'Project.id')))?>" class="item <?=$class?>" <?=$style?>>
	<!--span class="glyphicons user"></span-->
	<!--div class="title">Администратор</div-->
	<div class="aboutProject">
		<div class="thumb"><!--img alt="" src="<?//=$src?>"--></div>
		<div class="info">
			<div class="name"><?=$title?></div>
			<!--div><?=__('Members')?></div-->
		</div>
	</div>
	<div class="description"><?=Hash::get($project, 'Project.descr')?></div>
</a>