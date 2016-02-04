<div class="create-group">
	<div class="page-menu">
		<?=$this->Html->link(__('Create article'), array('controller' => 'Article', 'action' => 'view'), array('class' => 'btn btn-default pull-left'))?>
		<?=$this->Html->link(__('My articles'), array('controller' => 'Article', 'action' => 'myArticles'),
			array('class' => 'pull-right underlink', 'style' => "margin-top: 5px; margin-right: -8px;"))?>
	</div>
</div>

<div class="dropdown-panel-scroll">
	<ul class="group-list">
		
		<li class="simple-list-item">
			<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'subscriptions'))?>">
				<div class="user-list-item clearfix">
					<div class="user-list-item-body noImage" style="position: relative;">
						<div class="user-list-item-name">
							<span class="glyphicons vcard"> <?=__('Subscriptions')?></span>							
						</div>
<?
	if($newsCount) {
?>
						<div style="height: 18px; width: 18px; position: absolute; right: 6px; top: 11px; border-radius: 10px; background: #ff6363; color: white; line-height: 18px; font-family: 'Open Sans'; font-size: 10px; text-align: center;"><?=$newsCount?></div>
<?
	}
?>
					</div>
				</div>
			</a>
		</li>
		
		<li class="simple-list-item">
			<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'all'))?>">
				<div class="user-list-item clearfix">
					<div class="user-list-item-body noImage">
						<div class="user-list-item-name">
							<?=__('All articles')?>							
						</div>
					</div>
				</div>
			</a>
		</li>
		
<?
	if ($aCategories) {
		echo $this->element('notes_list');
	} else {
		echo $this->element('article_list', array('aArticles' => $aArticles, 'showControls' => false));
	}
?>
	</ul>
</div>


<script type="application/javascript">
    // search
    $('#searchArticleCatForm').ajaxForm({url: articleURL.panel, target: Article.panel});
<?
    if($newsCount) {
?>
	//news count
    $('#newsCount').html(<?=$newsCount?>);
<?
    }
?>
</script>