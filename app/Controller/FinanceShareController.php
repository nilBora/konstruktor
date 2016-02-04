<?php
App::uses('FinanceController', 'Controller');
App::uses('MediaHelper', 'View/Helper');

/**
 * Class FinanceShareController
 * @property FinanceAccount FinanceAccount
 * @property FinanceProject FinanceProject
 * @property FinanceOperation FinanceOperation
 * @property FinanceCategory FinanceCategory
 * @property FinanceBudget FinanceBudget
 * @property FinanceGoal FinanceGoal
 */
class FinanceShareController extends FinanceController {

	public $name = 'FinanceShare';
	public $layout = 'profile_new';
	public $components = array('RequestHandler');
	public $shareTypes = array('accounts', 'categories', 'operations', 'budgets', 'goals');


	/**
	 * Provides shared functionality
	 * @param $id - project id
	 */
	public function index($id) {
		$this->loadModel('FinanceProject');
		$this->loadModel('FinanceAccount');
		$this->loadModel('FinanceCategory');
		$this->loadModel('FinanceBudget');
		$this->loadModel('FinanceGoal');
		$user = $this->request->query('user');
		$accounts = $this->FinanceAccount->search((int)$id);
		$operations = array('income', 'expense', 'transfer');
		$categories = $this->FinanceCategory->search((int)$id);
		$budgets = $this->FinanceBudget->search((int)$id);
		$goals = $this->FinanceGoal->search((int)$id);
		$shareItems = array(
			'accounts' => $accounts['aFinanceAccount'],
			'operations' => $operations,
			'categories' => $categories['aFinanceCategory'],
			'budgets' => $budgets['aFinanceBudget'],
			'goals' => $goals['aFinanceGoal'],
		);
		$userShare = $user ? $this->FinanceShare->findUserShare((int) $id, (int) $user) : null;
		$project = $this->FinanceProject->getProject((int) $id, true);
		$users = $this->FinanceShare->userList((int) $id);
		$this->set($project + $users + compact('id', 'user', 'share', 'userShare', 'shareItems'));
		$group = $this->Group->findByFinanceProjectId($project['aProject']['FinanceProject']['id']);
		$this->set('group', $group);
	}

	public function settings($id) {
		$this->index($id);
	}

	public function searchUser() {
		try {
			$email = $this->request->data('email');
			if (!$email) {
				throw new Exception('Email is required');
			}
			$user = $this->FinanceShare->findUserByEmail($email);
			if (isset($user['User']['id']) && $user['User']['id'] === $this->currUserID) {
				$user = [];
			}
			if (isset($user['UserMedia'])) {
				$mediaHelper = new MediaHelper(new View());
				$user['UserMedia']['url_img'] = $mediaHelper->imageUrl($user['UserMedia'], 'thumb50x50');
			}
			$this->set(compact('user'));
			$this->set('_serialize', array('user'));
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function sendInvite() {
		try {
			$userId = $this->request->data('userId');
			$projectId = $this->request->data('projectId');
			if (!$userId) {
				throw new Exception('User id is required');
			}
			if (!$projectId) {
				throw new Exception('Project id is required');
			}
			$user = $this->FinanceShare->findUserById($userId);
			if (!isset($user['User']['id'])) {
				exit(0);
			}
			if ($this->FinanceShare->isProjectOwner($userId, $projectId)) {
				exit(__('This is user is project owner'));
			}
			$this->FinanceShare->sendInvite($projectId, $userId);

			exit($user['User']['id']);
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function acceptInvite($projectId) {
		try {
			$this->FinanceShare->acceptInvite($this->currUserID, $projectId);
			exit(0);
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function declineInvite($projectId) {
		try {
			$this->FinanceShare->declineInvite($this->currUserID, $projectId);
			exit(0);
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function deleteUser($projectId, $userId) {
		$this->FinanceShare->deleteUser($this->currUserID, $projectId, $userId);
		if ($userId == $this->currUserID && !$this->FinanceShare->isProjectOwner($userId, $projectId)) {
			return $this->redirect(array('controller' => 'Mytime'));
		}
		return $this->redirect(array('controller' => 'FinanceShare', 'action' => 'index', $projectId));
	}

	public function setFullAccess() {
		$userId = $this->request->data('userId');
		$projectId = $this->request->data('projectId');
		if (!$userId) {
			throw new Exception('User id is required');
		}
		if (!$projectId) {
			throw new Exception('Project id is required');
		}
		$this->FinanceShare->setFullAccess($projectId, $userId);
		exit;
	}

	public function unsetFullAccess() {
		$userId = $this->request->data('userId');
		$projectId = $this->request->data('projectId');
		if (!$userId) {
			throw new Exception('User id is required');
		}
		if (!$projectId) {
			throw new Exception('Project id is required');
		}
		$this->FinanceShare->unsetFullAccess($projectId, $userId);
		exit;
	}

	public function setShareItems() {
		// params
		$shareItems = $this->request->data('shareItems');
		$userId = $this->request->data('userId');
		$projectId = $this->request->data('projectId');
		$fullAccess = $this->request->data('full_access');
		// check required
		if (!$userId) {
			exit(__('User id is required'));
		}
		if (!$projectId) {
			exit(__('Project id is required'));
		}
		if ($this->FinanceShare->isProjectOwner($userId, $projectId)) {
			exit(__('This is user is project owner'));
		}
		try {
			$this->FinanceShare->sendInvite($projectId, $userId);
		} catch (Exception $e) {}
		foreach ($this->shareTypes as $type) {
			if (!empty($shareItems[$type])) {
				$this->FinanceShare->setShareItems($projectId, $userId, $type, $shareItems[$type]);
			}
		}
		if ($fullAccess) {
			$this->FinanceShare->setFullAccess($projectId, $userId);
		} else {
			$this->FinanceShare->unsetFullAccess($projectId, $userId);
		}
		exit(0);
	}
}
