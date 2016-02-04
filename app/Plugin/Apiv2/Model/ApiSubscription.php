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
		
		$subscriptionListGroup = $this->Subscription->find('list',array(
					'fields' => array('Subscription.object_id'),
					'conditions' => array('Subscription.subscriber_id'=>$userId,'Subscription.type'=>$this->getGroupType())
				));
		
		$subscriptionListUser = $this->Subscription->find('list',array(
					'fields' => array('Subscription.object_id'),
					'conditions' => array('Subscription.subscriber_id'=>$userId,'Subscription.type'=>$this->getUserType())
				));
		
		if(!$subscriptionListGroup and !$subscriptionListUser){
			return array();
		}
		
		$this->loadModel('Article');
		$this->Article->bindModel(
				array('belongsTo' => array(
						'ArticleCategory' => array(
							'className' => 'ArticleCategory',
							'foreignKey' => 'cat_id',
						),
					)
				)				
			);
		$orCondtions = array();
		if($subscriptionListUser){
			$orCondtions[] = array('Article.group_id' => NULL,'Article.owner_id'=>$subscriptionListUser);
		}
		if($subscriptionListGroup){
			$orCondtions[] = array('Article.group_id' => $subscriptionListGroup);
		}
		$fields = array('Article.id','Article.title','Article.cat_id','Article.created','ArticleCategory.title','ArticleMedia.*');
		$conditions = array(
						'Article.published'=>1,
						'OR'=>$orCondtions
			);
		$order = array('Article.created DESC');
		$limit = 25;
		
		$articles = $this->Article->find('all',compact('conditions','fields','order','limit'));
		$articleIds = Hash::extract($articles, '{n}.Article.id');
		$this->commentsCount = $this->ApiArticleEvent->getCommentsCount($articleIds);
		
		$result = $this->formatUserSubcsribedArticles($articles);
		
		return $result;
	}
	
	/**
	* Форматирует список статей для ответа  
	*  
	* @param array $data 
	* @return array
	*/
	private function formatUserSubcsribedArticles($data){
		
		$aResult = array();
		
		foreach ($data as $id=>$article){
			$aResult['Article'][$id]['id'] = $article['Article']['id'];
			$aResult['Article'][$id]['title'] = $article['Article']['title'];
			$aResult['Article'][$id]['cat_id'] = $article['Article']['cat_id'];
			$aResult['Article'][$id]['category_title'] = $article['ArticleCategory']['title'];
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
