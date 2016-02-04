<?php

/**
* файл модели ApiChatRoom
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
App::uses('ChatMember', 'Model');
App::uses('ChatRoom', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');

/**
* Модель ApiChatRoom. Обертка под модель ChatRoom
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiChatRoom extends AppModel {

	public $useTable = 'chat_rooms';
	
	protected function _afterInit() {
		$this->loadModel('ChatContact');
		$this->loadModel('ChatEvent');
		$this->loadModel('ChatRoom');
		$this->loadModel('ChatMember');
		$this->loadModel('User');
		$this->loadModel('Group');
	}
	
	/**
	* проверка комнаты на существование
	* 
	* @param int $roomId   
	* @return bool
	*/
	public function isExist($roomId){
		$result = $this->ChatRoom->field('id',array('id'=>$roomId));
		if(!$result){
			return false;
		}
		return true;
	}
	
	/**
	* может ли добавить участника
	* 
	* @param int $roomId 
	* @param int $userId   
	* @return bool
	*/
	public function canAddMember($roomId,$userId){
		$room = $this->ChatRoom->findById($roomId);
		if(!$room){
			return false;
		}
		$result = !$room['ChatRoom']['group_id'] && in_array($userId, array($room['ChatRoom']['initiator_id'], $room['ChatRoom']['recipient_id']));
		return $result;
	}
	
	/**
	* может ли удалить участника
	* 
	* @param int $roomId 
	* @param int $userId   
	* @return bool
	*/
	public function canRemoveMember($roomId,$currUserId,$deletedUserId){
		$room = $this->ChatRoom->findById($roomId);
		if(!$room){
			return false;
		}
		$allowForDelete = !$room['ChatRoom']['group_id'] && in_array($currUserId, array($room['ChatRoom']['initiator_id'], $room['ChatRoom']['recipient_id']));
		//инициатор или рецепиент не могут быть удалены
		$canBeDeletedUser = $deletedUserId != $room['ChatRoom']['initiator_id'] && $deletedUserId != $room['ChatRoom']['recipient_id'];
		//не приватный чат
		$membersCount = $this->ChatMember->find('count',array('conditions' => array('ChatMember.room_id' => $roomId,'ChatMember.is_deleted'=>0)));
		$notPrivateChat = $membersCount > 2;		 
		return $allowForDelete && $canBeDeletedUser && $notPrivateChat;
	}
}
?>
