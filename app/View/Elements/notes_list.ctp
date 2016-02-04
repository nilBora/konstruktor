<?
	asort($aCategories);
	foreach($aCategories as $id => $cat) {
?>
	<li class="simple-list-item">
		<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'category', $id))?>">
			<div class="user-list-item clearfix">
				<div class="user-list-item-body noImage">
					<div class="user-list-item-name"><?=$cat?></div>
				</div>
			</div>
		</a>
	</li>
<?
	}
?>