<?
	foreach($aArticles as $article) {
?>
	<li class="simple-list-item">
		<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', $article['Article']['id']))?>">
			<div class="user-list-item clearfix">
				<div class="user-list-item-body noImage">
					<div class="user-list-item-name"><?=$article['Article']['title']?></div>
				</div>
			</div>
		</a>
<?
		// if ($this->request->data('type') != 'notes') {
		if ($article['Article']['owner_id'] == $currUserID && $showControls) {
?>
		<div class="buttonsBottom clearfix">
			<?=$this->Html->link(__('Edit'), array('controller' => 'Article', 'action' => 'edit', $article['Article']['id']), array( 'class' => 'btn btn-default pull-left'))?>
<?
			echo $this->Html->link('<span class="glyphicons '.($article['Article']['published'] ? 'eye_open' : 'eye_close').'"></span>',
					array('controller' => 'Article', 'action' => 'changePublish', $article['Article']['id']), 
					array('class' => 'btn btn-default smallBtn pull-left', 'escape' => false)
				);
			echo $this->Html->link('<span class="glyphicons bin"></span>', 
				array('controller' => 'Article', 'action' => 'delete', $article['Article']['id']), 
				array('class' => 'btn btn-default smallBtn pull-right', 'escape' => false),
				__('Are you sure you want delete this record?')
			);
?>
		</div>
<?
		}
?>
	</li>
<?
	}
?>