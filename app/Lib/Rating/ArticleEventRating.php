<?php
App::uses('AbstractRating', 'Lib/Rating');

class ArticleEventRating extends AbstractRating {

	protected $_config = array(
		'addComment' => array(
			'label' => 'Article comment',
			'createdOnly' => true,
			'target' => array(
				'Group' => 'group_id',
				'User' => 'user_id'
			),
			'value' => 1
		),
	);

	public function group_id($data){
		$articleModel = ClassRegistry::init('Article');
		$article = $articleModel->find('first', array(
			'conditions' => array('Article.id' => $data[$this->context]['article_id']),
			'recursive' => -1
		));
		$data[$this->context]['group_id'] = null;
		if(!empty($article)&&isset($article['Article']['group_id'])){
			$data[$this->context]['group_id'] = $article['Article']['group_id'];
		}
		return $data;
	}

}
