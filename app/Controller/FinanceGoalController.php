<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceGoalController
 * @property FinanceOperation FinanceOperation
 * @property FinanceProject FinanceProject
 * @property FinanceGoal FinanceGoal
 * @property FinanceAccount FinanceAccount
 */
class FinanceGoalController extends FinanceController {

	public $name = 'FinanceGoal';
	public $layout = 'profile_new';
	public $components = array('RequestHandler');

	/**
	 * Provides goals by project id
	 * @param $id - project id
	 */
	public function index($id) {
		$this->loadModel('FinanceProject');
		$project = $this->FinanceProject->getProject((int)$id, true);
		$goals = $this->FinanceGoal->search((int) $id);
		$this->set($goals + $project + compact('id'));
		$group = $this->Group->findByFinanceProjectId($project['aProject']['FinanceProject']['id']);
		$this->set('group', $group);
	}

	/**
	 * New Goal
	 * @param $id - Project id
	 */
	public function addGoal($id) {
		try {
			if (!$this->request->is('post')) {
				$this->loadModel('FinanceAccount');
				$accounts = $this->FinanceAccount->search((int) $id);
				$this->set(compact('id') + $accounts);
				return;
			}
			$this->loadModel('FinanceGoal');
			$this->FinanceGoal->addGoal($this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Edit Goal
	 * @param $id - Goal id
	 */
	public function editGoal($id) {
		try {
			if (!$this->request->is('post')) {
				$goal = $this->FinanceGoal->getOne((int)$id);
				$projectId = $goal['aFinanceGoal']['FinanceGoal']['project_id'];
				$this->setShareData($projectId);
				$this->loadModel('FinanceAccount');
				$accounts = $this->FinanceAccount->search($projectId);
				$this->set($goal + $accounts + compact('id', 'projectId'));
				return;
			}

			$this->FinanceGoal->editGoal((int) $id, $this->request->data);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * Delete Goal
	 * @throws Exception
	 */
	public function delGoal() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->FinanceGoal->deleteGoal($id);
			exit;
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}
}
