<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

/**
 * Class FinanceCategoryController
 * @property FinanceCategory FinanceCategory
 */
class FinanceCategoryController extends PAjaxController {
	public $name = 'FinanceCategory';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	/**
	 * Category list for project
	 * @param $id - Project id
	 */
	public function getList($id) {
		$q = $this->request->data('q');
		$type = $this->request->data('type');
		$result = $this->FinanceCategory->search((int) $id, $type, $q);
		$this->set($result + compact('id'));
	}

	/**
	 * New Category
	 * @param $id - Project id
	 */
	public function addCategory($id) {
		try {
			if (!$this->request->is('post')) {
				$this->set('id', $id);
				return;
			}
			$this->loadModel('FinanceCategory');
			$this->FinanceCategory->addCategory((int) $id, $this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Edit Category
	 * @param $id - Category id
	 */
	public function editCategory($id) {
		try {
			if (!$this->request->is('post')) {
				$category = $this->FinanceCategory->getOne($id);
				$this->set($category + compact('id'));
				return;
			}
			$this->FinanceCategory->id = $id;
			$this->FinanceCategory->save($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Delete Category
	 * @throws Exception
	 */
	public function delCategory() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->FinanceCategory->deleteCategory($id);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Provides operations in json by project id
	 */
	public function getStatistic() {
		$projectId = $this->request->data('project_id');
		$currency = $this->request->data('currency');
		$period = $this->request->data('period');
		$from = $this->request->data('from') ? $this->request->data('from') : null;
		$to = $this->request->data('to') ? $this->request->data('to') : null;
		$data = $this->FinanceCategory->getStatistic($projectId, $currency, $period, $from, $to);
		$this->set(compact('data'));
		$this->set('_serialize', array('data'));
	}
}