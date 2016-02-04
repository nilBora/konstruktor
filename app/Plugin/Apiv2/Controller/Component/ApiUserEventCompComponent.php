<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiUserEventCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	private function _getRoundedTime($datetime){
		$step = 5; //шаг 5 минут
		$stamp = strtotime($datetime);		
		$ts = floor($stamp/3600)*3600+floor(date("i",$stamp)/$step)*$step*60;
		return date('Y-m-d H:i:s',$ts);
	}
	
	private function _saveUserEventData(){
		
		$saveData['user_id'] = $this->_userId;
		if(isset($this->_controller->_controller->request->data['id'])){
			$saveData['id'] = $this->_controller->_controller->request->data['id'];
		}
		
		if(isset($this->_controller->request->data['task_id'])){
			if(!$this->_controller->ApiTask->isMyTask($this->_userId,$this->_controller->request->data['task_id'])){
				throw new ApiAccessDeniedException();
			}
			$saveData['task_id'] = $this->_controller->request->data['task_id'];
			//если задана привязка к задаче, то имя события должно быть идентично имени задачи
			$saveData['title'] = $this->_controller->ApiTask->field('title',array('id'=>$this->_controller->request->data['task_id']));
		}
		
		if(isset($this->_controller->request->data['title']) and !isset($this->_controller->request->data['task_id'])){
			$saveData['title'] = $this->_controller->request->data['title'];
		}
		if(isset($this->_controller->request->data['recipient_id'])){
			if(!$this->_controller->ApiUser->isActive($this->_controller->request->data['recipient_id'])){
				throw new ApiAccessDeniedException();
			}
			$saveData['recipient_id'] = $this->_controller->request->data['recipient_id'];
		}
		if(isset($this->_controller->request->data['type'])){
			App::uses('ApiUserEvent', 'ApiModel');
			$saveData['type'] = ApiUserEvent::$types[$this->_controller->request->data['type']];
		}
		if(isset($this->_controller->request->data['descr'])){
			$saveData['descr'] = $this->_controller->request->data['descr'];
		}
		if(isset($this->_controller->request->data['event_time'])){
			$this->_controller->request->data['event_time'] = date('Y-m-d H:i:s',strtotime($this->_controller->request->data['event_time']));
			$saveData['event_time'] = $this->_getRoundedTime($this->_controller->request->data['event_time']);
		}
		if(isset($this->_controller->request->data['event_end_time'])){
			$this->_controller->request->data['event_end_time'] = date('Y-m-d H:i:s',strtotime($this->_controller->request->data['event_end_time']));
			$saveData['event_end_time'] = $this->_getRoundedTime($this->_controller->request->data['event_end_time']);
		}
		
		if(isset($this->_controller->request->data['event_time']) && isset($this->_controller->request->data['event_end_time'])){
			if(strtotime($saveData['event_end_time']) < strtotime($saveData['event_time'])){
				throw new ApiIncorrectRequestException();
			}
		}
		
		$result = $this->_controller->ApiUserEvent->saveUserEvent($saveData);
		if(!isset($saveData['id'])){	
			$this->_controller->setResponse(array('UserEvent'=>array('id'=>$result)));
		}else{
			$this->_controller->setResponse();
		}
	}
	
	/**
	* добавить эвент пользователя 
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function add_user_event(){
			if (!isset($this->_controller->request->data['event_time'])
				or !isset($this->_controller->request->data['event_end_time'])
				or !isset($this->_controller->request->data['type'])	
				or !isset($this->_controller->request->data['title'])	
				or !isset($this->_controller->request->data['descr'])) {
					throw new ApiIncorrectRequestException();
			}
			
			$this->_controller->ApiUserEvent->set($this->_controller->request->data);
			if (!$this->_controller->ApiUserEvent->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUserEvent->validationErrors);
				return;
			}
			
			$this->_saveUserEventData();
	}
	
	/**
	* обновить эвент пользователя
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function update_user_event(){
			if (!isset($this->_controller->request->data['id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiUserEvent->isUserEventExist($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			
			//промежутки времени должны указываться в паре
			if(	  (isset($this->_controller->request->data['event_time']) && !isset($this->_controller->request->data['event_end_time'])) 
				or(!isset($this->_controller->request->data['event_time']) && isset($this->_controller->request->data['event_end_time']))){
					throw new ApiIncorrectRequestException();
				}
			
			$this->_controller->ApiUserEvent->set($this->_controller->request->data);
			if (!$this->_controller->ApiUserEvent->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUserEvent->validationErrors);
			}		
			$this->_saveUserEventData();
	}
	
	/**
	* удалить эвент пользователя
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function delete_user_event(){
			if (!isset($this->_controller->request->data['id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiUserEvent->isUserEventExist($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->ApiUserEvent->deleteUserEvent($this->_controller->request->data['id']);
			$this->_controller->setResponse();
	}
}
?>
