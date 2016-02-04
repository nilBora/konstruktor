<div class="text">
	<?=__('Unexpected server error')?><br>
	<?=__('If you believe this problem <br>is caused by us, please feel free to')?> - <a href="mailto:support@konstruktor.com" ><?=__('send us a message')?></a>.<br><br><a href="javascript:history.back()"><?=__('Return')?></a>
</div>
<?php if (Configure::read('debug') > 0): ?>
	<h2><?php echo $name; ?></h2>
	<p class="error">
		<strong><?php echo __d('cake', 'Error'); ?>: </strong>
		<?php echo __d('cake', 'An Internal Error Has Occurred.'); ?>
	</p>
	<?php echo $this->element('exception_stack_trace'); ?>
<?php endif; ?>
