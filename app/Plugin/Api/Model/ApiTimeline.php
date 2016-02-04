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
			$this->__userId = $userId;
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
			$aResult['recommended_users'][$id]['id'] = $userId;
			$aResult['recommended_users'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($user['User']['created']));
			$aResult['recommended_users'][$id]['full_name'] = $data['users'][$userId]['User']['full_name'];
			$aResult['recommended_users'][$id]['rating'] = $data['users'][$userId]['User']['rating'];
			$aResult['recommended_users'][$id]['url_img'] = $data['users'][$userId]['UserMedia']['url_img'];
		}
		
		foreach ($data['last_groups'] as $id=>$group){
			$aResult['recommended_groups'][$id]['id'] = $group['Group']['id'];
			$aResult['recommended_groups'][$id]['rating'] = $group['Group']['rating'];
			$aResult['recommended_groups'][$id]['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($group['Group']['created']));
			$aResult['recommended_groups'][$id]['title'] = $group['Group']['title'];
			$aResult['recommended_groups'][$id]['url_img'] = $group['GroupMedia']['url_img'];
		}

		if(isset($data['counters'])) {
			$aResult['konstruktor_stats']['acrticles_count'] = $data['counters']['articles'];
			$aResult['konstruktor_stats']['groups_count'] = $data['counters']['groups'];
			$aResult['konstruktor_stats']['projects_count'] = $data['counters']['projects'];
			$aResult['konstruktor_stats']['messages_count'] = $data['counters']['messages'];
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
				if(strtotime($event['Project']['deadline'])) {
					$aResult['Events'][$i]['Project']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($event['Project']['deadline']));
				}
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
					$aResult['Events'][$i]['ProjectEvent']['task']['title'] = $data['tasks'][$taskId]['Task']['title'];
					$aResult['Events'][$i]['ProjectEvent']['task']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['tasks'][$taskId]['Task']['created']));
					if(strtotime($data['tasks'][$taskId]['Task']['deadline'])) {
						$aResult['Events'][$i]['ProjectEvent']['task']['deadline'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['tasks'][$taskId]['Task']['deadline']));
					}
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
				$aResult['Events'][$i]['UserEvent']['user_event_type'] = (int)array_search($event['UserEvent']['type'], ApiUserEvent::$types);

				if(isset($event['UserEvent']['recipient_id']) && !empty($event['UserEvent']['recipient_id'])) {
					$recipientId = explode(',', $event['UserEvent']['recipient_id']);
					if(is_array($recipientId)) {
						$recipients = array();
						foreach($recipientId as $one) {
							if(isset($data['users'][$one]['User']['id'])) {
								$recipients[] = array('id' => $data['users'][$one]['User']['id'],
										'name' => $data['users'][$one]['User']['full_name'],
										'img'=>$data['users'][$one]['UserMedia']['url_img']);
							}
						}
						$aResult['Events'][$i]['UserEvent']['recipients'] = $recipients;

					}
				}

				if(isset( $event['UserEvent']['task_id'])) {
					$aResult['Events'][$i]['UserEvent']['task_id'] = $event['UserEvent']['task_id'];
				}
				$aResult['Events'][$i]['UserEvent']['title'] = $event['UserEvent']['title'];
				$aResult['Events'][$i]['UserEvent']['descr'] = $event['UserEvent']['descr'];

				if(isset($event['UserEvent']['place_name'])) {
					$aResult['Events'][$i]['UserEvent']['place'] = $event['UserEvent']['place_name'];
				}
				if(isset($event['UserEvent']['place_coords']) && !empty($event['UserEvent']['place_coords'])) {
					$coords = str_replace(')', '', $event['UserEvent']['place_coords']);
					$coords = str_replace('(', '', $coords);
					$coords = explode(',', trim($coords));
					$aResult['Events'][$i]['UserEvent']['latitude'] = (isset($coords[0]) ? $coords[0] : '');
					$aResult['Events'][$i]['UserEvent']['longitude'] = (isset($coords[1]) ? $coords[1] : '');
				}
				$aResult['Events'][$i]['UserEvent']['is_approved'] = (bool)$event['UserEvent']['accepted'][$this->__userId]['UserEventShare']['acceptance'];

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
				if($event['ArticleMedia']['url_img']) {
					$aResult['Events'][$i]['Article']['img'] = $event['ArticleMedia']['url_img'];
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
					$aResult['Events'][$i]['ChatEvent']['initiator_rating'] = $data['users'][$initiatorId]['User']['rating'];
				}
				$recipientId = $event['ChatEvent']['recipient_id'];
				$aResult['Events'][$i]['ChatEvent']['recipient_id'] = $recipientId;
				if(isset($data['users'][$recipientId])){
					$aResult['Events'][$i]['ChatEvent']['recipient_name'] = $data['users'][$recipientId]['User']['full_name'];
					$aResult['Events'][$i]['ChatEvent']['recipient_img'] = $data['users'][$recipientId]['UserMedia']['url_img'];
					$aResult['Events'][$i]['ChatEvent']['recipient_rating'] = $data['users'][$recipientId]['User']['rating'];
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

	/**
	 * Форматирует ответ для вложенного таймлайна
	 *
	 * @param array $data
	 * @return array
	 */
	private function formatInnerTimline($data, $typeFormat) {

		$aResult = array();
		$i = 0;

		foreach($data['Render_list'] as $time=>$event) {
			$gmtime = gmdate('Y-m-d\TH:i:s\Z', strtotime($time));
			$aResult['Events'][$i]['event_datetime'] = $gmtime;
			if(is_array($event)) {
				foreach($event as $one) {
					if($typeFormat == 'group') {
						if($one['type'] == 'project') {
							if($data['Projects'][$one['id']]['closed'] == 1) {
								$type = '9';
							} elseif(isset($one['event_type']) && $one['event_type'] == 1) {
								$type = '8';
							}else {
								$type = '10';
							}
							$aResult['Events'][$i]['type'] = $type;
							$aResult['Events'][$i]['project']['id'] = $data['Projects'][$one['id']]['id'];
							$aResult['Events'][$i]['project']['title'] = $data['Projects'][$one['id']]['title'];
							$aResult['Events'][$i]['project']['descr'] = $data['Projects'][$one['id']]['descr'];

						} elseif($one['type'] == 'member') {
							$aResult['Events'][$i]['type'] = '11';
							//$aResult['Events'][$i]['group_role'] = $one['role'];
							$aResult['Events'][$i]['user']['group_role'] = $data['Members'][$one['id']]['role'];
							$aResult['Events'][$i]['user']['id'] = $data['Members'][$one['id']]['id'];
							$aResult['Events'][$i]['user']['name'] = $data['Members'][$one['id']]['full_name'];
							$aResult['Events'][$i]['user']['img'] = $data['Members'][$one['id']]['img_url'];

						}
						
					}
					if($typeFormat == 'project') {
						if($one['type'] == 'task') {
							if(isset($one['event_type'])) {
								switch($one['event_type']) {
									case 3:
										$type = 12;
										break;
									case 6:
										$type = 14;
										break;
									default: $type = 13;
								}
							}
							
							$aResult['Events'][$i]['type'] = $type;
							//$aResult['Events'][$i]['task'] = $data['task-list'][$one['id']];

							$aResult['Events'][$i]['task']['id'] = $data['task-list'][$one['id']]['id_task'];
							$aResult['Events'][$i]['task']['title'] = $data['task-list'][$one['id']]['title'];
							$aResult['Events'][$i]['task']['project_title'] = $data['project']['Project']['title'];
							$aResult['Events'][$i]['task']['project_id'] = $data['task-list'][$one['id']]['project_id'];
							$aResult['Events'][$i]['task']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['task-list'][$one['id']]['created_task']));
							if(strtotime($data['task-list'][$one['id']]['deadline'])) {
								$aResult['Events'][$i]['task']['deadline'] =  gmdate('Y-m-d\TH:i:s\Z', strtotime($data['task-list'][$one['id']]['deadline']));
							}
							$aResult['Events'][$i]['task']['subproject_id'] = $data['task-list'][$one['id']]['subproject_id'];
							$aResult['Events'][$i]['task']['user_id'] = $data['task-list'][$one['id']]['user_id'];
							$aResult['Events'][$i]['task']['manager_id'] = $data['task-list'][$one['id']]['manager_id'];
							$aResult['Events'][$i]['task']['closed'] = $data['task-list'][$one['id']]['closed'];
							$aResult['Events'][$i]['task']['descr'] = $data['task-list'][$one['id']]['descr'];

						}
					}
					if($typeFormat == 'task'){
						$aResult['Events'][$i]['type'] = $one['type'];
						$aResult['Events'][$i]['task']['id'] = $one['id'];
						$aResult['Events'][$i]['task']['title'] =  $data['task']['Task']['title'];
						$aResult['Events'][$i]['task']['project_title'] = $data['project']['Project']['title'];
						$aResult['Events'][$i]['task']['project_id'] = $data['events'][$one['id']]['project_id'];
						$aResult['Events'][$i]['task']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['events'][$one['id']]['created']));
						if(strtotime($data['project']['Project']['deadline'])) {
							$aResult['Events'][$i]['task']['deadline'] =  gmdate('Y-m-d\TH:i:s\Z', strtotime($data['project']['Project']['deadline']));
						}
						$aResult['Events'][$i]['task']['subproject_id'] = $data['events'][$one['id']]['subproject_id'];
						$aResult['Events'][$i]['task']['user_id'] = $data['events'][$one['id']]['user_id'];
						$aResult['Events'][$i]['task']['closed'] = $data['project']['Project']['closed'];
						$aResult['Events'][$i]['task']['descr'] = $data['project']['Project']['descr'];
						$aResult['Events'][$i]['task']['msg_id'] = 	$data['events'][$one['id']]['msg_id'];
						if(isset($data['events'][$one['id']]['message']) && !empty($data['events'][$one['id']]['message'])) {
							$aResult['Events'][$i]['task']['message'] = $data['events'][$one['id']]['message'];
						}
						if(!empty($data['events'][$one['id']]['file_id'])) {
							$aResult['Events'][$i]['task']['file_id'] = $data['events'][$one['id']]['file_id'];
						}
						$aResult['Events'][$i]['task']['parent_id'] = $data['events'][$one['id']]['task_id'];
						if(isset($data['events'][$one['id']]['user_id'])) {

							$user_id = $data['events'][$one['id']]['user_id'];

							$aResult['Events'][$i]['task']['user']['group_role'] = $data['user_members'][$user_id]['GroupMember']['role'];
							$aResult['Events'][$i]['task']['user']['id'] = $data['users'][$user_id]['User']['id'];
							$aResult['Events'][$i]['task']['user']['name'] = $data['users'][$user_id]['User']['full_name'];
							$aResult['Events'][$i]['task']['user']['img'] = $data['users'][$user_id]['UserMedia']['url_img'];
						}


					}
				}
			}
			$i++;
		}

		return $aResult;
	}

	/**
	 * Выбирает вложенный таймлайн для группы
	 *
	 * @param int $userId
	 * @param int $id
	 * @return array
	 */
	public function groupDetails($userId, $id) {
		$this->loadModel('Group');
		$this->loadModel('GroupMember');
		$this->loadModel('Project');
		$this->loadModel('ProjectEvent');
		$this->loadModel('Subproject');
		$this->loadModel('Task');
		$this->loadModel('User');
		$this->loadModel('Article');

		$renderList = array();

		$this->Group->unbindModel(
				array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
		);
		$group = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));

		if($group['Group']['owner_id'] != $userId) {
			throw new ApiIncorrectRequestException();
		}

		//Создание группы
		//$renderList[$group['Group']['created']][] = array('date' => $group['Group']['created'], 'type' => 'group');

		// Получение членов группы
		$this->GroupMember->unbindModel(
				array('belongsTo' => array('Group'))
		);

		$members = $this->GroupMember->find('all', array(
				'conditions' => array(
						'GroupMember.group_id' => $group['Group']['id'],
						'GroupMember.is_deleted' => 0,
						'GroupMember.approved' => 1
				),
				'fields' => array('GroupMember.id', 'GroupMember.user_id', 'GroupMember.approve_date', 'GroupMember.role', 'GroupMember.group_id'),
				'order' => array('GroupMember.approve_date DESC')
		));

		$this->User->unbindModel(
				array(
						'hasOne' => array('GroupLimit', 'UniversityMedia'),
						'hasMany' => array('UserAchievement', 'BillingSubscriptions'),
				)
		);
		$users = $this->User->find('all', array(
				'conditions' => array(
						'User.id' => Hash::extract($members, '{n}.GroupMember.user_id')
				)
		));
		$users = Hash::combine($users, '{n}.User.id', '{n}');

		foreach($members as &$member) {
			$mid = $member['GroupMember']['user_id'];
			$member['GroupMember']['full_name'] = $users[$mid]['User']['full_name'];
			$member['GroupMember']['skills'] = $users[$mid]['User']['skills'];
			$member['GroupMember']['img_url'] = $users[$mid]['UserMedia']['url_img'];

			$renderList[$member['GroupMember']['approve_date']][] = array('date' => $member['GroupMember']['approve_date'], 
																			'type' => 'member', 
																			'id' => $mid, 
																			'role'=>$member['GroupMember']['role']);
			$member = $member['GroupMember'];
		}
		$members = Hash::combine($members, '{n}.user_id', '{n}');

		// Получение проектов
		$pst = $this->Group->getGroupComponentsID($id);

		$this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
		$projects = $this->Project->find('all', array(
				'conditions' => array('Project.id' => $pst['project']),
				'fields' => array('id', 'title', 'descr', 'closed', 'created')
		));

		foreach($projects as &$project) {
			$lastEvent = $this->ProjectEvent->find('first', array(
					'conditions' => array('ProjectEvent.project_id' => $project['Project']['id']),
					'order' => array('id' => 'DESC')
			));
			$renderList[$lastEvent['ProjectEvent']['created']][] = array('date' => $lastEvent['ProjectEvent']['created'],
																		'type' => 'project',
																		'id' => $project['Project']['id'],
																		'event_type'=> $lastEvent['ProjectEvent']['event_type']);
			$project = $project['Project'];
			$project['last_update'] = $lastEvent['ProjectEvent']['created'];
		}
		$projects = Hash::combine($projects, '{n}.id', '{n}');

		// Получение статей
		$articles = $this->Article->find('all', array(
				'conditions' => array('group_id' => $id, 'deleted' => 0),
			//'fields' => array('Article.id', 'Article.title', 'Article.published', 'Article.created', 'ArticleMedia.url_img')
		));

		foreach($articles as &$article) {
			//$articles['Artile']['img_url'] = $articles['Artile']['img_url']
			$article = array(
					'id' => Hash::get($article, 'Article.id'),
					'title' => Hash::get($article, 'Article.title'),
					'published' => Hash::get($article, 'Article.published'),
					'created' => Hash::get($article, 'Article.created'),
					'url_img' => Hash::get($article, 'ArticleMedia.url_img')
			);
			$renderList[$article['created']][] = array('date' => $article['created'], 'type' => 'article', 'id' => $article['id']);
		}
		$articles = Hash::combine($articles, '{n}.id', '{n}');

		krsort($renderList);
		$response = array(
				'Group' => $group,
				'Members' => $members,
				'Projects' => $projects,
				'Articles' => $articles,
				'Render_list' => $renderList
		);
		return $this->formatInnerTimline($response,'group');
	}

	/**
	 * Выбирает вложенный таймлайн для проекта
	 *
	 * @param int $userId
	 * @param int $id
	 * @return array
	 */
	public function projectDetails($userId, $id) {
		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('ProjectEvent');
		$this->loadModel('Task');

		$renderList = array();

		$taskLastEvents = $this->ProjectEvent->find('all', array(
				'conditions' => array(
						'ProjectEvent.project_id' => $id,
						'NOT' => array('ProjectEvent.task_id' => null)
				),
			//'group' => 'ProjectEvent.task_id',
				'order' => array('ProjectEvent.created ASC')
		));

		$aID = Hash::extract($taskLastEvents, '{n}.ProjectEvent.task_id');
		$aTasks = $this->Task->find('all', array('conditions' => array('Task.id' => $aID)));

		$aTasks = Hash::combine($aTasks, '{n}.Task.id', '{n}.Task');

		foreach($taskLastEvents as &$event){
			$event = $event['ProjectEvent'];
			$event['title'] = $aTasks[$event['task_id']]['title'];
			$event['deadline'] = $aTasks[$event['task_id']]['deadline'];
			$event['manager_id'] = $aTasks[$event['task_id']]['manager_id'];
			$event['user_id'] = $aTasks[$event['task_id']]['user_id'];
			$event['closed'] = $aTasks[$event['task_id']]['closed'];
			$event['descr'] = $aTasks[$event['task_id']]['descr'];
			$event['subproject_id'] = $aTasks[$event['task_id']]['subproject_id'];
			$event['id_task'] = $aTasks[$event['task_id']]['id'];
			$event['created_task'] = $aTasks[$event['task_id']]['created'];
		};
		$taskLastEvents = Hash::combine($taskLastEvents, '{n}.task_id', '{n}');
		$this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
		$response['project'] = $this->Project->findById($id);

		$this->Group->unbindModel(
				array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
		);
		$response['group'] = $this->Group->findById($response['project']['Project']['group_id']);
		$response['task-list'] = $taskLastEvents;

		if($response['group']['Group']['owner_id'] != $userId) {
			throw new ApiIncorrectRequestException();
		}

		foreach($response['task-list'] as $task){
			$renderList[$task['created']][] = array('date' => $task['created'],
													'type' => 'task',
													'id' => $task['task_id'],
													'event_type' => $task['event_type']);
		};

		krsort($renderList);
		$response['Render_list'] = $renderList;

		
		return $this->formatInnerTimline($response, 'project');
	}

	/**
	 * Выбирает вложенный таймлайн для задачи
	 *
	 * @param int $userId
	 * @param int $id
	 * @return array
	 */
	public function taskDetails($userId, $id) {
		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('Subproject');
		$this->loadModel('ProjectEvent');
		$this->loadModel('Task');
		$this->loadModel('Media');
		$this->loadModel('ChatMessage');
		$this->loadModel('User');
		$this->loadModel('GroupMember');

		//отключение связанных данных от моделей
		$this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
		$this->Group->unbindModel(
				array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
		);
		$this->User->unbindModel(
				array(
						'hasMany' => array('BillingSubscriptions', 'UserAchievement'),
						'hasOne' => array('GroupLimit', 'UniversityMedia')
				)
		);

		$renderList = array();

		$this->Task->unbindModel(array('hasOne' => array('CrmTask')));
		$task = $this->Task->findById($id);

		$project = $this->Subproject->findById($task['Task']['subproject_id']);
		$project = $this->Project->findById($project['Subproject']['project_id']);
		$group = $this->Group->findById($project['Project']['group_id']);

		if($group['Group']['owner_id'] != $userId) {
			throw new ApiIncorrectRequestException();
		}

		$aTaskEvents = $this->ProjectEvent->findAllByTaskId($task['Task']['id']);
		$aTaskEvents = Hash::combine($aTaskEvents, '{n}.ProjectEvent.id', '{n}.ProjectEvent');

		foreach($aTaskEvents as &$event) {
			if(!is_null($event['file_id'])) {
				$event['media'] = $this->Media->findById($event['file_id']);

				$renderList[$event['created']][] = array(
						'date' => $event['created'],
						'type' => 'file',
						'id' => $event['id'],
						'own' => $event['user_id'] == $userId
				);
			} else if(!is_null($event['msg_id'])) {
				$msg = $this->ChatMessage->findById($event['msg_id']);

				$conditions = array(
						'Media.object_type' => 'TaskComment',
						'Media.object_id' => $msg['ChatMessage']['id']
				);
				$aMedia = $this->Media->find('all', compact('conditions'));
				if($aMedia) {
					$tmp = array();
					foreach($aMedia as $media) {
						$tmp[] = $media['Media'];
					}
					$event['media'] = $tmp;
				}

				if($msg['ChatMessage']['message'] != "&nbsp;") {
					$event['message'] = $msg['ChatMessage'];
					$type = $aMedia ? "file_comment" : "16";

					$renderList[$event['created']][] = array(
							'date' => $event['created'],
							'type' => $type,
							'id' => $event['id'],
							'own' => $event['user_id'] == $userId
					);
				} else {
					$event['message'] = $msg['ChatMessage'];
					$renderList[$event['created']][] = array(
							'date' => $event['created'],
							'type' => 'file',
							'id' => $event['id'],
							'own' => $event['user_id'] == $userId
					);
				}
			} else if($event['event_type'] == 3) {
				$renderList[$event['created']][] = array(
						'date' => $event['created'],
						'type' => '15',
						'id' => $event['id'],
						'own' => $event['user_id'] == $userId
				);
			} else if($event['event_type'] == 6) {
				$renderList[$event['created']][] = array(
						'date' => $event['created'],
						'type' => '17',
						'id' => $event['id'],
						'own' => $event['user_id'] == $userId
				);
			}
		}

		$aUsers = Hash::extract($aTaskEvents, '{n}.user_id');
		$aMembers = $this->GroupMember->findAllByGroupId($group['Group']['id']);

		$aUsers = $this->User->findAllById($aUsers);
		$aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}');

		$aMembers = Hash::combine($aMembers, '{n}.GroupMember.user_id', '{n}');

		$response['user_members'] = $aMembers;
		$response['group'] = $group;
		$response['project'] = $project;
		$response['task'] = $task;
		$response['events'] = $aTaskEvents;
		$response['users'] = $aUsers;
		krsort($renderList);
		$response['Render_list'] = $renderList;

		return $this->formatInnerTimline($response, 'task');
	}

}
?>
