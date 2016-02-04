<?php

/**
 * Class FinanceProject
 * @property FinanceShare FinanceShare
 */
class FinanceProject extends AppModel {

	public $useTable = 'finance_project';

	public $hasMany = array(
		'Accounts' => array(
			'className' => 'FinanceAccount',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Categories' => array(
			'className' => 'FinanceCategory',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Goals' => array(
			'className' => 'FinanceGoal',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
		'Share' => array(
			'className' => 'FinanceShare',
			'foreignKey' => 'project_id',
			'dependent' => true,
		),
	);

	public function search($userId, $q) {
		$conditions = array(
			array(
				'FinanceProject.user_id' => $userId,
				'FinanceProject.hidden' => 0,
			),
		);
		if ($q) {
			$conditions['FinanceProject.name LIKE ?'] = '%' . $q . '%';
		}
		$order = 'FinanceProject.name';
		$share = $this->searchShared($userId, $q);
		$financeProjects = array_merge(
			$this->find('all', compact('conditions', 'order')),
			$share['projects']
		);
		$invites = array();
		foreach ($share['share'] as $projectId => $state) {
			if ($state == FinanceShare::STATE_INVITE) {
				$invites[] = $projectId;
			}
		}
		return array(
			'aFinanceProjects' => $financeProjects,
			'aSharedList' => $share['share'],
			'aInvites' => $invites
		);
	}

	public function searchShared($userId, $q) {
		$this->loadModel('FinanceShare');
		$share = $this->FinanceShare->getSharedProjects($userId);
		$conditions = array(
			array(
				'FinanceProject.id' => array_keys($share)
			),
		);
		if ($q) {
			$conditions['FinanceProject.name LIKE ?'] = '%' . $q . '%';
		}
		$order = 'FinanceProject.name';
		$financeProjects = $this->find('all', compact('conditions', 'order'));

		return array('projects' => $financeProjects, 'share' => $share);
	}

	public function getProject($id, $exception = false) {
		$conditions = array(
			'FinanceProject.id' => $id
		);
		$aProject = $this->find('first', compact('conditions'));
		if (empty($aProject) && $exception) {
			throw new Exception('Project not found');
		}
		return array('aProject' => $aProject);
	}

	public function addProject($userId, $data) {
		$name = $data['FinanceProject']['name'];
		$count = $this->find('count', array(
			'conditions' => array(
				'FinanceProject.name' => $name,
				'FinanceProject.user_id' => $userId,
			),
		));
		if ($count) {
			throw new Exception(__("Project with same name already exists"));
		}
		$data['FinanceProject']['user_id'] = $userId;
		$this->save($data);
	}

	public function deleteProject($userId, $id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceProject.id' => $id,
			'FinanceProject.user_id' => $userId
		)));
		if (!$item) {
			throw new Exception('Project is not found');
		}
		$this->delete($id);
	}
}