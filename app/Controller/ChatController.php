<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class ChatController extends SiteController {
	public $name = 'Chat';
	public $uses = array('ChatMember', 'Group', 'ChatRoom', 'StorageLimit');


	public function index($userID = 0) {
		$this->layout = 'chat';
		$this->set('chatUserID', $userID);
	}

	public function room($roomID) {
		$this->layout = 'chat';
        $this->StorageLimit->updateChatFileSize($this->Auth->user('id'));
//        $this->StorageLimit->chatFileSize($this->Auth->user('id'));
//        $this->StorageLimit->fileSizeByKey($this->Auth->user('id'), 'message_file_size');

		// проверим есть ли текущий юзер в данной комнате
		$members = $this->ChatMember->getRoomMembers($roomID);
		if (!in_array($this->currUserID, $members)) {
			throw new NotFoundException();
		}
		$this->set('roomID', $roomID);
	}

	public function group($groupID) {
		$this->layout = 'chat';
		$this->set('groupID', $groupID);
	}
}
