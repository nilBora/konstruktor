<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceOperationController
 * @property FinanceOperation FinanceOperation
 * @property FinanceProject FinanceProject
 * @property FinanceAccount FinanceAccount
 * @property FinanceCategory FinanceCategory
 */
class FinanceOperationController extends FinanceController {

	const SIZE_PAGE = 15;

	public $name = 'FinanceOperation';
	public $layout = 'profile_new';
	public $components = array('RequestHandler');

	/**
	 * Provides operations by project id
	 * @param $id - project id
	 */
	public function index($id) {
		$page = 0;
		$accountId = $this->request->query('accountId');
		$categoryId = $this->request->query('categoryId');
		$from = $this->request->query('from');
		$to = $this->request->query('to');
		$this->loadModel('FinanceProject');
		$project = $this->FinanceProject->getProject((int)$id, true);
		$operations = $this->FinanceOperation->search((int)$id, $page, $pageSize = self::SIZE_PAGE, $accountId, $categoryId, $from, $to);
		$countPages = $this->FinanceOperation->countPages((int)$id, $pageSize = self::SIZE_PAGE, $accountId, $categoryId, $from, $to);
		$this->loadModel('FinanceAccount');
		$accounts = $this->FinanceAccount->search((int)$id);
		$this->loadModel('FinanceCategory');
		$categories = $this->FinanceCategory->search((int)$id);

		$group = $this->Group->findByFinanceProjectId($project['aProject']['FinanceProject']['id']);
		$this->set('group', $group);
		$this->set($project + $operations + $accounts + $categories + compact('id', 'pageSize', 'page', 'countPages', 'accountId', 'categoryId'));
	}

	/**
	 * Provides operations by project id
	 * @param $id - project id
	 */
	public function showMore($id, $page) {
		$this->layout = false;
		$accountId = $this->request->query('accountId');
		$categoryId = $this->request->query('categoryId');

		$operations = $this->FinanceOperation->search((int)$id, $page, self::SIZE_PAGE, $accountId, $categoryId);
		$this->loadModel('FinanceAccount');
		$accounts = $this->FinanceAccount->search((int)$id);
		$this->set($operations + $accounts);
	}

	/**
	 * New Operation
	 */
	public function addOperation() {
		try {
			$this->FinanceOperation->addOperation($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Delete Operation
	 * @throws Exception
	 */
	public function delOperation() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->loadModel('FinanceOperation');
			$this->FinanceOperation->deleteOperation($id);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Provides operations in json by project id
	 */
	public function chartData() {
		$projectId = $this->request->data('project_id');
		$currency = $this->request->data('currency');
		$period = $this->request->data('period');
		$data = $this->FinanceOperation->getBalance($projectId, $currency, $period);
		$this->set(compact('data'));
		$this->set('_serialize', array('data'));
	}

	/**
	 * @param $id Project id
	 */
	public function compareAccounts($id) {
		$month1 = $this->request->data('from');
		$month2 = $this->request->data('to');
		$data = $this->FinanceOperation->compareAccounts($id, $month1, $month2);
		$this->set(compact('data'));
		$this->set('_serialize', array('data'));
	}

	public function editOperation() {
		try {
			$this->FinanceOperation->editOperation($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}
}
