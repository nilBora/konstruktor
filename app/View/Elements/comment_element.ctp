<?
if(!isset($commentAllow)){
	$commentAllow = true;
}
?>
<div class="item <?=$child ? 'subLevel' : ''?>">
	<?php echo $this->Avatar->userLink($user, array(
		'class' => 'pull-left',
		'style' => 'width:50px;',
		'size' => 'thumb100x100'
	)); ?>
	<div class="description">
		<span class="msgText"><?=$msg?></span>
		<div class="clearfix">
			<div class="time"><?=$this->LocalDate->dateTime($event['created'])?></div>
			<?php if ($articleOwner != $currUserID) : ?>

				<?php if ($user['User']['id'] == $currUserID && !$hasChilds) : ?>
					<a href="javascript: void(0)" class="reply remove" style="margin-left: 20px;" data-event_id="<?=$event['id']; ?>"><?=__('Remove'); ?></a>
				<?php endif; ?>

			<?php else: ?>
				<a href="javascript: void(0)" class="reply remove" style="margin-left: 20px;" data-event_id="<?= $event['id']; ?>"><?=__('Remove'); ?></a>
			<?php endif; ?>

			<?php if ($user['User']['id'] == $currUserID) : ?>
				<a href="javascript: void(0)" class="reply edit" style="margin-left: 20px;" data-event_id="<?=$event['id']; ?>"><?=__('Edit'); ?></a>
			<?php endif; ?>

			<?php if (!$child && $commentAllow) : ?>
				<a href="javascript: void(0)" class="reply answer" style="margin-left: 20px;" data-parent_id="<?=$event['id']; ?>"><?=__('Answer'); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>
