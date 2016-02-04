<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiDocumentCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Создание документа(только текст)
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function create_document(){
			if(!isset($this->_controller->request->data['title'])){
				throw new ApiIncorrectRequestException();
			}
			if(!isset($this->_controller->request->data['parent_id'])  or !$this->_controller->request->data['parent_id']){
				$this->_controller->request->data['parent_id'] = null;
			}
			if(!isset($this->_controller->request->data['is_folder'])){
				$this->_controller->request->data['is_folder'] = 0;
			}
			
			$this->_controller->request->data['user_id'] = $this->_userId;
			$this->_controller->ApiNote->set($this->_controller->request->data);
			if (!$this->_controller->ApiNote->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiNote->validationErrors);
			}
			
			if(!isset($this->_controller->request->data['body']) and !$this->_controller->request->data['is_folder']){
				throw new ApiIncorrectRequestException();
			}
			
			if((int)$this->_controller->request->data['parent_id']>0){
				if(!$this->_controller->ApiNote->checkAccessToParent($this->_userId,$this->_controller->request->data['parent_id'])){
					throw new ApiAccessDeniedException();
				}
			}
			$saveData['user_id'] = $this->_userId;
			$saveData['type'] = 'text';
			$saveData['parent_id'] = $this->_controller->request->data['parent_id'];
			$saveData['is_folder'] = $this->_controller->request->data['is_folder'];
			$saveData['title'] = $this->_controller->request->data['title'];
			if(!$this->_controller->request->data['is_folder']){
				$saveData['body'] = $this->_controller->request->data['body'];
			}
			
			$result = $this->_controller->ApiNote->saveDocument($saveData);
			$this->_controller->setResponse(array('Note'=>array('id'=>$result)));
	}
	
	/**
	* Создание документа(только текст)
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function update_document(){
			if(!isset($this->_controller->request->data['id'])){
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiNote->checkAccessToDoc($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			$saveData['id'] = $this->_controller->request->data['id'];
			$saveData['user_id'] = $this->_userId;
			$saveData['parent_id'] = $this->_controller->ApiNote->field('parent_id',array('id'=>$this->_controller->request->data['id']));
			$saveData['title'] = $this->_controller->request->data['title'];
			$saveData['body'] = $this->_controller->request->data['body'];
			$saveData['is_folder'] = 0;

			$this->_controller->ApiNote->set($saveData);
			if (!$this->_controller->ApiNote->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiNote->validationErrors);
			}		
			
			$this->_controller->ApiNote->saveDocument($saveData);
			$this->_controller->setResponse();
	}
	
	/**
	* Удаление документа
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function delete_document(){
			if(!isset($this->_controller->request->data['id']) or !$this->_controller->request->data['id']){
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiNote->checkAccessToDoc($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}		
			
			$this->_controller->ApiNote->delete($this->_controller->request->data['id']);
			$this->_controller->setResponse();
	}
	
	/**
	* Перемещение документа
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function move_document(){
			if(!isset($this->_controller->request->data['id']) or !$this->_controller->request->data['id']){
				throw new ApiIncorrectRequestException();
			}
			
			if(!isset($this->_controller->request->data['parent_id']) or !$this->_controller->request->data['parent_id']){
				$this->_controller->request->data['parent_id'] = null;
			}
			
			if(!$this->_controller->ApiNote->checkAccessToDoc($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			
			if((int)$this->_controller->request->data['parent_id']>0){
				if(!$this->_controller->ApiNote->checkAccessToParent($this->_userId,$this->_controller->request->data['parent_id'])){
					throw new ApiAccessDeniedException();
				}
			}
			
			if(!$this->_controller->ApiNote->moveDocument($this->_userId,$this->_controller->request->data['id'],$this->_controller->request->data['parent_id'])){
				throw new ApiIncorrectRequestException();
			}
			$this->_controller->setResponse();
	}
	
	/**
	* Список документов пользователя
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function document_list(){
			if (!isset($this->_controller->request->data['parent_id']) or !$this->_controller->request->data['parent_id']) {
				$this->_controller->request->data['parent_id']='';
			}
			
			$result = $this->_controller->ApiNote->search($this->_userId,$this->_controller->request->data['parent_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Список документов пользователя
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function get_document_body(){
			if (!isset($this->_controller->request->data['document_id']) or !$this->_controller->request->data['document_id']) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiNote->checkAccessToDoc($this->_userId,$this->_controller->request->data['document_id'])){
				throw ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiNote->getDocumentBody($this->_controller->request->data['document_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Сcыдка на скачивание документа
	* 
	* @uses ApiController:_userId
	* @uses Controller::request
	* @return void
	*/
	public function get_document_link(){
			if (!isset($this->_controller->request->data['document_id']) or !$this->_controller->request->data['document_id']) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiNote->checkAccessToDoc($this->_userId,$this->_controller->request->data['document_id'])){
				throw new ApiAccessDeniedException();
			}
			$result = array('Note'=>array('url'=>'http://'.$_SERVER['HTTP_HOST'].'/'.strtolower($this->_controller->request->param('controller')).'/download/entity:note/id:'.$this->_controller->request->data['document_id'].'/access_token:'.$this->_controller->request->data['access_token']));
			$this->_controller->setResponse($result);
	}
	
	/**
	* Поиск документов пользователя
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function search_documents(){
			if (!isset($this->_controller->request->data['search_query']) or !$this->_controller->request->data['search_query']) {
				throw new ApiIncorrectRequestException();
			}
			
			$result = $this->_controller->ApiNote->search($this->_userId,'',$this->_controller->request->data['search_query']);
			$this->_controller->setResponse($result);
	}
	
}
?>
