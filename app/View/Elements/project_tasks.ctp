<div class="row item">
	<div class="col-sm-4"><a href="<?=$this->Html->url(array('controller' => 'Project', 'action' => 'task', Hash::get($task, 'Task.id')))?>" class="underlink"><?=Hash::get($task, 'Task.title')?></a></div>
	<div class="col-sm-6">
		<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>">
			<?php echo $this->Avatar->user($user, array(
				'size' => 'thumb25x25'
			)); ?>
			<?=$user['User']['full_name']?>
		</a>
	</div>
	<div class="col-sm-2">
		<?=$this->Localdate->date(Hash::get($task, 'Task.deadline'))?>
	</div>
</div>
