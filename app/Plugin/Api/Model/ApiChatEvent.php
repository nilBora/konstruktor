<?php


/**
* файл модели ApiChatEvent
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('ChatContact', 'Model');
App::uses('ApiChatContact', 'Api.Model');
App::uses('ChatEvent', 'Model');
App::uses('ChatRoom', 'Model');
App::uses('ChatMember', 'Model');
App::uses('ChatMessage', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Media', 'Media.Model');

/**
* Модель ApiChatEvent. Обертка под модель ChatEvent
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiChatEvent extends AppModel {

	public $useTable = 'chat_events';
	
	protected function _afterInit() {
		$this->loadModel('ChatContact');
		$this->loadModel('ChatEvent');
		$this->loadModel('ChatRoom');
		$this->loadModel('ChatMember');
		$this->loadModel('ChatMessage');
		$this->loadModel('Media.Media');
		$this->loadModel('User');
		$this->loadModel('Group');
	}
	
	/**
	* открытие комнаты приватной или групповой
	* 
	* @param int $currUserId
	* @param int $userId
	* @param int $groupId   
	* @return array
	*/
	public function getRoom($currUserId,$userId,$groupId=null){
		 $room = $this->ChatEvent->openRoom($currUserId, $userId, $groupId);
		 if($room){
			 $room['ChatRoom']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($room['ChatRoom']['created']));
		 }
		 return $room;
	} 
	
	/**
	* отправить сообщение в чат
	* 
	* @param int $userId
	* @param int $roomId
	* @param string $message   
	*/
	public function addMessage($userId,$roomId,$message){
		$msgId = $this->ChatEvent->addMessage($userId, $roomId, $message);
		if(!$msgId){
			throw new Exception('Chat Send Message Error');
		}
		return $this->getEvent($msgId,false);
	}
	
	/**
	* отметить как прочитанные
	* 
	* @param int $userId
	* @param array $eventIds  
	* @return void
	*/
	public function markRead($userId,$eventIds){
		$this->ChatEvent->updateInactive($userId, $eventIds);
	}
	
	/**
	* отправка файла в чат
	* 
	* @param int $currUserId
	* @param int $roomId
	* @param int $mediaId   
	* @return void
	*/
	public function addFile($currUserId,$roomId,$mediaId){
		$this->ChatEvent->addFile($currUserId, $roomId, $mediaId);
		return $this->getEvent(false, $mediaId);
	}
	
	private function getEvent($msgId,$fileId){
		if($msgId){
			$event = $this->ChatEvent->findByIdAndEventType($msgId,  ChatEvent::OUTCOMING_MSG);
		}else{
			$event = $this->ChatEvent->findByFileIdAndEventType($fileId,  ChatEvent::FILE_UPLOADED);
		}
		$initiator = $this->User->findById($event['ChatEvent']['initiator_id'],array('User.id','User.full_name','UserMedia.*'));
		if($event['ChatEvent']['msg_id']){
			$message = $this->ChatMessage->findById($event['ChatEvent']['msg_id']);
		}
		if($event['ChatEvent']['file_id']){
			$file = $this->Media->findById($event['ChatEvent']['file_id']);
		}
		$aResult['ChatEvent']['id'] = $event['ChatEvent']['id'];
		$aResult['ChatEvent']['user_id'] = $event['ChatEvent']['user_id'];
		$aResult['ChatEvent']['room_id'] = $event['ChatEvent']['room_id'];
		$aResult['ChatEvent']['active'] = $event['ChatEvent']['active'];
		$aResult['ChatEvent']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ChatEvent']['created']));
		$aResult['ChatEvent']['event_type'] = $event['ChatEvent']['event_type'];
		$aResult['ChatEvent']['initiator_id'] = $event['ChatEvent']['initiator_id'];
		$aResult['ChatEvent']['initiator_name'] = $initiator['User']['full_name'];
		$aResult['ChatEvent']['initiator_img'] = $initiator['UserMedia']['url_img'];
		$aResult['ChatEvent']['recipient_id'] = $event['ChatEvent']['recipient_id'];
		$aResult['ChatEvent']['file_id'] = $event['ChatEvent']['file_id'];
		$aResult['ChatEvent']['msg_id'] = $event['ChatEvent']['msg_id'];
		if(isset($message)){
			$aResult['ChatEvent']['message'] = $message['ChatMessage']['message'];
		}
		if(isset($file)){
			$aResult['ChatEvent']['media_type'] = $file['Media']['media_type'];
			$aResult['ChatEvent']['ext'] = $file['Media']['ext'];
			$aResult['ChatEvent']['url'] = $file['Media']['url_download'];
			if(isset($file['Media']['url_img'])){
				$aResult['ChatEvent']['image'] = $file['Media']['url_img'];
			}
		}
		return $aResult;	
	}
	
	/**
	* получение обновлений в чатах
	* 
	* @param int $userId  
	* @return array
	*/
	public function getUpdates($userId,$roomId){
		$this->loadModel('Api.ApiChatContact');
		$data = $this->ChatEvent->getActiveEvents($userId,array('room_id'=>$roomId));
		if(!$data){
			return array();
		}
		$initiatorsList = Hash::extract($data['events'], '{n}.ChatEvent.initiator_id');
		if($initiatorsList){
			$this->initiators = $this->User->findAllById($initiatorsList,array('User.id','User.full_name','UserMedia.*'));
			$this->initiators = Hash::combine($this->initiators, '{n}.User.id','{n}');
		}
		
		$recipientsList = Hash::extract($data['events'], '{n}.ChatEvent.recipient_id');
		if($initiatorsList){
			$this->recipients = $this->User->findAllById($recipientsList,array('User.id','User.full_name','UserMedia.*'));
			$this->recipients = Hash::combine($this->recipients, '{n}.User.id','{n}');
		}
		
		$this->contacts = $this->ApiChatContact->getList($userId,'',$roomId);
		return $this->formatUpdates($data);
	}
	
	/**
	* форматирование ответа по обновлением
	* 
	* @param array $data  
	* @return array
	*/
	private function formatUpdates($data){
		$aResult['Events'] = array();
		foreach ($data['events'] as $id=>$event){
			$aResult['Events'][$id]['id'] = $event['ChatEvent']['id'];			
			$aResult['Events'][$id]['user_id'] = $event['ChatEvent']['user_id'];
			
			$aResult['Events'][$id]['room_id'] = $event['ChatEvent']['room_id'];
			$aResult['Events'][$id]['active'] = $event['ChatEvent']['active'];
			$aResult['Events'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ChatEvent']['created']));
			$aResult['Events'][$id]['event_type'] = $event['ChatEvent']['event_type'];
			
			$initiatorId = $event['ChatEvent']['initiator_id'];
			$aResult['Events'][$id]['initiator_id'] = $initiatorId;
			if(isset($this->initiators[$initiatorId])){
				$aResult['Events'][$id]['initiator_name'] = $this->initiators[$initiatorId]['User']['full_name'];
				$aResult['Events'][$id]['initiator_img'] = $this->initiators[$initiatorId]['UserMedia']['url_img'];
			}
			
			$recipientId = $event['ChatEvent']['recipient_id'];
			$aResult['Events'][$id]['recipient_id'] = $recipientId;
			if(isset($this->recipients[$recipientId])){
				$aResult['Events'][$id]['recipient_name'] = $this->recipients[$recipientId]['User']['full_name'];
				$aResult['Events'][$id]['recipient_img'] = $this->recipients[$recipientId]['UserMedia']['url_img'];
			}
			
			$aResult['Events'][$id]['file_id'] = $event['ChatEvent']['file_id'];
			$aResult['Events'][$id]['msg_id'] = $event['ChatEvent']['msg_id'];
			if($event['ChatEvent']['msg_id'] and isset($data['messages'][$event['ChatEvent']['msg_id']])){
				$aResult['Events'][$id]['message'] = $data['messages'][$event['ChatEvent']['msg_id']]['message'];
			}
			if($event['ChatEvent']['file_id'] and isset($data['files'][$event['ChatEvent']['file_id']])){
				
				$aResult['Events'][$id]['media_type'] = $data['files'][$event['ChatEvent']['file_id']]['media_type'];
				$aResult['Events'][$id]['ext'] = $data['files'][$event['ChatEvent']['file_id']]['ext'];
				$aResult['Events'][$id]['url'] = $data['files'][$event['ChatEvent']['file_id']]['url_download'];
				$aResult['Events'][$id]['image'] = $data['files'][$event['ChatEvent']['file_id']]['image'];
			}
		}
		$aResult['UpdateRooms'] = array();
		if($data['updateRooms']){
			foreach($data['updateRooms'] as $roomId=>$users){
				foreach($users as $userId=>$user){
					$aResult['UpdateRooms'][$roomId][$userId]['id'] = $user['User']['id'];
					$aResult['UpdateRooms'][$roomId][$userId]['full_name'] = $user['User']['full_name'];
					$aResult['UpdateRooms'][$roomId][$userId]['url_img'] = $user['UserMedia']['url_img'];
				}
			}
		}
		if(isset($this->contacts['User'])){
			$aResult['Contacts'] = $this->contacts['User'];
		}
		return $aResult;
	}
	
	/**
	* подгрузка по оффсету
	* 
	* @param int $currUserId
	* @param int $roomId
	* @param int $lastEventId   
	* @return array
	*/
	public function loadEvents($currUserId,$roomId,$lastEventId){
		
		$data = $this->ChatEvent->loadEvents($currUserId, $roomId, $lastEventId);
		if(!$data){
			return array();
		}
		
		$initiatorIds = Hash::extract($data, 'events.{n}.ChatEvent.initiator_id');
		$this->initiators = $this->User->findAllById($initiatorIds);
		$this->initiators = Hash::combine($this->initiators, '{n}.User.id','{n}');
		
		$recipientsIds = Hash::extract($data, 'events.{n}.ChatEvent.recipient_id');
		$this->recipients = $this->User->findAllById($recipientsIds);
		$this->recipients = Hash::combine($this->recipients, '{n}.User.id','{n}');
		//return $this->formatUpdates($data);
		return $this->formatLoadEvents($data);
	}
	
		/**
	* форматирование ответа по обновлением
	* 
	* @param array $data  
	* @return array
	*/
	private function formatLoadEvents($data){
		$aResult['Events'] = array();
		foreach ($data['events'] as $id=>$event){
			$aResult['Events'][$id]['id'] = $event['ChatEvent']['id'];
			$aResult['Events'][$id]['user_id'] = $event['ChatEvent']['user_id'];
			$aResult['Events'][$id]['room_id'] = $event['ChatEvent']['room_id'];
			$aResult['Events'][$id]['active'] = $event['ChatEvent']['active'];
			$aResult['Events'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ChatEvent']['created']));
			$aResult['Events'][$id]['event_type'] = $event['ChatEvent']['event_type'];
			if(isset($this->initiators[$event['ChatEvent']['user_id']]['User']['rating'])) {
				$aResult['Events'][$id]['rating'] = $this->initiators[$event['ChatEvent']['user_id']]['User']['rating'];
			}
			
			
			$initiatorId = $event['ChatEvent']['initiator_id'];
			$aResult['Events'][$id]['initiator_id'] = $initiatorId;
			if(isset($this->initiators[$initiatorId])){
				$aResult['Events'][$id]['initiator_name'] = $this->initiators[$initiatorId]['User']['full_name'];
				$aResult['Events'][$id]['initiator_img'] = $this->initiators[$initiatorId]['UserMedia']['url_img'];
				$aResult['Events'][$id]['rating'] = $this->initiators[$initiatorId]['User']['rating'];
			}
			
			$recipientId = $event['ChatEvent']['recipient_id'];
			$aResult['Events'][$id]['recipient_id'] = $recipientId;
			if(isset($this->recipients[$recipientId])){
				$aResult['Events'][$id]['recipient_name'] = $this->recipients[$recipientId]['User']['full_name'];
				$aResult['Events'][$id]['recipient_img'] = $this->recipients[$recipientId]['UserMedia']['url_img'];
			}
			
			$aResult['Events'][$id]['recipient_id'] = $event['ChatEvent']['recipient_id'];
			$aResult['Events'][$id]['file_id'] = $event['ChatEvent']['file_id'];
			$aResult['Events'][$id]['msg_id'] = $event['ChatEvent']['msg_id'];
			if($event['ChatEvent']['msg_id'] and isset($data['messages'][$event['ChatEvent']['msg_id']])){
				$aResult['Events'][$id]['message'] = $data['messages'][$event['ChatEvent']['msg_id']]['message'];
			}
			if($event['ChatEvent']['file_id'] and isset($data['files'][$event['ChatEvent']['file_id']])){
				
				$aResult['Events'][$id]['media_type'] = $data['files'][$event['ChatEvent']['file_id']]['media_type'];
				$aResult['Events'][$id]['ext'] = $data['files'][$event['ChatEvent']['file_id']]['ext'];
				$aResult['Events'][$id]['url'] = $data['files'][$event['ChatEvent']['file_id']]['url_download'];
				$aResult['Events'][$id]['image'] = $data['files'][$event['ChatEvent']['file_id']]['image'];
			}
		}
		$aResult['UpdateRooms'] = array();
		if($data['updateRooms']){
			foreach($data['updateRooms'] as $roomId=>$users){
				foreach($users as $userId=>$user){
					$aResult['UpdateRooms'][$roomId][$userId]['id'] = $user['User']['id'];
					$aResult['UpdateRooms'][$roomId][$userId]['full_name'] = $user['User']['full_name'];
					$aResult['UpdateRooms'][$roomId][$userId]['url_img'] = $user['UserMedia']['url_img'];
				}
			}
		}
		if(isset($this->contacts['User'])){
			$aResult['Contacts'] = $this->contacts['User'];
		}
		return $aResult;
	}
	
	public function lastEventId($userId,$roomId){
		return $this->ChatEvent->field('MAX(id)',array('user_id'=>$userId,'room_id'=>$roomId));
	}
	
}

?>
