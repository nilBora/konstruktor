<?
App::uses('AppModel', 'Model');
App::uses('User', 'Model');
class ProjectMember extends AppModel {
	
	public $belongsTo = array('Project');
	// public $hasOne = array('User');
	
	protected $User;

	public function getList($project_id) {
		$conditions = array('ProjectMember.project_id' => $project_id);
		$order = array('ProjectMember.sort_order', 'ProjectMember.created');
		$aMembers = $this->find('all', compact('conditions', 'order'));
		// $aMembers = Hash::combine($aMembers, '{n}.GroupMember.user_id', '{n}');
		return $aMembers;
	}
	
	public function getUserProjects($user_id) {
		$conditions = array('user_id' => $user_id);
		$aRows = $this->find('all', compact('conditions'));
		return Hash::extract($aRows, '{n}.ProjectMember.project_id');
	}
	
	public function timelineEvents($currUserID, $date, $date2) {
		$conditions = $this->dateRange('ProjectMember.created', $date, $date2);
		$conditions['ProjectMember.user_id'] = $currUserID;
		$order = 'ProjectMember.created DESC';
		return $this->find('all', compact('conditions', 'order'));
	}
}