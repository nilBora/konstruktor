<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiChatCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Чат-контакты
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_contacts_list(){
			if (!isset($this->_controller->request->data['search_query'])) {
				$this->_controller->request->data['search_query'] = '';
			}
			
			$result = $this->_controller->ApiChatContact->getList($this->_userId,$this->_controller->request->data['search_query']);
			$this->_controller->setResponse($result);
	}

	/**
	* Открыть или создать чат-комнату с пользователем
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function user_chat_create(){
			if (!isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if($this->_controller->request->data['user_id'] == $this->_userId){
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiUser->isActive($this->_controller->request->data['user_id'])){
				throw new ApiIncorrectRequestException();
			}			
			$result = $this->_controller->ApiChatContact->openRoom($this->_userId,$this->_controller->request->data['user_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Открыть или создать чат-комнату с группой
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function group_chat_create(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$result = $this->_controller->ApiChatContact->openRoom($this->_userId,0,$this->_controller->request->data['group_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Открыть комнату по id
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function get_chat_room(){
			if (!isset($this->_controller->request->data['room_id'])) {
				throw new ApiIncorrectRequestException();
			}
				
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiChatContact->openRoom($this->_userId,0,null,$this->_controller->request->data['room_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Список пользователей чата
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function get_chat_room_members(){
			if (!isset($this->_controller->request->data['room_id'])) {
				throw new ApiIncorrectRequestException();
			}
				
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiIncorrectRequestException();
			}		
			$result['Member'] = $this->_controller->ApiChatMember->getMembers($this->_controller->request->data['room_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Отправить сообщение в чат-комнату
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_send_message(){
			if (!isset($this->_controller->request->data['room_id']) or !isset($this->_controller->request->data['message'])or !trim($this->_controller->request->data['message'])) {
				throw new ApiIncorrectRequestException();
			}
				
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}		
			$result = $this->_controller->ApiChatEvent->addMessage($this->_userId, $this->_controller->request->data['room_id'], $this->_controller->request->data['message']);
			$this->_controller->setResponse($result);
	}

	/**
	* Отправить файл в чат-комнату
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_send_file(){
			if (!isset($this->_controller->request->data['room_id'])) {
				throw new ApiIncorrectRequestException();
			}
				
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
			$mediaId = $this->_controller->sendFile('Chat', null);
			$result = $this->_controller->ApiChatEvent->addFile($this->_userId, $this->_controller->request->data['room_id'], $mediaId);

			$this->_controller->setResponse($result);
	}
	
	/**
	* Удалить чат-контакт
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_delete_contact(){
			if (!isset($this->_controller->request->data['contact_id'])) {
				throw new ApiIncorrectRequestException();
			}
				
			if(!$this->_controller->ApiChatContact->checkAccess($this->_userId,$this->_controller->request->data['contact_id'])){
				throw new ApiAccessDeniedException();
			}

			$this->_controller->ApiChatContact->deleteContact($this->_userId, $this->_controller->request->data['contact_id']);
			$result = $this->_controller->ApiChatContact->getList($this->_userId,'');
			
			$this->_controller->setResponse($result);
	}
	
	/**
	* Отметить сообщения как прочитанные
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_mark_read(){
			if (!isset($this->_controller->request->data['event_ids']) or !is_array($this->_controller->request->data['event_ids'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$this->_controller->ApiChatEvent->markRead($this->_userId, $this->_controller->request->data['event_ids']);
			$this->_controller->setResponse();
	}
	
	/**
	* добавить пользователя в чат
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_add_member(){
			if (!isset($this->_controller->request->data['room_id']) or !isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if($this->_controller->request->data['user_id'] == $this->_userId){
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiChatRoom->canAddMember($this->_controller->request->data['room_id'],$this->_userId)){
				throw ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiUser->isActive($this->_controller->request->data['user_id'])){
				throw ApiAccessDeniedException();
			}
			
			if($this->_controller->ApiChatMember->inRoom($this->_controller->request->data['user_id'],$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
		
			$roomId = $this->_controller->ApiChatMember->addChatMember($this->_userId,$this->_controller->request->data['room_id'],$this->_controller->request->data['user_id']);			
			$result = $this->_controller->ApiChatContact->openRoom($this->_userId,0,null,$roomId);
			$this->_controller->setResponse($result);
	}
	
	/**
	* удалить пользователя из чата
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_remove_member(){
			if (!isset($this->_controller->request->data['room_id']) or !isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiChatRoom->canRemoveMember($this->_controller->request->data['room_id'],$this->_userId,$this->_controller->request->data['user_id'])){
				throw new ApiAccessDeniedException();
			}
						
			if(!$this->_controller->ApiChatMember->inRoom($this->_controller->request->data['user_id'],$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->ApiChatMember->removeChatMember($this->_userId,$this->_controller->request->data['room_id'],$this->_controller->request->data['user_id']);
			$this->_controller->setResponse();
	}
	
	/**
	* удалить пользователя из чата
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_get_updates(){			
			if (!isset($this->_controller->request->data['room_id'])) {
				throw new ApiIncorrectRequestException();
			}
						
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$data = $this->_controller->ApiChatEvent->getUpdates($this->_userId,$this->_controller->request->data['room_id']);
			$this->_controller->setResponse($data);
	}
	
	/**
	* удалить пользователя из чата
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function chat_load_more(){
			if (!isset($this->_controller->request->data['room_id'])) {
				throw new ApiIncorrectRequestException();
			}
						
			if(!$this->_controller->ApiChatMember->inRoom($this->_userId,$this->_controller->request->data['room_id'])){
				throw new ApiAccessDeniedException();
			}
			
			if(!isset($this->_controller->request->data['last_event_id'])){
				$this->_controller->request->data['last_event_id'] = $this->_controller->ApiChatEvent->lastEventId($this->_userId,$this->_controller->request->data['room_id']);
				//так как условие "стого больше"
				$this->_controller->request->data['last_event_id']++;
			}
			
			$result = $this->_controller->ApiChatEvent->loadEvents($this->_userId,$this->_controller->request->data['room_id'],(int)$this->_controller->request->data['last_event_id']);
			$this->_controller->setResponse($result);
	}

}
?>
