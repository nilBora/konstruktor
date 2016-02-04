<?
	$title = Hash::get($group, 'Group.title');
	$eUser = isset($user) ? $user : $currUser;
    $owner = Hash::get($group, 'Group.owner_id') == $eUser['User']['id'];
	$class = (isset($hide) && $hide) ? 'can-hide' : '';
	$style = (isset($hide) && $hide) ? 'style="display: none"' : '';
?>
<a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', Hash::get($group, 'Group.id')))?>" class="item <?=$class?>" <?=$style?>>
	<div class="title"><?=Hash::get($group, 'GroupMember.role')?></div>
<?
    if($owner) {
?>
    <span class="glyphicons user"></span>
<?
    }
?>
	<div class="aboutProject">
		<div class="thumb">
			<?php echo $this->Avatar->group($group, array(
				'class' => 'ava',
				'size' => 'thumb100x100',
				'title' => $title,
			)); ?>
			<!--img alt="<?=$title?>" src="<?=$src?>" style="width: 50px"-->
		</div>
		<div class="info">
			<div class="name"><?=$title?></div>
			<div><?=Hash::get($group, 'Group.members')?> <?=__('member(s)')?></div>
		</div>
	</div>
	<div class="description"><?=Hash::get($group, 'Group.descr')?></div>
</a>
