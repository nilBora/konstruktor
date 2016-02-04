<?php

/**
 * Class FinanceOperation
 * @property FinanceAccount FinanceAccount
 * @property FinanceGoal FinanceGoal
 * @property FinanceOperationHasCategory FinanceOperationHasCategory
 * @property FinanceCategory FinanceCategory
 */
class FinanceOperation extends AppModel {

	const TYPE_ACCOUNT_CREATE = -1;
	const TYPE_INCOME = 0;
	const TYPE_EXPENSE = 1;
	const TYPE_TRANSFER = 2;
	// Inner types only for model
	const TYPE_TRANSFER_OUT = 3;
	const TYPE_TRANSFER_IN = 4;

	public $useTable = 'finance_operation';

	public $hasMany = array(
		'OperationHasCategory' => array(
			'className' => 'FinanceOperationHasCategory',
			'foreignKey' => 'operation_id',
			'dependent' => true,
		)
	);

	var $hasAndBelongsToMany = array(
		'Categories' => array(
			'className' => 'FinanceCategory',
			'joinTable' => 'finance_operation_has_category',
			'foreignKey' => 'operation_id',
			'associationForeignKey' => 'category_id',
			'unique' => true,
		)
	);

	public function countPages(
		$projectId,
		$pageSize = 15,
		$accountId = null,
		$categoryId = null,
		$from = null,
		$to = null
	) {
		$conditions = array(
			'FinanceOperation.project_id' => $projectId,
			'FinanceOperation.type<>' . self::TYPE_ACCOUNT_CREATE,
		);
		if ($accountId) {
			$conditions['FinanceOperation.account_id'] = $accountId;
		}
		if ($categoryId) {
			$conditions[] = "FinanceOperation.id IN (SELECT operation_id FROM finance_operation_has_category WHERE category_id='$categoryId')";
		}
		if ($from) {
			$conditions[] = "FinanceOperation.created >= '$from'";
		}
		if ($to) {
			$conditions[] = "FinanceOperation.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		$allDates = $this->find('all', array(
			'fields' => array('SUBSTR(created, 1, 10) AS date'),
			'conditions' => $conditions,
			'group' => array('date'),
		));
		$countDates = count($allDates);
		$countPages = ceil($countDates / $pageSize);

		return $countPages;
	}

	public function search(
		$projectId,
		$page = 0,
		$pageSize = 15,
		$accountId = null,
		$categoryId = null,
		$from = null,
		$to = null
	) {
		$conditions = array(
			'FinanceOperation.project_id' => $projectId,
			'FinanceOperation.type<>' . self::TYPE_ACCOUNT_CREATE,
		);
		if (FinanceShare::isPartAccess()) {
			$itog = array();
			foreach (FinanceShare::$get['operations'] as $item) {
				switch($item) {
					case 'income': $itog[] = self::TYPE_INCOME; break;
					case 'expense': $itog[] = self::TYPE_EXPENSE; break;
					case 'transfer': $itog[] = self::TYPE_TRANSFER; break;
				}
			}
			$conditions['FinanceOperation.type'] = $itog;
			$conditions['FinanceOperation.account_id'] = FinanceShare::$get['accounts'];
		}
		if ($accountId) {
			$conditions['FinanceOperation.account_id'] = $accountId;
		}
		if ($categoryId) {
			$conditions[] = "FinanceOperation.id IN (SELECT operation_id FROM finance_operation_has_category WHERE category_id='$categoryId')";
		}
		if ($from) {
			$conditions[] = "FinanceOperation.created >= '$from'";
		}
		if ($to) {
			$conditions[] = "FinanceOperation.created <= DATE_ADD('$to', INTERVAL 24 HOUR)";
		}
		$order = 'FinanceOperation.created desc';

		// Paging
		$dates = $this->find('all', array(
			'fields' => array('SUBSTR(created, 1, 10) AS date'),
			'conditions' => $conditions,
			'order' => $order,
			'limit' => $pageSize,
			'offset' => $page * $pageSize,
			'group' => array('date'),
		));
		if (empty($dates)) {
			return array('aFinanceOperations' => array());
		}
		$max = $dates[0][0]['date'];
		$min = $dates[count($dates) - 1][0]['date'];

		if ($min == $max) {
			$conditions[] = "SUBSTR(FinanceOperation.created, 1, 10) = '$min'";
		} else {
			$conditions[] = "SUBSTR(FinanceOperation.created, 1, 10) >='$min'";
			$conditions[] = "SUBSTR(FinanceOperation.created, 1, 10) <= '$max'";
		}
		// /Paging

		$financeOperations = $this->find('all', array(
			'conditions' => $conditions,
			'order' => $order,
		));

		return array('aFinanceOperations' => $financeOperations);
	}

	/**
	 * SELECT  id, account_id, created, balance_after, currency, substr(created,1,10) as date FROM finance_operation o1 WHERE currency = 'USD' AND id = (SELECT MAX(id) FROM finance_operation o2 WHERE substr(o1.created,1,10)=substr(o2.created,1,10) AND o1.account_id=o2.account_id) GROUP BY account_id, date;
	 * # Last variant with 'finance_calendar'
	 * SELECT * FROM finance_calendar c LEFT JOIN finance_operation o ON substr(o.created,1,10) <= c.date AND o.id=(SELECT MAX(id) FROM finance_operation o2 WHERE substr(o2.created,1,10)<=c.date AND o.account_id=o2.account_id) WHERE currency = 'USD'
	 * # AND date = "2015-02-07"
	 */
	public function getBalance($projectId, $currency, $period) {
		switch ($period) {
			case 'week':
				$periodCondition = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)';
				break;
			case 'month':
				$periodCondition = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)';
				break;
			case 'quarter':
				$periodCondition = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)';
				break;
			case 'year':
				$periodCondition = 'FinanceCalendar.date <= CURDATE() AND FinanceCalendar.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
				break;
		}
		$this->loadModel('FinanceCalendar');
		$balanceList = $this->FinanceCalendar->find('all', array(
			'conditions' => array(
				'FinanceOperation.project_id' => $projectId,
				'FinanceOperation.currency' => $currency,
				$periodCondition
			),
			'joins' => array(
				array(
					'table' => 'finance_operation',
					'alias' => 'FinanceOperation',
					'type' => 'left',
					'conditions' => array(
						'substr(FinanceOperation.created,1,10) <= FinanceCalendar.date',
						'FinanceOperation.created >= (SELECT o1.created FROM finance_operation o1 WHERE o1.type=-1 AND o1.account_id=FinanceOperation.account_id LIMIT 1)',
						'FinanceOperation.created=(SELECT MAX(o2.created) FROM finance_operation o2
						WHERE substr(o2.created,1,10)<=FinanceCalendar.date AND FinanceOperation.account_id=o2.account_id)',
					)
				),
			),
			'fields' => array('FinanceOperation.balance_after', 'FinanceCalendar.date'),
		));
		$result = array();
		foreach ($balanceList as $item) {
			$date = $item['FinanceCalendar']['date'];
			$balance = (double)$item['FinanceOperation']['balance_after'];
			if (isset($result[$date])) {
				$result[$date] += $balance;
			} else {
				$result[$date] = $balance;
			}
		}
		ksort($result);
		$return = array(array('date', 'balance'));
		$i = 0;
		$count = count($result);
		foreach ($result as $date => $balance) {
			$i++;
			if (in_array($period, array('quarter', 'year')) && substr($date, 8) !== '01' && $i !== $count) {
				continue;
			}
			if (in_array($period, array('quarter', 'year'))) {
				$day = __(date('M', strtotime($date)));
			} elseif ($period == 'month') {
				$day = date('j', strtotime($date));
			} else {
				$day = __(date('D', strtotime($date)));
				$day .= ' (' . date('j', strtotime($date));
				$day .= ' ' . __(date('M', strtotime($date))) . ')';
			}

			$return[] = array($day, $balance);
		}

		return $return;
	}

	public function addOperation($data, $timeIsSet = false) {
		$result = array();

		if (!$timeIsSet) {
			if (isset($data['FinanceOperation']['created'])) {
				$data['FinanceOperation']['created'] = substr($data['FinanceOperation']['created'], 0,
						10) . ' ' . date('H:i:s');
			} else {
				$data['FinanceOperation']['created'] = date('Y-m-d H:i:s');
			}
		}
		if ($data['FinanceOperation']['type'] == self::TYPE_TRANSFER) {

			$accountId2 = $data['FinanceOperation']['account_id_2'];
			$amount2 = $data['FinanceOperation']['amount_2'];
			unset($data['FinanceOperation']['account_id_2'], $data['FinanceOperation']['amount_2']);
			$data['FinanceOperation']['type'] = self::TYPE_TRANSFER_OUT;
			$result = $this->_transaction($data);
			$realCategoryIdList = $result['realCategoryIdList'];
			$linkId = $result['id'];
			$data['FinanceOperation']['type'] = self::TYPE_TRANSFER_IN;
			$data['FinanceOperation']['account_id'] = $accountId2;
			$data['FinanceOperation']['amount'] = $amount2;
			$data['FinanceOperationHasCategory']['category_id'] = implode(',', $realCategoryIdList);
			$this->_transaction($data, $linkId);
		} else {
			$result = $this->_transaction($data);
		}
		return $result['id'];
	}

	public function editOperation($data) {
		$id = (int)$data['FinanceOperation']['id'];
		$item = $this->find('first',
			array('conditions' => array(
				'FinanceOperation.id' => $id,
			))
		);
		if (!$item) {
			throw new Exception('Operation is not found');
		}
		$this->deleteOperation($id);
		unset($data['FinanceOperationHasCategory']['category_id_fake'], $data['FinanceOperation']['id']);
		$data['FinanceOperation']['project_id'] = $item['FinanceOperation']['project_id'];
		$data['FinanceOperation']['type'] = $item['FinanceOperation']['type'];
		if( isset($data['FinanceOperation']['created'])) {
			$data['FinanceOperation']['created'] = date('Y-m-d H:i:s', strtotime($data['FinanceOperation']['created']));
			if (substr($data['FinanceOperation']['created'], 0, 10) == substr($item['FinanceOperation']['created'], 0, 10)
			) {
				$data['FinanceOperation']['created'] = $item['FinanceOperation']['created'];
			} else {
				$data['FinanceOperation']['created'] = substr($data['FinanceOperation']['created'], 0,
						10) . ' ' . date('H:i:s');
			}
		} else {
			$data['FinanceOperation']['created'] = $item['FinanceOperation']['created'];
		}
		return $this->addOperation($data, true);
	}

	public function deleteOperation($id, $viaLink = false) {
		$item = $this->find('first',
			array('conditions' => array(
				'FinanceOperation.id' => $id,
			)))
		;
		if (!$item) {
			throw new Exception('Operation is not found');
		}
		$accountId = $item['FinanceOperation']['account_id'];
		$amount = $item['FinanceOperation']['amount'];
		$type = $item['FinanceOperation']['type'];
		$created = $item['FinanceOperation']['created'];
		// Before delete
		if ($type == self::TYPE_TRANSFER_OUT && !$viaLink) {
			$transferIn = $this->find('first', array('conditions' => array('FinanceOperation.link_id' => $id)));
			$this->deleteOperation((int)$transferIn['FinanceOperation']['id']);
		}
		$this->delete($id);
		// After delete
		$this->updateAll(
			array('FinanceOperation.balance_after' => "FinanceOperation.balance_after+$amount"),
			array('FinanceOperation.account_id' => $accountId, "FinanceOperation.created >= '$created'",)
		);
		switch ($type) {
			case self::TYPE_INCOME:
			case self::TYPE_TRANSFER_IN: // exists link_id
				if ($item['FinanceOperation']['link_id']) {
					$this->deleteOperation((int)$item['FinanceOperation']['link_id'], true);
				}
				break;
		}
	}

	/**
	 * SELECT currency, balance_after
	 * FROM finance_operation o1
	 * WHERE created <= '2015-02-01'
	 * AND created=(SELECT MAX(o.created) FROM finance_operation o WHERE o.created <= '2015-02-01' AND o.currency=o1.currency)
	 * GROUP BY currency
	 */
	public function compareAccounts($projectId, $month1, $month2) {
		$conditions = array(
			'FinanceOperation.project_id' => $projectId,
		);
		$conditions1 = $conditions + array(
				"FinanceOperation.created <= '$month1-01'",
				"FinanceOperation.created = (SELECT MAX(o.created) FROM finance_operation o WHERE o.created <= '$month1-01' AND o.currency=FinanceOperation.currency)",
			);
		$result1 = $this->find('all', array(
			'conditions' => $conditions1,
			'group' => 'FinanceOperation.created',
			'fields' => array('FinanceOperation.currency', 'FinanceOperation.balance_after'),
		));
		$result1 = Hash::combine($result1, '{n}.FinanceOperation.currency', '{n}.FinanceOperation.balance_after');

		$conditions2 = $conditions + array(
				"FinanceOperation.created < DATE_ADD('$month2-01', INTERVAL 1 MONTH)",
				"FinanceOperation.created = (SELECT MAX(o.created) FROM finance_operation o WHERE o.created < DATE_ADD('$month2-01', INTERVAL 1 MONTH) AND o.currency=FinanceOperation.currency)",
			);
		$result2 = $this->find('all', array(
			'conditions' => $conditions2,
			'group' => 'FinanceOperation.created',
			'fields' => array('FinanceOperation.currency', 'FinanceOperation.balance_after'),
		));
		$result2 = Hash::combine($result2, '{n}.FinanceOperation.currency', '{n}.FinanceOperation.balance_after');

		$currencyList = array('USD' => 0, 'EUR' => 0, 'RUB' => 0,);
		$return = array(array('currency', '%'));
		foreach ($currencyList as $currency => $balance) {
			if (isset($result1[$currency]) && isset($result2[$currency])) {
				$balance1 = (double)$result1[$currency];
				$balance2 = (double)$result2[$currency];
				$percent = 100 * ($balance2 - $balance1);
				if ($balance1 == 0) { // except division by zero
					$percent = $balance2 ? 100 : 0;
				} else {
					$percent /= $balance1;
				}
				$return[] = array($currency, intval($percent));
			} elseif (!isset($result1[$currency]) && isset($result2[$currency])) {
				$return[] = array($currency, 100);
			} elseif (isset($result1[$currency]) && !isset($result2[$currency])) {
				$return[] = array($currency, -100);
			} elseif (!isset($result1[$currency]) && !isset($result2[$currency])) {
				$return[] = array($currency, 0);
			}
		}

		return $return;
	}

	/**
	 * @param $projectId
	 * @param $accountId
	 * @param null $datetime
	 * @return mixed
	 * @throws Exception
	 */
	public function accountCurrentBalance($accountId, $datetime = null) {
		$this->loadModel('FinanceAccount');

		if ($datetime === null) {
			$datetime = date('Y-m-d H:i:s');
		}
		$item = $this->find('first', array(
			'conditions' => array(
				'FinanceOperation.account_id' => $accountId,
				"FinanceOperation.created<='$datetime'",
			),
			'order' => array('FinanceOperation.created' => 'DESC'),
			'fields' => array(
				'FinanceOperation.balance_after',
			),
		));
		if (!$item) {
			$this->FinanceAccount->getOne($accountId);
			throw new Exception(__('The selected account was created later than the operations date'));
		}

		return $item['FinanceOperation']['balance_after'];
	}

	private function _transaction($data, $linkId = null) {
		$this->loadModel('FinanceAccount');
		$created = $data['FinanceOperation']['created'];
		$accountId = $data['FinanceOperation']['account_id'];
		$projectId = $data['FinanceOperation']['project_id'];
		$amount = $data['FinanceOperation']['amount'];
		$type = $data['FinanceOperation']['type'];

		if (preg_match("/[^0-9.^0-9]/", $amount)){
			die(__('Expense amount must be a number'));
		}
		if ($type == self::TYPE_ACCOUNT_CREATE) {
			$currency = $data['FinanceOperation']['currency'];
		} else {
			$account = $this->FinanceAccount->getOne($accountId);
			$currency = $account['aFinanceAccount']['FinanceAccount']['currency'];
		}
		switch ($type) {
			case self::TYPE_ACCOUNT_CREATE:
				$newBalance = $amount;
				break;
			case self::TYPE_INCOME:
			case self::TYPE_TRANSFER_IN:
				$currBalance = $this->accountCurrentBalance($accountId, $created);
				$newBalance = $currBalance + $amount;
				// Regular Payment
				$this->loadModel('FinanceGoal');
				if (
					isset($data['FinanceOperation']['is_planned'])
					&& $data['FinanceOperation']['is_planned']
					&& $this->FinanceGoal->isAccumulated($accountId)
				) {
					$data['FinanceOperation']['is_planned'] = 0;
				}
				break;
			case self::TYPE_EXPENSE:
			case self::TYPE_TRANSFER_OUT:
				$currBalance = $this->accountCurrentBalance($accountId, $created);
				$newBalance = $currBalance - $amount;
				// !!! Important. Save as negative number for any of expense
				$data['FinanceOperation']['amount'] = -$amount;
				break;
			default:
				throw new Exception('Unknown finance type of operations');
		}
		$data['FinanceOperation']['balance_after'] = $newBalance;
		$data['FinanceOperation']['currency'] = $currency;
		if ($linkId) {
			$data['FinanceOperation']['link_id'] = $linkId;
		}
		$realCategoryIdList = array();
		$operation_id = null;
		if ($this->save($data) && $type !== self::TYPE_ACCOUNT_CREATE) {
			// After transaction
			switch ($type) {
				case self::TYPE_INCOME:
				case self::TYPE_TRANSFER_IN:
					$this->updateAll(
						array('FinanceOperation.balance_after' => "FinanceOperation.balance_after+$amount"),
						array('FinanceOperation.account_id' => $accountId, "FinanceOperation.created > '$created'",)
					);
					break;
				case self::TYPE_EXPENSE:
				case self::TYPE_TRANSFER_OUT:
					$this->updateAll(
						array('FinanceOperation.balance_after' => "FinanceOperation.balance_after-$amount"),
						array('FinanceOperation.account_id' => $accountId, "FinanceOperation.created > '$created'",)
					);
					break;
			}
			// Categories
			if ($type == self::TYPE_TRANSFER_IN || $type == self::TYPE_TRANSFER_OUT) {
				$type = self::TYPE_TRANSFER;
			}
			$this->loadModel('FinanceOperationHasCategory');
			$this->loadModel('FinanceCategory');
			$categoryIdList = explode(',', $data['FinanceOperationHasCategory']['category_id']);
			$operation_id = $this->id;
			foreach ($categoryIdList as $category_id) {
				$category_id = trim($category_id);
				if (!$category_id) {
					continue;
				}
				if (!is_numeric($category_id)) {
					$this->FinanceCategory->save(array(
						'name' => $category_id,
						'project_id' => $data['FinanceOperation']['project_id'],
						'type' => $type,
					));
					$category_id = $this->FinanceCategory->id;
					$this->FinanceCategory->clear();
				}
				$this->FinanceOperationHasCategory->save(compact('operation_id', 'category_id'));
				$this->FinanceOperationHasCategory->clear();
				$realCategoryIdList[] = $category_id;
			}
			$this->clear();
		}

		return array('realCategoryIdList' => $realCategoryIdList, 'id' => $operation_id);
	}

	/**
	 * SELECT g.id, g.name, g.final_sum, SUM(o.amount),o.*
	 * FROM finance_operation o
	 * LEFT JOIN finance_goals g ON o.account_id = g.account_id
	 * WHERE is_planned = 1
	 * GROUP BY account_id
	 */
	public function regularPayments($projectId) {
		$this->virtualFields['accumulate'] = 'SUM(FinanceOperation.amount)';
		$aGoals = $this->find('all', array(
			'conditions' => array(
				'FinanceOperation.project_id' => $projectId,
				'FinanceOperation.is_planned' => 1,
				'FinanceOperation.created >= FinanceGoal.created',
				'FinanceOperation.created <= FinanceGoal.finish',
			),
			'joins' => array(
				array(
					'table' => 'finance_goals',
					'alias' => 'FinanceGoal',
					'type' => 'left',
					'conditions' => array(
						'FinanceOperation.account_id = FinanceGoal.account_id',
					)
				),
				array(
					'table' => 'finance_account',
					'alias' => 'FinanceAccount',
					'type' => 'left',
					'conditions' => array(
						'FinanceOperation.account_id = FinanceAccount.id',
					)
				),
			),
			'group' => array('FinanceOperation.account_id'),
			'fields' => array(
				'FinanceOperation.*',
				'FinanceGoal.*',
				'FinanceAccount.name',
			),
			'recursive' => -1,
		));
		$aGoals = Hash::combine($aGoals, '{n}.FinanceOperation.account_id', '{n}');
		$this->virtualFields = array();
		$aRegularPayments = $this->find('all', array(
			'conditions' => array(
				'FinanceOperation.project_id' => $projectId,
				'FinanceOperation.is_planned' => 1,
			),
			'recursive' => -1,
		));
		foreach ($aRegularPayments as $i => $item) {
			$accountId = $item['FinanceOperation']['account_id'];
			$created = strtotime($item['FinanceOperation']['created']);
			if (
				isset($aGoals[$accountId])
				&& strtotime($aGoals[$accountId]['FinanceGoal']['created']) <= $created
				&& strtotime($aGoals[$accountId]['FinanceGoal']['finish']) >= $created
			) {
				unset($aRegularPayments[$i]);
			}
		}
		$aRegularPayments = array_merge($aRegularPayments, $aGoals);
		$result = array('aRegularPayments' => $aRegularPayments);

		return $result;
	}
}
