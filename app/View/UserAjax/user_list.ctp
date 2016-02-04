<?
	if (isset($aUsers) && $aUsers) {
		foreach($aUsers as $user) {
			$uID = Hash::get($user, 'User.id');
            if(!is_null($uID)) {
                $name = Hash::get($user, 'User.full_name');
                $src = $this->Media->imageUrl(Hash::get($user, 'UserMedia'), 'thumb100x100');
            }
            else {
                $name = Hash::get($user, 'User.name');
                $src = Hash::get($user, 'User.img_url');
            }

?>
<div class="item clearfix user" data-user_id="<?=$uID?>">
    <?php if(isset($user['UserMedia'])): ?>
        <?php echo $this->Avatar->user($user, array(
            'size' => 'thumb100x100'
        )); ?>
    <?php else: ?>
        <img alt="<?=$name?>" src="<?=$src?>" class="ava blockLine">
    <?php endif;?>
	<div class="info">
		<span class="name"><?=$name?></span>
		<span class="position"><?=Hash::get($user, 'User.skills')?></span>
	</div>
    <?php if(is_null($uID)):?>
        <input type="hidden" name="UserEvent[new_user]" value="<?php echo $name;?>" />
    <?php endif;?>
</div>
<?
		}
	}
?>
