<?php

/**
 * Class FinanceShare
 * @property User User
 * @property FinanceProject FinanceProject
 */
class FinanceShare extends AppModel {

	const STATE_INVITE = 0;
	const STATE_APPROVE = 1;
	const STATE_DECLINE = 2;

	public $useTable = 'finance_share';

	public static $isOwner;
	public static $get;
	public static $isInit = false;

	public static function isOwner() {
		return self::$isOwner;
	}

	public static function isFullAccess() {
		return (!self::$isOwner && self::$get['full_access']);
	}

	public static function isPartAccess() {
		return (!self::$isOwner && !self::$get['full_access']);
	}

	public function findUserByEmail($email) {
		$this->loadModel('User');
		$result = $this->User->find('first', array(
			'conditions' => array(
				'User.username' => $email,
			)
		));

		return $result;
	}

	public function findUserById($userId) {
		$this->loadModel('User');
		$result = $this->User->findById($userId);

		return $result;
	}

	public function isProjectOwner($userId, $projectId) {
		$this->loadModel('FinanceProject');
		$isOwner = $this->FinanceProject->find('count', array(
			'conditions' => array(
				'FinanceProject.id' => $projectId,
				'FinanceProject.user_id' => $userId,
			)
		));

		return $isOwner;
	}

	public function getProjectOwnerId($projectId) {
		$result = $this->FinanceProject->find('first', array(
			'conditions' => array(
				'FinanceProject.id' => $projectId
			),
		));
		if (empty($result)) {
			return;
		}
		return $result['FinanceProject']['user_id'];
	}

	public function sendInvite($projectId, $userId) {
		$isExist = $this->find('count', array(
			'conditions' => array(
				'FinanceShare.project_id' => $projectId,
				'FinanceShare.user_id' => $userId,
			)
		));
		if ($isExist) {
			throw new Exception(__('Username has already been invited to this project'));
		}
		$result = $this->save(array(
			'project_id' => $projectId,
			'user_id' => $userId,
			'state' => self::STATE_INVITE,
		));

		return $result;
	}

	public function getSharedProjects($userId) {
		$share = $this->find('list', array(
			'conditions' => array(
				'FinanceShare.user_id' => $userId,
				'FinanceShare.state' => array(self::STATE_APPROVE, self::STATE_INVITE),
			),
			'fields' => array(
				'FinanceShare.project_id',
				'FinanceShare.state'
			),
		));

		return $share;
	}

	public function acceptInvite($userId, $projectId) {
		$conditions = array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
			'FinanceShare.state' => self::STATE_INVITE,
		);
		$isExist = $this->find('count', array('conditions' => $conditions));
		if (!$isExist) {
			throw new Exception(__('Invitation was deleted'));
		}
		$this->updateAll(array('FinanceShare.state' => self::STATE_APPROVE), $conditions);
	}

	public function declineInvite($userId, $projectId) {
		$conditions = array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
			'FinanceShare.state' => self::STATE_INVITE,
		);
		$isExist = $this->find('count', array('conditions' => $conditions));
		if (!$isExist) {
			throw new Exception(__('Invitation was deleted'));
		}
		$this->updateAll(array('FinanceShare.state' => self::STATE_DECLINE), $conditions);
	}

	public function userList($projectId) {
		$userIds = $this->find('list', array(
			'conditions' => array(
				'FinanceShare.project_id' => $projectId,
			),
			'fields' => array(
				'FinanceShare.user_id',
				'FinanceShare.state',
			),
		));
		$this->loadModel('User');
		$result = $this->User->find('all', array(
			'conditions' => array(
				'User.id' => array_keys($userIds),
			)
		));
		foreach ($result as &$user) {
			$user['User']['shareState'] = $userIds[$user['User']['id']];
		}

		return array('aUsers' => $result);
	}

	public function deleteUser($ownerId, $projectId, $userId) {
		$this->loadModel('FinanceProject');
		$isExist = $this->FinanceProject->find('count', array(
			'conditions' => array(
				'FinanceProject.user_id' => $ownerId,
			)
		));
		if (!$isExist) {
			throw new Exception(__('Project not found'));
		}
		$this->deleteAll(array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
		));
	}

	public function findUserShare($projectId, $userId) {
		$result = $this->find('first', array(
			'conditions' => array(
				'FinanceShare.project_id' => $projectId,
				'FinanceShare.user_id' => $userId,
			),
		));
		if (!empty($result)) {
			$result = $result['FinanceShare'];
			$result['accounts'] = $result['accounts'] ? explode(',', $result['accounts']) : array();
			$result['goals'] = $result['goals'] ? explode(',', $result['goals']) : array();
			$result['budgets'] = $result['budgets'] ? explode(',', $result['budgets']) : array();
			$result['categories'] = $result['categories'] ? explode(',', $result['categories']) : array();
			$result['operations'] = $result['operations'] ? explode(',', $result['operations']) : array();
		}

		return $result;
	}

	public function setFullAccess($projectId, $userId) {
		$conditions = array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
		);
		$this->updateAll(array('FinanceShare.full_access' => 1), $conditions);
	}

	public function unsetFullAccess($projectId, $userId) {
		$conditions = array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
		);
		$this->updateAll(array('FinanceShare.full_access' => 0), $conditions);
	}

	public function setShareItems($projectId, $userId, $type, $items) {
		$conditions = array(
			'FinanceShare.project_id' => $projectId,
			'FinanceShare.user_id' => $userId,
		);
		if (!is_array($items)) {
			$items = '';
		} else {
			$items = implode(',', $items);
		}
		$this->updateAll(array('FinanceShare.' . $type => "'" . $items . "'"), $conditions);
	}
}