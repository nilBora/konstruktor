<p><?php echo __('You have been advised to the article on the site'); ?> - <?php echo $_SERVER['HTTP_HOST']; ?></p>
<p></p>
<h1><?php echo $article['Article']['title'] ?></h1>
<p>
    <?php if( $article['ArticleMedia'] ): ?>
        <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $this->Media->imageUrl($article['ArticleMedia'], 'thumb100x100') ?>" alt="" style="float: left; margin: 0 10px 10px 0;">
    <?php endif; ?>
    <?php echo $this->Text->truncate(strip_tags($article['Article']['body']), 100, array( 'ellipsis' => '...', 'exact' => false )) ?>
</p>
<p></p>
<p style="text-align: right;"><?php echo __('More details can be found here'); ?> - <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/Article/view/<?php echo $article['Article']['id']; ?>"><?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/Article/view/<?php echo $article['Article']['id']; ?></a></p>