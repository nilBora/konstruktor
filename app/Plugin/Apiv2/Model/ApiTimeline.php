<?php

/**
* файл модели ApiTimeline
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
App::uses('Group', 'Model');
App::uses('GroupCategory', 'Model');
App::uses('Article', 'Model');
App::uses('Media', 'Media.Model');

/**
* Модель ApiLine.
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiTimeline extends AppModel {

	public $useTable = false;
	
	public $validate = array(
		'start_date' => array(
			'rule' => array('date', 'ymd'),
			'message' => 'Enter a valid date',
			'allowEmpty' => false
		),
		'end_date' => array(
			'rule' => array('date', 'ymd'),
			'message' => 'Enter a valid date',
			'allowEmpty' => false
		),
	);
	
	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('Media.Media');
	}
	

	/**
	* Выбирает таймлайн массив для пользователя
	*  
	* @param int $userId
	* @param string $startDate
	* @param string $endDate 
	* @return array
	*/
	public function getTimeline($userId,$startDate,$endDate){
			$result = $this->User->getTimeline($userId, date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)));
			return $this->formatTimeLine($result);
	}
	
	/**
	* Форматирует ответ для таймлайна
	* 
	* @param array $data 
	* @return array
	*/
	private function formatTimeLine($data){
		$aResult = array();
		foreach ($data['last_users'] as $id=>$user){
			$userId = $user['User']['id'];
			$aResult['LastUsers'][$id]['id'] = $userId;
			$aResult['LastUsers'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($user['User']['created']));
			$aResult['LastUsers'][$id]['full_name'] = $data['users'][$userId]['User']['full_name'];
			$aResult['LastUsers'][$id]['url_img'] = $data['users'][$userId]['UserMedia']['url_img'];
		}
		
		foreach ($data['last_groups'] as $id=>$group){
			$aResult['LastGroups'][$id]['id'] = $group['Group']['id'];
			$aResult['LastGroups'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($group['Group']['created']));
			$aResult['LastGroups'][$id]['title'] = $group['Group']['title'];
			$aResult['LastGroups'][$id]['url_img'] = $group['GroupMedia']['url_img'];
		}
		$this->loadModel('Api.ApiUserEvent');
		
		$this->loadModel('GroupCategory');
		$fields = array('GroupCategory.id','GroupCategory.name');
		$groupCategories = $this->GroupCategory->find('list',  compact('fields'));
		
		$i = 0;
		foreach ($data['events'] as $time=>$event){
			$gmtime = gmdate('Y-m-d\TH:i:s\Z', strtotime($time));
			$aResult['Events'][$i]['event_datetime'] = $gmtime;
			if(isset($event['Group']) and isset($event['GroupMember'])){
				$aResult['Events'][$i]['type'] = 1;
				$groupId = $event['Group']['id'];
				$aResult['Events'][$i]['Group']['id'] = $groupId;
				$aResult['Events'][$i]['Group']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['Group']['created']));
				$aResult['Events'][$i]['Group']['title'] = $event['Group']['title'];
				$aResult['Events'][$i]['Group']['owner_id'] = $event['Group']['owner_id'];
				$aResult['Events'][$i]['Group']['hidden'] = $event['Group']['hidden'];
				if($event['Group']['cat_id']){
					$aResult['Events'][$i]['category_name'] = $groupCategories[$event['Group']['cat_id']];
				}
				$aResult['Events'][$i]['Group']['video_url'] = $event['Group']['video_url'];
				if(isset($data['groups'][$groupId])){
					$aResult['Events'][$i]['Group']['url_img'] = $data['groups'][$groupId]['GroupMedia']['url_img'];
				}
			
				$aResult['Events'][$i]['GroupMember']['id'] = $event['GroupMember']['id'];
				$aResult['Events'][$i]['GroupMember']['user_id'] = $event['GroupMember']['user_id'];
				$aResult['Events'][$i]['GroupMember']['role'] = $event['GroupMember']['role'];
				$aResult['Events'][$i]['GroupMember']['approved'] = (bool)$event['GroupMember']['approved'];
				$aResult['Events'][$i]['GroupMember']['show_main'] = $event['GroupMember']['show_main'];
				$aResult['Events'][$i]['GroupMember']['is_invited'] = $event['GroupMember']['is_invited'];
				$aResult['Events'][$i]['GroupMember']['is_deleted'] = $event['GroupMember']['is_deleted'];
			}
			if(isset($event['Project']) and isset($event['ProjectMember'])){
				$aResult['Events'][$i]['type'] = 2;
				$aResult['Events'][$i]['Project']['id'] = $event['Project']['id'];
				$aResult['Events'][$i]['Project']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['Project']['created']));
				$aResult['Events'][$i]['Project']['title'] = $event['Project']['title'];
				$aResult['Events'][$i]['Project']['descr'] = $event['Project']['descr'];
				$aResult['Events'][$i]['Project']['group_id'] = $event['Project']['group_id'];
				$aResult['Events'][$i]['Project']['owner_id'] = $event['Project']['owner_id'];
				$aResult['Events'][$i]['Project']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['Project']['deadline']));
				$aResult['Events'][$i]['Project']['closed'] = (bool)$event['Project']['closed'];
			
				$aResult['Events'][$i]['ProjectMember']['id'] = $event['ProjectMember']['id'];
				$aResult['Events'][$i]['ProjectMember']['is_responsible'] = (bool)$event['ProjectMember']['is_responsible'];
			}
			if(isset($event['ProjectEvent'])){
				$aResult['Events'][$i]['type'] = 3;
				$projectId = $event['ProjectEvent']['project_id'];
				$aResult['Events'][$i]['ProjectEvent']['id'] = $event['ProjectEvent']['id'];
				$aResult['Events'][$i]['ProjectEvent']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['ProjectEvent']['created']));
				$aResult['Events'][$i]['ProjectEvent']['project_id'] = $event['ProjectEvent']['project_id'];
				$aResult['Events'][$i]['ProjectEvent']['project_title'] = $data['projects'][$projectId]['Project']['title'];
				$aResult['Events'][$i]['ProjectEvent']['user_id'] = $event['ProjectEvent']['user_id'];
				$aResult['Events'][$i]['ProjectEvent']['event_type'] = $event['ProjectEvent']['event_type'];
				if($event['ProjectEvent']['msg_id']){
					$msgId = $event['ProjectEvent']['msg_id'];
					$aResult['Events'][$i]['ProjectEvent']['message'] = $data['messages'][$msgId]['message'];
					
					//костыль под мультизагрузку
						$conditions = array('object_id'=>$msgId,'object_type'=>'TaskComment');
						$multifiles = $this->Media->find('all',  compact('conditions'));
						
						foreach($multifiles as $key=>$media){
							$aResult['Events'][$i]['ProjectEvent']['msg_files'][$key]['file_type'] = $media['Media']['media_type'];
							$aResult['Events'][$i]['ProjectEvent']['msg_files'][$key]['ext'] = $media['Media']['ext'];
							if(isset($media['Media']['url_img'])){
								$aResult['Events'][$i]['ProjectEvent']['msg_files'][$key]['image'] = $media['Media']['url_img'];
							}
							$aResult['Events'][$i]['ProjectEvent']['msg_files'][$key]['url_download'] = $media['Media']['url_download'];
						}
				}
				if($event['ProjectEvent']['file_id']){
					$fileId = $event['ProjectEvent']['file_id'];
					$aResult['Events'][$i]['ProjectEvent']['file']['media_type'] = $data['files'][$fileId]['media_type'];
					$aResult['Events'][$i]['ProjectEvent']['file']['ext'] = $data['files'][$fileId]['ext'];
					$aResult['Events'][$i]['ProjectEvent']['file']['image'] = $data['files'][$fileId]['image'];
					$aResult['Events'][$i]['ProjectEvent']['file']['url_download'] = $data['files'][$fileId]['url_download'];
				}
				if($event['ProjectEvent']['task_id']){
					$taskId = $event['ProjectEvent']['task_id'];
					$aResult['Events'][$i]['ProjectEvent']['task']['id'] = $data['tasks'][$taskId]['Task']['id'];
					$aResult['Events'][$i]['ProjectEvent']['task']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['tasks'][$taskId]['Task']['created']));
					$aResult['Events'][$i]['ProjectEvent']['task']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['tasks'][$taskId]['Task']['deadline']));
					$aResult['Events'][$i]['ProjectEvent']['task']['subproject_id'] = $data['tasks'][$taskId]['Task']['subproject_id'];
					$aResult['Events'][$i]['ProjectEvent']['task']['user_id'] = $data['tasks'][$taskId]['Task']['user_id'];
					$aResult['Events'][$i]['ProjectEvent']['task']['manager_id'] = $data['tasks'][$taskId]['Task']['manager_id'];
					$aResult['Events'][$i]['ProjectEvent']['task']['closed'] = $data['tasks'][$taskId]['Task']['closed'];
					$aResult['Events'][$i]['ProjectEvent']['task']['descr'] = $data['tasks'][$taskId]['Task']['descr'];
				}
				$aResult['Events'][$i]['ProjectEvent']['subproject_id'] = $event['ProjectEvent']['subproject_id'];
			}
			if(isset($event['UserEvent'])){
				$aResult['Events'][$i]['type'] = 4;
				$aResult['Events'][$i]['UserEvent']['id'] = $event['UserEvent']['id'];
				$aResult['Events'][$i]['UserEvent']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['UserEvent']['created']));
				$aResult['Events'][$i]['UserEvent']['event_time'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['UserEvent']['event_time']));
				$aResult['Events'][$i]['UserEvent']['event_end_time'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['UserEvent']['event_end_time']));
				$aResult['Events'][$i]['UserEvent']['user_event_type'] = array_search($event['UserEvent']['type'], ApiUserEvent::$types);
				
				$recipientId = $event['UserEvent']['recipient_id'];
				$aResult['Events'][$i]['UserEvent']['recipient_id'] = $recipientId;
				if(isset($data['users'][$recipientId]) and $recipientId){
					$aResult['Events'][$i]['UserEvent']['recipient_name'] = $data['users'][$recipientId]['User']['full_name'];
					$aResult['Events'][$i]['UserEvent']['recipient_img'] = $data['users'][$recipientId]['UserMedia']['url_img'];
				}
				$aResult['Events'][$i]['UserEvent']['task_id'] = $event['UserEvent']['task_id'];
				$aResult['Events'][$i]['UserEvent']['title'] = $event['UserEvent']['title'];
				$aResult['Events'][$i]['UserEvent']['descr'] = $event['UserEvent']['descr'];
			}
			if(isset($event['Article']) and $event['Article']['type']=='text'){
				$aResult['Events'][$i]['type'] = 5;
				$aResult['Events'][$i]['Article']['id'] = $event['Article']['id'];
				$aResult['Events'][$i]['Article']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['Article']['created']));
				$aResult['Events'][$i]['Article']['title'] = $event['Article']['title'];
				
				$ownerId = $event['Article']['owner_id'];
				$groupId = $event['Article']['group_id'];
				$aResult['Events'][$i]['Article']['owner_id'] = $ownerId;
				
				if(isset($data['users'][$ownerId])and !$groupId){
					$aResult['Events'][$i]['Article']['author_name'] = $data['users'][$ownerId]['User']['full_name'];
					$aResult['Events'][$i]['Article']['author_img'] = $data['users'][$ownerId]['UserMedia']['url_img'];
				}

				$aResult['Events'][$i]['Article']['group_id'] = $groupId;				
				if(isset($data['groups'][$groupId])){
					$aResult['Events'][$i]['Article']['author_name'] = $data['groups'][$groupId]['Group']['title'];
					$aResult['Events'][$i]['Article']['author_img'] = $data['groups'][$groupId]['GroupMedia']['url_img'];
				}
				
				$aResult['Events'][$i]['Article']['cat_id'] = $event['Article']['cat_id'];
			}
			if(isset($event['ChatEvent'])){
				$aResult['Events'][$i]['type'] = 6;
				$aResult['Events'][$i]['ChatEvent']['id'] = $event['ChatEvent']['id'];
				$aResult['Events'][$i]['ChatEvent']['room_id'] = $event['ChatEvent']['room_id'];
				$aResult['Events'][$i]['ChatEvent']['event_type'] = $event['ChatEvent']['event_type'];
				
				$initiatorId = $event['ChatEvent']['initiator_id'];
				$aResult['Events'][$i]['ChatEvent']['initiator_id'] = $initiatorId;
				if(isset($data['users'][$initiatorId])){
					$aResult['Events'][$i]['ChatEvent']['initiator_name'] = $data['users'][$initiatorId]['User']['full_name'];
					$aResult['Events'][$i]['ChatEvent']['initiator_img'] = $data['users'][$initiatorId]['UserMedia']['url_img'];
				}
				
				$recipientId = $event['ChatEvent']['recipient_id'];
				$aResult['Events'][$i]['ChatEvent']['recipient_id'] = $recipientId;
				if(isset($data['users'][$recipientId])){
					$aResult['Events'][$i]['ChatEvent']['recipient_name'] = $data['users'][$recipientId]['User']['full_name'];
					$aResult['Events'][$i]['ChatEvent']['recipient_img'] = $data['users'][$recipientId]['UserMedia']['url_img'];
				}
				
				if($event['ChatEvent']['msg_id']){
					$msgId = $event['ChatEvent']['msg_id'];
					$aResult['Events'][$i]['ChatEvent']['message'] = $data['messages'][$msgId]['message'];
				}
				if($event['ChatEvent']['file_id']){
					$fileId = $event['ChatEvent']['file_id'];
					$aResult['Events'][$i]['ChatEvent']['file']['media_type'] = $data['files'][$fileId]['media_type'];
					$aResult['Events'][$i]['ChatEvent']['file']['ext'] = $data['files'][$fileId]['ext'];
					$aResult['Events'][$i]['ChatEvent']['file']['image'] = $data['files'][$fileId]['image'];
					$aResult['Events'][$i]['ChatEvent']['file']['url_download'] = $data['files'][$fileId]['url_download'];
				}
			}
			if(isset($event['SelfRegistration'])){
				$aResult['Events'][$i]['type'] = 0;
				$aResult['Events'][$i]['SelfRegistration']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['SelfRegistration']['created']));
			}
			$i++;
		}
		return $aResult;
	}
	
}
?>
