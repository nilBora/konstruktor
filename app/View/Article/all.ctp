<script src="/js/vendor/jquery/inview.js"></script>

<?php
/* Breadcrumbs */
	$this->Html->addCrumb(__('Articles'), array('controller' => 'Article', 'action' => 'all'));

    $this->Html->script(array(
        'select2.full.min.js',
        'isotope.pkgd.min.js',
        'fitColumns.js',
        'imagesloaded.pkgd.min.js'
    ), array('inline' => false));

    $css = array(
        'select2.min.css',
        'all_art.css'
    );

    $this->Html->css($css, array('inline' => false));
?>

<script type="text/javascript">
$(document).ready(function() {

	 var $grid = $('.grid').imagesLoaded( function() {
	    $grid.isotope({
		itemSelector: '.grid-item',
		percentPosition: true,
		masonry: {},
		isFitWidth: true
	    });
	  });

	 $(window).load(function() {
	 	$('.grid').isotope('layout');
	 });

	$('.stylerSelectBig').select2({
		minimumResultsForSearch:-1
	});

	$('.toggleBtnSort').on('click', function(e){
		e.preventDefault();
		$(this).toggleClass('active');
		$(this).siblings('.sortCategories').toggleClass('showCat');
	});

	function getFilterLink(){
		var sort = '<?php echo $sort?>';
		var top = $('#topSel').val();
		var category = $('#categorySel').val();
		var timeFilter = $('.filters-btn').find('button.active').data('filter') || null;
		var search = $('#searchInput').val() || '';

		if (sort=='date-down' && top=='all'){
			var sortStr = '';
			var topStr = '';
		} else {
			var sortStr = '/' + sort ;
			if(top=='all'){
				var topStr = '';
			} else {
				var topStr = '/' + top;
			}
		}

		if(category>0){
			var link = '/Article/category/' + category + sortStr + topStr;
		} else if (timeFilter != null && search == '') {
			var link = '/Article/timeFilter/' + timeFilter + '/'+ sortStr + topStr;
		} else if (search != '') {
			var link = '/Article/search/' + search + sortStr + topStr;
		} else {
			var link = '/Article/all' + sortStr + topStr;
		}

		return link;
	}

	$('#topSel,#categorySel').on('change',function(){
		window.location.href = getFilterLink();
	});

	//Time Filters
	$('.filters-btn').on('click', 'button', function() {

		var _this = $(this);
		var filter = _this.data('filter');

		if ( !_this.hasClass('active')) {
			$('.filters-btn').find('button').removeClass('active');
			_this.addClass('active');
			window.location.href = getFilterLink();
		}

	});
	//Search
	$('#header .searchLine .glyphicons.search').on('click', function() {
		window.location.href = getFilterLink();
	});
	//Search Enter key press
	$("body").keyup(function(event){
		if ($('#searchInput').val() != '') {
			if(event.keyCode == 13){
				window.location.href = getFilterLink();
			}
		}
	});

	var timeFilterReturn = $('.filters-btn').find('button.active').data('filter') || '';
	var searchReturn = $('#searchInput').val() || '';
	if (timeFilterReturn.length || searchReturn.length) {
		$('#returnMarkers').fadeIn(400);
	}

	$('body').on('click', '#returnMarkers', function(){
		location.replace('/Article/all');
	});

	<?php if($top == 'all'): ?>

	function asyncLoop(iterations, func, callback) {
           var index = 0;
           var done = false;
           var loop = {
               next: function() {
                   if (done) {
                       return;
                   }

                   if (index < iterations) {
                       index++;
                       func(loop);

                   } else {
                       done = true;
                       if(typeof callback == 'function') {
                       	callback();
                   		}
                   }
               },

               iteration: function() {
                   return index - 1;
               },

               break: function() {
                   done = true;
                   if(typeof callback == 'function') {
                       	callback();
                   	}
               }
           };
           loop.next();
           return loop;
       }

		var alreadySend = false;
		$(window).on('scroll', function (event) {
			var dh = $(document).height();
			if ((/iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
				dh = dh - 150;
			}
			if(($(window).scrollTop() + $(window).height() >= dh) && alreadySend == false) {
				alreadySend = true;
				$('#map-load').show();

				$.post(articleURL.loadMore, {
					data: {
						page: ($('.stylingArticlesGrid .grid-item').length/16)+1,
						published: '1',
						sort : '<?php echo $sort;?>',
						<?php echo isset($category) ? 'category : '. $category : '';?>
					}
				}, function (response) {
					var html = $.parseHTML(response);
					if( $(html).length > 0 ) {

						asyncLoop($(html).length, function(loop){
							$('.stylingArticlesGrid .grid').append( $(html[i]))
							if ($(html[loop.iteration()]).find('.wrapperImgG').find('img').length) {
								$(html[loop.iteration()]).find('.wrapperImgG').find('img').load(function() {
									$('.stylingArticlesGrid .grid').append( $(html[loop.iteration()])).isotope( 'appended', $(html[loop.iteration()]));
									loop.next();
								});
							} else {
								$('.stylingArticlesGrid .grid').append( $(html[loop.iteration()])).isotope( 'appended', $(html[loop.iteration()]));
								loop.next();
							}


						});

						setTimeout(function () {
							alreadySend = false;
						}, 1000);

					}
					$('#map-load').hide();
				});
			}
		});
	<?php endif?>

});

$(document).mouseup(function(e) {
    var div = $(".sortCategories, .toggleBtnSort");
    if (!div.is(e.target) && div.has(e.target).length === 0) {
        div.removeClass('showCat');
        div.siblings('.toggleBtnSort').removeClass('active');
    }
});


</script>

<style>#map-load{position:absolute;top:0;right:0;bottom:0;left:0;display:none;width:60px;height:46px;margin:auto;-ms-transform:translateY(-80px);transform:translateY(-80px);pointer-events:none;opacity:.6}#map-load:before{content:'';position:absolute;top:50%;left:50%;width:94px;height:94px;margin:-17px 0 0 -48px;border:3px solid #777;border-radius:200px;background-color:#fff}.cssload-thecube,.cssload-thecube .cssload-cube{-webkit-transform:rotateZ(45deg);-moz-transform:rotateZ(45deg);-ms-transform:rotateZ(45deg);position:relative}.map-loading #map-load{display:block}.cssload-thecube{width:45px;height:45px;margin:30px auto 0;-o-transform:rotateZ(45deg);transform:rotateZ(45deg)}.cssload-thecube .cssload-cube{transform:rotateZ(45deg);border:1px solid #fff;float:left;width:50%;height:50%;-webkit-transform:scale(1.1);-moz-transform:scale(1.1);-ms-transform:scale(1.1);-o-transform:scale(1.1);transform:scale(1.1)}.cssload-thecube .cssload-cube:before{content:'';position:absolute;top:0;left:0;width:100%;height:100%;-webkit-transform-origin:100% 100%;-moz-transform-origin:100% 100%;-ms-transform-origin:100% 100%;-o-transform-origin:100% 100%;transform-origin:100% 100%;-webkit-animation:cssload-fold-thecube 1.32s infinite linear both;-moz-animation:cssload-fold-thecube 1.32s infinite linear both;-ms-animation:cssload-fold-thecube 1.32s infinite linear both;-o-animation:cssload-fold-thecube 1.32s infinite linear both;animation:cssload-fold-thecube 1.32s infinite linear both;background-color:#777}.cssload-thecube .cssload-c2{-webkit-transform:scale(1.1) rotateZ(90deg);-moz-transform:scale(1.1) rotateZ(90deg);-ms-transform:scale(1.1) rotateZ(90deg);-o-transform:scale(1.1) rotateZ(90deg);transform:scale(1.1) rotateZ(90deg)}.cssload-thecube .cssload-c3{-webkit-transform:scale(1.1) rotateZ(180deg);-moz-transform:scale(1.1) rotateZ(180deg);-ms-transform:scale(1.1) rotateZ(180deg);-o-transform:scale(1.1) rotateZ(180deg);transform:scale(1.1) rotateZ(180deg)}.cssload-thecube .cssload-c4{-webkit-transform:scale(1.1) rotateZ(270deg);-moz-transform:scale(1.1) rotateZ(270deg);-ms-transform:scale(1.1) rotateZ(270deg);-o-transform:scale(1.1) rotateZ(270deg);transform:scale(1.1) rotateZ(270deg)}.cssload-thecube .cssload-c2:before{-webkit-animation-delay:.165s;-moz-animation-delay:.165s;-ms-animation-delay:.165s;-o-animation-delay:.165s;animation-delay:.165s}.cssload-thecube .cssload-c3:before{-webkit-animation-delay:.33s;-moz-animation-delay:.33s;-ms-animation-delay:.33s;-o-animation-delay:.33s;animation-delay:.33s}.cssload-thecube .cssload-c4:before{-webkit-animation-delay:.495s;-moz-animation-delay:.495s;-ms-animation-delay:.495s;-o-animation-delay:.495s;animation-delay:.495s}@keyframes cssload-fold-thecube{0%,10%{transform:perspective(84px) rotateX(-180deg);opacity:0}25%,75%{transform:perspective(84px) rotateX(0);opacity:1}100%,90%{transform:perspective(84px) rotateY(180deg);opacity:0}}
</style>

<input type="hidden" id="timeFilter" value="<?php if (isset($timeFilter)) echo $timeFilter;?>"/>
<div class="headerControls">
	<div class="wrapperSelects">
		<div>
			<select id="topSel" class="stylerSelectBig">
				<option value="all" <?php echo $top=='all' ? 'selected' : '' ?>><?php echo __('All') ?></option>
				<option value="top25" <?php echo $top=='top25' ? 'selected' : '' ?>>TOP-25</option>
				<option value="top50" <?php echo $top=='top50' ? 'selected' : '' ?>>TOP-50</option>
				<option value="top100" <?php echo $top=='top100' ? 'selected' : '' ?>>TOP-100</option>
			</select>
		</div>

		<div>
			<select id="categorySel" class="stylerSelectBig">
				<option value=""><?php echo __('Category') ?></option>
				<?php foreach($aCategoryOptions as $k=>$v): ?>
					<?php if($v!=''): ?>
						<option value="<?php echo $k ?>" <?php echo isset($category) ? ($category==$k ? 'selected' : '') : ''; ?>><?php echo $v ?></option>
					<?php endif ?>
				<?php endforeach ?>
			</select>
		</div>
	</div>

	<div class="wrapperToggleSort">
		<a href="" class="toggleBtnSort">
			<span>
				<span>a</span>
				<span>z</span>
			</span>
			<span class="arrSt">
				<svg height="16" width="8" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8 16">
				    <path fill-rule="evenodd" d="M 8.01 10.67 C 8.01 10.67 4 15.99 4 15.99 C 4 15.99 -0.01 10.67 -0.01 10.67 C -0.01 10.67 2.67 10.67 2.67 10.67 C 2.67 10.67 2.67 1.33 2.67 1.33 C 2.67 0.6 3.26 0 4 0 C 4.74 0 5.33 0.6 5.33 1.33 C 5.33 1.33 5.33 10.67 5.33 10.67 C 5.33 10.67 8.01 10.67 8.01 10.67 Z" />
				</svg>
			</span>
		</a>

		<div class="sortCategories">
			<ul>
				<li <?php echo in_array($sort,['date-up','date-down']) ? 'class="activeSort '.$sort.'"' : '' ?>>
					<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action'=>$this->action, isset($category) ? $category : false, $sort=='date-down' ? 'date-up' : 'date-down', $top!='all' ? $top : false )) ?>">По дате публикации</a>
				</li>
				<li <?php echo in_array($sort,['hits-up','hits-down']) ? 'class="activeSort '.$sort.'"' : '' ?>>
					<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action'=>$this->action, isset($category) ? $category : false, $sort=='hits-down' ? 'hits-up' : 'hits-down', $top!='all' ? $top : false )) ?>">По рейтингу</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="wrapperBtns">
	    <a class="btn btn-default btn-create-articles" href="<?=$this->Html->url(array('controller' => 'Article', 'action' => 'view'))?>">
	        <?=__('Create article')?>
	    </a>
	</div>
    <div class="clear"></div>
</div>

<?php if(!empty($aArticles) || !empty($aArticlesTop)): ?>
	<div class="containerArticlesAll">
		<?php if(!empty($aArticlesTop)): ?>
			<?php $articleTop = array_shift($aArticlesTop); ?>
			<div class="wrapperLeftBig col-sm-8">
				<?php echo $this->Html->link(
					Hash::get($articleTop,'Article.title'),
					array(
						'controller' => 'Article',
						'action' => 'view',
						Hash::get($articleTop,'Article.id')
					),
					array(
						'class' => 'titleBigArt'
					));
				?>
				<?php if($articleTop['ArticleMedia']['id']): ?>
				<div class="wrapperBigImg">
					<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action'=>'view',Hash::get($articleTop,'Article.id'))) ?>">
						<img src="<?=$this->Media->imageUrl($articleTop['ArticleMedia'], '770x')?>" alt="">
					</a>
				</div>
				<?php endif; ?>
				<div class="infoUserBig">

					<?php if(Hash::get($articleTop,'Article.group_id')>0): ?>
						<div class="imgUserBig">
							<a href="<?php echo $this->Html->url(array('controller' => 'Group', 'action'=>'view',Hash::get($articleTop,'Article.group_id'))) ?>">
								<img class="avatar rounded" src="<?=$this->Media->imageUrl($articleTop['GroupMedia'], '40x40')?>" alt="<?php echo Hash::get($articleTop,'Group.title'); ?>">
							</a>
						</div>
						<div class="nameUserBig"><?php echo Hash::get($articleTop,'Group.title') ?></div>
					<?php else: ?>
						<div class="imgUserBig">
							<a href="<?php echo $this->Html->url(array('controller' => 'User', 'action'=>'view',Hash::get($articleTop,'Article.owner_id'))) ?>">
								<img class="avatar rounded" src="<?=$this->Media->imageUrl($aUsers[Hash::get($articleTop,'Article.owner_id')]['UserMedia'], '40x40')?>" alt="">
							</a>
						</div>
						<div class="nameUserBig"><?php echo Hash::get($aUsers[Hash::get($articleTop,'Article.owner_id')],'User.full_name') ?></div>
					<?php endif; ?>

					<?php if(isset($aCategoryOptions[Hash::get($articleTop,'Article.cat_id')]) && Hash::get($articleTop,'Article.cat_id') > 0): ?>
						<div class="tagsArticleBig">
							<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action' => 'category', Hash::get($articleTop,'Article.cat_id'))) ?>">
								<?php echo __($aCategoryOptions[Hash::get($articleTop,'Article.cat_id')]); ?>
							</a>
						</div>
					<?php endif ?>
					<div class="clear"></div>
				</div>
				<div class="descriptionArticleBig">
					<p><?php echo $this->Text->truncate(strip_tags(Hash::get($articleTop,'Article.body'))); ?></p>
				</div>
				<div class="wrapR wrapR-big">
					<div class="right-similar-article_item-meta">
						<div class="right-similar-article_item-date"><?=date('d.m.Y',strtotime($articleTop['Article']['created']));?></div>
						<div class="right-similar-article_item-users"><?php echo Hash::get($articleTop,'Article.hits') ?></div>
						<div class="right-similar-article_item-backing"><?php echo Hash::get($articleTop,'Article.shared') ?></div>
					</div>
				</div>
			</div>
			<?php if(!empty($aArticlesTop)): ?>
				<div class="wrapperRightBig col-sm-4">
					<div class="arRightWrap">
						<?php foreach($aArticlesTop as $articleTop): ?>
							<div class="right-similar-article_item">
								<div class="right-similar-article_item-body">
									<?php echo $this->Html->link(
										Hash::get($articleTop,'Article.title'),
										array(
											'controller' => 'Article',
											'action' => 'view',
											Hash::get($articleTop,'Article.id')
										),
										array(
											'class' => 'right-similar-article_item-title'
										));
									?>
								</div>

								<div class="authorInformation">

									<?php if(Hash::get($articleTop,'Article.group_id')>0): ?>
										<a href="<?php echo $this->Html->url(array('controller' => 'Group', 'action'=>'view',Hash::get($articleTop,'Article.group_id'))) ?>" class="right-similar-article_item-image">
											<img class="avatar rounded" src="<?=$this->Media->imageUrl($articleTop['GroupMedia'], '40x40')?>" alt="<?php echo Hash::get($articleTop,'Group.title'); ?>">
										</a>
										<div class="nameAuthorArt">
											<p><?php echo Hash::get($articleTop,'Group.title') ?></p>
										</div>
									<?php else: ?>
										<a href="<?php echo $this->Html->url(array('controller' => 'User', 'action'=>'view',Hash::get($articleTop,'Article.owner_id'))) ?>" class="right-similar-article_item-image">
											<img class="avatar rounded" src="<?=$this->Media->imageUrl($aUsers[Hash::get($articleTop,'Article.owner_id')]['UserMedia'], '30x30')?>" alt="">
										</a>
										<div class="nameAuthorArt">
											<p><?php echo Hash::get($aUsers[Hash::get($articleTop,'Article.owner_id')],'User.full_name') ?></p>
										</div>
									<?php endif; ?>

									<?php if(isset($aCategoryOptions[Hash::get($articleTop,'Article.cat_id')]) && Hash::get($articleTop,'Article.cat_id') > 0): ?>
										<div class="tagsLinkThemes">
											<a href="<?php echo $this->Html->url(array('controller' => 'Article', 'action' => 'category', Hash::get($articleTop,'Article.cat_id'))) ?>">
												<?php echo __($aCategoryOptions[Hash::get($articleTop,'Article.cat_id')]); ?>
											</a>
										</div>
									<?php endif ?>
									<div class="clear"></div>
								</div>

								<div class="shortDescrArt">
									<p><?php echo $this->Text->truncate(strip_tags(Hash::get($articleTop,'Article.body'))); ?></p>
								</div>

								<div class="wrapR">
									<div class="right-similar-article_item-meta">
										<div class="right-similar-article_item-date"><?=date('d.m.Y',strtotime($articleTop['Article']['created']));?></div>
										<div class="right-similar-article_item-users"><?php echo Hash::get($articleTop,'Article.hits') ?></div>
										<div class="right-similar-article_item-backing"><?php echo Hash::get($articleTop,'Article.shared') ?></div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						<?php endforeach ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if(!empty($aArticles)): ?>
		<div class="col-sm-12 stylingArticlesGrid">
			<div class="grid">
				<?php foreach($aArticles as $article): ?>
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
			</div>
			<div id="map-load" style="margin-bottom: 200px"><div class="cssload-thecube"><div class="cssload-cube cssload-c1"></div><div class="cssload-cube cssload-c2"></div><div class="cssload-cube cssload-c4"></div><div class="cssload-cube cssload-c3"></div></div></div>
		</div>
		<?php endif; ?>
	</div>
<?php else :?>
	<p><?php echo __('No articles in this category yet'); ?></p>
<?php endif; ?>

