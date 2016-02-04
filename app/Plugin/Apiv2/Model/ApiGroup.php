<?php
/**
* файл модели ApiGroup
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('Group', 'Model');
App::uses('GroupAddress', 'Model');
App::uses('GroupAchievement', 'Model');
App::uses('GroupCategory', 'Model');
App::uses('GroupMember', 'Model');
App::uses('ApiGroupMember', 'Api.Model');
App::uses('ApiProjectMember', 'Api.Model');

/**
* Модель ApiGroup. Обертка под модель Group
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiGroup extends AppModel {

	const NOT_IN_GROUP = 0;
	const IS_ADMIN = 1;
	const IN_GROUP = 2;
	const INVITED = 3;
	const DELETED = 4;
	const JOIN_SENT = 5;
	
	public $useTable = 'groups';
	public $validate = array(
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
		'hidden' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'hiddenCheck' => array(
				'rule' => array('inList',array(0,1)),
				'message' => 'hidden option is empty'
			),
		),
		'cat_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'catCheck' => array(
				'rule' => 'inCategoryList',
				'message' => 'Incorrect Category'
			),
		),
	);
	
	protected function _afterInit() {
		$this->loadModel('Group');
		$this->loadModel('GroupMember');
		$this->loadModel('GroupCategory');
		$this->loadModel('Api.ApiGroupMember');
		$this->loadModel('Api.ApiProjectMember');
	}
	
	public function inCountryList($check){
		$catList = $this->GroupCategory->find('list', array('fields'=>array('GroupCategory.id')));
		return in_array($check['cat_id'], $catList);
	}
	
	/**
	* Возращает список групп, 
	* в которых пользователь является админом 
	*  
	* @param int $userId 
	* @return array
	*/
	public function getUserAdminGroups($userId){

		$this->Group->unbindModel(
			array('hasMany' => array('GroupAddress','GroupAchievement','GroupGallery','GroupVideo'))
		);
		return $this->formatGroupData($this->Group->findAllByOwnerId($userId));
	}

	/**
	* Форматирует ответ для списка групп 
	*  
	* @param array $data 
	* @return array
	*/
	private function formatGroupData($data) {

		if (!$data) {
			return array();
		}
		$fields = array('GroupCategory.id','GroupCategory.name');
		$groupCategories = $this->GroupCategory->find('list',  compact('fields'));
		
		foreach($data as $id=>$group){
			$groupData['Group'][$id]['id'] = $group['Group']['id'];
			$groupData['Group'][$id]['image'] = $group['GroupMedia']['url_img'];
			$groupData['Group'][$id]['title'] = $group['Group']['title'];
			$groupData['Group'][$id]['headline'] = $group['Group']['descr'];
			if($group['Group']['cat_id']){
				$groupData['Group'][$id]['category_name'] = $groupCategories[$group['Group']['cat_id']];
			}
			$groupData['Group'][$id]['hidden'] = $group['Group']['hidden'];
		}

		return $groupData;
	}
	
	/**
	* Информация о группе
	* 
	* @param int $groupId 
	* @return array
	*/
	public function getInfo($groupId,$userId){

		$this->Group->unbindModel(
			array('hasMany' => array('GroupVideo'))
		);
		$this->Group->bindModel(
				array('hasMany' => array(
						'Article' => array(
							'className' => 'Article',
							'foreignKey' => 'group_id',
							'conditions' => 'published = 1',
							'fields' =>array('Article.id','Article.title','Article.cat_id')
						),
						'Project' => array(
							'className' => 'Project',
							'foreignKey' => 'group_id',
							'conditions' => 'closed = 0',
							'fields' =>array('Project.id','Project.title','Project.owner_id','Project.descr as headline'),
						),
					),
				)				
			);
		$fields = array('Group.id','Group.title','Group.descr as headline','Group.owner_id','Group.hidden','Group.cat_id','Group.video_url','GroupMedia.*');
	
		$result = $this->Group->findById($groupId,$fields);
		if(!$result){
			return array();
		}
		
		//определяем категорию
		if($result['Group']['cat_id']){
			$result['Group']['category_name'] = $this->GroupCategory->field('name',array('id'=>$result['Group']['cat_id']));
		}
		unset($result['Group']['cat_id']);
		
		//определяем статус
		if($result['Group']['owner_id']==$userId){
			$this->isAdmin = true;
			$result['Group']['user_status'] = self::IS_ADMIN;
		}else{
			$this->isAdmin = false;
			$result['Group']['user_status'] = $this->getMemberStatus($userId,$groupId);
		}
		
		$result['Group']['user_role'] = $this->GroupMember->field('role',array('user_id'=>$userId,'group_id'=>$groupId));
		
		//команда пользователя
		$result['Team'] = $this->ApiGroupMember->getGroupMemberList($groupId,true);
		
		//определяем подписку
		$this->loadModel('Api.ApiSubscription');	
		$result['Group']['is_subscribed'] = (bool)$this->ApiSubscription->isSubscribed($userId, $groupId, $this->ApiSubscription->getGroupType());
		
		//в каких проектах состоит пользователь
		$projectIds = Hash::extract($result, 'Project.{n}.id');
		if($projectIds){
			$this->loadModel('ProjectMember');
			$this->usersProject = $this->ApiProjectMember->getUsersProjectList($userId,$projectIds);
		}
		return $this->formatGroupInfo($result);
	}
	
	
	private function getMemberStatus($userId,$groupId){
		$member = $this->GroupMember->findByUserIdAndGroupId($userId,$groupId);
		if(!$member){
				return self::NOT_IN_GROUP;
		}
		if($member['GroupMember']['approved']==0 and $member['GroupMember']['is_deleted']==0 and $member['GroupMember']['is_invited']==0){
			return self::JOIN_SENT;
		}
		if($member['GroupMember']['is_deleted']==1){
			return self::DELETED;
		} 
		if($member['GroupMember']['approved']==1){
			return self::IN_GROUP;
		}	
		if($member['GroupMember']['is_invited']==1){
			return self::INVITED;
		}		
	}

	/**
	* Форматирует ответ для группы 
	*  
	* @param array $data 
	* @return array
	*/
	private function formatGroupInfo($data){
		if(!$data){
			return array();
		}

		unset($data['Group']['owner_id']);
		
		$data['Group']['image'] = $data['GroupMedia']['url_img'];
		unset($data['GroupMedia']);
		
		if(isset($data['GroupGallery'])){
			foreach ($data['GroupGallery'] as &$gallery){
				$gallery = array('image'=>$gallery['url_img']);
			}
		}
		
		if(isset($data['GroupAchievement'])){
			foreach ($data['GroupAchievement'] as &$item){
				$item['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($item['created']));
			}
		}
		
		//фильтруем проекты по принадлежности к пользователю
		//если не админ - показываеются только проекты в которых состоит пользователь
		if($data['Project'] and !$this->isAdmin){
			if(!$this->usersProject){
				$data['Project'] = array();
			}else{
				foreach ($data['Project'] as $id=>$project){
					if(!in_array($project['id'],$this->usersProject)){
						unset($data['Project'][$id]);
					}
				}
			}
		}
		
		return $data;
	}
	
	/**
	* Сохранение в таблицу 
	*  
	* @param array $data 
	* @return int
	*/
	public function saveInfo($data){
		//при обновлении вычиляем те айдишники адресов и достижений, которые не указаны в запросе удаляются
		if(isset($data['Group']['id'])){
			$groupAddressIds = Hash::extract($data, 'GroupAddress.{n}.id');
			$groupAchIds = Hash::extract($data, 'GroupAchievement.{n}.id');
			
			$conditions = array('group_id'=>$data['Group']['id']);
			$fields = array('id');
			
			$this->loadModel('GroupAddress');
			$savedAddressIds = $this->GroupAddress->find('list',compact('conditions','fields'));
			$deleteAddrIds = array_diff($savedAddressIds,$groupAddressIds);
			$this->GroupAddress->delete($deleteAddrIds);
			
			$this->loadModel('GroupAchievement');
			$savedAchIds = $this->GroupAchievement->find('list',compact('conditions','fields'));		
			$deleteAchIds = array_diff($savedAchIds,$groupAchIds);
			$this->GroupAchievement->delete($deleteAchIds);
		}
		if(!$this->Group->saveAll($data)){
			throw new Exception('Server Error');
		}
		return $this->Group->id;
	}
	
	/**
	* Является ли пользователь админом 
	*  
	* @param int $groupId
	* @param int $userId  
	* @return bool
	*/
	public function isAdmin($groupId,$userId){
		$conditions = array('Group.id'=>$groupId,'Group.owner_id'=>$userId);
		$result = $this->Group->field('Group.id',  $conditions);
		if(!$result){
			return false;
		}
		return true;
	}
	
}
?>
