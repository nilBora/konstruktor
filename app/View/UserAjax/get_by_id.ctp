<?
	if (isset($aUsers) && $aUsers) {
		foreach($aUsers as $user) {
			$uID = Hash::get($user, 'User.id');
			$name = Hash::get($user, 'User.full_name');
?>
<div class="item clearfix user" data-user_id="<?=$uID?>">
	<?php echo $this->Avatar->user($currUser, array(
		'size' => 'thumb100x100'
	)); ?>
	<div class="info">
		<span class="name"><?=$name?></span>
		<span class="position"><?=Hash::get($user, 'User.skills')?></span>
	</div>
</div>
<?
		}
	}
?>
