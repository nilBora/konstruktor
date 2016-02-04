<?php

/**
 * Class FinanceBudget
 * @property FinanceOperation FinanceOperation
 * @property FinanceProject FinanceProject
 */
class FinanceBudget extends AppModel {

	public $useTable = 'finance_budget';
	public $belongsTo = array(
		'Category' => array(
			'className' => 'FinanceCategory',
			'foreignKey' => 'category_id'
		),
		'Account' => array(
			'className' => 'FinanceAccount',
			'foreignKey' => 'account_id'
		)
	);

	public function search($projectId, $accountId = null, $q = null, $categoryId = null) {
		$conditions = array(
			'FinanceBudget.project_id' => $projectId,
		);
		
		if (FinanceShare::isPartAccess()) {
			$conditions['FinanceBudget.id'] = FinanceShare::$get['budgets'];
			$conditions['FinanceBudget.category_id'] = FinanceShare::$get['categories'];
			$conditions['FinanceBudget.account_id'] = FinanceShare::$get['accounts'];
		}
		if ($accountId) {
			$conditions['FinanceBudget.account_id'] = $accountId;
		}
		if ($categoryId) {
			$conditions['FinanceBudget.category_id'] = $categoryId;
		}
		if ($q) {
			$conditions['FinanceBudget.name LIKE ?'] = '%' . $q . '%';
		}
		$financeBudget = $this->find('all', array('conditions' => $conditions));
		$financeBudget = Hash::combine($financeBudget, '{n}.FinanceBudget.category_id', '{n}');
		
		return array(
			'aFinanceBudget' => $financeBudget,
		);
	}

	public function addBudget($data) {
		$count = $this->find('count', array(
			'conditions' => array(
				'FinanceBudget.category_id' => $data['FinanceBudget']['category_id'],
				'FinanceBudget.account_id' => $data['FinanceBudget']['account_id'],
			),
		));
		if ($count) {
			throw new Exception(__("Budget with same pair of account and category already exists"));
		}
		if (!$this->save($data)) {
			throw new Exception(__('Operation is break'));
		}
	}
}