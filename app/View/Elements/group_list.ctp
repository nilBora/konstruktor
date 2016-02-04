<ul class="group-list">
	<!--li class="simple-list-item">
		<a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'all'))?>">
			<div class="user-list-item clearfix">
				<div class="user-list-item-body noImage">
					<div class="user-list-item-name">
						<?=__('All groups')?>
					</div>
				</div>
			</div>
		</a>
	</li-->
<?
    if(isset($aInvites) && $aInvites) {
        foreach($aInvites as $group) {
            $name = $group['Group']['title'];
            $url = $this->Html->url(array('controller' => 'Group', 'action' => 'view', $group['Group']['id']));
?>
    <li class="simple-list-item">
        <a href="<?=$url?>">
            <div class="user-list-item clearfix">
                <div class="user-list-item-avatar">
					<?php echo $this->Avatar->group($group, array(
						'style' => 'width: 50px;',
						'size' => 'thumb100x100'
					)); ?>
				</div>
                <div class="user-list-item-body">
                    <div class="user-list-item-name"><?=$name?></div>
                    <div class="user-list-item-spec"><?=$group['Group']['membersCount']?> <?=__('member(s)')?></div>
                </div>
            </div>
        </a>
        <div class="group-enter-btn clearfix">
            <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'acceptInvite', $group['Group']['id']))?>" class="btn btn-default"><?=__('Accept')?></a>
            <a href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'declineInvite', $group['Group']['id']))?>" class="btn btn-default"><?=__('Decline')?></a>
        </div>
    </li>
<?
        }
    }
	foreach($aGroups as $group) {
		$owner = $currUserID ==  $group['Group']['owner_id'];
		if($owner) {
			$name = $group['Group']['title'];
			$url = $this->Html->url(array('controller' => 'Group', 'action' => 'view', $group['Group']['id']));
?>
    <li class="simple-list-item">
        <a href="<?=$url?>">
            <div class="user-list-item clearfix">
                <div class="user-list-item-avatar">
					<?php echo $this->Avatar->group($group, array(
						'style' => 'width: 50px;',
						'size' => 'thumb100x100'
					)); ?>
				</div>
                <div class="user-list-item-body">
                    <div class="user-list-item-name"><span class="glyphicons user"> <?=$name?></span></div>
                    <br/><div class="user-list-item-spec"><?=$group['Group']['membersCount']?> <?=__('member(s)')?></div>
                </div>
            </div>
        </a>
    </li>
<?
		}
	}
	foreach($aGroups as $group) {
		$owner = $currUserID ==  $group['Group']['owner_id'];
		if(!$owner) {
			$name = $group['Group']['title'];
			$url = $this->Html->url(array('controller' => 'Group', 'action' => 'view', $group['Group']['id']));
			$owner = $currUserID ==  $group['Group']['owner_id'];
?>
    <li class="simple-list-item">
        <a href="<?=$url?>">
            <div class="user-list-item clearfix">
                <div class="user-list-item-avatar">
					<?php echo $this->Avatar->group($group, array(
						'style' => 'width: 50px;',
						'size' => 'thumb100x100'
					)); ?>
				</div>
                <div class="user-list-item-body">
                    <div class="user-list-item-name"><?=$name?></div>
                    <br/><div class="user-list-item-spec"><?=$group['Group']['membersCount']?> <?=__('member(s)')?></div>
                </div>
            </div>
        </a>
    </li>
<?
		}
	}
?>
</ul>
