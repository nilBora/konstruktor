<?php

/**
 * Class FinanceAccount
 * @property FinanceOperation FinanceOperation
 * @property FinanceShare FinanceShare
 */
class FinanceAccount extends AppModel {

	public $useTable = 'finance_account';

	public $hasMany = array (
		'Operations' => array (
			'className' => 'FinanceOperation',
			'foreignKey' => 'account_id',
			'dependent' => true,
		)
	);

	public function search($projectId, $q = null) {
		$conditions = array(
			'FinanceAccount.project_id' => $projectId
		);
		$this->loadModel('FinanceShare');
		if (FinanceShare::$isInit && FinanceShare::isPartAccess()) {
			$conditions['FinanceAccount.id'] = FinanceShare::$get['accounts'];
		}
		if ($q) {
			$conditions['FinanceAccount.name LIKE ?'] = '%' . $q . '%';
		}
		$financeAccount = $this->find('all', array('conditions' => $conditions));
		$financeAccount = Hash::combine($financeAccount, '{n}.FinanceAccount.id', '{n}');
		$this->loadModel('CrmTask');
		$taskAccountIds = $this->CrmTask->find('list', array (
			'conditions' => array(
				'CrmTask.account_id' => array_keys($financeAccount),
			),
			'fields' => array('CrmTask.account_id')
		));
		foreach ($taskAccountIds as $accountId) {
			unset($financeAccount[$accountId]);
		}
		return array(
			'aFinanceAccount' => $financeAccount,
		);
	}

	/**
	 * @param $id - Account Id
	 * @return array
	 * @throws Exception
	 */
	public function getOne($id) {
		$item = $this->find('first',
			array('conditions' => array(
				'FinanceAccount.id' => $id,
			)
		));
		if (!$item) {
			throw new Exception(__('Account is not found'));
		}
		$this->loadModel('FinanceOperation');
		$item['FinanceAccount']['balance'] = $this->FinanceOperation->accountCurrentBalance($id);
		return array(
			'aFinanceAccount' => $item,
		);
	}

	public function addAccount($data) {
		$balance = $data['FinanceAccount']['balance'];
		if (!$balance) {
			$balance = 0;
		}
		if ($this->save($data)) {
			$this->loadModel('FinanceOperation');
			$this->FinanceOperation->addOperation(array(
				'FinanceOperation' => array(
					'project_id' => $data['FinanceAccount']['project_id'],
					'account_id' => $this->id,
					'type' => FinanceOperation::TYPE_ACCOUNT_CREATE,
					'amount' => $balance,
					'currency' => $data['FinanceAccount']['currency'],
					'comment' => 'The first operation: Auto save balance',
				)
			));
		}
	}

	public function editAccount($id, $data) {
		$account = $this->getOne($id);
		$account = $account['aFinanceAccount']['FinanceAccount'];

		$oldBalance = $account['balance'];
		$newBalance = $data['FinanceAccount']['balance'];

		$this->id = $id;
		unset($data['FinanceAccount']['balance']);
		if ($this->save($data) && $newBalance !== $oldBalance) {
			$delta = $newBalance - $oldBalance;
			$this->loadModel('FinanceOperation');
			$this->FinanceOperation->updateAll(
				array('FinanceOperation.balance_after' => 'FinanceOperation.balance_after + ' . $delta),
				array('FinanceOperation.account_id' => $id)
			);
		}
	}

	public function deleteAccount($id) {
		$item = $this->find('first', array('conditions' => array(
			'FinanceAccount.id' => $id,
		)));
		if (!$item) {
			throw new Exception('Account is not found');
		}
		$this->delete($id);
	}

	public function updateBalance($id, $newBalance) {
		$this->id = $id;
		$this->saveField('balance', $newBalance);
	}

	/**
	 * @param $projectId
	 * @return array
	 */
	public function currencyBalances($projectId) {
		$result = $this->find('all', array(
			'fields' => array(
				'FinanceAccount.id',
				'FinanceAccount.currency',
				'(SELECT o.balance_after FROM finance_operation o
					WHERE FinanceAccount.id = o.account_id
					ORDER BY o.created DESC LIMIT 1) balance',
			),
			'conditions' => array(
				'FinanceAccount.project_id' => $projectId,
			),
		));
		$return = array();
		foreach ($result as $item) {
			@$return[$item['FinanceAccount']['currency']] += $item[0]['balance'];
		}

		return array('aCurrencyBalances' => $return);
	}

	/**
	SELECT a.id, (SELECT o.balance_after FROM finance_operation o
	WHERE a.id = o.account_id ORDER BY created DESC LIMIT 1) balance
	FROM finance_account a
	 */
	public function lastBalances($projectId) {
		$result = $this->find('all', array(
			'fields' => array(
				'FinanceAccount.id',
				'(SELECT o.balance_after FROM finance_operation o
					WHERE FinanceAccount.id = o.account_id
					ORDER BY o.created DESC LIMIT 1) balance',
			),
			'conditions' => array(
				'FinanceAccount.project_id' => $projectId,
			),
		));
		$return = array();
		foreach ($result as $item) {
			$return[$item['FinanceAccount']['id']] = $item[0]['balance'];
		}

		return $return;
	}

	public function fullIncome($accountId, $from = null, $to = null, $to2 = null) {
		$this->loadModel('FinanceOperation');
		$this->FinanceOperation->virtualFields['result'] = 'SUM(FinanceOperation.amount)';
		$conditions = array(
			'FinanceOperation.account_id' => $accountId,
			'FinanceOperation.type' => FinanceOperation::TYPE_INCOME,
		);
		if ($from) {
			$conditions[] = "UNIX_TIMESTAMP(FinanceOperation.created) >= '$from'";
		}
		if ($to) {
			$conditions[] = "FinanceOperation.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		if ($to2) {
			$conditions[] = "UNIX_TIMESTAMP(FinanceOperation.created) < '$to2'";
		}
		$result = $this->FinanceOperation->find('all', array(
			'conditions' => $conditions,
			'group' => array('FinanceOperation.account_id'),
			'fields' => array('FinanceOperation.result'),
		));
		if (!empty($result)) {
			return $result[0][0]['FinanceOperation__result'];
		}
	}

	public function fullExpense($accountId, $from = null, $to = null, $to2 = null) {
		$this->loadModel('FinanceOperation');
		$this->FinanceOperation->virtualFields['result'] = '-SUM(FinanceOperation.amount)';
		$conditions = array(
			'FinanceOperation.account_id' => $accountId,
			'FinanceOperation.type' => FinanceOperation::TYPE_EXPENSE,
		);
		if ($from) {
			$conditions[] = "UNIX_TIMESTAMP(FinanceOperation.created) >= '$from'";
		}
		if ($to) {
			$conditions[] = "FinanceOperation.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		if($to2) {
			$conditions[] = "UNIX_TIMESTAMP(FinanceOperation.created) < '$to2'";
		}

		$result = $this->FinanceOperation->find('all', array(
			'conditions' => $conditions,
			'group' => array('FinanceOperation.account_id'),
			'fields' => array('FinanceOperation.result'),
		));

		if (!empty($result)) {
			return $result[0][0]['FinanceOperation__result'];
		}
	}
}