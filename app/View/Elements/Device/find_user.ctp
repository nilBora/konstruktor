<?
	if (isset($user) && $user) {
?>
<div class="item clearfix">
	<?php echo $this->Avatar->user($currUser, array(
		'size' => 'thumb100x100'
	)); ?>
    <div class="info">
        <span class="name"><?=$user['User']['full_name']?></span>
        <span class="position"><?=$this->Avatar->skills($user['User']['skills'])?></span>
    </div>
    <div class="buttonsControls">
        <div class="accept" onclick="DeviceManage.distribProducts(<?=$user['User']['id']?>);"><span class="glyphicons ok_2"></span></div>
        <div class="remove" onclick="$('.groupAccess .item').remove();"><span class="glyphicons bin"></span></div>
    </div>
</div>
<?
	} else {
?>
<div class="item clearfix">
    <?=__('No records found')?>
</div>
<?
	}
?>
