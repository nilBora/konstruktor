<?php
App::uses('AppModel', 'Model');
class ChatMember extends AppModel {

	public function getMembers($room_id, $is_deleted = null) {
		if (is_null($is_deleted)) {
			$aRows = $this->findAllByRoomId($room_id);
		} else {
			$aRows = $this->findAllByRoomIdAndIsDeleted($room_id, $is_deleted);
		}
		return Hash::extract($aRows, '{n}.ChatMember.user_id');
	}

	public function getRoomMembers($room_id) {
		return $this->getMembers($room_id, 0);
	}

	public function removeMember($room_id, $user_id) {
		$this->updateAll(array('is_deleted' => 1), compact('room_id', 'user_id'));
	}
}
