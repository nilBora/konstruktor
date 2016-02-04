<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="ava">
	<?php echo $this->Avatar->user($user, array(
		'size' => 'thumb50x50'
	)); ?>
</a>
<a href="<?=$this->Html->url(array('controller' => 'User', 'action' => 'view', $user['User']['id']))?>" class="underlink">
	<?=$user['User']['full_name']?>
</a>
