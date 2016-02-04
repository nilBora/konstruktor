<?php

/**
* файл модели ApiProject
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('Project', 'Model');
App::uses('Subproject', 'Model');
App::uses('ApiSubproject', 'Api.Model');
App::uses('ApiProjectMember', 'Api.Model');
App::uses('Task', 'Model');
App::uses('ProjectMember', 'Model');
App::uses('ProjectEvent', 'Model');
App::uses('Group', 'Model');
App::uses('GroupMember', 'Model');

/**
* Модель ApiProject. Обертка под модель Project
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiProject extends AppModel {

	public $useTable = 'projects';
	
	public $validate = array(
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		),
		'group_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'checkPswLen' => array(
				'rule' => 'numeric',
				'message' => 'Only digits'
			),
		),
		'responsible_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'checkPswLen' => array(
				'rule' => 'numeric',
				'message' => 'Only digits'
			),
		),
		'deadline' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
	);
	
	protected function _afterInit() {
		$this->loadModel('Project');
		$this->loadModel('ProjectMember');
		$this->loadModel('Subproject');
		$this->loadModel('Api.ApiSubproject');
		$this->loadModel('Api.ApiProjectMember');
		$this->loadModel('Task');
		$this->loadModel('ProjectEvent');
		$this->loadModel('User');
		$this->loadModel('Group');
		$this->loadModel('GroupMember');
	}
	
	/**
	* Доступ к проекту
	*  
	* @param int $userId
	* @param int $projectId   
	* @return bool
	*/
	public function checkAccessToProject($userId,$projectId){
		if(!$this->ApiProjectMember->isProjectMember($userId,$projectId)){
			return false;
		}
		
		$groupId = $this->getGroupByProjectId($projectId);
		if(!$groupId){
			return false;
		}
		
		$isDeleted = $this->GroupMember->field('is_deleted', array('user_id'=>$userId,'group_id'=>$groupId));
		if($isDeleted){
			return false;
		}
		return true;
	}
	
	/**
	* Является ли создателем проекта
	*  
	* @param int $userId
	* @param int $projectId   
	* @return bool
	*/
	public function isProjectOwner($userId,$projectId){
		$ownerId = $this->Project->field('owner_id',array('id'=>$projectId));
		if($ownerId!=$userId){
			return false;
		}
		return true;
	}
	
	/**
	* Определить группу по айди проекта
	*  
	* @param int $projectId   
	* @return int
	*/
	public function getGroupByProjectId($projectId){
		return $this->Project->field('group_id',array('id'=>$projectId));
	}
	
	/**
	* Информация о проекте
	*  
	* @param int $projectId   
	* @return array
	*/
	public function getInfo($projectId){
		$this->Project->bindModel(
					array('hasMany' => array(
						'ProjectMember' => array(
							'className' => 'ProjectMember',
							'foreignKey' => 'project_id',
							'fields' =>array('ProjectMember.user_id','ProjectMember.is_responsible'),
						),
					)
				)
		);
		$data = $this->Project->findById($projectId);

		if(!$data){
			return array();
		}
		
		$groupId = Hash::extract($data, 'Project.group_id');
		
		$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
		$this->User->bindModel(array('hasMany'=>array(
										'GroupMember'=>array(
											'className' => 'GroupMember',
											'foreignKey' => 'user_id',
											'fields' =>array('GroupMember.role'),
											'conditions' => array("GroupMember.group_id" => $groupId)
										)
									)
								)
				);
		$fields = array(
						'User.id',
						'User.full_name',
						'UserMedia.*',
		);
		
		
		$userIds = Hash::extract($data, 'ProjectMember.{n}.user_id');
		$users = $this->User->findAllById($userIds,$fields);
		$this->users = Hash::combine($users, '{n}.User.id','{n}');
		
		$fields = array('GroupMember.user_id');
		$conditions = array('GroupMember.group_id'=>$groupId,'is_deleted'=>0);
		$this->activeMembers = $this->GroupMember->find('list',compact('fields','conditions'));

		$conditions = array('ProjectEvent.project_id' => $projectId);
		$order = 'ProjectEvent.created DESC';
		$limit = 5;
		$this->events = $this->ProjectEvent->find('all', compact('conditions', 'order', 'limit'));
		
		$result = $this->formatProjectInfo($data);
		$subprojects = $this->ApiSubproject->getSubprojects($projectId);
		$result['Subproject'] = $subprojects['Subproject'];
		return $result;
	}
	
	/**
	* Форматирование инфо о проекте
	*  
	* @param array $data   
	* @return array
	*/
	public function formatProjectInfo($data){
		$aResult = array();
		
		$aResult['Project']['id'] = $data['Project']['id'];
		$aResult['Project']['title'] = $data['Project']['title'];
		$aResult['Project']['descr'] = $data['Project']['descr'];
		$aResult['Project']['group_id'] = $data['Project']['group_id'];
		$aResult['Project']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Project']['created']));
		$aResult['Project']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Project']['deadline']));
		$aResult['Project']['closed'] = $data['Project']['closed'];
		
		$aResult['ProjectMember'] = array();
		$i=0;
		foreach ($data['ProjectMember'] as $id=>$member){
			$userId = $member['user_id'];
			if(!in_array($userId, $this->activeMembers)){
				continue;
			}
			$aResult['ProjectMember'][$i]['user_id'] = $member['user_id'];
			$aResult['ProjectMember'][$i]['is_responsible'] = $member['is_responsible'];
			if(isset($this->users[$userId]['User']['full_name'])){
				$aResult['ProjectMember'][$i]['full_name'] = $this->users[$userId]['User']['full_name'];
			}
			if(isset($this->users[$userId]['UserMedia']['url_img'])){
				$aResult['ProjectMember'][$i]['url_img'] = $this->users[$userId]['UserMedia']['url_img'];
			}
			if(isset($this->users[$userId]['GroupMember'][0]['role'])){
				$aResult['ProjectMember'][$i]['role'] = $this->users[$userId]['GroupMember'][0]['role'];
			}
			$i++;
		}
		
		$aResult['Events'] = array();
		foreach ($this->events as $id=>$event){
				$aResult['Events'][$id]['id'] = $event['ProjectEvent']['id'];
				$aResult['Events'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ProjectEvent']['created']));
				$aResult['Events'][$id]['user_id'] = $event['ProjectEvent']['user_id'];
				$userId = $event['ProjectEvent']['user_id'];
				if(isset($this->users[$userId]['User']['full_name'])){
					$aResult['Events'][$id]['full_name'] = $this->users[$userId]['User']['full_name'];
				}
				if(isset($this->users[$userId]['UserMedia']['url_img'])){
					$aResult['Events'][$id]['user_url_img'] = $this->users[$userId]['UserMedia']['url_img'];
				}
				$aResult['Events'][$id]['task_id'] = $event['ProjectEvent']['task_id'];
				$aResult['Events'][$id]['subproject_id'] = $event['ProjectEvent']['subproject_id'];
				$aResult['Events'][$id]['event_type'] = $event['ProjectEvent']['event_type'];
		}
		
		return $aResult;
	}
	
	/**
	* Создание проекта 
	*  
	* @param array $data 
	* @return int
	*/
	public function createProject($data){
		$projectData['group_id'] = $data['group_id'];
		$projectData['owner_id'] = $data['owner_id'];
		$projectData['hidden_id'] = $data['hidden'];
		$projectData['title'] = $data['title'];
		$projectData['deadline'] = $data['deadline'];
		$projectData['descr'] = $data['descr'];
		
		if(!$this->Project->save($projectData)){
			throw new Exception('Server Error');
		}
		
		$projectId = $this->Project->id;
		
		$this->ProjectEvent->addEvent(ProjectEvent::PROJECT_CREATED, $projectId, $data['owner_id']);				
		$this->ProjectMember->save(array('project_id' => $projectId, 'user_id' => $data['owner_id'], 'sort_order' => '0'));
		
		$this->ProjectMember->clear();
		if($data['responsible_id'] == $data['owner_id']){
			$this->ProjectMember->updateAll(array('is_responsible' => 1),array('user_id' => $data['owner_id'],'project_id' => $projectId));
		}else{
			$this->ProjectMember->save(array(
					'project_id' => $projectId, 
					'user_id' => $data['responsible_id'], 
					'is_responsible' => 1,
					'sort_order' => 0
				));
		}
		return $projectId;
	}
	
	/**
	* Обновление проекта 
	*  
	* @param array $data 
	*/
	public function updateProject($data){
		
		if(!$this->Project->save($data)){
			throw new Exception('Server Error');
		}
		
		$member = $this->ProjectMember->findByProjectIdAndIsResponsible($this->Project->id,'1');
		if($member) {
				$mID = Hash::get($member, 'ProjectMember.id');
				$this->ProjectMember->save(array( 'id' => $mID, 'is_responsible' => 0, 'sort_order' => 1));
		}
		
		$member = $this->ProjectMember->findByProjectIdAndUserId($this->Project->id, $data['responsible_id']);
		$this->ProjectMember->clear();
		if($member) {
			$this->ProjectMember->save(array(
					'id' => $member['ProjectMember']['id'],
					'is_responsible' => 1,
					'sort_order' => 0
			));
		} else {
			$this->ProjectMember->save(array(
					'project_id' => $data['id'], 
					'user_id' => $data['responsible_id'], 
					'is_responsible' => 1,
					'sort_order' => 0
			));
		}
	}
}
?>
