<?php

/**
 * Class FinanceGoal
 * @property FinanceOperation FinanceOperation
 */
class FinanceGoal extends AppModel {

	public $useTable = 'finance_goals';
	public $belongsTo = array(
		'Account' => array(
			'className' => 'FinanceAccount',
			'foreignKey' => 'account_id'
		)
	);

	public function search($projectId) {
		$conditions = array(
			'FinanceGoal.project_id' => $projectId
		);
		if (FinanceShare::isPartAccess()) {
			$conditions['FinanceGoal.id'] = FinanceShare::$get['goals'];
		}
		$order = array('FinanceGoal.created' => 'DESC');
		$this->virtualFields['startBalance'] = "
		SELECT FinanceOperation.balance_after FROM finance_operation FinanceOperation
			WHERE FinanceOperation.account_id = FinanceGoal.account_id AND created < FinanceGoal.created
			ORDER BY FinanceOperation.created DESC LIMIT 1
		";
		$financeGoal = $this->find('all', compact('conditions', 'order'));
		$financeGoal = Hash::combine($financeGoal, '{n}.FinanceGoal.id', '{n}');
		$balances = $this->_monthlyBalance($financeGoal);
		foreach ($financeGoal as $id => &$item) {
			$item['Balance'] = $balances[$id];
		}

		return array(
			'aFinanceGoal' => $financeGoal,
		);
	}

	public function getOne($id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceGoal.id' => $id,
		)));
		if (!$item) {
			throw new Exception(__('Goal is not found'));
		}
		return array(
			'aFinanceGoal' => $item,
		);
	}

	public function addGoal($data) {
		$data['FinanceGoal']['finish'] = substr($data['FinanceGoal']['finish'], 0, 10) . ' 23:59:59';
		if (strtotime($data['FinanceGoal']['finish']) < time()) {
			throw new Exception(__('Invalid date'));
		}
		$accountId = $data['FinanceGoal']['account_id'];
		$exists = $this->find('count', array(
			'conditions' => array(
				'FinanceGoal.account_id' => $accountId
			),
		));
		if ($exists) {
			throw new Exception(__('Selected account is already in use for another goal'));
		}
		if ($data['FinanceGoal']['final_sum'] <= 0) {
			throw new Exception(__('Necessary sum must be a positive number'));
		}
		$this->save($data);
	}

	public function editGoal($id, $data) {
		$data['FinanceGoal']['finish'] = substr($data['FinanceGoal']['finish'], 0, 10) . ' 23:59:59';
		if (strtotime($data['FinanceGoal']['finish']) < time()) {
			throw new Exception(__('Invalid date'));
		}
		$accountId = $data['FinanceGoal']['account_id'];
		$exists = $this->find('count', array(
			'conditions' => array(
				"FinanceGoal.id <> $id",
				'FinanceGoal.account_id' => $accountId
			),
		));
		if ($exists) {
			throw new Exception(__('Selected account is already in use for another goal'));
		}
		if ($data['FinanceGoal']['final_sum'] <= 0) {
			throw new Exception(__('Necessary sum must be a positive number'));
		}
		$this->id = $id;
		$this->save($data);
	}

	public function deleteGoal($id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceGoal.id' => $id,
		)));
		if (!$item) {
			throw new Exception('Goal is not found');
		}
		$this->delete($id);
		$this->loadModel('FinanceOperation');
		$this->FinanceOperation->updateAll(
			array('FinanceOperation.is_planned' => 0,),
			array('FinanceOperation.account_id' => $item['FinanceGoal']['account_id'],)
		);
	}

	/**
	SELECT SUM(o.amount)
	FROM finance_operation o
	WHERE is_planned = 1 AND account_id = 19
	GROUP BY account_id
	 */
	public function isAccumulated($accountId) {
		$goal = $this->find('first', array(
			'conditions' => array(
				'FinanceGoal.account_id' => $accountId,
			),
			'fields' => array(
				'FinanceGoal.*',
			),
		));
		if (!isset($goal['FinanceGoal']['final_sum'])) {
			return null;
		}
		$this->loadModel('FinanceOperation');
		$this->FinanceOperation->virtualFields['accum'] = 'SUM(FinanceOperation.amount)';
		$operation = $this->FinanceOperation->find('all', array(
			'conditions' => array(
				'FinanceOperation.account_id' => $accountId,
				'FinanceOperation.is_planned' => 1,
				"FinanceOperation.created >= '" . $goal['FinanceGoal']['created'] . "'",
				"FinanceOperation.created <= '" . $goal['FinanceGoal']['finish'] . "'",
			),
			'group' => array('FinanceOperation.account_id'),
			'fields' => array(
				'FinanceOperation.accum',
			),
			'recursive' => -1
		));
		if (isset($operation[0][0]['FinanceOperation__accum']) && $operation[0][0]['FinanceOperation__accum'] && $operation[0][0]['FinanceOperation__accum'] >= $goal['FinanceGoal']['final_sum']) {
			return true;
		}
		return false;
	}

	private function _monthlyBalance(array $list) {
		$result = array();
		foreach ($list as $id => $item) {
			$accountId = $item['FinanceGoal']['account_id'];
			$created = $item['FinanceGoal']['created'];
			$finish = $item['FinanceGoal']['finish'];
			$this->loadModel('FinanceOperation');
			$this->FinanceOperation->virtualFields['date'] = 0;
			$result[$id] = $this->query("
				SELECT *, SUBSTR(created, 1, 7) AS FinanceOperation__date
					FROM (SELECT * FROM finance_operation
					WHERE account_id=$accountId
						AND created >= '$created'
						AND created <= '$finish'
						AND is_planned = 1
					ORDER BY created DESC) AS FinanceOperation
					GROUP BY FinanceOperation__date
			");
		}

		return $result;
	}
}