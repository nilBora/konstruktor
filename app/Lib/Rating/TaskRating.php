<?php
App::uses('AbstractRating', 'Lib/Rating');

class TaskRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Task creation',
			'createdOnly' => true,
			'target' => array(
				'Group' => 'group_id',
				'User' => 'creator_id'
			),
			'value' => 2
		),
		'close' => array(
			'label' => 'Close task in group',
			'target' => array(
				'Group' => 'group_id',
				'User' => array('creator_id', 'manager_id', 'user_id')
			),
			'value' => 3
		),
	);

	protected function close($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		if($result&&!empty($data[$this->context]['closed'])){
			$result = true;
		} elseif($result&&empty($data[$this->context]['closed'])) {
			$result = false;
		}
		return $result;
	}

	public function group_id($data){
		$subprojectModel = ClassRegistry::init('Subproject');
		$subprojectModel->bindModel(array(
			'belongsTo' => array(
            	'Project' => array(
                    'className' => 'Project',
					'foreignKey' => 'project_id',
                )
	        )
	    ));
		$subproject = $subprojectModel->find('first', array(
			'conditions' => array('Subproject.id' => $data[$this->context]['subproject_id']),
		));
		$data[$this->context]['group_id'] = null;
		if(!empty($subproject)&&isset($subproject['Project']['group_id'])){
			$data[$this->context]['group_id'] = $subproject['Project']['group_id'];
		}
		return $data;
	}

}
