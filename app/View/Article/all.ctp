<script src="/js/vendor/jquery/inview.js"></script>

<?php
/* Breadcrumbs */
	$this->Html->addCrumb(__('Articles'), array('controller' => 'Article', 'action' => 'all'));
?>
<style>
.absoluteWrap {
	overflow: hidden;
	position: absolute;
	width: 100%;
	left:0;
	right:0;
}
.wrapper-container{
	overflow-y: scroll;
}
</style>
<script type="text/javascript">
$(document).ready(function() {

	$( window ).resize(function() {
		$('.absoluteWrap').height( $(window).height() );
	});

	$('.absoluteWrap').height( $(window).height() );

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

	var alreadySend = false;
	$('.wrapper-container').on('scroll', function (event) {
		var dh = $('.userArticles').height();
		if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
			dh = dh - 150;
		}
		if(($('.wrapper-container').height() + $('.wrapper-container').scrollTop() >= dh) && alreadySend == false) {
			alreadySend = true;
			$('#loadMore').show();

			$.post(articleURL.loadMore, {
				data: {
					page: ($('.item.article').length/15)+1,
					published: '1'
				}
			}, function (response) {
				var html = $.parseHTML(response);
				if( $(html).length > 0 ) {
					var column = 0
					for(i = 0; i<$(html).length; i++) {
						column++;
						if(column > 3) column = 1;
						item = $(html)[i];
//                        console.log($(item));
						$('.articleColumn:nth-of-type('+column+')').append( $(item).addClass('hide-article'));
					}
                    inViw();
					setTimeout(function () {
						$('.userArticles').find('.item.article.good.hide-article').removeClass('hide-article').addClass('show-article');
						alreadySend = false;
					}, 100);
				}
				$('#loadMore').hide();
			});
		}
	});
});
</script>
<div class="row taskViewTitle fixedLayout">
    <div class="col-sm-6 col-sm-push-6 controlButtons">
        <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'subscriptions'))?>">
            <?=__('Subscriptions')?>
        </a>
        <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'myArticles'))?>">
            <?=__('My articles')?>
        </a>
        <a class="btn btn-default" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view'))?>">
            <?=__('Create article')?>
        </a>
    </div>
</div>

<?php
    if ($aArticles) :
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
		<?php foreach($aContainer as $container) : ?>
			<div class="col-sm-4 articleColumn">
				<?=$container?>
			</div>
		<?php endforeach; ?>
<?php
    else :
        echo __('No articles in this category yet');
    endif;
?>
	<div id="loadMore" class="all-articles-page">
		<img src="/img/ajax_loader.gif" style="height: 25px; margin-left: 7px;">
	</div>

</div>
