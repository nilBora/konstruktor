<?php

/**
* файл модели ApiSubroject
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
App::uses('Task', 'Model');
App::uses('ProjectMember', 'Model');
App::uses('ProjectEvent', 'Model');
App::uses('Group', 'Model');
App::uses('GroupMember', 'Model');
App::uses('ApiGroupMember', 'Api.Model');

/**
* Модель ApiSubroject. Обертка под модель Subproject
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiSubproject extends AppModel {

	public $useTable = 'subprojects';
	
	protected function _afterInit() {
		$this->loadModel('Project');
		$this->loadModel('ProjectMember');
		$this->loadModel('Subproject');
		$this->loadModel('Task');
		$this->loadModel('ProjectEvent');
		$this->loadModel('User');
		$this->loadModel('Group');
		$this->loadModel('GroupMember');
		$this->loadModel('Api.ApiGroupMember');
	}
	
	/**
	* Возвращает айди проекта по айди подпроекта
	*  
	* @param int $subprojectId   
	* @return int
	*/
	public function getProjectId($subprojectId){
		return $this->Subproject->field('project_id',array('id'=>$subprojectId));
	} 
	
	/**
	* Доступ к подпроекту
	*  
	* @param int $user 
	* @param int $projectId   
	* @return bool
	*/
	public function checkAccess($userId,$projectId){
		
		$group_id = $this->Project->field('group_id',array('id'=>$projectId));
		if(!$group_id){
			return false;
		}

		if(!$this->ApiGroupMember->checkInGroup($userId,$group_id)){
			return false;
		}
		
		$ownerId = $this->Project->field('owner_id',array('id'=>$projectId));
		if(!$ownerId){
			return false;
		}
		$fields = array('user_id');
		$conditions = array('is_responsible'=>1,'project_id'=>$projectId);
		$responsibleUsers = $this->ProjectMember->find('list',  compact('fields','conditions'));
		
		if(($userId != $ownerId)  && (!in_array($userId, $responsibleUsers)))
		{
			return false;
		}
		return true;
	}

	/**
	* Создать подпроект
	*  
	* @param array $data   
	* @return int
	*/
	public function createSubproject($data){
		$saveData['project_id'] = $data['project_id'];
		$saveData['title'] = $data['title'];
		if(!$this->Subproject->save($saveData)){
			throw new Exception('Subproject Save Error');
		}
		$this->ProjectEvent->addEvent(ProjectEvent::SUBPROJECT_CREATED, $data['project_id'], $data['user_id'], $this->Subproject->id);
		return $this->Subproject->id;
	}
	
	/**
	* Подпроекты по айди проекта
	*  
	* @param int $projectId   
	* @return array
	*/
	public function getSubprojects($projectId){
		$this->Subproject->bindModel(array('hasMany'=>array(
										'Task'=>array(
											'className' => 'Task',
											'foreignKey' => 'subproject_id',
										)
									)
								)
				);
		$subprojects = $this->Subproject->findAllByProjectId($projectId);
		if(!$subprojects){
			return array('Subproject'=>array());
		}
		$userIds = Hash::extract($subprojects,'{n}.Task.{n}.user_id');		
		$fields = array(
						'User.id',
						'User.full_name',
						'UserMedia.*',
		);
		
		$users = $this->User->findAllById($userIds,$fields);
		$this->users = Hash::combine($users, '{n}.User.id','{n}');
		
		return $this->formatSubprojects($subprojects);
	}
	
	/**
	* Форматирование инфо по подпроектам
	*  
	* @param array $data   
	* @return data
	*/
	public function formatSubprojects($data){
		$aResult = array();
		foreach($data as $id=>$item){
			$aResult['Subproject'][$id]['id'] = $item['Subproject']['id'];
			$aResult['Subproject'][$id]['title'] = $item['Subproject']['title'];
			foreach ($item['Task'] as $tid=>$task){
				$aResult['Subproject'][$id]['Task'][$tid]['id'] = $task['id'];
				$aResult['Subproject'][$id]['Task'][$tid]['title'] = $task['title'];
				$aResult['Subproject'][$id]['Task'][$tid]['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($task['deadline']));
				$aResult['Subproject'][$id]['Task'][$tid]['assignee_user_id'] = $task['user_id'];
				if(isset($this->users[$task['user_id']]['User']['full_name'])){		
					$aResult['Subproject'][$id]['Task'][$tid]['assignee_full_name'] = $this->users[$task['user_id']]['User']['full_name'];
				}
				if(isset($this->users[$task['user_id']]['UserMedia']['url_img'])){		
					$aResult['Subproject'][$id]['Task'][$tid]['assignee_url_img'] = $this->users[$task['user_id']]['UserMedia']['url_img'];
				}
				$aResult['Subproject'][$id]['Task'][$tid]['closed'] = (int)$task['closed'];
			}
		}
		return $aResult;
	}
}
?>
