<?php
if (empty($modelClass)) {
	$modelClass = Inflector::singularize($this->name);
}
if (!isset($className)) {
	$className = strtolower($this->name);
}
$indexTitle = empty($this->request->params['plugin']) ? __($this->name) : __d($this->request->params['plugin'], $this->name);

echo $this->Modal->modalScripts($modelClass);
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<?php
				if ($titleBlock = $this->fetch('title')):
					echo $titleBlock;
				elseif(isset($title_for_layout)):
					echo $this->Html->tag('h3', $title_for_layout);
				else:
					echo $this->Html->tag('h3', __('Data manager'));
				endif;
			?>
		</div>
		<div class="modal-body">
			<?php if ($contentBlock = $this->fetch('content')): ?>
				<?php echo $contentBlock; ?>
			<?php endif; ?>
		</div>
		<div class="modal-footer">
		<?php if ($actionsBlock = $this->fetch('actions')): ?>
				<?php echo $actionsBlock; ?>
		<?php else: ?>
			<?php
			echo $this->Html->tag('button',
				__('Save'),
				array(
					'id' => $modelClass.'ManagerSubmit',
					'name' => 'save',
				    'class' => 'btn btn-primary'    ,
				)
			);
			echo $this->Html->tag('button',
				__('Close'),
				array(
					'id' => $modelClass.'ManagerClose',
					'class' => 'btn btn-link',
					'data-dismiss' => 'modal',
					'aria-hidden' => 'true',
				)
			);
			?>
		<?php endif; ?>
		</div>
	</div>
</div>

