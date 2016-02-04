<?
if(!isset($commentAllow)){
	$commentAllow = true;
}
?>
<div class="item <?php echo $class ?>">
	<?php echo $this->Avatar->userLink($user, array('size' => 'thumb100x100','class'=>'stylingCom')); ?>
	<div class="descriptionComment">
		<span class="textMsg"><?=$msg?></span>
		<div class="wrappTimeBtn">
			<div class="timeCom"><?=$this->LocalDate->dateTime($event['created'])?></div>
			<?php if ($articleOwner != $currUserID) : ?>

				<?php if ($user['User']['id'] == $currUserID && !$hasChilds) : ?>
					<a href="javascript: void(0)" class="sendMsg reply remove" style="margin-left: 20px;" data-event_id="<?=$event['id']; ?>"><?=__('Remove'); ?></a>
				<?php endif; ?>

			<?php else: ?>
				<a href="javascript: void(0)" class="sendMsg reply remove" style="margin-left: 20px;" data-event_id="<?= $event['id']; ?>"><?=__('Remove'); ?></a>
			<?php endif; ?>

			<?php if ($user['User']['id'] == $currUserID) : ?>
				<a href="javascript: void(0)" class="sendMsg reply edit" style="margin-left: 20px;" data-event_id="<?=$event['id']; ?>"><?=__('Edit'); ?></a>
			<?php endif; ?>

			<?php if (!$child && $commentAllow) : ?>
				<a href="javascript: void(0)" class="sendMsg reply answer" style="margin-left: 20px;" data-parent_id="<?=$event['id']; ?>"><?=__('Answer'); ?></a>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
