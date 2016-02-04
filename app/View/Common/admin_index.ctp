<?php

//if (!isset($model))
if (empty($modelClass)) {
	$modelClass = Inflector::singularize($this->name);
}
if (!isset($className)) {
	$className = strtolower($this->name);
}

//you can override crumbs addition in view but after crumbs addition you need to set
// $viewCrumbs to true: $this->set('viewCrumbs', true);
if (!isset($viewCrumbs)||($viewCrumbs === false)){
	$this->Html->addCrumb($title_for_layout, array('action' => 'index'));
}
?>

<?php
	if ($titleBlock = $this->fetch('title')):
		$header = $titleBlock;
	else:
		$header = $this->Html->tag('h1', $title_for_layout);
	endif;

	if ($actionsBlock = $this->fetch('actions')):
		$header .=  $actionsBlock;
	endif;
	$this->assign('page_title', $header);
?>
<div id="content-header">
	<?php if ($titleBlock = $this->fetch('page_title')) { ?>
		<?php echo $titleBlock; ?>
	<?php } ?>
</div>

<div class="row">
	<div class="col-xs-12">
	<?php if ($filterBlock = $this->fetch('filter')): ?>
		<?php echo $filterBlock; ?>
	<?php else: ?>
		<?php if(in_array('Filter', $this->helpers)||array_key_exists('Filter', $this->helpers)) : ?>
			<?php echo $this->Filter->filterForm($modelClass.'Filter'); ?>
		<?php endif; ?>
	<?php endif; ?>
	</div>
</div>

<div class="<?php echo Inflector::slug(Inflector::underscore($this->name), '-').' '.str_replace('admin_', '', $this->request->params['action']); ?>">
	<div class="row">
		<div class="col-xs-12">
			<?php if ($formCreateBlock = $this->fetch('form_create')): ?>
				<?php echo $formCreateBlock; ?>
			<?php endif; ?>
			<?php if($this->fetch('content_title')): ?>
			<div class="widget-box">
				<?php if ($widgetTitleBlock = $this->fetch('content_title')): ?>
				<div class="widget-title">
					<?php echo $widgetTitleBlock; ?>
				</div>
				<?php endif; ?>
				<div class="widget-content nopadding">
					<?php if ($contentBlock = $this->fetch('content')): ?>
						<?php echo $contentBlock; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php else: ?>
				<?php if ($contentBlock = $this->fetch('content')): ?>
					<?php echo $contentBlock; ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($formEndBlock = $this->fetch('form_end')): ?>
				<?php echo $formEndBlock; ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?php if ($pagingBlock = $this->fetch('paging')): ?>
				<?php echo $pagingBlock; ?>
			<?php else: ?>
				<?php echo $this->element('AdminUI/pagination'); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if ($modalBlock = $this->fetch('modal')): ?>
	<?php echo $modalBlock; ?>
<?php endif; ?>
