<?
	// $src = $this->Media->imageUrl($group['GroupMedia'], 'thumb50x50');
	$class = ($hide) ? 'can-hide' : '';
	$style = ($hide) ? 'style="display: none"' : ''
?>
			<div class="news-article <?=$class?>" <?=$style?>>
                <div class="dimention-link">
                    <a href="javascript:void(0)">
                        <?=$aCategoryOptions[Hash::get($article, 'Article.cat_id')]?>
                    </a>
                </div>
                <a href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view', Hash::get($article, 'Article.id')))?>">
                    <div class="news-article-title">
                        <?=Hash::get($article, 'Article.title')?>
                    </div>
                </a>
                <div class="news-article-pubdate">
                    <?=$this->LocalDate->date('created')?>
                    <!--div class="comments-num">
                        <span class="glyphicons comments"></span> 8
                    </div-->
                </div>
            </div>