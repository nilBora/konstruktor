<?php
App::uses('AppController', 'Controller');
class DashboardController extends AppController {
	//public $name = 'Admin';
	//public $layout = 'admin';
	// public $components = array();
	public $uses = array();

	protected $aNavBar = array(), $aBottomLinks = array(), $currMenu = '', $currLink = '';

	public function _beforeInit() {
	}

	public function beforeFilter() {
	    parent::beforeFilter();
		//$this->currMenu = $this->_getCurrMenu();
	    //$this->currLink = $this->currMenu;
	}

	public function beforeRender() {
		//$this->set('aNavBar', $this->aNavBar);
		//$this->set('currMenu', $this->currMenu);
		//$this->set('aBottomLinks', $this->aBottomLinks);
		//$this->set('currLink', $this->currLink);
		//$this->set('pageTitle', $this->pageTitle);
		//$this->set('isAdmin', $this->isAdmin());
	}

	public function isAdmin() {
		return AuthComponent::user('id') == 1;
	}

	public function admin_index() {
		$this->loadModel('User');
		$this->loadModel('Country');

		$fields = array('User.live_country', 'COUNT(*) AS count');
		$order = array('User.live_country');
		$group = array('User.live_country');
		$aStats = $this->User->find('all', compact('fields', 'conditions', 'order', 'group'));

		$conditions = $this->User->dateTimeRange('User.created', date('Y-m-d H:i:s', time() - DAY), date('Y-m-d H:i:s'));
		$aStatsToday = $this->User->find('all', compact('fields', 'conditions', 'order', 'group'));

		$aCountryOptions = $this->Country->options();
		$this->set(compact('aStats', 'aStatsToday', 'aCountryOptions'));
	}

	protected function _getCurrMenu() {
		$curr_menu = strtolower(str_ireplace('Admin', '', $this->request->controller)); // By default curr.menu is the same as controller name
		foreach($this->aNavBar as $currMenu => $item) {
			if (isset($item['submenu'])) {
				foreach($item['submenu'] as $_currMenu => $_item) {
					if (strtolower($_currMenu) === $curr_menu) {
						return $currMenu;
					}
				}
			}
		}
		return $curr_menu;
	}

	public function delete($id) {
		$this->autoRender = false;

		$model = $this->request->query('model');
		if ($model) {
			$this->loadModel($model);
			if (strpos($model, '.') !== false) {
				list($plugin, $model) = explode('.',$model);
			}
			$this->{$model}->delete($id);
		}
		if ($backURL = $this->request->query('backURL')) {
			$this->redirect($backURL);
			return;
		}
		$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
	}

}
