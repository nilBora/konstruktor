<?php
/**
* файл модели ApiGroupMember
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('GroupMember', 'Model');
App::uses('Group', 'Model');
App::uses('GroupCategory', 'Model');
App::uses('User', 'Model');
/**
* Модель ApiGroupMember. Обертка под модель GroupMember
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class ApiGroupMember extends AppModel {

	public $useTable = 'group_members';
	
	protected function _afterInit() {
		$this->loadModel('GroupMember');
		$this->loadModel('Group');
	}
	
	/**
	* Выдает список групп, 
	* в которых пользователь стостоит как участник  
	* или является админом
	*  
	* @param int $userId 
	* @return array
	*/
	public function getUserGroups($userId,$isDeleted=-1,$showHidden=1){
		
		return $this->GroupMember->getUserGroups($userId,$isDeleted,$showHidden);
	}
	
	/**
	* список групп, для просмотра в профиле пользователя 
	* 
	*  
	* @param int $userId 
	* @return array
	*/
	public function getUserGroupsList($userId){

		$data = $this->getUserGroups($userId,-1,0);

		$groupIds = Hash::extract($data, '{n}.Group.id');
		$this->membersCount = $this->groupMembersCount($groupIds);
		
		$this->loadModel('GroupCategory');
		$fields = array('GroupCategory.id','GroupCategory.name');
		$this->groupCategories = $this->GroupCategory->find('list',  compact('fields'));

		$data = Hash::map($data, '{n}', array($this, 'userGroupsCallback'));

		$results = Hash::extract($data, '{n}.Group');
		return $results;
	}
	
	function userGroupsCallback($data){
				$aResult['Group']['id'] = $data['Group']['id'];
				$aResult['Group']['title'] = $data['Group']['title'];
				if($data['Group']['cat_id']){
					$aResult['Group']['category_name'] = $this->groupCategories[$data['Group']['cat_id']];
				}
				$aResult['Group']['headline'] = $data['Group']['descr'];
				$aResult['Group']['url_img'] = $data['GroupMedia']['url_img'];
				
				//специально от бага, где в группе нет пользователей
				if(isset($data['GroupMember']['role'])){
					$aResult['Group']['role'] = $data['GroupMember']['role'];
				}
				if(isset($data['GroupMember']['show_main'])){
					$aResult['Group']['in_team'] = (bool)$data['GroupMember']['show_main'];
				}
				$aResult['Group']['members'] = 0;

				if(isset($this->membersCount[$data['Group']['id']])){
					$aResult['Group']['members'] = (int)$this->membersCount[$data['Group']['id']];
				}
				return $aResult;
	}

		/**
	* список пользователей в группе(активные) 
	* в функционале просмотра группы
	* 
	* @param int $userId
	* @param bool $onlyTeam true - только команда, false - все 
	* @return array
	*/
	public function getGroupMemberList($groupId, $onlyTeam = false){
		
		if($onlyTeam){
			$groupMembers = $this->GroupMember->getMainList($groupId);
		}else{
			$groupMembers = $this->GroupMember->getList($groupId,null,0);
		}
		$aID = Hash::extract($groupMembers, '{n}.GroupMember.user_id');
		
		if(!$groupMembers){
			return array();
		}
		
		$this->loadModel('User');
		$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
		$fields = array(
						'User.id',
						'User.full_name',
						'UserMedia.*'
		);
		$users = $this->User->findAllById($aID,$fields);
		$users = Hash::combine($users, '{n}.User.id','{n}');
		$aResult = array(); 
		foreach($groupMembers as $id=>$member){
			$userId = $member['GroupMember']['user_id'];
			$aResult[$id]['user_id'] = $member['GroupMember']['user_id'];
			$aResult[$id]['is_team'] = (bool)$member['GroupMember']['show_main'];
			$aResult[$id]['role'] = $member['GroupMember']['role'];
			if(isset($users[$userId])){
				$aResult[$id]['full_name'] = $users[$userId]['User']['full_name'];
				$aResult[$id]['url_img'] = $users[$userId]['UserMedia']['url_img'];
			}
		}
		return $aResult;
	}
	
	/**
	* Проверка на участие в группе пользователя(активный)
	* 
	* @param int $userId 
	* @param int $groupId 
	* @return array
	*/
	public function checkInGroup($userId,$groupId){
		$conditions = array(
							'GroupMember.group_id'=>$groupId, 
							'GroupMember.user_id'=>$userId,
							'GroupMember.is_deleted'=>0,
							'GroupMember.approved'=>1
		);
		return $this->GroupMember->field('GroupMember.id',$conditions);
	}
	
	/**
	* Число участников в группе
	* 
	* @param int $userId 
	* @param array $groupIds 
	* @return array
	*/
	public function groupMembersCount($groupIds){
		if(!$groupIds){
			return array();
		}
		
		$this->GroupMember->virtualFields['members_count'] = 'COUNT(GroupMember.id)';
		
		$fields = array('GroupMember.group_id','GroupMember.members_count');
		$group = array('GroupMember.group_id');
		$conditions = array('GroupMember.group_id'=>$groupIds,'GroupMember.is_deleted'=>0,'GroupMember.approved'=>1);
		
		return $this->GroupMember->find('list',compact('fields','conditions','group'));
	}
	
	/**
	* Сохранение строки в таблицу 
	*  
	* @param array $data 
	* @return bool
	*/
	public function saveRow($data){
		if(!$this->GroupMember->save($data)){
			throw new Exception('Server Error');
		}
		return true;
	}
	
	/**
	* Сохранение в таблицу 
	*  
	* @param array $data 
	* @return bool
	*/
	public function saveInfo($data){
		if(!$this->GroupMember->saveAll($data)){
			throw new Exception('Server Error');
		}
		return true;
	}
	
	/**
	* обновление записи 
	*  
	* @param array $data 
	* @return bool
	*/
	public function updateInfo($data,$params){
		if(!$this->GroupMember->updateAll($data,$params)){
			throw new Exception('Server Error');
		}
		return true;
	}
	
	/**
	* Сотоит ли пользователь в группе 
	*  
	* @param int $groupId
	* @param int $userId  
	* @return bool
	*/
	public function inGroup($groupId,$userId){
		$conditions = array('GroupMember.group_id'=>$groupId,'GroupMember.user_id'=>$userId);
		$result = $this->GroupMember->field('GroupMember.id',  $conditions);
		if(!$result){
			return false;
		}
		return true;
	}
	
	/**
	* Приглашения из групп
	*  
	* @param int $groupId
	* @param int $userId  
	* @return bool
	*/
	public function getInvites($userId){
		$conditions = array('GroupMember.is_invited' => '1');
		$aID = Hash::extract($this->GroupMember->findAllByUserIdAndIsInvited($userId, 1), '{n}.GroupMember.group_id');
		if(!$aID){
			return array();
		}
		$conditions = array('Group.id' => $aID);
		$fields = array('Group.id','Group.title', 'Group.rating', 'GroupMedia.*');
		$this->Group->unbindModel(
			array('hasMany' => array('GroupVideo','GroupAchievement','GroupAddress','GroupGallery'))
		);
		$invites = $this->Group->find('all', compact('fields','conditions'));
		$aResult = array();
		foreach ($invites as $id=>$item){
			$aResult['Group'][$id]['id'] = $item['Group']['id'];
			$aResult['Group'][$id]['title'] = $item['Group']['title'];
			$aResult['Group'][$id]['url_img'] = $item['GroupMedia']['url_img'];
			$aResult['Group'][$id]['rating'] = $item['Group']['rating'];
		}
		return $aResult;
	}
	
	/**
	* Ответить на приглашение
	*  
	* @param array $data
	* @return bool
	*/
	public function inviteAnswer($data){
		if($data['accept']==1){
			unset($data['accept']);		
			$data['is_invited'] = 0;
			$data['is_deleted'] = 0;
			$data['sort_order'] = 1;
			$data['approved'] = 1;
			$data['show_main'] = 1;
			$data['approve_date'] = date('Y-m-d H:i:s');
			$this->GroupMember->save($data);
		}else{
			$this->GroupMember->delete($data['id']);
		}
	}
}
?>
