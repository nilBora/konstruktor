<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceBudgetController
 * @property FinanceProject FinanceProject
 * @property FinanceCategory FinanceCategory
 * @property FinanceAccount FinanceAccount
 * @property FinanceBudget FinanceBudget
 */
class FinanceBudgetController extends FinanceController {
	public $name = 'FinanceBudget';
	public $layout = 'profile_new';
	public $components = array('RequestHandler');

	/**
	 * Provides statistic reports
	 * @param $id - project id
	 */
	public function index($id) {
		$this->loadModel('FinanceAccount');
		$this->loadModel('Group');
		$this->loadModel('Project');
		$accounts = $this->FinanceAccount->search((int)$id);

		$fromMonth = $this->request->query('fromMonth');
		$accountId = $this->request->query('accountId');
		$groupProjectId = $this->request->query('groupProjectId');
		if( $groupProjectId == 'none') {
			$groupProjectId = null;
		}

		if($groupProjectId) {
			$project = $this->Project->findById($this->request->query('groupProjectId'));
			$accountId = Hash::get($project, 'Project.finance_account_id');
			$categoryId = Hash::get($project, 'Project.finance_category_id');

		} else {
			if (!$fromMonth) {
				$fromMonth = date('Y-m');
			}
			if (!$accountId && !empty($accounts['aFinanceAccount'])) {
				$currAccount = current($accounts['aFinanceAccount']);
				$accountId = $currAccount['FinanceAccount']['id'];
			}
			$categoryId = null;
		}

		$this->loadModel('FinanceProject');
		$project = $this->FinanceProject->getProject((int)$id, true);

		$this->loadModel('FinanceCategory');
		$this->loadModel('FinanceBudget');
		$budget = $this->FinanceBudget->search((int)$id, $accountId, null, $categoryId);

		$currency = @$accounts['aFinanceAccount'][$accountId]['FinanceAccount']['currency'];
		$month1 = date('Y-m', strtotime($fromMonth));
		$month2 = date('Y-m', strtotime('+1 month', strtotime($fromMonth)));
		$month3 = date('Y-m', strtotime('+2 month', strtotime($fromMonth)));
		$month4 = date('Y-m', strtotime('+3 month', strtotime($fromMonth)));

		$report = $this->FinanceCategory->getBudget((int) $id, $accountId, $month1, $month2, $month3, $month4, $categoryId);
		$this->set($budget + $report + $project + $accounts + compact('id', 'accountId', 'groupProjectId', 'currency', 'month1', 'month2', 'month3', 'month4', 'fromMonth'));

		//для фильтров по проектам
		$conditions = array('Group.finance_project_id' => $id);
		$group = $this->Group->find('first', compact('conditions'));
		$this->set('group', $group);
		$conditions = array(
			'Project.group_id' => Hash::get($group, 'Group.id'),
			'NOT' => array(
				'Project.finance_account_id' => null,
				'Project.finance_category_id' => null
			)
		);
		$aProjectOptions = $this->Project->find('all', compact('conditions'));
		$aProjectOptions = Hash::combine($aProjectOptions, '{n}.Project.id', '{n}.Project.title');
		$this->set('aProjectOptions', $aProjectOptions);

		$conditions = array(
			'Project.group_id' => Hash::get($group, 'Group.id'),
			'NOT' => array(
				'Project.finance_category_id' => null
			)
		);

		//TODO ФИнансовый отчет
		$this->loadModel('Task');
		$this->loadModel('Subproject');
		$this->loadModel('FinanceOperation');
		$this->loadModel('CrmTask');
		$this->loadModel('InvestProject');
		//$this->loadModel('BillingPlan');
		//$this->loadModel('BillingSubscription');

		$projectsFull = $this->Project->find('all', compact('conditions'));
		$projectsFull = Hash::combine($projectsFull, '{n}.Project.id', '{n}');

		$conditions = array(
			'Subproject.project_id' => Hash::extract($projectsFull, '{n}.Project.id'),
		);
		$subprojectsFull = $this->Subproject->find('all',compact('conditions'));
		$subprojectsFull = Hash::combine($subprojectsFull, '{n}.Subproject.id', '{n}');

		$conditions = array(
			'Task.subproject_id' => Hash::extract($subprojectsFull, '{n}.Subproject.id'),
		);
		$taskFull = $this->Task->find('all',compact('conditions'));
		$taskFull = Hash::combine($taskFull, '{n}.Task.id', '{n}');

//		$conditions = array(
//			'FinanceOperation.account_id' => Hash::extract($taskFull, '{n}.Task.id'),
//		);
//		$finOperationFull = $this->FinanceOperation->find('all',compact('conditions'));
//		$finOperationFull = Hash::combine($finOperationFull, '{n}.FinanceOperation.id', '{n}');

		$conditions = array(
			'CrmTask.task_id' => Hash::extract($taskFull, '{n}.Task.id'),
		);
		$crmTaskFull = $this->CrmTask->find('all',compact('conditions'));
		$crmTaskFull = Hash::combine($crmTaskFull, '{n}.CrmTask.task_id', '{n}');

//		$conditions = array(
//			'FinanceAccount.project_id' => $id,
//		);
		//$financeAccountFull = $this->FinanceAccount->find('all',compact('conditions'));
		//$financeAccountFull = Hash::combine($financeAccountFull, '{n}.FinanceAccount.id', '{n}');

		//echo strtotime('+1 month', strtotime($fromMonth));

		foreach($taskFull as $key=>$item) {
			$taskFull[$key]['Task']['fullExpense_m1'] = $this->FinanceAccount->fullExpense($item['CrmTask']['account_id'],strtotime($fromMonth), null, strtotime('+1 month', strtotime($fromMonth)));
			$taskFull[$key]['Task']['fullIncome_m1'] = $this->FinanceAccount->fullIncome($item['CrmTask']['account_id'], strtotime($fromMonth), null, strtotime('+1 month', strtotime($fromMonth)));

			$taskFull[$key]['Task']['fullExpense_m2'] = $this->FinanceAccount->fullExpense($item['CrmTask']['account_id'],strtotime('+1 month', strtotime($fromMonth)), null, strtotime('+2 month', strtotime($fromMonth)));
			$taskFull[$key]['Task']['fullIncome_m2'] = $this->FinanceAccount->fullIncome($item['CrmTask']['account_id'], strtotime('+1 month', strtotime($fromMonth)), null, strtotime('+2 month', strtotime($fromMonth)));
		}


		$transactions = Braintree_Transaction::search([
			Braintree_TransactionSearch::customerId()->is('konstruktor-'.$this->currUser['User']['id']),
		]);

		$transact = array();
		$transact['month1'] = 0;
		$transact['month2'] = 0;

		foreach($transactions as $transaction) {
			$trans_stamp = $transaction->updatedAt->getTimestamp();
			if($trans_stamp >= strtotime($fromMonth) && $trans_stamp < strtotime('+1 month', strtotime($fromMonth))) {
				$transact['month1']+= $transaction->amount;
			}
			if($trans_stamp >= strtotime('+1 month', strtotime($fromMonth)) && $trans_stamp < strtotime('+2 month', strtotime($fromMonth))) {
				$transact['month2']+= $transaction->amount;
			}
			//echo $transaction->amount.' '.$transaction->currencyIsoCode." - ".$transaction->updatedAt->format('Y-m-d H:i:s');

		}
		$investProject = $this->InvestProject->findByGroupId($group['Group']['id']);

		$this->set(compact('investProject','transact', 'projectsFull','subprojectsFull', 'taskFull', 'crmTaskFull'));


	}

	/**
	 * New Budget
	 * @param $id - Project id
	 */
	public function addBudget($id, $accountId) {
		try {
			if (!$this->request->is('post')) {
				$this->loadModel('FinanceAccount');
				$accounts = $this->FinanceAccount->search((int) $id);
				$this->loadModel('FinanceCategory');
				$categories = $this->FinanceCategory->search((int) $id);
				$this->set(compact('id', 'accountId') + $accounts + $categories);
				return;
			}
			$this->loadModel('FinanceBudget');
			$this->FinanceBudget->addBudget($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Rendering chart without layout
	 * @param $id - Project id
	 */
	public function chart($id) {
		$this->index($id);
		$this->layout = null;
	}
}
