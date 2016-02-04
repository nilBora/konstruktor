<a href="<?=$this->Html->url(array("controller" => "Article", "action" => "view", $article['Article']['id']));?>" class="item article good">
<? 	
	$published = Hash::get($article, 'Article.published');
	$cat_id = Hash::get($article, 'Article.cat_id');
	if($category) {
?>
	<div class="category">
		<?=$aCategoryOptions[$cat_id]?>
<? 	if(!$published) { ?>
		<span class="glyphicons lock"></span>
<? } ?>	

<? 	
    if(isset($newArticle)) {
		if($newArticle) { 
?>
		<span class="glyphicons circle_exclamation_mark"></span>
<?		
		}
	} 
?>	
	</div>
<? 			
	}
?>	
<?
	if($article['ArticleMedia']['id']) {
?>
	<div style="position: relative;">
		<? if($article['Article']['video_url']) { ?>
			<div class="video-overlay" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(0, 0, 0, .45); background-image: url(/img/play2.png); background-position: center center; background-repeat: no-repeat; background-size: 100px 100px;"></div>
		<? } ?>
		<img src="<?=$this->Media->imageUrl($article['ArticleMedia'], '400x')?>" class="img-responsive text-center" alt="<?=$article['Article']['title']?>" title="<?=$article['Article']['title']?>" />
	</div>
<? 			
    }
?>	
	<div class="title"><?=$article['Article']['title']?></div>
	<div class="time">
		<?=$this->LocalDate->dateTime($article['Article']['created'])?>
<? 			
	if($comments>0) {
?>
		<span class="glyphicons comments pull-right"><?=$comments?></span> 
<? 			
    }
?>			
	</div>
</a>