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
<?
	$id = $this->request->params['pass'][0];
?>

<div class="row taskViewTitle fixedLayout">
	<div class="col-sm-3 col-sm-push-9 controlButtons">
		<a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view'))?>">
			<?=__('Create article')?>
		</a>
	</div>
	<div class="col-sm-9 col-sm-pull-3">
		<h1><?=$aCategoryOptions[$id]?></h1>
	</div>
</div>

<?
	if ($aArticles) {
		$aContainer = array('', '', '');
		$i = 0;
		foreach($aArticles as $article) {
			$articleID = $article['Article']['id'];
			$comments = Hash::extract($aComments, '{n}.ArticleEvent[article_id='.$articleID.'].id');
			
			$aContainer[$i].= $this->element('article_entry', array('article' => $article, 'comments' => count($comments), 'category' => false));
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
			<div class="col-sm-4">
				<?=$container?>
			</div>
<?
		}
	} else { 
		echo __('No articles in this category yet');
	}
?>
	
</div>