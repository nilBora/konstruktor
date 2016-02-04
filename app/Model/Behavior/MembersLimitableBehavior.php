<?php

App::uses('AppModel', 'Model');

class MembersLimitableBehavior extends ModelBehavior
{

	public function countUsedMembers(Model $model, $owner_id){
		App::uses('GroupLimit', 'Model');
		App::uses('Group', 'Model');
		$groupModel = new Group();
        $groupLimitModel = new GroupLimit();
        $memberCounts = $groupModel->find('list', array(
            'fields' => array('Group.id', 'Group.active_members'),
            'conditions' => array(
                'Group.owner_id' => $owner_id
            )
        ));
        $totalPaidMembers = 0;
        foreach($memberCounts as $count){
            if($count - 6 > 0){
                $totalPaidMembers += $count - 6;
            }
        }
        $limit = $groupLimitModel->findByOwnerId($owner_id);
        if(!empty($limit)){
            $groupLimitModel->id = $limit['GroupLimit']['id'];
            $groupLimitModel->saveField('members_used', $totalPaidMembers, array(
                'validate' => false,
                'callbacks' => false,
                'counterCache' => false,
            ));
        }
		return $totalPaidMembers;
	}
}
