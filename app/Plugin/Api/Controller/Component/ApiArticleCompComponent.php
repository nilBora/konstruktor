<?php
App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiArticleCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	public function category_articles(){
		$allArt = false;
		if (!isset($this->_controller->request->data['cat_id']) || $this->_controller->request->data['cat_id'] == 0) {
			$allArt = true;
			$cat_id = false;
		} else {
			$cat_id = $this->_controller->request->data['cat_id'];
		}

		$result = $this->_controller->ApiArticle->categoryArticles($this->_userId, $cat_id, $allArt);
		$this->_controller->setResponse($result);
	}

	public function article_content(){
		if (!isset($this->_controller->request->data['article_id'])) {
			throw new ApiIncorrectRequestException();
		}
			
		if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['article_id'])){
			throw new ApiAccessDeniedException();
		}
			
		$result = $this->_controller->ApiArticle->getArticleBody($this->_controller->request->data['article_id']);
		$this->_controller->_setStatParams('Article', 'view', $this->_controller->request->data['article_id']);
		$this->_controller->setResponse($result);
	}
	
	public function comment_article(){
			if (!isset($this->_controller->request->data['article_id']) or !isset($this->_controller->request->data['message'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if (!isset($this->_controller->request->data['parent_id'])) {
				$this->_controller->request->data['parent_id'] = '';
			}
			
			if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['article_id'])){
				throw new ApiAccessDeniedException();
			}
			
			if($this->_controller->request->data['parent_id']){
				$parentComment = $this->_controller->ApiArticleEvent->findById($this->_controller->request->data['parent_id']);
				if(!$parentComment){
					throw new ApiAccessDeniedException();
				}
				//проверяем, является ли комментарий корневым
				if($parentComment['ApiArticleEvent']['parent_id']){
					throw new ApiAccessDeniedException();
				}
			}
			
			$this->_controller->ApiArticleEvent->set($this->_controller->request->data);
			if (!$this->_controller->ApiArticleEvent->validates()) {
				throw new ApiIncorrectRequestException();
			}
			
			$this->_controller->ApiArticleEvent->addComment($this->_userId, $this->_controller->request->data['message'], $this->_controller->request->data['article_id'],$this->_controller->request->data['parent_id']);
			$this->_controller->setResponse();
	}
	
	/**
	* Список комметариев к статье
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function create_article(){
			if (!isset($this->_controller->request->data['title']) or !isset($this->_controller->request->data['type']) or !isset($this->_controller->request->data['cat_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			App::uses('ApiArticle', 'Api.Model');
			if($this->_controller->request->data['type'] == ApiArticle::TEXT){
				if(!isset($this->_controller->request->data['body']) or !$this->_controller->request->data['body']){
					throw new ApiIncorrectRequestException();
				}
				$this->_controller->request->data['video_url'] = null;
			}else if($this->_controller->request->data['type'] == ApiArticle::VIDEO){
				if(!isset($this->_controller->request->data['video_url']) or !$this->_controller->request->data['video_url']){
					throw new ApiIncorrectRequestException();
				}
				$this->_controller->request->data['video_url'] = (strpos($this->_controller->request->data['video_url'], 'http://') === false) ? 'http://'.$this->_controller->request->data['video_url'] : $this->_controller->request->data['video_url'];
				$this->_controller->request->data['body'] = null;
			}
			
			if(!isset($this->_controller->request->data['published'])){
				$this->_controller->request->data['published'] = 1;
			}			
			$this->_controller->request->data['owner_id'] = $this->_userId;
			
			$this->_controller->ApiArticle->set($this->_controller->request->data);
			if (!$this->_controller->ApiArticle->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiArticle->validationErrors);
			}
			
			$saveData['owner_id'] = $this->_controller->request->data['owner_id'];
			if(isset($this->_controller->request->data['group_id']) and $this->_controller->request->data['group_id']){
				if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
					throw new ApiAccessDeniedException();
				}
				$saveData['group_id'] = $this->_controller->request->data['group_id'];
			}
			$saveData['title'] = $this->_controller->request->data['title'];	
			$saveData['type'] = ApiArticle::$types[$this->_controller->request->data['type']];
			$saveData['body'] = $this->_controller->request->data['body'];
			$saveData['video_url'] = $this->_controller->request->data['video_url'];
			$saveData['published'] = $this->_controller->request->data['published'];
			$saveData['cat_id'] = $this->_controller->request->data['cat_id'];
						
			$result = $this->_controller->ApiArticle->saveArticle($saveData);
			$this->_controller->setResponse(array('Article'=>array('id'=>$result)));
	}
	
	/**
	* обновить статью
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function update_article(){
			if (!isset($this->_controller->request->data['id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			
			$saveData['id'] = $this->_controller->request->data['id'];
			
			if(isset($this->_controller->request->data['title'])){
				$saveData['title'] = $this->_controller->request->data['title'];
			}
			
			App::uses('ApiArticle', 'Api.Model');
			$articleType = $this->_controller->ApiArticle->field('type',array('id'=>$this->_controller->request->data['id'])); 
			$articleType = array_search($articleType, ApiArticle::$types);		
			if($articleType == ApiArticle::TEXT){
				if(!isset($this->_controller->request->data['body'])){
					throw new ApiIncorrectRequestException();
				}
				$saveData['body'] = $this->_controller->request->data['body'];
			}else if($articleType == ApiArticle::VIDEO){
				if(!isset($this->_controller->request->data['video_url']) or !$this->_controller->request->data['video_url']){
					throw new ApiIncorrectRequestException();
				}
				$saveData['video_url'] = (strpos($this->_controller->request->data['video_url'], 'http://') === false) ? 'http://'.$this->_controller->request->data['video_url'] : $this->_controller->request->data['video_url'];
			}
			
			if(isset($this->_controller->request->data['published'])){
				$saveData['published'] = $this->_controller->request->data['published'];
			}			
			
			if(isset($this->_controller->request->data['cat_id'])){
				$saveData['cat_id'] = $this->_controller->request->data['cat_id'];
			}
			
			$this->_controller->ApiArticle->set($saveData);
			if (!$this->_controller->ApiArticle->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiArticle->validationErrors);
			}
				
			$this->_controller->ApiArticle->saveArticle($saveData);
			$this->_controller->setResponse();
	}
	
	/**
	* добавить картинку к статье
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function set_article_image(){
			if (!isset($this->_controller->request->data['article_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['article_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->saveImage('Article', $this->_controller->request->data['article_id']);
	}
	
	/**
	* удалить статью
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function delete_article(){
			if (!isset($this->_controller->request->data['article_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['article_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->ApiArticle->removeArticle($this->_controller->request->data['article_id']);
			$this->_controller->setResponse();
	}
	
	/**
	* Список комметариев к статье
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function article_comments_list(){
			if (!isset($this->_controller->request->data['article_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiArticle->articleCheckAccess($this->_userId,$this->_controller->request->data['article_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiArticleEvent->getCommentsList($this->_controller->request->data['article_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Поиск по статьям
	* 
	* @uses Controller::request
	* @return void
	*/
	public function search_articles(){
			if (!isset($this->_controller->request->data['search_query'])) {
				throw new ApiIncorrectRequestException();
			}

			$this->_controller->ApiArticle->set(array('search_query' => $this->_controller->request->data['search_query']));
			if (!$this->_controller->ApiArticle->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiArticle->validationErrors);
			}

			$searchResult = $this->_controller->ApiArticle->search($this->_controller->request->data['search_query']);
			$this->_controller->setResponse($searchResult);
	}
	
	/**
	* Статьи польователя
	* 
	* @uses Controller::request
	* @return void
	*/
	public function my_articles(){
			$result = $this->_controller->ApiArticle->usersArticles($this->_userId);
			$this->_controller->setResponse($result);
	}
}
?>
