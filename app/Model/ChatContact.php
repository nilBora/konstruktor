<?
App::uses('AppModel', 'Model');
class ChatContact extends AppModel {
/*
	public $belongsTo = array(
		'User' => array(
			'foreignKey' => 'initiator_id'
		)
	);
	при recursive=1 не выдает UserMedia, а при 2 пихает его как элемент на уровень ниже в [User] :(
*/
	protected $User, $ChatRoom;

	/**
	 * Добавить к счетчику входящих по юзеру и комнате. Создает или обновляет чат-контакт
	 *
	 * @param int $user_id
	 * @param int $room_id
	 * @param int $initiator_id - инициатор события
	 * @param str $msg
	 * @param int $chat_event_id - ID события для логирования
	 */

	public function updateList($user_id, $room_id, $initiator_id, $msg, $chat_event_id, $group_id = null) {
		$conditions = compact('user_id', 'room_id');
		$row = $this->find('first', compact('conditions'));
		$id = Hash::get($row, 'ChatContact.id');
		//when used afterSave callback in ChatEvents this row not need anymore
		//$active_count = intval(Hash::get($row, 'ChatContact.active_count')) + 1;

		/*
		$data = compact('id', 'user_id', 'room_id', 'msg', 'chat_event_id');
		if ($initiator_id) {
			$data['initiator_id'] = $initiator_id;
		}
		$this->save($data);
		*/
		$this->save(compact('id', 'user_id', 'room_id', 'initiator_id', 'msg', 'chat_event_id', 'group_id'));
	}

	/**
	 * Установить значение счетчика входящих
	 *
	 * @param int $user_id
	 * @param int $room_id
	 * @param int $active_count
	 */
	public function setActiveCount($user_id, $room_id, $active_count) {
		//$row = $this->findByUserIdAndRoomId($user_id, $room_id);
		//$id = Hash::get($row, 'ChatContact.id');
		//$this->save(compact('id', 'user_id', 'room_id', 'active_count'));
	}

	/**
	 * Получить список контактов юзера или поиск по контактам и юзерам
	 *
	 * @param int $user_id
	 * @param str $q
	 * @return array
	 */
	public function getList($user_id, $q = '') {
		$this->loadModel('User');
		$this->loadModel('ChatMember');
		$this->loadModel('Group');

		$conditions = array(
            'ChatContact.user_id' => $user_id,
            'ChatContact.initiator_id !=' => $user_id,
//            'ChatContact.group_id' => null
        );
		$order = array('ChatContact.modified DESC');
		$aContacts = $this->find('all', compact('conditions', 'order', 'recursive'));
		// добавлять в комнату других юзеров  может только иницитор открытия комнаты или его изначальный оппонент
		// $aID = Hash::extract($aContacts, '{n}.ChatContact.room_id');
		$aPrivateRooms = array();
		$aGroupID = array();
		$count = -1;
		foreach($aContacts as &$_row) {
			$count ++;
			$roomID = $_row['ChatContact']['room_id'];

			$mConditions = array('ChatMember.user_id' => $user_id, 'ChatMember.room_id' => $roomID, 'ChatMember.is_deleted' => '1');
			$isDeleted = $this->ChatMember->find('first', array('conditions' => $mConditions));
			if($isDeleted) {
				unset($aContacts[$count]);
				continue;
			}

			$members = $this->ChatMember->getRoomMembers($roomID);

			// исключаем текущего юзера]
			$members = array_combine($members, $members);
			unset($members[$user_id]);
			$members = array_values($members);

			$_row['ChatContact']['members'] = $members;

			if ( count($members) == 1 && Hash::get($_row, 'ChatContact.group_id') == null ) {
				$aPrivateRooms[$members[0]] = $_row;
			}
			if ($_row['ChatContact']['group_id']) {
				$aGroupID[] = $_row['ChatContact']['group_id'];
			}
		}
        $aGroups = Hash::combine($this->Group->findAllById($aGroupID), '{n}.Group.id', '{n}');
		$aID = Hash::extract($aContacts, '{n}.ChatContact.initiator_id');
		$aResult = array();
		if ($q) {
			$aUsers = $this->User->search($user_id, $q);
			// по обсуждению с Ярославом показываем чат-контакты только для приватных комнат
			// и только тех юзеров, кот. есть в списке найденных в порядке очередности поиска
			foreach($aUsers as $row) {
				$user_id = $row['User']['id'];
				if (isset($aPrivateRooms[$user_id])) {
					$row = array_merge($row, $aPrivateRooms[$user_id]);
				}
				$aResult[] = $row;
			}
		}else {
			// Просто показываем весь контакт лист в привязке к оппоненту, который последним писал в комнату
			$aUsers = $this->User->getUsers($aID);
			foreach($aContacts as $row) {

                $temp = $row;
                if(!is_null($row['ChatContact']['group_id'])) {
                    $temp['ChatContact']['group_url'] = Router::url(array('controller' => 'Chat', 'action' => 'group', $row['ChatContact']['group_id']));
                    $temp['ChatContact']['logo'] = $aGroups[$row['ChatContact']['group_id']]['GroupMedia']['url_img'];
                    $temp['ChatContact']['group_name'] = $aGroups[$row['ChatContact']['group_id']]['Group']['title'];
                    $temp['ChatContact']['responsible_id'] = $aGroups[$row['ChatContact']['group_id']]['Group']['responsible_id'];
                }
                $aResult[] = array_merge($temp, $aUsers[$row['ChatContact']['initiator_id']]);
			}
		}

		return array('aUsers' => $aResult, 'aGroups' => $aGroups);
	}

	//method for minichat contacts
	public function getMiniList($user_id, $q = '') {
		$this->loadModel('User');
		$this->loadModel('ChatMember');
		$this->loadModel('Group');

		$conditions = array(
			'ChatContact.user_id' => $user_id,
			'ChatContact.initiator_id !=' => $user_id,
			'ChatContact.group_id IS NULL',
		);
		$order = array(
			'ChatContact.active_count' => 'DESC',
		    'ChatContact.modified' => 'DESC',
		);
		$aContacts = $this->find('all', compact('conditions', 'order', 'recursive'));
		// добавлять в комнату других юзеров  может только иницитор открытия комнаты или его изначальный оппонент
		// $aID = Hash::extract($aContacts, '{n}.ChatContact.room_id');
		$aPrivateRooms = array();
		$aGroupID = array();
		$count = -1;
		foreach($aContacts as &$_row) {
			$count ++;
			$roomID = $_row['ChatContact']['room_id'];

			$mConditions = array('ChatMember.user_id' => $user_id, 'ChatMember.room_id' => $roomID, 'ChatMember.is_deleted' => '1');
			$isDeleted = $this->ChatMember->find('first', array('conditions' => $mConditions));
			if($isDeleted) {
				unset($aContacts[$count]);
				continue;
			}

			$members = $this->ChatMember->getRoomMembers($roomID);

			// исключаем текущего юзера]
			$members = array_combine($members, $members);
			unset($members[$user_id]);
			$members = array_values($members);

			$_row['ChatContact']['members'] = $members;

			if ( count($members) == 1 && Hash::get($_row, 'ChatContact.group_id') == null ) {
				$aPrivateRooms[$members[0]] = $_row;
			}
			if ($_row['ChatContact']['group_id']) {
				$aGroupID[] = $_row['ChatContact']['group_id'];
			}
		}
        $aGroups = Hash::combine($this->Group->findAllById($aGroupID), '{n}.Group.id', '{n}');
		$aID = Hash::extract($aContacts, '{n}.ChatContact.initiator_id');
		$aResult = array();
		if ($q) {
			$aUsers = $this->User->search($user_id, $q);
			// по обсуждению с Ярославом показываем чат-контакты только для приватных комнат
			// и только тех юзеров, кот. есть в списке найденных в порядке очередности поиска
			foreach($aUsers as $row) {
				$user_id = $row['User']['id'];
				if (isset($aPrivateRooms[$user_id])) {
					$row = array_merge($row, $aPrivateRooms[$user_id]);
				}
				$aResult[] = $row;
			}
		}else {
			// Просто показываем весь контакт лист в привязке к оппоненту, который последним писал в комнату
			$aUsers = $this->User->getUsers($aID);
			foreach($aContacts as $row) {

                $temp = $row;
                if(!is_null($row['ChatContact']['group_id'])) {
                    $temp['ChatContact']['group_url'] = Router::url(array('controller' => 'Chat', 'action' => 'group', $row['ChatContact']['group_id']));
                    $temp['ChatContact']['logo'] = $aGroups[$row['ChatContact']['group_id']]['GroupMedia']['url_img'];
                    $temp['ChatContact']['group_name'] = $aGroups[$row['ChatContact']['group_id']]['Group']['title'];
                    $temp['ChatContact']['responsible_id'] = $aGroups[$row['ChatContact']['group_id']]['Group']['responsible_id'];
                }
                $aResult[] = array_merge($temp, $aUsers[$row['ChatContact']['initiator_id']]);
			}
		}

		return array('aUsers' => $aResult, 'aGroups' => $aGroups);
	}

}
