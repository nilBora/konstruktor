<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiFavouriteUserCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	public function favourite_user_add(){
		if (!isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
		}
		
		if(!isset($this->_controller->request->data['list_id'])){
			$this->_controller->request->data['list_id'] = 0;
		}
		
		if(!$this->_controller->ApiFavouriteList->isListOwner($this->_userId,$this->_controller->request->data['list_id'])){
			throw new ApiAccessDeniedException();
		}
		
		if(!$this->_controller->ApiUser->isActive($this->_controller->request->data['user_id'])){
			throw new ApiIncorrectRequestException();
		}
		
		if(!$this->_controller->ApiFavouriteUser->canAddToList($this->_controller->request->data['user_id'], $this->_userId)){
			throw new ApiAccessDeniedException();
		}
		
		$saveData['fav_user_id'] = $this->_controller->request->data['user_id'];
		$saveData['favourite_list_id'] = $this->_controller->request->data['list_id'];
		$saveData['user_id'] = $this->_userId;
		$this->_controller->ApiFavouriteUser->saveUser($saveData);
		$this->_controller->setResponse();
	}
	
	public function favourite_user_delete(){
		if (!isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
		}
		
		if(!isset($this->_controller->request->data['list_id'])){
			$this->_controller->request->data['list_id'] = 0;
		}
		
		if(!$this->_controller->ApiFavouriteList->isListOwner($this->_userId,$this->_controller->request->data['list_id'])){
			throw new ApiAccessDeniedException();
		}
		
		if(!$this->_controller->ApiFavouriteUser->inList($this->_controller->request->data['user_id'], $this->_controller->request->data['list_id'],$this->_userId)){
			throw new ApiIncorrectRequestException();
		}

		$this->_controller->ApiFavouriteUser->deleteUser($this->_controller->request->data['user_id'], $this->_controller->request->data['list_id'],$this->_userId);
		$this->_controller->setResponse();
	}
	
	public function favourite_user_move(){
		if (!isset($this->_controller->request->data['user_id']) or !isset($this->_controller->request->data['list_id'])) {
				throw new ApiIncorrectRequestException();
		}
		
		
		if(!$this->_controller->ApiFavouriteList->isListOwner($this->_userId,$this->_controller->request->data['list_id'])){
			throw new ApiAccessDeniedException();
		}
		
		if(!$this->_controller->ApiFavouriteUser->inList($this->_controller->request->data['user_id'], 0 ,$this->_userId)){
			throw new ApiIncorrectRequestException();
		}
		
		$saveData['favourite_list_id'] =  $this->_controller->request->data['list_id'];
		$conditions['user_id'] = $this->_userId;
		$conditions['fav_user_id'] = $this->_controller->request->data['user_id'];
		$conditions['favourite_list_id'] = 0;
		
		$this->_controller->ApiFavouriteUser->updateUser($saveData, $conditions);
		$this->_controller->setResponse();
	}
}
?>
