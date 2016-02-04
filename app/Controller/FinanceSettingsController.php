<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceSettingsController
 * @property FinanceOperation FinanceOperation
 * @property FinanceProject FinanceProject
 * @property FinanceAccount FinanceAccount
 * @property FinanceCategory FinanceCategory
 */
class FinanceSettingsController extends FinanceController {
	public $name = 'FinanceSettings';
	public $layout = 'profile_new';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	/**
	 * Project settings
	 * @param $id - project id
	 */
	public function index($id) {
		$this->loadModel('FinanceProject');
		$project = $this->FinanceProject->getProject((int) $id, true);
		$this->loadModel('FinanceCategory');
		$categories = $this->FinanceCategory->search((int) $id);
		$this->loadModel('FinanceAccount');
		$accounts = $this->FinanceAccount->search((int) $id);
		$this->set($project + $categories + $accounts + compact('id'));
		$group = $this->Group->findByFinanceProjectId($project['aProject']['FinanceProject']['id']);
		$this->set('group', $group);
	}
}
