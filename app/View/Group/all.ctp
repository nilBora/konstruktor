<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(Hash::get($group, 'Group.title'), array('controller' => 'Group', 'action' => 'all'));

	if ($aGroups) {
		$aContainer = array('', '', '');
		$i = 0;
		$j = 0;
		foreach($aGroups as $group) {
			$aContainer[$i].= $this->element('Profile/profile_groups', array('group' => $group));
			$j++;
			$i++;
			if ($i >= 3) {
				$i = 0;
			}
		}
?>
<div class="row taskViewTitle fixedLayout">
	<div class="col-sm-3 col-sm-push-9 controlButtons">
		<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'edit'))?>">
			<?=__('Create group')?>
		</a>
	</div>
	<div class="col-sm-9 col-sm-pull-3">
		<h1><?=__('All groups')?></h1>
	</div>
</div>

<div class="row fixedLayout userProjects">
<?
		foreach($aContainer as $container) {
?>
		<div class="col-sm-6 col-md-4">
			<?=$container?>
		</div>
<?
		}
	}
?>
</div>
