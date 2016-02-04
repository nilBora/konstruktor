<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class ChatAjaxController extends PAjaxController {
	public $name = 'ChatAjax';
	public $uses = array('User', 'ChatMessage', 'ChatEvent', 'ChatContact', 'ChatRoom', 'ChatMember', 'Group');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	public function jsSettings() {
		$this->response->type(array('type' => 'text/javascript'));
	}

	public function contactList() {
		try {
			$aUsers = array();
			$q = $this->request->data('q');
			$data = $this->ChatContact->getList($this->currUserID, $q);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function miniContactList() {
		try {
			$aUsers = array();
			$q = $this->request->data('q');
			$data = $this->ChatContact->getMiniList($this->currUserID, $q);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function openRoom() {
		$userID = $this->request->data('user_id');
		$roomID = $this->request->data('room_id');
		$groupID = $this->request->data('group_id');
		try {
			if ($roomID) {
				$room = $this->ChatRoom->findById($roomID);
			} elseif ($groupID) {
				$this->loadModel('Group');
				$group = $this->Group->findById($groupID);
                $owner = Hash::get($group, 'Group.owner_id');
                $recipient = !is_null(Hash::get($group, 'Group.responsible_id')) ? Hash::get($group, 'Group.responsible_id') : $owner;
				$room = $this->ChatEvent->openRoom($this->currUserID, $recipient, $groupID);
			} elseif ($userID) {
				$room = $this->ChatEvent->openRoom($this->currUserID, $userID);
			} else {
				throw new Exception('Incorrect request');
			}

			if (!$room) {
				throw new Exception('Room does not exists');
			}
			$room['ChatRoom']['canAddMember'] = !$groupID && in_array($this->currUserID, array($room['ChatRoom']['initiator_id'], $room['ChatRoom']['recipient_id']));

			$aID = $this->ChatMember->getRoomMembers($room['ChatRoom']['id']);
			$members = $this->User->getUsers($aID);
			unset($members[$this->currUserID]);

			$aID = $this->ChatMember->getMembers($room['ChatRoom']['id']);
			$all_members = $this->User->getUsers($aID);
			unset($all_members[$this->currUserID]);

			$group = array();
			if ($room['ChatRoom']['group_id']) {
				$group = $this->Group->findById($room['ChatRoom']['group_id']);
			}
			$events = $this->ChatEvent->getInitialEvents($this->currUserID, $room['ChatRoom']['id']);
			// $events = $this->ChatEvent->getAllRoomEvents($this->currUserID, $room['ChatRoom']['id']);
			return $this->setResponse(compact('room', 'members', 'events', 'all_members', 'group'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function getRoomHistory() {
		$userID = $this->request->data('user_id');
        $groupID = $this->request->data('group_id');
		$lastMsgId = $this->request->data('message_count');

        if (!$userID) {
			throw new Exception('User does not exists');
		}
		if(empty($lastMsgId)){
			$lastMsgId = 0;
		}else{
			$lastMsgId = (int)$lastMsgId;
		}
		//var_dump($lastMsgId);
		$room = $this->ChatEvent->openRoom($this->currUserID, $userID, $groupID);
		if (!$room) {
			throw new Exception('Room does not exists');
		}
		$this->ChatEvent->bindModel(array(
			'belongsTo' => array(
				'ChatMessage' => array(
					'className' => 'ChatMessage',
					'foreignKey' => 'msg_id'
				),
				'File' => array(
					'className' => 'Media.Media',
					'foreignKey' => 'file_id'
//					'conditions' => array('File.object_type' => 'Chat')
				),
			),
		));
		$condition = array(
			'ChatEvent.room_id' => $room['ChatRoom']['id'],
			'ChatEvent.event_type' => array(1, 2, 6, 7),
			'ChatEvent.user_id' => $userID,
		);

		$this->ChatEvent->Behaviors->load('Containable');
		$events = $this->ChatEvent->find('all', array(
			'conditions' => $condition,
			'contain' => array('ChatMessage', 'File'),
			'order' => array('ChatEvent.created' => 'DESC'),
			'limit' => 20,
			'offset' => $lastMsgId
		));
		$events = array_reverse($events);
		$this->set(compact('room', 'events'));
	}

	public function sendMsg() {
		$roomID = $this->request->data('roomID');
		$msg = $this->request->data('msg');
		$msg = htmlentities($msg);
		if (!($msg && $roomID)) {
			throw new Exception('Incorrect request');
		}
		try {
			$id = $this->ChatEvent->addMessage($this->currUserID, $roomID, $msg);
			return $this->setResponse($id);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function sendFiles() {
		$roomID = $this->request->data('roomID');
		$files_data = $this->request->data('files_data');
		$media_ids = array();
		try {
			if (!($files_data && $roomID)) {
				throw new Exception('Incorrect request');
			}

            $events = array();

			foreach($files_data as $file) {
				$orig_fname = $file['name'];
				$tmp_name = PATH_FILES_UPLOAD.$orig_fname;
				list($media_type) = explode('/', $file['type']);
				if (!in_array($media_type, $this->Media->types)) {
				    $media_type = 'bin_file';
				}
				$object_type = 'Chat';
				$object_id = null;
				$path = pathinfo($tmp_name);
				$file = $media_type; // $path['filename'];
				$ext = '.'.$path['extension'];

				$data = compact('media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext', 'orig_fname');
				$mediaID = $this->Media->uploadMedia($data);

				$events[$mediaID] = $this->ChatEvent->addFile($this->currUserID, $roomID, $mediaID);
				$media_ids[] = $mediaID;
			}

            $this->loadModel( 'MediaFile' );

            $list = $this->MediaFile->getList(array('Media.id' => $media_ids), array('Media.id' => 'ASC'));
            foreach ($list as $k => $v) {
                $list[$k]['Media']['object_id'] = isset($events[$v['Media']['id']]) ? $events[$v['Media']['id']] : $list[$k]['Media']['object_id'];
            }

            return $this->setResponse($list);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	/**
	 * Отправка файла с "Моего Времени" пользователям – зажать его мышкой (или пальцем на тач падах) и перенести на
	 * любого пользователя на времени или в списке диалогов (справа) и выбранный файл автоматически отправиться
	 * пользователю в диалог чата
	 *
	 * @param int $roomID
	 * @param int $userInitial
	 * @param int $userRecipient
	 * @param int $mediaID
	 */
	public function sendTimelineFiles() {

		$roomID = $this->request->data('roomId');
		$userInitial = $this->request->data('userInitial');
		$userRecipient = $this->request->data('userRecipient');
		$mediaID = $this->request->data('fileId');
		/** If there no room send by data, then check for existing or create new */
		if (!$roomID) {
			$room = $this->ChatEvent->openRoom($userInitial, $userRecipient);
			$roomID = $room['ChatRoom']['id'];
		}
		try {
			if (!($mediaID && $roomID)) {
				throw new Exception('Incorrect request');
			}
			$this->ChatEvent->addFile($this->currUserID, $roomID, $mediaID);
			$this->loadModel( 'MediaFile' );

			return $this->setResponse($this->MediaFile->getList(array('Media.id' => $mediaID), array('Media.id' => 'ASC')));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function updateState() {
		try {
			$data = $this->ChatEvent->getActiveEvents($this->currUserID);
			$data['aUsers'] = $this->ChatContact->getList($this->currUserID);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}

	}

	public function markRead() {
		try {
			$ids = $this->request->data('ids');
			if (!$ids || !is_array($ids)) {
				throw new Exception('Incorrect request');
			}
			$this->ChatEvent->updateInactive($this->currUserID, $ids);
			$this->setResponse(true);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function delContact() {
		try {
			$id = $this->request->data('contact_id');
			$this->ChatEvent->removeContact($this->currUserID, $id);
			$data = $this->ChatContact->getList($this->currUserID);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function addMember() {
		try {
			$userID = $this->request->data('user_id');
			$roomID = $this->request->data('room_id');

			if (!($roomID && $userID)) {
				throw new Exception('Incorrect request');
			}

			$members = $this->ChatMember->getRoomMembers($roomID);
			if (count($members) == 2) {
				// create a new room for a group chat
				$newRoom = $this->ChatEvent->createRoom($members[0], $members[1]);
				if ($userID != $members[0] && $userID !== $members[1]) {
					// $this->ChatMember->save(array('room_id' => $newRoom['ChatRoom']['id'], 'user_id' => $userID));
					$this->ChatEvent->addMember($this->currUserID, $newRoom['ChatRoom']['id'], $userID);
				}
				$this->setResponse(compact('newRoom'));
				return;
			}
			if ($roomID && $userID) {
				$this->ChatEvent->addMember($this->currUserID, $roomID, $userID);
			} else {
				throw new Exception('Incorrect request');
			}

			$aID = $this->ChatMember->getRoomMembers($roomID);
			$members = $this->User->getUsers($aID);
			unset($members[$this->currUserID]);

			$aID = $this->ChatMember->getMembers($roomID);
			$all_members = $this->User->getUsers($aID);
			unset($all_members[$this->currUserID]);

			$this->setResponse(compact('members', 'all_members'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function removeMember() {
		try {
			$userID = $this->request->data('user_id');
			$roomID = $this->request->data('room_id');

			if (!($roomID && $userID)) {
				throw new Exception('Incorrect request');
			}

			$this->ChatEvent->removeMember($this->currUserID, $roomID, $userID);
			$aID = $this->ChatMember->getRoomMembers($roomID);
			$members = $this->User->getUsers($aID);
			unset($members[$this->currUserID]);

			$aID = $this->ChatMember->getMembers($roomID);
			$all_members = $this->User->getUsers($aID);
			unset($all_members[$this->currUserID]);

			$this->setResponse(compact('members', 'all_members'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function loadMore() {
		try {
			$id = $this->request->data('id');
			$room_id = $this->request->data('room_id');

			if (!$id || !$room_id) {
				throw new Exception('Incorrect request');
			}

			$data = $this->ChatEvent->loadEvents($this->currUserID, $room_id, $id);
			$this->setResponse($data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function editMessage() {
		$event = $this->ChatEvent->findByIdAndInitiatorId($this->request->data('event_id'), $this->currUserID);

		if (!$event) {
			$this->setError(__('Error updaing event'));
			return;
		}

		$message = $this->ChatMessage->findById($event['ChatEvent']['msg_id']);

		if (!$message) {
			$this->setError(__('Error updaing event'));
			return;
		}

		$message['ChatMessage']['message'] = $this->request->data('message');

		if (!$this->ChatMessage->save($message)) {
			$this->setError(__('Error cant save message'));
			return;
		}

		$events = $this->ChatEvent->find('all', array(
			'conditions' => array('msg_id' => $event['ChatEvent']['msg_id'])
		));
		if (!$events) {
			$this->setError(__('Error updaing events active'));
			return;
		}
		foreach($events as $eventMsg){
			$eventMsg['ChatEvent']['active'] = 1;
			$this->ChatEvent->save($eventMsg);
		}
		$this->setResponse();
		return;
	}

	public function removeMessage() {
		$event = $this->ChatEvent->findByIdAndInitiatorId($this->request->data('event_id'), $this->currUserID);
		$msgId = Hash::get($event, 'ChatEvent.msg_id');
        $fileId = isset($event['ChatEvent']['file_id']) ? $event['ChatEvent']['file_id'] : NULL;

//        var_dump($event);die;

		if (!$event) {
			$this->setError(__('Error updaing event'));
			return;
		}

		$message = $this->ChatMessage->findById( $msgId );

		if($message) {
            $aEvent = $this->ChatEvent->findAllByMsgId( $msgId );
            $aEventId = Hash::extract($aEvent, '{n}.ChatEvent.id');

            $message['ChatMessage']['message'] = $this->request->data('message');

            if (!$this->ChatMessage->delete( $msgId )) {
                $this->setError(__('Error cant delete message'));
                return;
            }
            foreach( $aEventId as $eid ) {
                if (!$this->ChatEvent->delete($eid)) {
                    $this->setError(__('Error cant delete event'));
                    return;
                }
            }
        } else if($fileId !== NULL) {
            $files = $this->Media->getList(array('id' => $fileId, 'object_type' => 'Chat'));
            if(count($files)) {
                foreach ($files as $file) {
                    $file = $file['Media'];
                    $this->Media->delete($file['id']);
                    $this->ChatEvent->deleteAll(array('file_id' => $file['id']), false);
                }
            }
        } else {
            $this->setError(__('Error cant delete event'));
            return;
        }

		$this->setResponse();
		return;
	}
}
