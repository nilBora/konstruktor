<?php

/**
 * Class InvestReward
 */
class InvestReward extends AppModel {

	public $useTable = 'invest_reward';

	public $belongsTo = array(
		'InvestProject' => array(
			'className' => 'InvestProject',
			'foreignKey' => 'project_id'
		),
	);

	public $hasMany = array(
		'Sponsors' => array(
			'className' => 'InvestSponsor',
			'foreignKey' => 'reward_id',
			'order' => array('Sponsors.id' => 'DESC'),
			//Limiting association sponsor list
			'limit' => 50
		),
	);

	public function get($id) {
		$conditions = array('InvestReward.id' => $id);
		$order = array('InvestReward.id' => 'DESC');
		return $this->find('all', compact('conditions', 'order'));
	}

	public function addReward($userId, $projectId, $data) {
		$data['InvestReward']['project_id'] = (int) $projectId;
		$data['InvestReward']['user_id'] = (int) $userId;
		$this->save($data);
		$this->clear();
	}

	public function editReward($id, $data) {
		$this->id = (int) $id;
		$this->save($data);
		$this->clear();
	}

	public function delReward($userId, $id) {
		$this->deleteAll(array(
			'InvestReward.user_id' => $userId,
			'InvestReward.id' => $id,
		));
	}
}
