<div class="row groupViewInfo fixedLayout">
    <div class="col-sm-8">
        <div class="thumb">
			<?php echo $this->Avatar->group($group, array(
				'size' => 'thumb200x200'
			)); ?>
		</div>
        <h1><?=Hash::get($group, 'Group.title')?></h1>
    </div>
    <div class="col-sm-4">
        <div class="controlButtons">
            <a class="linkIcon" href="<?=$this->Html->url(array('controller' => 'Group', 'action' => 'view', Hash::get($group, 'Group.id') ))?>">
                <div class="glyphicons group"></div>
                <div class="caption"><?=__('Group')?></div>
            </a>
        </div>
    </div>
</div>
