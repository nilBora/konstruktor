<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class ProjectAjaxController extends PAjaxController {
	public $name = 'ProjectAjax';

	public $uses = array('Project', 'ProjectEvent', 'ProjectFinance', 'Subproject', 'Task', 'CrmTask', 'GroupMember', 'User', 'Group', 'Media.Media', 'ChatMessage', 'ProjectMember');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	public function addComment(){
		$id = $this->request->data['task_id'];
		$this->loadModel('MediaFile');
		$task = $this->Task->findById($id);

		if( !$task || $task['Task']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		$subproject = $this->Subproject->findById($task['Task']['subproject_id']);

		if( !$subproject || $subproject['Subproject']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		$project_id = $subproject['Subproject']['project_id'];
		$project = $this->Project->findById($project_id);

		if( !$project || $project['Project']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		if ($this->request->is('put') || $this->request->is('post')) {
			$media = is_array($this->request->data('media')) ?  $this->request->data('media') : array();
			$result = $this->ProjectEvent->addTaskComment(
				$this->currUserID,
				$this->request->data('message'),
				$id,
				$project_id,
				$media
			);
			if( !$result ) {
				throw new Exception(__('Cant save comment'));
			}
			$this->setResponse('done');
		}
	}

	public function comments($id){
		$this->loadModel('MediaFile');
		$task = $this->Task->findById($id);
		if( !$task || $task['Task']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		$subproject = $this->Subproject->findById($task['Task']['subproject_id']);

		if( !$subproject || $subproject['Subproject']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		$project_id = $subproject['Subproject']['project_id'];
		$project = $this->Project->findById($project_id);

		if( !$project || $project['Project']['deleted'] == '1' ) {
			throw new NotFoundException();
		}

		$owner_id = Hash::get($project, 'Project.owner_id');

		if(!$task['CrmTask']['id']) {
			$crmData = array(
				'task_id' => Hash::get($task, 'Task.id'),
				'contractor_id' => null,
				'money' => 0,
				'currency' => 'USD',
			);
			$this->CrmTask->save($crmData);

			$this->Task->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
			$task = $this->Task->findById($id);
		}

		$title = __('Task').': '.$task['Task']['title'];
		$this->set(compact('title'));

		if( !$task['CrmTask']['account_id'] ) {
			$this->Task->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
		}
		$task = $this->Task->findById($id);

		$aUsers = $this->User->getUsers(array($task['Task']['manager_id'], $task['Task']['user_id']));
		$this->set('aUsers', Hash::combine($aUsers, '{n}.User.id', '{n}'));
		$group = $this->Group->findById($project['Project']['group_id']);

		$members = $this->ProjectMember->getList($project_id);
		$aID = Hash::extract($members, '{n}.ProjectMember.user_id');

		$aGroupMembers = $this->GroupMember->getList(Hash::get($project, 'Project.group_id'), null, 0);
		$aGID = Hash::extract($aGroupMembers, '{n}.GroupMember.user_id');

		$group = $this->Project->getProjectGroup($project['Project']['id']);
		$isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

		if( !in_array($this->currUserID, $aID) && !$isGroupAdmin ) {
			return $this->redirect(array('controller' => 'Group', 'action' => 'view', $project['Project']['group_id']));
		}

		$members = $this->GroupMember->getList($project['Project']['group_id'], null, null, null);
		$aID = Hash::extract($members, '{n}.GroupMember.user_id');
		$aUsers = $this->User->getUsers($aID);
		$this->set('aUsers', $aUsers);

		$conditions = array('ProjectEvent.project_id' => $project_id, 'ProjectEvent.task_id' => $id);
		$order = 'ProjectEvent.created DESC';
		$limit = 5;
		$aEvents = $this->ProjectEvent->find('all', compact('conditions', 'order', 'limit'));
		$aEvents = array_reverse($aEvents);

		$aID = Hash::extract($aEvents, '{n}.ProjectEvent.msg_id');
		$messages = $this->ChatMessage->findAllById($aID);
		$messages = Hash::combine($messages, '{n}.ChatMessage.id', '{n}.ChatMessage');

		// media for comments
		$commentsMedia = array();
		if (!empty($messages)) {
			$commentsMediaResult = $this->MediaFile->getList(array(
				'object_id' => array_keys($messages),
				'object_type' => "TaskComment",
			), 'Media.id');
			$commentsMediaResult = Hash::combine($commentsMediaResult, '{n}.Media.id', '{n}.Media');
			foreach ($commentsMediaResult as $mediaItem) {
				$commentsMedia[$mediaItem['object_id']][] = $mediaItem;
			}
		}
		$this->set('commentsMedia', $commentsMedia);

		$aID = Hash::extract($aEvents, '{n}.ProjectEvent.file_id');
		$files = $this->Media->getList(array('id' => $aID), 'Media.id');
		$files = Hash::combine($files, '{n}.Media.id', '{n}.Media');

		$this->set(compact('task', 'subproject', 'project', 'group', 'messages', 'files', 'members', 'aEvents'));
	}
}
