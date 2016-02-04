<?php

/**
* файл модели ApiChatContact
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
App::uses('ChatEvent', 'Model');
App::uses('ApiChatEvent', 'Api.Model');
App::uses('ChatMember', 'Model');
App::uses('ApiChatMember', 'Api.Model');
App::uses('ChatRoom', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');

/**
* Модель ApiChatContact. Обертка под модель ChatContact
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiChatContact extends AppModel {

	public $useTable = 'chat_contacts';
	
	protected function _afterInit() {
		$this->loadModel('ChatContact');
		$this->loadModel('ChatEvent');
		$this->loadModel('ChatMember');
		$this->loadModel('Api.ApiChatMember');
		$this->loadModel('ChatRoom');
		$this->loadModel('User');
		$this->loadModel('Group');
	}
	
	/**
	* Проверка принадлежности контакта
	* 
	* @param int $userId 
	* @param int $contactId  
	* @return bool
	*/
	public function checkAccess($userId,$contactId){
		$result = $this->ChatContact->field('id',array('id'=>$contactId,'user_id'=>$userId));
		if($result){
			return true;
		}
		return false;
	}

	/**
	* Список контактов
	* 
	* @param int $userId 
	* @param string $query  
	* @return array
	*/
	public function getList($userId,$query='',$roomId = false){
		$data = $this->ChatContact->getList($userId, $query);
		if(!$data){
			return array();
		}	
		$roomList = Hash::extract($data['aUsers'],'{n}.ChatContact.room_id');
		if($roomList){
			$this->rooms = $this->ChatRoom->findAllById($roomList,array('id','recipient_id','initiator_id','group_id'));
			$this->rooms = Hash::combine($this->rooms, '{n}.ChatRoom.id','{n}');
		}
		
		return $this->formatContactList($data,$userId,$roomId);
	}
	
	/**
	* Форматирование Список контактов
	* 
	* @param array $data   
	* @return array
	*/
	private function formatContactList($data,$currUserId,$roomId = false){
		$aResult['User'] = array();
		
		if(isset($data['aUsers']) and $data['aUsers']){
			foreach ($data['aUsers'] as $id=>$user){
				if($roomId and isset($user['ChatContact']) and $user['ChatContact']['room_id']!=$roomId ){
					continue;
				}
				$aResult['User'][$id]['id'] = $user['User']['id'];
				$aResult['User'][$id]['full_name'] = $user['User']['full_name'];
				$aResult['User'][$id]['url_img'] = $user['UserMedia']['url_img'];
				$aResult['User'][$id]['rating'] = $user['User']['rating'];
				if(isset($user['ChatContact']) and $user['ChatContact']){
					$aResult['User'][$id]['contact_id'] = $user['ChatContact']['id'];
					$aResult['User'][$id]['chat_event_id'] = $user['ChatContact']['chat_event_id'];
					$aResult['User'][$id]['active_count'] = $user['ChatContact']['active_count'];
					$aResult['User'][$id]['msg'] = $user['ChatContact']['msg'];
					
					$contactRoom = $user['ChatContact']['room_id'];
					$aResult['User'][$id]['ChatRoom']['room_id'] = $contactRoom;
					$aResult['User'][$id]['ChatRoom']['recipient_id'] = $this->rooms[$contactRoom]['ChatRoom']['recipient_id'];
					$aResult['User'][$id]['ChatRoom']['initiator_id'] = $this->rooms[$contactRoom]['ChatRoom']['initiator_id'];
					$aResult['User'][$id]['ChatRoom']['group_id'] = $this->rooms[$contactRoom]['ChatRoom']['group_id'];
					$aResult['User'][$id]['ChatRoom']['can_add_member'] = !($this->rooms[$contactRoom]['ChatRoom']['group_id'])&&($currUserId == $this->rooms[$contactRoom]['ChatRoom']['recipient_id'] or $currUserId == $this->rooms[$contactRoom]['ChatRoom']['initiator_id']);
					$aResult['User'][$id]['ChatRoom']['can_delete_member'] = $aResult['User'][$id]['ChatRoom']['can_add_member'] && (count($user['ChatContact']['members'])>=2);
					
					if($user['ChatContact']['group_id']){
						$groupId = $user['ChatContact']['group_id'];
						$aResult['User'][$id]['Group']['id'] = $groupId;
						$aResult['User'][$id]['Group']['title'] = $data['aGroups'][$groupId]['Group']['title'];
						$aResult['User'][$id]['Group']['url_img'] = $data['aGroups'][$groupId]['GroupMedia']['url_img'];
					}
					$aResult['User'][$id]['members_count'] = count($user['ChatContact']['members'])+1;
					
				}
			}
		}
		
		return $aResult;		
 	}


	 /**
	* открытие/создание комнаты
	* 
	* @param int $currUserId 
	* @param int $userId
	* @param int $groupId
	* @param int $roomId  
	* @return array
	*/
	 public function openRoom($currUserId,$userId,$groupId=null,$roomId=null){
		if($roomId){
			$room = $this->ChatRoom->findById($roomId);
			if($room){
				$room['ChatRoom']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($room['ChatRoom']['created']));
			}
		}else{
			$this->loadModel('Api.ApiChatEvent');
			$room = $this->ApiChatEvent->getRoom($currUserId, $userId, $groupId);
		}
		if(!$room){
			throw new Exception('Server Error');
		}

		$this->members = $this->ApiChatMember->getMembers($room['ChatRoom']['id'], $currUserId);

		if ($room['ChatRoom']['group_id']) {
				$this->group = $this->Group->findById($room['ChatRoom']['group_id']);
		}
		//$this->events = $this->ChatEvent->getInitialEvents($currUserId, $room['ChatRoom']['id']);
		$recipient_rating = $this->User->findById($room['ChatRoom']['recipient_id'], array("User.id", "User.rating"));

		$room['ChatRoom']['rating'] = $recipient_rating['User']['rating'];
		return $this->formatRoomData($room,$currUserId);
		 
	}
	

	/**
	* Форматирование информации по комнате
	* 
	* @param array $data   
	* @return array
	*/
	private function formatRoomData($room,$currUserId){
		$aResult = $room;
		if($room['ChatRoom']['group_id']){
			$aResult['ChatRoom']['Group']['id'] = $room['ChatRoom']['group_id'];
			$aResult['ChatRoom']['Group']['title'] = $this->group['Group']['title'];
			$aResult['ChatRoom']['Group']['url_img'] = $this->group['GroupMedia']['url_img'];
		}
		
		$aResult['ChatRoom']['can_add_member'] = !($room['ChatRoom']['group_id'])&&($currUserId == $room['ChatRoom']['recipient_id'] or $currUserId == $room['ChatRoom']['initiator_id']);
		$aResult['ChatRoom']['can_delete_member'] = $aResult['ChatRoom']['can_add_member'] && (count($this->members)>2);
		
		unset($aResult['ChatRoom']['group_id']);
		$aResult['Member'] = $this->members;
		
		/*$aResult['Events'] = array();
		foreach ($this->events['events'] as $id=>$event){
			$aResult['Events'][$id]['id'] = $event['ChatEvent']['id'];
			$aResult['Events'][$id]['user_id'] = $event['ChatEvent']['user_id'];
			$aResult['Events'][$id]['active'] = $event['ChatEvent']['active'];
			$aResult['Events'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ChatEvent']['created']));
			$aResult['Events'][$id]['event_type'] = $event['ChatEvent']['event_type'];
			$aResult['Events'][$id]['initiator_id'] = $event['ChatEvent']['initiator_id'];
			$aResult['Events'][$id]['recipient_id'] = $event['ChatEvent']['recipient_id'];
			if($event['ChatEvent']['msg_id'] and isset($this->events['messages'][$event['ChatEvent']['msg_id']])){
				
				$aResult['Events'][$id]['message'] = $this->events['messages'][$event['ChatEvent']['msg_id']]['message'];
				
			}
			if($event['ChatEvent']['file_id'] and isset($this->events['files'][$event['ChatEvent']['file_id']])){
				
				$aResult['Events'][$id]['media_type'] = $this->events['files'][$event['ChatEvent']['file_id']]['media_type'];
				$aResult['Events'][$id]['ext'] = $this->events['files'][$event['ChatEvent']['file_id']]['ext'];
				$aResult['Events'][$id]['url'] = $this->events['files'][$event['ChatEvent']['file_id']]['url_download'];
				$aResult['Events'][$id]['image'] = $this->events['files'][$event['ChatEvent']['file_id']]['image'];
				
			}
		}
		$aResult['UpdateRooms'] = array();
		if($this->events['updateRooms']){
			foreach($this->events['updateRooms'] as $roomId=>$users){
				foreach($users as $userId=>$user){
					$aResult['UpdateRooms'][$roomId][$userId]['id'] = $user['User']['id'];
					$aResult['UpdateRooms'][$roomId][$userId]['full_name'] = $user['User']['full_name'];
					$aResult['UpdateRooms'][$roomId][$userId]['url_img'] = $user['UserMedia']['url_img'];
				}
			}
		}*/
		return $aResult;
	}
	
	/**
	* Удаление контакта
	* 
	* @param int $userId 
	* @param int $contactId  
	* @return void
	*/
	public function deleteContact($userId,$contactId){
		$this->ChatEvent->removeContact($userId, $contactId);
	}
}
?>
