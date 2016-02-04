<?php
App::uses('AbstractRating', 'Lib/Rating');

class ProjectEventRating extends AbstractRating {

	protected $_config = array(
		'addComment' => array(
			'label' => 'Task comment',
			'createdOnly' => true,
			'target' => array(
				'Group' => 'group_id',
				'User' => 'user_id'
			),
			'value' => 1
		),
	);

	protected function addComment($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		$projectEventModel = ClassRegistry::init('ProjectEvent');
		if($data[$this->context]['event_type'] != $projectEventModel::TASK_COMMENT){
			$result = false;
		}
		return $result;
	}

	public function group_id($data){
		$projectModel = ClassRegistry::init('Project');
		$project = $projectModel->find('first', array(
			'conditions' => array('Project.id' => $data[$this->context]['project_id']),
			'recursive' => -1
		));
		$data[$this->context]['group_id'] = null;
		if(!empty($project)&&isset($project['Project']['group_id'])){
			$data[$this->context]['group_id'] = $project['Project']['group_id'];
		}
		return $data;
	}

}
