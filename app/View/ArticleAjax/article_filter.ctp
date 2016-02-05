<?
	if ($aArticles) {
		$aContainer = array('', '', '');
		$i = 0;
		foreach($aArticles as $article) {
			$articleID = $article['Article']['id'];
			$comments = Hash::extract($aComments, '{n}.ArticleEvent[article_id='.$articleID.'].id');

			echo $this->element('article_entry', array('article' => $article, 'comments' => count($comments), 'category' => true));
		}
	}
?>
