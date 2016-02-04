<?php
App::uses('AppController', 'Controller');

/**
 * Class FinanceController
 * @property FinanceShare FinanceShare
 */
class FinanceController extends AppController {

	public $whenCalling = array(
		// project main menu
		array('controller' => 'FinanceProject', 'actions' => array('index')),
		array('controller' => 'FinanceOperation', 'actions' => array('index', 'showMore')),
		array('controller' => 'FinanceReport', 'actions' => array('index')),
		array('controller' => 'FinanceGoal', 'actions' => array('index', 'addGoal')),
		array('controller' => 'FinanceBudget', 'actions' => array('index', 'addBudget', 'chart')),
		// project top menu
		array('controller' => 'FinanceShare', 'actions' => array('index')),
		array('controller' => 'FinanceSettings', 'actions' => array('index')),
	);

	public $onlyOwnerAccess = array(
		array('controller' => 'FinanceSettings', 'actions' => '*'),
		array('controller' => 'FinanceProject', 'actions' => array('delProject')),
	);

	protected function _checkAuth() {
		parent::_checkAuth();
		$controller = $this->request->params['controller'];
		$action = $this->request->params['action'];
		foreach ($this->whenCalling as $item) {
			if ($controller == $item['controller'] && ($item['actions'] == '*' || in_array($action, $item['actions']))) {
				$this->setShareData($this->request->params['pass'][0]);
			}
		}
	}

	public function setShareData($projectId) {
		try {
			if ($this->_wasCalled) {
				return;
			}
			$this->_wasCalled = true;

			$this->loadModel('FinanceShare');
			$this->_isOwner = $this->FinanceShare->isProjectOwner($this->currUserID, (int)$projectId);
			$this->_currUserShare = $this->FinanceShare->findUserShare((int)$projectId, (int)$this->currUserID);
			// Not owner and not share user
			if (!$this->_isOwner && empty($this->_currUserShare)) {
				throw new Exception(__('Permission denied'));
			}
			// Denied rules for full access share type
			$controller = $this->request->params['controller'];
			$action = $this->request->params['action'];
			if (!$this->_isOwner) {
				foreach ($this->onlyOwnerAccess as $item) {
					if ($controller == $item['controller'] && ($item['actions'] == '*' || in_array($action,
								$item['actions']))
					) {
						throw new Exception(__('Permission denied'));
					}
				}
			}
			// view global variables assigned
			$this->set(array(
				'isOwner' => $this->_isOwner,
				'isFullAccess' => (!$this->_isOwner && $this->getShare('full_access')),
				'isPartAccess' => (!$this->_isOwner && !$this->getShare('full_access')),
				'currUserShare' => $this->_currUserShare,
				'getShare' => function ($name) {
					return $this->getShare($name);
				},
			));
			// for model access
			FinanceShare::$isOwner = $this->_isOwner;
			FinanceShare::$get = $this->_currUserShare;
			FinanceShare::$isInit = true;
		} catch (Exception $e) {
			return $this->redirect(array('controller' => 'Errors', 'action' => 'error404'));
		}
	}

	public function getShare($name) {
		if (empty($this->_currUserShare)) {
			return;
		}
		return $this->_currUserShare[$name];
	}

	private $_wasCalled = false;
	private $_isOwner;
	private $_currUserShare;
}