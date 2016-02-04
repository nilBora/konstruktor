<?
	// $src = $this->Media->imageUrl($group['GroupMedia'], 'thumb50x50');
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : ''
?>


<a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', Hash::get($article, 'Article.id')))?>" class="item <?=$class?>" <?=$style?>>
	<div class="category">
		<?=$aCategoryOptions[Hash::get($article, 'Article.cat_id')]?>
	</div>
<?
	if($article['ArticleMedia']['id']) {
?>
	<div style="position: relative;">
		<? if($article['Article']['video_url']) { ?>
			<div class="video-overlay" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(0, 0, 0, .45); background-image: url(img/play2.png); background-position: center center; background-repeat: no-repeat; background-size: 100px 100px;"></div>
		<? } ?>
		<img src="<?=$this->Media->imageUrl($article['ArticleMedia'], '400x')?>" class="img-responsive text-center" alt="<?=$article['Article']['title']?>" title="<?=$article['Article']['title']?>" />
	</div>
<?
    }
?>
	<div class="title">
		<?=Hash::get($article, 'Article.title')?>
	</div>
	<div class="time">
		<?=$this->LocalDate->dateTime($article['Article']['created'])?>
	</div>
	<!--div class="comments-num">
		<span class="glyphicons comments"></span> 8
	</div-->
</a>
