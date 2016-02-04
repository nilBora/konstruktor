<?php

/**
* файл модели ApiSubscription
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('Subscription', 'Model');
App::uses('Article', 'Model');

/**
* Модель ApiSubscription. Обертка под модель Subscription
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/
class ApiSubscription extends AppModel {

	public $useTable = 'subscriptions';
	
	const GROUP_TYPE = 'group';
	const USER_TYPE = 'user';
	
	protected function _afterInit() {
		$this->loadModel('Subscription');
	}
	
	/**
	* Геттер для для типа подписки "группа"
	* 
	* @return string
	*/
	public function getGroupType(){
		return self::GROUP_TYPE;
	}
	
	/**
	* Геттер для для типа подписки "пользователь"
	* 
	* @return string
	*/
	public function getUserType(){
		return self::USER_TYPE;
	}
	
	/**
	* Выдает список статей по подписке пользователя 
	*  
	* @param int $userId 
	* @return array
	*/
	public function getUserSubcsribedArticles($userId){
		$this->loadModel('Api.ApiArticleEvent');
		$this->loadModel('Article');
		$this->loadModel('ArticleEvent');

		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($userId, 'group');
		$GID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');

		$aSubscriptions = $this->Subscription->findAllBySubscriberIdAndType($userId, 'user');

		$UID = Hash::extract($aSubscriptions, '{n}.Subscription.object_id');

		$conditions = array('OR' => array(
				array(
						'group_id' => $GID,
						'published' => 1,
						'deleted' => 0
				),
				array(
						'owner_id' => $UID,
						'group_id' => null,
						'published' => 1,
						'deleted' => 0
				)
		));

		$order = 'Article.created DESC';

		$fields = array('Article.id','Article.title','Article.cat_id','Article.created','ArticleMedia.*');
		$aArticles = $this->Article->find('all', compact('conditions', 'fields', 'order'));

		if($aArticles) {
			$aID = Hash::extract($aArticles, '{n}.Article.id');
			$this->commentsCount = $this->ApiArticleEvent->getCommentsCount($aID);
			$result = $this->formatUserSubcsribedArticles($aArticles);
			return $result;
		}
	}
	
	/**
	* Форматирует список статей для ответа  
	*  
	* @param array $data 
	* @return array
	*/
	private function formatUserSubcsribedArticles($data){
		$this->loadModel('ArticleCategory');
		$aResult = array();
		$categoty = $this->ArticleCategory->options();
		foreach ($data as $id=>$article){
			$aResult['Article'][$id]['id'] = $article['Article']['id'];
			$aResult['Article'][$id]['title'] = $article['Article']['title'];
			$aResult['Article'][$id]['cat_id'] = $article['Article']['cat_id'];
			$aResult['Article'][$id]['category_title'] = $categoty[$article['Article']['cat_id']];
			//$aResult['Article'][$id]['category_title'] = $article['ArticleCategory']['title'];
			$aResult['Article'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($article['Article']['created']));
			$aResult['Article'][$id]['comments'] = 0;
			if(isset($this->commentsCount[$article['Article']['id']])){
				$aResult['Article'][$id]['comments'] = $this->commentsCount[$article['Article']['id']];
			}
			if($article['ArticleMedia']['id']){
				$aResult['Article'][$id]['article_img'] = $article['ArticleMedia']['url_img'];
			}

		}
		return $aResult;
	}
	
	/**
	* Проверка подписки  
	*  
	* @param int $userId
	* @param int $objectId
	* @param srting $type 
	* @return bool
	*/
	public function isSubscribed($userId, $objectId, $type){
		$id = $this->Subscription->field('id',array('subscriber_id'=>$userId,'object_id'=>$objectId,'type'=>$type));
		if(!$id){
			return false;
		}
		return true;
	}
}
?>
