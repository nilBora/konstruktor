<?php
if (empty($modelClass)) {
	$modelClass = Inflector::singularize($this->name);
}
if (!isset($className)) {
	$className = strtolower($this->name);
}
$indexTitle = empty($this->request->params['plugin']) ? __($this->name) : __d($this->request->params['plugin'], $this->name);

//you can override crumbs addition in view but after crumbs addition you need to set
// $viewCrumbs to true: $this->set('viewCrumbs', true);
if (!isset($viewCrumbs)||($viewCrumbs === false)){
	$this->Html->addCrumb($indexTitle, array('action' => 'index'));
	$this->Html->addCrumb($title_for_layout);
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

<div class="<?php echo Inflector::slug(Inflector::underscore($this->name), '-').' '.str_replace('admin_', '', $this->request->params['action']); ?>">
	<div class="row">
		<div class="col-xs-12">
			<?php if ($contentBlock = $this->fetch('content')): ?>
				<?php echo $contentBlock; ?>
			<?php else: ?>

			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ($modalBlock = $this->fetch('modal')): ?>
	<?php echo $modalBlock; ?>
<?php endif; ?>
