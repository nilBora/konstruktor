<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

/**
 * Class FinanceAccountController
 * @property FinanceOperation FinanceOperation
 * @property FinanceAccount FinanceAccount
 */
class FinanceAccountController extends PAjaxController {

	public $name = 'FinanceAccount';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	/**
	 * Account list for project
	 * @param $id - Project id
	 */
	public function getList($id) {
		$q = $this->request->data('q');
		$result = $this->FinanceAccount->search((int) $id, $q);
		$this->set($result + compact('id'));
	}

	/**
	 * New Account
	 * @param $id - Project id
	 */
	public function addAccount($id) {
		try {
			if (!$this->request->is('post')) {
				$this->set('id', $id);
				return;
			}
			$this->loadModel('FinanceAccount');
			$this->FinanceAccount->addAccount($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Edit Account
	 * @param $id - Account id
	 */
	public function editAccount($id) {
		try {
			if (!$this->request->is('post')) {
				$account = $this->FinanceAccount->getOne((int) $id);
				$this->set($account + compact('id'));
				return;
			}
			$this->FinanceAccount->editAccount((int) $id, $this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Delete Account
	 * @throws Exception
	 */
	public function delAccount() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->FinanceAccount->deleteAccount($id);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}
}