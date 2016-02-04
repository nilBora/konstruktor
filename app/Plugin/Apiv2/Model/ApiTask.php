<?php


/**
* файл модели ApiTask
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
App::uses('ApiProjectMember', 'Api.Model');
App::uses('Task', 'Model');
App::uses('ProjectMember', 'Model');
App::uses('ProjectEvent', 'Model');
App::uses('ChatMessage', 'Model');
App::uses('Media', 'Media.Model');
App::uses('Group', 'Model');
App::uses('GroupMember', 'Model');

/**
* Модель ApiTask. Обертка под модель Task
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiTask extends AppModel {

	public $useTable = 'tasks';
	
	protected function _afterInit() {
		$this->loadModel('Project');
		$this->loadModel('ProjectMember');
		$this->loadModel('Subproject');
		$this->loadModel('Api.ApiProjectMember');
		$this->loadModel('Task');
		$this->loadModel('ProjectEvent');
		$this->loadModel('ChatMessage');
		$this->loadModel('Media.Media');
		$this->loadModel('User');
		$this->loadModel('Group');
		$this->loadModel('GroupMember');
	}
	
	/**
	* Найти айди проекта по айди задачи
	*  
	* @param int $taskId   
	* @return int
	*/
	public function getProjectIdByTaskId($taskId){
		$subprojectId = $this->Task->field('subproject_id',array('id'=>$taskId));
		if(!$subprojectId){
			return false;
		}
		return $this->Subproject->field('project_id',array('id'=>$subprojectId));
	}
	
	/**
	* Найти айди подпроекта по айди задачи
	*  
	* @param int $taskId   
	* @return int
	*/
	public function getSubprojectIdByTaskId($taskId){
		$subprojectId = $this->Task->field('subproject_id',array('id'=>$taskId));
		return $subprojectId;
	}
	
	public function createTask($userId,$data,$projectId){
		if(isset($data['id'])){
			unset($data['id']);
		}
		$data['deadline'] = date('Y-m-d H:i:s',  strtotime($data['deadline']));
		if(!$this->Task->save($data)){
			throw new Exception('Task Save Error');
		}
		$this->ProjectEvent->addEvent(ProjectEvent::TASK_CREATED, $projectId, $userId, $this->Task->id);
		return $this->Task->id;
	}

	/**
	* Инфо о задаче
	*  
	* @param int $taskId
	* @param int $projectId   
	* @return array
	*/
	public function getInfo($taskId,$projectId){
		$task = $this->Task->findById($taskId,array(
					'Task.id',
					'Task.created',
					'Task.title',
					'Task.subproject_id',
					'Task.descr',
					'Task.manager_id',
					'Task.user_id',
					'Task.closed',
					'Task.deadline'
			));
		
		if(!$task){
			return array();
		}
		
		$task['Task']['project_id'] = $projectId;
		$task['Task']['project_title'] = $this->Project->field('title',array('id'=>$projectId));
		
		$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
		$userInfo = $this->User->findById($task['Task']['user_id'],array('User.full_name','UserMedia.*'));
		$task['Task']['user_full_name'] = $userInfo['User']['full_name'];
		$task['Task']['user_url_img'] = $userInfo['UserMedia']['url_img'];
		
		$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
		$managerInfo = $this->User->findById($task['Task']['manager_id'],array('User.full_name','UserMedia.*'));
		$task['Task']['manager_full_name'] = $managerInfo['User']['full_name'];
		$task['Task']['manager_url_img'] = $managerInfo['UserMedia']['url_img'];
		
		$conditions = array('ProjectEvent.project_id' => $projectId, 'ProjectEvent.task_id' => $taskId,'ProjectEvent.event_type'=>array(ProjectEvent::FILE_ATTACHED,  ProjectEvent::TASK_COMMENT));
		$order = 'ProjectEvent.created DESC';
		$this->events = $this->ProjectEvent->find('all', compact('conditions', 'order'));
		
		if($this->events){
			$aMessages = array_filter(Hash::extract($this->events, '{n}.ProjectEvent.msg_id'));
			if($aMessages){
				$conditions = array('ChatMessage.id'=>$aMessages);
				$fields = array('ChatMessage.id','ChatMessage.message');
				$this->messages = $this->ChatMessage->find('list',compact('conditions','fields'));
			}

			$aFiles = array_filter(Hash::extract($this->events, '{n}.ProjectEvent.file_id'));
			if($aFiles){
				$this->files = $this->Media->getList(array('id' => $aFiles), 'Media.id');
				$this->files = Hash::combine($this->files, '{n}.Media.id', '{n}.Media');
			}
			$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
			$aUsers = Hash::extract($this->events, '{n}.ProjectEvent.user_id');
			if($aUsers){
				$fields = ('User.id,User.full_name,UserMedia.*');
				$this->users = $this->User->findAllById($aUsers,  compact('fields'));
				$this->users = Hash::combine($this->users, '{n}.User.id','{n}');
			}
		}
		
		return $this->formatTaskInfo($task);

	}
	
	/**
	* Форматирование инфо о задаче
	*  
	* @param array $data  
	* @return array
	*/
	private function formatTaskInfo($data){
		$aResult = array();
		
		$aResult = $data;
		$aResult['Task']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Task']['created']));
		$aResult['Task']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Task']['deadline']));
		
		$aResult['Comments'] = array();
		if($this->events){
			foreach ($this->events as $id=>$event){

				$user_id = $event['ProjectEvent']['user_id'];
				
				$aResult['Comments'][$id]['id'] = $event['ProjectEvent']['id'];
				$aResult['Comments'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ProjectEvent']['created']));
				$aResult['Comments'][$id]['user_id'] = $event['ProjectEvent']['user_id'];
				$aResult['Comments'][$id]['event_type'] = $event['ProjectEvent']['event_type'];
				
				$aResult['Comments'][$id]['full_name'] = '';
				if(isset($this->users[$user_id]['User']['id'])){
						$aResult['Comments'][$id]['full_name'] = $this->users[$user_id]['User']['full_name'];
				}
				$aResult['Comments'][$id]['user_url_img'] = '';
				if(isset($this->users[$user_id]['UserMedia']['url_img'])){
					$aResult['Comments'][$id]['user_url_img'] = $this->users[$user_id]['UserMedia']['url_img'];
				}
				
				if($event['ProjectEvent']['msg_id']){
					$msg_id = $event['ProjectEvent']['msg_id'];

					$aResult['Comments'][$id]['msg_id'] = $msg_id;
					$aResult['Comments'][$id]['message'] = '';
					if(isset($this->messages[$msg_id])){
						$aResult['Comments'][$id]['message'] = $this->messages[$msg_id];
						
						//костыль под мультизагрузку
						$conditions = array('object_id'=>$msg_id,'object_type'=>'TaskComment');
						$multifiles = $this->Media->find('all',  compact('conditions'));
						
						foreach($multifiles as $key=>$media){
							$aResult['Comments'][$id]['msg_files'][$key]['file_type'] = $media['Media']['media_type'];
							$aResult['Comments'][$id]['msg_files'][$key]['ext'] = $media['Media']['ext'];
							if(isset($media['Media']['url_img'])){
								$aResult['Comments'][$id]['msg_files'][$key]['image'] = $media['Media']['url_img'];
							}
							$aResult['Comments'][$id]['msg_files'][$key]['url_download'] = $media['Media']['url_download'];
						}						
					}
					
				}
				if($event['ProjectEvent']['file_id']){
					$file_id = $event['ProjectEvent']['file_id'];

					$aResult['Comments'][$id]['file_type'] = '';
					if($this->files[$file_id]['file']){
						$aResult['Comments'][$id]['file_type'] = $this->files[$file_id]['file'];
					}
					$aResult['Comments'][$id]['ext'] = '';
					if($this->files[$file_id]['ext']){
						$aResult['Comments'][$id]['ext'] = $this->files[$file_id]['ext'];
					}
					$aResult['Comments'][$id]['image'] = '';
					if($this->files[$file_id]['image']){
						$aResult['Comments'][$id]['image'] = $this->files[$file_id]['image'];
					}
					$aResult['Comments'][$id]['url_download'] = '';
					if($this->files[$file_id]['url_download']){
						$aResult['Comments'][$id]['url_download'] = $this->files[$file_id]['url_download'];
					}
				}
			}
		}
		return $aResult;
	}
	
	/**
	* Добавить комметарий к задаче
	*
	* @param int $userId   
	* @param int $taskId
	* @param string $message
	* @param int $projectId   
	* @return int
	*/
	public function addComment($userId,$taskId,$message,$projectId,array $mediaIds = array()){
		$eventId = $this->ProjectEvent->addTaskComment(
				$userId, 
				$message,
				$taskId, 
				$projectId,
				$mediaIds
			);
		if(!$eventId){
			return false;
		}
		
		$event = $this->ProjectEvent->findById($eventId);
		$aResult['Comment']['id'] = $event['ProjectEvent']['id'];
		$aResult['Comment']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ProjectEvent']['created']));
		$aResult['Comment']['user_id'] = $event['ProjectEvent']['user_id'];
		$aResult['Comment']['event_type'] = $event['ProjectEvent']['event_type'];
		
		$this->User->unbindModel(array('hasMany'=>array('UserAchievement')));
		$user = $this->User->findById($event['ProjectEvent']['user_id'],  array('User.id,User.full_name,UserMedia.*'));
		$aResult['Comment']['full_name'] = $user['User']['full_name'];
		$aResult['Comment']['user_url_img'] = $user['UserMedia']['url_img'];
		
		if($event['ProjectEvent']['msg_id']){
			$message = $this->ChatMessage->findById($event['ProjectEvent']['msg_id'],array('ChatMessage.id','ChatMessage.message'));
			$aResult['Comment']['message'] = $message['ChatMessage']['message'];
			$multifiles = $this->Media->findAllByObjectIdAndObjectType($event['ProjectEvent']['msg_id'],'TaskComment');
			if($multifiles){
				foreach ($multifiles as $id=>$content){
					$aResult['Comment']['msg_files'][$id]['media_type'] = $content['Media']['media_type'];
					$aResult['Comment']['msg_files'][$id]['ext'] = $content['Media']['ext'];
					if(isset($content['Media']['url_img'])){
						$aResult['Comment']['msg_files'][$id]['image'] = $content['Media']['url_img'];
					}
					$aResult['Comment']['msg_files'][$id]['url_download'] = $content['Media']['url_download'];
				}
			}
		}
		if($event['ProjectEvent']['file_id']){
				$file = $this->Media->findById($event['ProjectEvent']['file_id']);
				$aResult['Comment']['media_type'] = $file['Media']['media_type'];
				$aResult['Comment']['ext'] = $file['Media']['ext'];
				if(isset($file['Media']['url_img'])){
					$aResult['Comment']['image'] = $file['Media']['url_img'];
				}
				$aResult['Comment']['url_download'] = $file['Media']['url_download'];
		}
		return $aResult;
	}
	
	/**
	* Доступ к задаче
	*  
	* @param int $userId
	* @param int $taskId    
	* @return bool
	*/
	public function checkAccessToTask($userId,$taskId){
		$projectId = $this->getProjectIdByTaskId($taskId);
		
		if(!$this->ApiProjectMember->isProjectMember($userId,$projectId)){
			return false;
		}
		
		$conditions = array(
			'id'=>$taskId,
			'OR' => array(
				'user_id'=>$userId,
				'manager_id'=>$userId
			)
		);
		$result = $this->Task->field('id',$conditions);
		if(!$result){
			return false;
		}
		return true;
	}
	
	/**
	* Закрыть задачу
	* 
	* @param int $userId 
	* @param int $taskId   
	* @param int $subprojectId
	*/
	public function closeTask($userId,$taskId,$subprojectId){
		$this->Task->save(array('id' => $taskId, 'closed' => 1));
		$this->ProjectEvent->addEvent(ProjectEvent::TASK_CLOSED, $subprojectId, $userId, $taskId);
	}
	
	public function getMyTasks($userId,$query='') {
		
		$fields = array('Project.id');
		$conditions = array('Project.owner_id'=>$userId);
		$projectIds = $this->Project->find('list',compact('fields','conditions'));
		if(!$projectIds){
			return array();
		}

		$fields = array('Subproject.id');
		$conditions = array('Subproject.project_id'=>$projectIds);
		$subprojectIds = $this->Subproject->find('list',compact('fields','conditions'));
		if(!$subprojectIds){
			return array();
		}

		$fields = array('Task.id','Task.title');
		$conditions = array( 'subproject_id' => $subprojectIds,'closed'=>0);
		if($query) {
			$conditions['Task.title LIKE ?'] = '%'.$query.'%';
		}
		
		$data = $this->Task->find('all', compact('fields','conditions'));
		
		$result = array();
		foreach($data as $id=>$task){
			$result['Task'][$id]['id'] = $task['Task']['id'];
			$result['Task'][$id]['title'] = $task['Task']['title'];
		}
		
		return $result;
	}
	
	public function isMyTask($userId,$taskId){
		$taskList = $this->getMyTasks($userId);
		$taskList = Hash::extract($taskList, 'Task.{n}.id');
		if(!$taskList){
			return false;
		}
		
		if(in_array($taskId, $taskList)){
			return true;
		}
		return false;
	}
}
?>
