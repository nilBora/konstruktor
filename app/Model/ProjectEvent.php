<?
App::uses('AppModel', 'Model');
class ProjectEvent extends AppModel {
	const PROJECT_CREATED = 1;
	const SUBPROJECT_CREATED = 2;
	const TASK_CREATED = 3;
	const PROJECT_CLOSED = 4;
	const SUBPROJECT_CLOSED = 5;
	const TASK_CLOSED = 6;
	const TASK_COMMENT = 7;
	const FILE_ATTACHED = 8;

	const TASK_DELETED = 9;				// unused
	const SUBPROJECT_DELETED = 10;		// unused

	protected $ChatMessage, $Task, $Subproject, $Project, $GroupMember, $ProjectMember;

	public $actsAs = array('Ratingable');

	public function addEvent($event_type, $project_id, $user_id, $object_id = 0) {
		$data = compact('event_type', 'project_id', 'user_id');
		if ($event_type == self::SUBPROJECT_CREATED || $event_type == self::SUBPROJECT_CLOSED || $event_type == self::SUBPROJECT_DELETED) {
			$data['subproject_id'] = $object_id;
		} elseif ($event_type == self::TASK_CREATED || $event_type == self::TASK_CLOSED || $event_type == self::TASK_DELETED) {
			$data['task_id'] = $object_id;
		} elseif ($event_type == self::TASK_COMMENT || $event_type == self::FILE_ATTACHED) {
			$data = array_merge($data, $object_id);
		}

		$this->clear();
		if (!$this->save($data)) {
			throw new Exception("Chat event cannot be saved\n".print_r($data, true));
		}
	}

	public function addTaskComment($user_id, $message, $task_id, $project_id, array $media = array()) {
		$this->loadModel('ChatMessage');
		$this->loadModel('Media');
		$message = $message ? $message : '&nbsp;';
		if (!$this->ChatMessage->save(compact('message'))) {
			throw new Exception("Message cannot be saved\n".print_r(get_defined_vars(), true));
		}
		$msg_id = $this->ChatMessage->id;
		if (!empty($media)) {
			$this->Media->updateAll(
				array('Media.object_id' => $msg_id),
				array('Media.id' => $media)
			);
		}
		$this->addEvent(self::TASK_COMMENT, $project_id, $user_id, compact('task_id', 'msg_id'));
		return $this->id;
	}

	public function addTaskFile($user_id, $task_id, $file_id) {
		$this->loadModel('Task');
		$this->loadModel('Subproject');
		$this->loadModel('Project');

		$task = $this->Task->findById($task_id);
		$subproject = $this->Subproject->findById($task['Task']['subproject_id']);
		$project_id = $subproject['Subproject']['project_id'];
		$project = $this->Project->findById($project_id);
		$this->addEvent(self::FILE_ATTACHED, $project_id, $user_id, compact('task_id', 'file_id'));
	}

	public function timelineEvents($currUserID, $date, $date2, $view = 0, $mail = false, $search = '') {
		$this->loadModel('ProjectMember');
		$aProjectID = $this->ProjectMember->getUserProjects($currUserID);

		//Seems was wrong date interval
		//$conditions = $this->dateRange('ProjectEvent.created', $date, $date2);
		$conditions = $this->dateRange('ProjectEvent.created', $date2, $date);
		if((strtotime($date2) > strtotime($date))||($mail)) {
			$conditions = $this->dateTimeRange('ProjectEvent.created', $date, $date2);
		}
		$conditions['ProjectEvent.project_id'] = $aProjectID;
		$conditions['ProjectEvent.event_type'] = array(self::TASK_COMMENT, self::FILE_ATTACHED);

		$order = 'ProjectEvent.created DESC';
		$result =  $this->find('all', compact('conditions', 'order'));
		if($view == 0) {
			return $result;
		}
		return array();
	}
}
