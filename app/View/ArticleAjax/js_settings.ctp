var articleURL = {
    panel: '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'panel'))?>',
    loadMore: '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'loadMore'))?>',
    saveArticle: '<?=$this->Html->url(array('controller' => 'ArticleAjax', 'action' => 'saveArticle')).'.json'?>',
}
