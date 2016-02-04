<?php

/**
* файл модели ApiArticleEvent
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('ArticleEvent', 'Model');
App::uses('ChatMessage', 'Model');
App::uses('User', 'Model');

/**
* Модель ApiArticleEvent. Обертка под модель ArticleEvent
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiArticleEvent extends AppModel {

	public $useTable = 'article_events';
	
	public $validate = array(
		'message' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		),
		'article_id' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		)
	);
	
	protected function _afterInit() {
		$this->loadModel('ArticleEvent');
		$this->loadModel('User');
	}
	
	/**
	* Добавление комментария к статье 
	*  
	* @param int $userId
	* @param string $message
	* @param int $articleId  
	* @return array
	*/
	public function addComment($userId, $message, $articleId,$parent_id) {
		$this->ArticleEvent->addComment($userId,$message,$articleId,$parent_id);		
	}

	/**
	* Выдает количество комментариев к статьям 
	*  
	* @param int $articleIds  
	* @return array
	*/
	public function getCommentsCount($articleIds){
		$this->ArticleEvent->virtualFields['comments_count'] = 'COUNT(ArticleEvent.id)';
		
		$fields = array('ArticleEvent.article_id','ArticleEvent.comments_count');
		$conditions = array('ArticleEvent.article_id' => $articleIds);
		$group = array('ArticleEvent.article_id');
		
		$result = $this->ArticleEvent->find('list',compact('fields','conditions','group'));

		return $result;
	}
	
	/**
	* Выдает количество комментариев к статьям 
	*  
	* @param int $articleIds  
	* @return array
	*/
	public function getCommentsList($articleId){
		
		$joins = array(
		array('table' => 'chat_messages',
			'alias' => 'ChatMessage',
			'type' => 'LEFT',
			'conditions' => array(
				'`ArticleEvent`.msg_id = `ChatMessage`.id',
				)
			),
		);
		
		$fields = array('ArticleEvent.id','ArticleEvent.article_id','ArticleEvent.parent_id','ArticleEvent.created','ArticleEvent.user_id','ChatMessage.message');
		$conditions = array('ArticleEvent.article_id' => $articleId);
		$order = array('ArticleEvent.created DESC');
		
		$messages = $this->ArticleEvent->find('all',compact('fields','conditions','joins','order'));
		$messages = Hash::combine($messages, '{n}.ArticleEvent.id','{n}');
		
		$userIds = Hash::extract($messages, '{n}.ArticleEvent.user_id');
		
		$this->users = $this->User->getUsers($userIds);
		
		return $this->formatCommentsList($messages);
	}
	
	private function formatCommentsList($data){
		$aResult = array();
		foreach ($data as $id=>$comment){
			$userId = $comment['ArticleEvent']['user_id'];
			$item['comment_id'] = $comment['ArticleEvent']['id'];
			$item['text'] = $comment['ChatMessage']['message'];
			$item['author_id'] = $comment['ArticleEvent']['user_id'];
			$item['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($comment['ArticleEvent']['created']));
			if(isset($this->users[$userId])){
				$item['author_name'] = $this->users[$userId]['User']['full_name'];
				$item['author_image'] = $this->users[$userId]['UserMedia']['url_img'];
			}
			$parentId = $comment['ArticleEvent']['parent_id'];
			if(!$parentId){
				$parentComments[$id] = $item;
			}else{
				$subcomments[$parentId][] = $item;
			}
		}
		
		if(isset($subcomments)){
			foreach ($subcomments as $parentId=>$children){
				$parentComments[$parentId]['Subcomments'] = $children;
			}
		}
		
		if(isset($parentComments)){
			foreach($parentComments as $comment){
				$aResult['Comments'][]=$comment;
			}
		}
		return $aResult;
	}


	/**
	* Форматирует список списка комметариев  
	*  
	* @param array $data 
	* @return array
	*/
	function commentsListCallback($data){
		$userId = $data['ArticleEvent']['user_id'];
		$aResult['comment_id'] = $data['ArticleEvent']['id'];
		$aResult['text'] = $data['ChatMessage']['message'];
		$aResult['parent_id'] = $data['ArticleEvent']['parent_id'];

		$aResult['author_id'] = $data['ArticleEvent']['user_id'];
		$aResult['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['ArticleEvent']['created']));

		if(isset($this->users[$userId])){
			$aResult['author_name'] = $this->users[$userId]['User']['full_name'];
			$aResult['author_image'] = $this->users[$userId]['UserMedia']['url_img'];
		}
		return $aResult;
	}
}
?>
