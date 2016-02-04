<?
	$nameWidth = ($isProjectAdmin || $isResponsible) ? 'col-sm-3' : 'col-sm-5';
?>


<div class="row item">
	<div class="col-sm-3">
		<a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', Hash::get($task, 'Task.id')))?>" class="underlink"><?=Hash::get($task, 'Task.title')?></a>
	</div>
	<div class="<?=$nameWidth?>">
		<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="ava">
			<?php echo $this->Avatar->user($user, array(
				'size' => 'thumb25x25'
			)); ?>
		</a>
		<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="underlink">
			<?=$user['User']['full_name']?>
		</a>
	</div>
<?
	if ($isProjectAdmin || $isResponsible || $isGroupAdmin) {
?>
	<div class="col-sm-3 editColumn">
		<span class="editTask" data-id="<?=Hash::get($task, 'Task.id')?>" data-title="<?=Hash::get($task, 'Task.title')?>" data-manager="<?=Hash::get($task, 'Task.manager_id')?>" data-user="<?=Hash::get($task, 'Task.user_id')?>" data-descr="<?=Hash::get($task, 'Task.descr')?>" data-js-deadline="<?=$this->Localdate->date(Hash::get($task, 'Task.deadline'))?>" data-deadline="<?=Hash::get($task, 'Task.deadline')?>"><?=__('Edit')?></span> / <a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'removeTask', Hash::get($task, 'Task.id')))?>" class="removeTask"><?=__('Remove')?></a>
	</div>
<?
	}
?>
	<div class="col-sm-2">
		<?=$this->Localdate->date(Hash::get($task, 'Task.deadline'))?>
	</div>
	<div class="col-sm-1">
<?
		if(Hash::get($task, 'CrmTask.money') != 0) {
?>
			<?=Hash::get($task, 'CrmTask.money').' '.Hash::get($task, 'CrmTask.currency')?>
<?
		}
?>
	</div>


</div>
