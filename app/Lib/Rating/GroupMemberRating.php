<?php
App::uses('AbstractRating', 'Lib/Rating');

class GroupMemberRating extends AbstractRating {
	protected $_config = array(
		'inviteTo' => array(
			'label' => 'Invite user to group by admin',
			'target' => array(
				'Group' => 'group_id',
				'User' => 'owner_id'
			),
			'value' => 3
		),
		'requestTo' => array(
			'label' => 'User request to group approval',
			'target' => array(
				'Group' => 'group_id',
				'User' => 'user_id'
			),
			'value' => 1
		),
	);

	public function inviteTo($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		if($result&&($data[$this->context]['approved'] == true)){
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	public function requestTo($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		if($result&&($data[$this->context]['approved'] == true)){
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	public function owner_id($data){
		$groupModel = ClassRegistry::init('Group');
		$group = $groupModel->find('first', array(
			'conditions' => array('Group.id' => $data[$this->context]['group_id']),
		));
		$data[$this->context]['owner_id'] = null;
		if(!empty($group)&&isset($group['Group']['owner_id'])){
			$data[$this->context]['owner_id'] = $group['Group']['owner_id'];
		}
		return $data;
	}
}
