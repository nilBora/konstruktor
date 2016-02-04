<?php
App::uses('AppModel', 'Model');
App::uses('Group', 'Model');
App::uses('User', 'Model');
class GroupMember extends AppModel {

	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'counterCache' => array(
				'active_members' => array(
					'approved' => 1,
	            	'is_deleted' => 0
				)
			),
		),
	);

    public $actsAs = array('MembersLimitable', 'Ratingable');

	protected $User;

	public function afterSave($created, $options = array()){
        $ownerId = 0;
		if($created){
			$memberId = $this->getLastInsertId();
		} else {
			$memberId = $this->data[$this->alias]['id'];
		}
		$this->recursive = 0;
		$groupMember = $this->findById($memberId);
		if($groupMember){
			$this->countUsedMembers($groupMember['Group']['owner_id']);
        }
		return true;
    }

    public function getGroupMembersFormatted($id, $current_user) {
        $result = $this->find('list', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'UserJoin',
                    'type' => 'INNER',
                    'conditions' => array(
                        'UserJoin.id = GroupMember.user_id'
                    )
                )
            ),
            'conditions' => array(
                'GroupMember.group_id' => $id,
                'UserJoin.id !=' => $current_user
            ),
            'fields' => array('UserJoin.id', 'UserJoin.full_name'),
            'order' => 'UserJoin.full_name DESC'
        ));
        return $result;
    }

	function timelineEvents($currUserID, $date, $date2) {
	//	$date = '2014-11-30';
		$approve_data = $this->dateRange('GroupMember.approve_date', $date, $date2);
		$conditions = array_merge(
			array('GroupMember.user_id' => $currUserID, 'GroupMember.approved' => 1)
		);
		$order = array('GroupMember.approve_date', 'GroupMember.created');
		$data['joined'] = $this->find('all', compact('conditions', 'order'));
		$request_data = $this->dateRange('GroupMember.created', $date, $date2);
		$conditions = array_merge(
			array('Group.owner_id' => $currUserID, 'GroupMember.approved' => 0)
		);
		$order = array('GroupMember.created');
		$data['request'] = $this->find('all', compact('conditions', 'order'));
		//var_dump($data);exit();
		return $data;
	}

	public function getList($group_id, $show_main = null, $is_deleted = null, $is_approved = '1') {
		$conditions = array('GroupMember.group_id' => $group_id);
		if (!is_null($is_approved)) {
			$conditions['approved'] = $is_approved;
		}
		if (!is_null($show_main)) {
			$conditions['show_main'] = $show_main;
		}
		if (!is_null($is_deleted)) {
			$conditions['is_deleted'] = $is_deleted;
		}
		$order = array('GroupMember.sort_order', 'GroupMember.created');
		return $this->find('all', compact('conditions', 'order'));
	}

	public function getMainList($group_id) {
		/*
		$conditions = array('GroupMember.group_id' => $group_id, 'GroupMember.approved' => 1, 'show_main' => 1, 'is_deleted' => 0);
		$order = array('GroupMember.sort_order', 'GroupMember.created');
		return $this->find('all', compact('conditions', 'order'));
		*/
		return $this->getList($group_id, 1, 0);
	}
	public function getTimelineUserGroups($user_id, $is_deleted = -1, $showHidden = 1) {
		//$conditions = array('AND' => array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 0 ));
		//$member = $this->find('all', array( 'conditions' => $conditions ));
		if( $is_deleted == 0 ) {
			$conditions = array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 0);
		} else if( $is_deleted == 1 ) {
			$conditions = array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 1 );
		} else if( $is_deleted == -1 ) {
			$conditions = array( 'user_id' => $user_id );
		}

		$member = $this->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'fields' => array('group_id',)
		));
		$member = Hash::combine($member, '{n}.GroupMember.group_id', '{n}');

		$conditions = array('OR' => array(
			array('Group.owner_id' => $user_id),
			array('Group.id' => array_keys($member))
		));

		if( $showHidden == 0 ) {
			$conditions['hidden'] = 0;
		}

		$groups = $this->Group->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'fields' => array('id')
		));
		foreach($groups as &$group) {
			$group_id = $group['Group']['id'];
			if (isset($member[$group_id])) {
				$group['GroupMember'] = $member[$group_id]['GroupMember'];
			}
		}
		return Hash::combine($groups, '{n}.Group.id', '{n}');
	}
	public function getUserGroups($user_id, $is_deleted = -1, $showHidden = 1) {
		//$conditions = array('AND' => array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 0 ));
		//$member = $this->find('all', array( 'conditions' => $conditions ));
		if( $is_deleted == 0 ) {
			$conditions = array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 0);
		} else if( $is_deleted == 1 ) {
			$conditions = array( 'user_id' => $user_id, 'approved' => 1, 'is_deleted' => 1 );
		} else if( $is_deleted == -1 ) {
			$conditions = array( 'user_id' => $user_id );
		}

		$member = $this->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
		));
		$member = Hash::combine($member, '{n}.GroupMember.group_id', '{n}');

		$conditions = array('OR' => array(
			array('Group.owner_id' => $user_id),
			array('Group.id' => array_keys($member))
		));

		if( $showHidden == 0 ) {
			$conditions['hidden'] = 0;
		}

		$groups = $this->Group->find('all', compact('conditions'));
		foreach($groups as &$group) {
			$group_id = $group['Group']['id'];
			if (isset($member[$group_id])) {
				$group['GroupMember'] = $member[$group_id]['GroupMember'];
			}
		}
		return Hash::combine($groups, '{n}.Group.id', '{n}');
	}

}
