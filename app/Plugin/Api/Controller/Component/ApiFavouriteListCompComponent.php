<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiFavouriteListCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	public function create_favourite_list(){
		if (!isset($this->_controller->request->data['title']) or !trim($this->_controller->request->data['title'])) {
				throw new ApiIncorrectRequestException();
		}
		
		$saveData['title'] = $this->_controller->request->data['title'];
		$saveData['user_id'] = $this->_userId;
		$listId = $this->_controller->ApiFavouriteList->saveList($saveData);
		$this->_controller->setResponse(array('FavouriteList'=>array('id'=>$listId)));
	}
	
	public function delete_favourite_list(){
		if (!isset($this->_controller->request->data['id'])) {
				throw new ApiIncorrectRequestException();
		}
		
		if(!$this->_controller->ApiFavouriteList->isListOwner($this->_userId,$this->_controller->request->data['id'])){
			throw new ApiAccessDeniedException();
		}
		
		$this->_controller->ApiFavouriteList->deleteList($this->_controller->request->data['id']);
		$this->_controller->setResponse();
	}
	
	public function get_favourite_lists(){
		$result = $this->_controller->ApiFavouriteUser->getUserFavourites($this->_userId);
		$this->_controller->setResponse($result);
	}
	
	public function favourite_list_names(){
		$result = $this->_controller->ApiFavouriteList->getListNames($this->_userId);
		$this->_controller->setResponse($result);
	}
}
?>