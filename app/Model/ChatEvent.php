<?php
App::uses('AppModel', 'Model');
class ChatEvent extends AppModel {
//    public $actsAs = array(
//        'Containable'
//    );
//    public $belongsTo = array(
//        'Media' => array(
//            'className' => 'Media',
//            'foreignKey' => 'file_id',
//            'fields' => array('Media.id','Media.orig_fsize')
//        )
//    );
	const OUTCOMING_MSG = 1;
	const INCOMING_MSG = 2;
	const ROOM_OPENED = 3;
	const FILE_UPLOADED = 6;
	const FILE_DOWNLOAD_AVAIL = 7;
	const INVITED_USER = 8;
	const WAS_INVITED = 9;
	const JOINED_ROOM = 10;
	const EXCLUDED_USER = 11;
	const WAS_EXCLUDED = 12;
	const LEFT_ROOM = 13;

	const ACTIVE = 1;
	const INACTIVE = 0;

	protected $ChatMessage, $ChatRoom, $Media, $ChatContact, $ChatMember;

	protected function _addEvent($event_type, $user_id, $room_id, $obj_id, $initiator_id, $active = 1) {
		$data = compact('event_type', 'user_id', 'room_id', 'initiator_id', 'active');
		if (in_array($event_type, array(self::OUTCOMING_MSG, self::INCOMING_MSG))) {
			$data['msg_id'] = $obj_id;
		} elseif (in_array($event_type, array(self::INVITED_USER, self::WAS_INVITED, self::JOINED_ROOM, self::EXCLUDED_USER, self::WAS_EXCLUDED, self::LEFT_ROOM))) {
			$data['recipient_id'] = $obj_id;
		} elseif (in_array($event_type, array(self::FILE_UPLOADED, self::FILE_DOWNLOAD_AVAIL))) {
			$data['file_id'] = $obj_id;
		}

		$this->clear();
		if (!$this->save($data)) {
			throw new Exception("Chat event cannot be saved\n".print_r($data, true));
		}
		return $this->id;
	}

	/**
	 * Returns all users in the chat room
	 *
	 * @param int $roomID
	 * @return array
	 */
	protected function _getRoomUsersID($roomID) {
		$this->loadModel('ChatMember');
		$aID = $this->ChatMember->getRoomMembers($roomID);
		return $aID;
	}

	public function addMessage($currUserID, $roomID, $message) {
		$this->loadModel('ChatMessage');
		$this->loadModel('ChatContact');
		$this->loadModel('ChatRoom');

		if (!$this->ChatMessage->save(compact('message'))) {
			throw new Exception("Message cannot be saved\n".print_r($data, true));
		}
		$msgID = $this->ChatMessage->id;

		$aUsersID = $this->_getRoomUsersID($roomID);
		$room = $this->ChatRoom->findById($roomID);
		$eventID = 0;
		foreach($aUsersID as $userID) {
			if ($userID == $currUserID) {
				$eventID = $this->_addEvent(self::OUTCOMING_MSG, $currUserID, $roomID, $msgID, $currUserID, self::INACTIVE);

				// выбираем первого юзера в комнате, но не самого себя
				// нужно выбрать того, кому адресовано сообщение
				foreach($aUsersID as $anotherUserID) {
					if ($anotherUserID != $currUserID) {
						break;
					}
				}
				$this->ChatContact->updateList($currUserID, $roomID, $anotherUserID, $message, $eventID, $room['ChatRoom']['group_id']);
				$this->ChatContact->setActiveCount($currUserID, $roomID, 0);
			} else {
				$this->_addEvent(self::INCOMING_MSG, $userID, $roomID, $msgID, $currUserID);
				// TODO: для КонтактЛиста - сохранять точное время из события
				// т.к. время сохранения КонтактЛиста и ЧатСобытия может отличаться по минутам
				// напр. ЧатСобытие записалось 03:59:59, а контактЛист - уже 04:00:00
				$this->ChatContact->updateList($userID, $roomID, $currUserID, $message, $eventID, $room['ChatRoom']['group_id']);
			}
		}
		return $eventID;
	}

	public function addFile($currUserID, $roomID, $mediaID) {
		$this->loadModel('ChatContact');
		$this->loadModel('ChatRoom');

		$aUsersID = $this->_getRoomUsersID($roomID);
		$room = $this->ChatRoom->findById($roomID);
        $eventID = 0;
		foreach($aUsersID as $userID) {
			if ($userID == $currUserID) {
                $eventID = $this->_addEvent(self::FILE_UPLOADED, $currUserID, $roomID, $mediaID, $currUserID, self::INACTIVE);
			} else {
				$this->_addEvent(self::FILE_DOWNLOAD_AVAIL, $userID, $roomID, $mediaID, $currUserID);
				$this->ChatContact->updateList($userID, $roomID, $currUserID, __('You have received a file'), $eventID, $room['ChatRoom']['group_id']);
			}
		}
        return $eventID;
	}

	public function createRoom($currUserID, $userID, $groupID = null) {
		$this->loadModel('ChatRoom');
		$this->loadModel('ChatMember');
		$this->loadModel('ChatContact');

		$this->ChatRoom->clear();
		$data = array('initiator_id' => $currUserID, 'recipient_id' => $userID, 'group_id' => $groupID);
		if (!$this->ChatRoom->save($data)) {
			throw new Exception("Room cannot be opened\n".print_r($data));
		}
		$room = $this->ChatRoom->findById($this->ChatRoom->id);

		// добавляем в нее обоих юзеров
		$this->ChatMember->clear();
		$this->ChatMember->save(array('room_id' => $this->ChatRoom->id, 'user_id' => $currUserID));
		$this->ChatMember->clear();
		$this->ChatMember->save(array('room_id' => $this->ChatRoom->id, 'user_id' => $userID));

		// Комнату для чата открывает сам юзер - помечаем это событие как прочитанное
		$eventID = $this->_addEvent(self::ROOM_OPENED, $currUserID, $room['ChatRoom']['id'], $userID, $currUserID, self::INACTIVE);

		// Создать чат-контакт при открытии комнаты - мы ведь его по идее уже выбираем для общения
		$msg = ''; // сообщение при открытии комнаты еще никакое не пришло. Можно выставлять skills, т.к. при поиске все равно они показываются
		$this->ChatContact->updateList($currUserID, $room['ChatRoom']['id'], $userID, $msg, $eventID, $groupID);

		// раз он сам открывает комнату - по ней не должно быть входящих
		$this->ChatContact->setActiveCount($currUserID, $room['ChatRoom']['id'], 0);

		// Если реципиенту не написали - нет смысла показывать открытие комнаты как НЕпрочитанное
		// Нет смысла вносить этот контакт в список пока он ничего не написал
		$this->_addEvent(self::ROOM_OPENED, $userID, $room['ChatRoom']['id'], $userID, $currUserID, self::INACTIVE);
		return $room;
	}

	/**
	 * Открывает или создает комнату для 2х юзеров, а также чат-контакт для текущего
	 *
	 * @param int $currUserID
	 * @param int $userID
	 * @return array - данные о комнате
	 */
	public function openRoom($currUserID, $userID, $groupID = null) {
		$this->loadModel('ChatRoom');
		$this->loadModel('ChatMember');
		$this->loadModel('ChatContact');

		/**
		 * Нужно проверять - открыта ли комната уже.
		 * Может быть ситуация, когда один юзер открывает комнату с другим, а тот уже ее открыл
		 * Тогда они будут писать друг другу в разные комнаты, что недопустимо
		 */
		if ($groupID) { // ищем по ID сообщества
			$this->loadModel('Group');
			$group = $this->Group->findById($groupID);
			$owner_id = $group['Group']['owner_id'];
			$room = $this->ChatRoom->findByGroupIdAndInitiatorId($groupID, array($currUserID, $userID));
		} else {
			$room = $this->ChatRoom->getRoomWith2Users($currUserID, $userID);
		}

		if (!$room) {
			// первичная инициализация комнаты чата
			if ($groupID) {
				// админ группы должен заходить в чат уже в готовую комнату!!!
				if ($currUserID == $owner_id) {
					throw new Exception('Group admin cannot create chat room with himself');
				}
                if(!empty($group)) {
                    $recipient = !is_null(Hash::get($group, 'Group.responsible_id')) ? Hash::get($group, 'Group.responsible_id') : $owner_id;
                    if($userID != $recipient)
                        $userID = $recipient;
                }
//				$userID = $owner_id;
			}
			$room = $this->createRoom($currUserID, $userID, $groupID);
		} else {
			// проверить есть ли такой контакт - возможно контакт был удален
			if (!$this->ChatContact->findByUserIdAndRoomId($currUserID, $room['ChatRoom']['id'])) {
				// найти событие, по которому комната была открыта для логирования
				$conditions = array('user_id' => $currUserID, 'active' => 0, 'room_id' => $room['ChatRoom']['id'], 'event_type' => self::ROOM_OPENED);
				$order = 'ChatEvent.created DESC';
				$event = $this->find('first', compact('conditions', 'order'));

				$msg = ''; // сообщение при открытии комнаты еще никакое не пришло. Можно выставлять skills, т.к. при поиске все равно они показываются
				$this->ChatContact->updateList($currUserID, $room['ChatRoom']['id'], $userID, $msg, $event['ChatEvent']['id'], $groupID);
				$this->ChatContact->setActiveCount($currUserID, $room['ChatRoom']['id'], 0);
			}
		}
		return $room;
	}

	protected function _getEvents($currUserID, $conditions, $limit = null) {
		$this->loadModel('ChatMessage');
		$this->loadModel('User');
		$this->loadModel('Media.Media');
        $this->loadModel('MediaFile');
		$this->loadModel('ChatMember');

		$conditions = array_merge(array('user_id' => $currUserID), $conditions);
		$order = array('room_id', 'created');
		$events = $this->find('all', compact('conditions', 'order', 'limit'));

		//backward compatibility for last messages from minichat
		$now = date('Y-m-d H:i:s');
		$last = date('Y-m-d H:i:s', time()-10);
		$conditions = array('OR' => array(
			'user_id' => $currUserID,
			'AND' => array(
				'created <=' => $now,
				'created >=' => $last,
			)
		));
		$order = array('room_id', 'created');
		$selfEvents = $this->find('all', compact('conditions', 'order', 'limit'));

		// Get info about sent messages
		$aID = Hash::extract($events, '{n}.ChatEvent.msg_id');
		$messages = $this->ChatMessage->findAllById($aID);
		$messages = Hash::combine($messages, '{n}.ChatMessage.id', '{n}.ChatMessage');

		// Get info about sent files
		$aID = Hash::extract($events, '{n}.ChatEvent.file_id');
		$files = $this->MediaFile->getList(array('id' => $aID), 'Media.id');
		$files = Hash::combine($files, '{n}.Media.id', '{n}.Media');

		// Get info about updated rooms (members joined)
		// $aRooms = Hash::combine($events, '{n}.ChatEvent.room_id')
		$rooms = array();
		foreach($events as $event) {
			if ($event['ChatEvent']['active'] && in_array($event['ChatEvent']['event_type'], array(self::INVITED_USER, self::WAS_INVITED, self::JOINED_ROOM))) {
				$rooms[] = $event['ChatEvent']['room_id'];
			}
		}
		$updateRooms = array();
		foreach($rooms as $roomID) {
			$members = $this->ChatMember->getRoomMembers($roomID);
			$members = array_combine($members, $members);
			unset($members[$currUserID]);
			$updateRooms[$roomID] = $this->User->getUsers($members);
		}
		return compact('events', 'selfEvents', 'messages', 'authors', 'files', 'updateRooms');
	}

	public function getActiveEvents($currUserID,$usersConditions = array()) {
		if($usersConditions){
			$conditions = array_merge(array('active' => 1), $usersConditions);
		}else{
			$conditions = array('active' => 1);
		}
		$now = date('Y-m-d H:i:s');
		$last = date('Y-m-d H:i:s', time()-10);
		$conditions = array('OR' => array(
			'active' => 1,
			'AND' => array(
				'created <=' => $now,
				'created >=' => $last,
			)
		));

		return $this->_getEvents($currUserID, $conditions);
	}

	public function getAllRoomEvents($currUserID, $room_id) {
		return $this->_getEvents($currUserID, compact('room_id'));
	}

	public function getInitialEvents($currUserID, $room_id) {
		$fields = array('COUNT(*) as count');
		$conditions = array('user_id' => $currUserID, 'room_id' => $room_id, 'active' => self::ACTIVE);
		$order = array('room_id', 'created');
        $this->recursive = -1;
		$events = $this->find('all', compact('conditions', 'order'));
		$count = Hash::get($events, '0.count');
		if ($count >= Configure::read('chat.initialEvents')) { // активных событий больше чем лимит
			// изначально вычитываем ВСЕ активные события
			$conditions = array('ChatEvent.room_id' => $room_id, 'ChatEvent.active' => self::ACTIVE);
			$limit = null;
		} else {
			// задаем такие условия, чтобы вычитать лимит последних событий
			$fields = array('ChatEvent.id', 'ChatEvent.created');
			$conditions = array('ChatEvent.user_id' => $currUserID, 'ChatEvent.room_id' => $room_id);
			$order = 'ChatEvent.id DESC';
			$limit = Configure::read('chat.initialEvents');
			$events = $this->find('all', compact('fields', 'conditions', 'order', 'limit'));
			$conditions = array('ChatEvent.room_id' => $room_id);
			if ($events) {
				$lastEvent = array_pop($events);
				$conditions['ChatEvent.id >= '] = $lastEvent['ChatEvent']['id'];
			}
		}

		return $this->_getEvents($currUserID, $conditions, $limit);
	}

	public function loadEvents($currUserID, $room_id, $firstEventID = 0) {
		$fields = array('ChatEvent.id', 'ChatEvent.created');
		$conditions = array('ChatEvent.user_id' => $currUserID, 'ChatEvent.room_id' => $room_id, 'ChatEvent.id < ' => $firstEventID);
		$order = 'ChatEvent.id DESC';
		$limit = Configure::read('chat.loadMore');
		$events = $this->find('all', compact('fields', 'conditions', 'order', 'limit'));
		if ($events) {
			$lastEvent = array_pop($events);
			$conditions['ChatEvent.id >= '] = $lastEvent['ChatEvent']['id'];
		}
		return $this->_getEvents($currUserID, $conditions, $limit);
	}

	public function updateInactive($userID, $ids) {
		$this->updateAll(array('active' => self::INACTIVE), array('id' => $ids));
		// var_dump($ids);
		// update contact list
		$this->loadModel('ChatContact');
		$fields = array('user_id', 'room_id', 'SUM(active) as active_count');
		$conditions = array('id' => $ids, 'user_id' => $userID);
		$order = 'ChatEvent.created DESC';
		$group = array('room_id');
		$aEvents = $this->find('all', compact('fields', 'conditions', 'order', 'group'));
		foreach($aEvents as $event) {
			$this->ChatContact->setActiveCount($userID, $event['ChatEvent']['room_id'], $event[0]['active_count']);
		}
	}
	/*
	public function getActiveRooms($userID) {
		$this->loadModel('ChatMessage', 'Media.Media');

		$fields = array('ChatEvent.event_type', 'ChatEvent.room_id', 'ChatEvent.created', 'ChatEvent.initiator_id', 'ChatEvent.msg_id', 'ChatEvent.file_id', 'ChatMessage.message', 'SUM(active) AS count');
		$conditions = array('ChatEvent.user_id' => $userID, 'ChatEvent.active' => 1);
		$joins = array(
			array('type' => 'left', 'table' => $this->ChatMessage->getTableName(), 'alias' => 'ChatMessage', 'conditions' => array('`ChatEvent`.msg_id = `ChatMessage`.id'))
		);
		$order = array('count DESC');
		$group = array('ChatEvent.room_id');
		return $this->find('all', compact('fields', 'conditions', 'joins', 'order', 'group'));
	}
	*/
	public function timelineEvents($currUserID, $date, $date2, $view = 0, $mail = false) {
		$conditions = array_merge(
			$this->dateRange('ChatEvent.created', $date, $date2),
			array(
				'ChatEvent.event_type' => array(self::INCOMING_MSG, self::FILE_DOWNLOAD_AVAIL),
				'ChatEvent.user_id' => $currUserID,
				'ChatEvent.active' => 1
			)
		);

		if($mail) $conditions = array_merge(
			$this->dateTimeRange('ChatEvent.created', $date, $date2),
			array(
				'ChatEvent.event_type' => array(self::INCOMING_MSG, self::FILE_DOWNLOAD_AVAIL),
				'ChatEvent.user_id' => $currUserID,
				'ChatEvent.active' => 1
			)
		);

		$this->loadModel('ChatMember');
		$rID = $this->ChatMember->findAllByUserIdAndIsDeleted($currUserID, '1');
		$rID = Hash::extract($rID, '{n}.ChatMember.room_id');
		$conditions['NOT'] = array('ChatEvent.room_id' => $rID);

		$order = 'ChatEvent.created DESC';
		$limit = 5;
//        $this->recursive = -1;
		return $this->find('all', compact('conditions', 'order', 'limit'));
	}

	public function removeContact($currUserID, $id) {
		$this->loadModel('ChatContact');
		$contact = $this->ChatContact->findById($id);
		if ($contact) {
			// помечаем все НЕпрочитанные сообщения как прочитанные,
			// иначе будет глюк с общей статистикой по событиям (вылазит общее кол-во на иконке чата)
			$fields = array('active' => 0);
			$conditions = array('user_id' => $currUserID, 'room_id' => $contact['ChatContact']['room_id']);
			$this->updateAll($fields, $conditions);
			$this->ChatContact->delete($id);
		}
	}

	public function addMember($currUserID, $room_id, $user_id) {
		$this->loadModel('ChatMember');
		$this->loadModel('ChatContact');
		$this->loadModel('User');

		$alreadyMember = $this->ChatMember->findByRoomIdAndUserId($room_id, $user_id);
		if (!$alreadyMember) {
			$this->ChatMember->save(compact('room_id', 'user_id'));
		} else {
			$this->ChatMember->updateAll(array('is_deleted' => 0), compact('room_id', 'user_id'));
		}

		$user = $this->User->getUser($user_id);
		$aUsersID = $this->_getRoomUsersID($room_id);
		foreach($aUsersID as $userID) {
			if ($currUserID == $userID) {
				$eventID = $this->_addEvent(self::INVITED_USER, $userID, $room_id, $user_id, $currUserID, self::INACTIVE);
				$this->ChatContact->updateList($currUserID, $room_id, $user_id, __('You invited user "%s" in this room', $user['User']['full_name']), $eventID);
				$this->ChatContact->setActiveCount($currUserID, $room_id, 0);
			} elseif ($userID == $user_id) {
				$eventID = $this->_addEvent(self::WAS_INVITED, $userID, $room_id, $user_id, $currUserID, self::ACTIVE);
				$this->ChatContact->updateList($userID, $room_id, $currUserID, __('You was invited into this room', $user['User']['full_name']), $eventID);
			} else {
				$eventID = $this->_addEvent(self::JOINED_ROOM, $userID, $room_id, $user_id, $currUserID, self::ACTIVE);
				$this->ChatContact->updateList($userID, $room_id, $currUserID, __('User "%s" joined this room', $user['User']['full_name']), $eventID);
			}
		}
	}

	public function removeMember($currUserID, $room_id, $user_id) {
		$this->loadModel('ChatMember');
		$this->loadModel('ChatContact');
		$this->loadModel('User');

		$user = $this->User->getUser($user_id);
		$aUsersID = $this->_getRoomUsersID($room_id);
		foreach($aUsersID as $userID) {
			if ($currUserID == $userID) {
				$eventID = $this->_addEvent(self::EXCLUDED_USER, $userID, $room_id, $user_id, $currUserID, self::INACTIVE);
				$this->ChatContact->updateList($currUserID, $room_id, $user_id, __('You invited user "%s" in this room', $user['User']['full_name']), $eventID);
				$this->ChatContact->setActiveCount($currUserID, $room_id, 0);
			} elseif ($userID == $user_id) {
				$eventID = $this->_addEvent(self::WAS_EXCLUDED, $userID, $room_id, $user_id, $currUserID, self::ACTIVE);
				$this->ChatContact->updateList($userID, $room_id, $currUserID, __('You was invited into this room', $user['User']['full_name']), $eventID);
			} else {
				$eventID = $this->_addEvent(self::LEFT_ROOM, $userID, $room_id, $user_id, $currUserID, self::ACTIVE);
				$this->ChatContact->updateList($userID, $room_id, $currUserID, __('User "%s" joined this room', $user['User']['full_name']), $eventID);
			}
		}

		$this->ChatMember->removeMember($room_id, $user_id);
	}
}
