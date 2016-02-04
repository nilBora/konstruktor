<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceReportController
 * @property FinanceProject FinanceProject
 * @property FinanceCategory FinanceCategory
 * @property FinanceAccount FinanceAccount
 */
class FinanceReportController extends FinanceController {
	public $name = 'FinanceReport';
	public $layout = 'profile_new';

	/**
	 * Provides statistic reports
	 * @param $id - project id
	 */
	public function index($id) {
		$this->loadModel('FinanceAccount');
		$accounts = $this->FinanceAccount->search((int)$id);
		$this->loadModel('FinanceProject');
		$project = $this->FinanceProject->getProject((int)$id, true);
		$this->loadModel('FinanceCategory');

		$month1 = $this->request->query('month1');
		$month2 = $this->request->query('month2');
		$accountId = $this->request->query('accountId');

		if (!$accountId && !empty($accounts['aFinanceAccount'])) {
			$currAccount = current($accounts['aFinanceAccount']);
			$accountId = $currAccount['FinanceAccount']['id'];
		}
		$currency = @$accounts['aFinanceAccount'][$accountId]['FinanceAccount']['currency'];
		if (!$month1) {
			$month1 = date('Y-m', strtotime('-1 month'));
		}
		if (!$month2) {
			$month2 = date('Y-m');
		}

		$group = $this->Group->findByFinanceProjectId($project['aProject']['FinanceProject']['id']);
		$this->set('group', $group);

		$report = $this->FinanceCategory->getReport((int) $id, $accountId, $month1, $month2);
		$this->set($report + $project + $accounts + compact('id', 'accountId', 'currency', 'month1', 'month2'));
	}
}
