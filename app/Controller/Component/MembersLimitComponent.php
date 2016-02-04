<?php
App::uses('File', 'Utility');

class MembersLimitComponent extends Component {
	public function initialize(Controller $controller) {
	    $this->controller = $controller;
	    parent::initialize($controller);
	}

	public function canAddMember($groupId = null){
		$response = false;
		if(!empty($groupId)){
			$this->GroupLimit = ClassRegistry::init('GroupLimit');
			$this->GroupLimit->recursive = -1;
			$limit = $this->GroupLimit->find('first', array(
				'conditions' => array('GroupLimit.owner_id' => $this->controller->currUserID)
			));
			if(empty($limit)){
				$limit = array('GroupLimit' => array(
					'owner_id' => $this->controller->currUserID,
					'members_used' => 0,
					'members_limit' => 0,
				));
				$groupLimitModel->save($limit);
				$limit['GroupLimit']['id'] = $this->GroupLimit->getLastInserId();
			}
			$this->Group = ClassRegistry::init('Group');
			$this->Group->recursive = -1;
			$groups = $this->Group->findAllByOwnerId($this->controller->currUserID);
			$currentGroup = null; $totalPaidMembers = 0;
			foreach($groups as $group){
				if($group['Group']['id'] ==$groupId){
					$currentGroup = $group;
				}
				if($group['Group']['active_members'] - 5 > 0){
					$totalPaidMembers += $group['Group']['active_members'] - 5;
				}
			}
			//Autocorrection used members count for group limits
			if($totalPaidMembers != $limit['GroupLimit']['members_used']){
				$this->GroupLimit->id = $limit['GroupLimit']['id'];
				$this->GroupLimit->saveField('members_used', $totalPaidMembers, array(
					'validate' => false,
					'callbacks' => false,
					'counterCache' => false,
				));
			}
			if($currentGroup['Group']['active_members'] <= 4){
				//if group contain less than 5 member it allow add 5 member
				$response = true;
			} else {
				//if group already contain 5 members we need to check paid members qty availabe
				if(!empty($limit)&&($totalPaidMembers < $limit['GroupLimit']['members_limit'])){
					$response = true;
				}
			}
		}
		return $response;
	}
}
