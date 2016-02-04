<?php if ($aArticles) :?>
	<?php foreach($aArticles as $article) : ?>
		<div class="grid-item">
			<p class="titleArticleG">
				<?php echo $this->Html->link(
					Hash::get($article,'Article.title'),
					array(
						'controller' => 'Article',
						'action' => 'view',
						Hash::get($article,'Article.id')
					));
				?>
			</p>
			<?php if($article['ArticleMedia']['id']): ?>
				<div class="wrapperImgG">
					<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action'=>'view',Hash::get($article,'Article.id'))) ?>">
						<img src="<?=$this->Media->imageUrl($article['ArticleMedia'], '270x')?>" alt="<?php echo Hash::get($article,'Article.title'); ?>">
					</a>
				</div>
			<?php endif; ?>
			<div class="wrapperInfoUserG">
				<?php if(Hash::get($article,'Article.group_id')>0): ?>
					<a href="<?php echo $this->Html->url(array('controller' => 'Group', 'action'=>'view',Hash::get($article,'Article.group_id'))) ?>" class="right-similar-article_item-image">
						<img class="avatar rounded" src="<?=$this->Media->imageUrl($article['GroupMedia'], '40x40')?>" alt="<?php echo Hash::get($article,'Group.title'); ?>">
					</a>
					<div class="nameAuthorArt">
						<p><?php echo Hash::get($article,'Group.title') ?></p>
					</div>
				<?php else: ?>
					<a href="<?php echo $this->Html->url(array('controller' => 'User', 'action'=>'view',Hash::get($article,'Article.owner_id'))) ?>" class="right-similar-article_item-image">
						<img class="avatar rounded" src="<?=$this->Media->imageUrl($aUsers[Hash::get($article,'Article.owner_id')]['UserMedia'], '30x30')?>" alt="">
					</a>
					<div class="nameAuthorArt nameAuthorArtG">
						<p><?php echo Hash::get($aUsers[Hash::get($article,'Article.owner_id')],'User.full_name') ?></p>
					</div>
				<?php endif; ?>

				<?php if(isset($aCategoryOptions[Hash::get($article,'Article.cat_id')]) && Hash::get($article,'Article.cat_id') > 0): ?>
					<div class="tagsLinkThemes tagsLinkThemesG">
						<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action' => 'category', Hash::get($article,'Article.cat_id'))) ?>">
							<?php echo __($aCategoryOptions[Hash::get($article,'Article.cat_id')]); ?>
						</a>
					</div>
				<?php endif ?>

				<div class="clear"></div>

			</div>
			<div class="descriptionAtricleG">
				<p><?php echo $this->Text->truncate(strip_tags(Hash::get($article,'Article.body'))); ?></p>
			</div>
			<div class="wrapR wrapRG">
				<div class="right-similar-article_item-meta">
					<div class="right-similar-article_item-date"><?=date('d.m.Y',strtotime($article['Article']['created']));?></div>
					<div class="right-similar-article_item-users"><?php echo Hash::get($article,'Article.hits') ?></div>
					<div class="right-similar-article_item-backing"><?php echo Hash::get($article,'Article.shared') ?></div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
