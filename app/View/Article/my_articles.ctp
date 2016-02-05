<script src="/js/vendor/jquery/inview.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		function inViw() {
			$('.userArticles .article').each(function(index, el) {
				if (!$(el).hasClass('inviewShow')) {
					$(el).bind('inview', function (event, isInView, visiblePartX, visiblePartY) {
						if (isInView) {
							$(this).addClass('inviewShow');
						}
					});
				}
			})
		}

		if($('.userArticles').length) {
			inViw();
		}
	});
</script>
<?php
	/* Breadcrumbs */
	$this->Html->addCrumb(__('Articles'), array('controller' => 'Article', 'action' => 'all'));
	$this->Html->addCrumb(__('My articles'), array('controller' => 'Article', 'action' => 'view'));
?>

<div class="row taskViewTitle fixedLayout">
	<div class="col-sm-3 col-sm-push-9 controlButtons">
		<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view'))?>">
			<?=__('Create article')?>
		</a>
	</div>
</div>
<?
	if ($aArticles) {
		$aContainer = array('', '', '');
		$i = 0;
		foreach($aArticles as $article) {
			$articleID = $article['Article']['id'];
			$comments = Hash::extract($aComments, '{n}.ArticleEvent[article_id='.$articleID.'].id');

			$aContainer[$i].= $this->element('article_entry', array('article' => $article, 'comments' => count($comments), 'category' => true));
			$i++;
			if ($i >= 3) {
				$i = 0;
			}
		}
?>


<div class="row fixedLayout userArticles">
<?
		foreach($aContainer as $container) {
?>
			<div class="col-sm-4 articleColumn">
				<?=$container?>
			</div>
<?
		}
	} else {
		echo __('No articles in this category yet');
	}
?>
</div>
