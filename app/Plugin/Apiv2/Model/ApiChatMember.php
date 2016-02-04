<?php

/**
* файл модели ApiChatMember
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
App::uses('ChatRoom', 'Model');
App::uses('ChatMember', 'Model');
App::uses('User', 'Model');
App::uses('Group', 'Model');

/**
* Модель ApiChatMember. Обертка под модель ChatMember
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiChatMember extends AppModel {

	public $useTable = 'chat_members';
	
	protected function _afterInit() {
		$this->loadModel('ChatContact');
		$this->loadModel('ChatEvent');
		$this->loadModel('ChatRoom');
		$this->loadModel('ChatMember');
		$this->loadModel('User');
		$this->loadModel('Group');
	}
	
	/**
	* является ли пользователь активным участником комнаты
	* 
	* @param int $userId
	* @param int $roomId   
	* @return bool
	*/
	public function inRoom($userId,$roomId){
		$result = $this->ChatMember->field('id',array('room_id'=>$roomId,'user_id'=>$userId,'is_deleted'=>0));
		if(!$result){
			return false;
		}
		return true;
	}
	
	/**
	* участники комнаты
	* 
	* @param int $roomId
	* @param int $currUserId  
	* @return array
	*/
	public function getMembers($roomId,$currUserId = false){
		$aID = $this->ChatMember->getRoomMembers($roomId);
		$this->User->unbindModel(
			array('hasMany' => array('UserAchievement'))
		);
		$members = $this->User->getUsers($aID,array('User.id','User.full_name','UserMedia.*'));
		$aResult = array();
		if($members){
			$i=0;
			foreach ($members as $member){
				$aResult[$i]['id'] = $member['User']['id'];
				$aResult[$i]['full_name'] = $member['User']['full_name'];
				$aResult[$i]['url_img'] = $member['UserMedia']['url_img'];
				$i++;
			}
		}
		//выкидываем себя из списка - Николай сказал, что ему необходима эта инфа
		//unset($members[$currUserId]);
		
		return $aResult;
	}
	
	/**
	* добавить участника в комнату
	* 
	* @param int $currUserId
	* @param int $roomId 
	* @param int $userId   
	* @return int
	*/
	public function addChatMember($currUserId,$roomId,$userId){
		$membersIds = $this->ChatMember->getRoomMembers($roomId);
		if (count($membersIds) == 2) {
			$chatRoom = $this->ChatEvent->createRoom($membersIds[0], $membersIds[1]);
			$this->ChatEvent->addMember($currUserId, $chatRoom['ChatRoom']['id'], $userId);
			return $chatRoom['ChatRoom']['id'];
		}
		
		$this->ChatEvent->addMember($currUserId, $roomId, $userId);
		return $roomId;
	}
	
	/**
	* удалить участник из комнаты
	* 
	* @param int $currUserId
	* @param int $roomId 
	* @param int $userId   
	* @return void
	*/
	public function removeChatMember($currUserId,$roomId,$userId){
		$this->ChatEvent->removeMember($currUserId, $roomId, $userId);
	}
}

?>
