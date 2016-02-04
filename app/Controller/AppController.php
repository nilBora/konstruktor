<?php

App::uses('Controller', 'Controller');

/**
 * Class AppController
 * @property Statistic Statistic
 */

class AppController extends Controller {
	public $paginate;
	public $pageTitle = '';

	public $uses = array(
		'User',
		'Media.Media',
		'Group',
		'GroupMember',
		'Lang'
	);

	public $helpers = array(
		'Html',
		'Form',
		'Flash',
		'Avatar',
		'Core.PHTime',
		'Media',
		'LocalDate',
		'AssetCompress.AssetCompress'
	);

	public $components = array(
		'Session',
		'Paginator',
		'Cookie',
		'Auth' => array(
			'authenticate' => array(
				'Authenticate.Cookie' => array(
					'fields' => array(
						'username' => 'username',
						'password' => 'password'
					),
					'userModel' => 'User',
					'crypt' => 'rijndael',
				),
				'Form' => array(
					'fields' => array(
						'username' => 'username',
						'password' => 'password'
					),
					'userModel' => 'User',
				)
			),
			'authorize'      => array('Controller'),
			'loginAction'    => array('plugin' => '', 'controller' => 'User', 'action' => 'login'),
			'loginRedirect'  => array('plugin' => '', 'controller' => 'User', 'action' => 'index'),
			'logoutRedirect' => '/'
		),
		'Maintenance' => array(
			'maintenanceUrl' => array(
				'plugin' => '',
				'controller' => 'maintenance',
				'action' => 'index'
			),
			'allowedAction' => array(
				'user' => array('login'),
				'maintenance' => array('index')
			)
		),
		'Tools',
		'DebugKit.Toolbar',
	);

	protected $currUser = array(), $currUserID = false, $profile, $user;

	public function __construct($request = null, $response = null) {
		$this->_beforeInit();
		parent::__construct($request, $response);
		$this->_afterInit();
	}

	protected function _beforeInit() {
	    // Add here components, models, helpers etc that will be also loaded while extending child class
	}

	protected function _afterInit() {

	}
	/* Перестает работать загрузка моделей из uses :(
	public function loadModel($models) {
		if (!is_array($models)) {
			$models = array($models);
		}
		foreach($models as $model) {
			parent::loadModel($model);
		}
	}
	*/

	public function isAuthorized($user) {
		if(isset($this->request->params['prefix'])&&($this->request->params['prefix'] == 'admin')){
			if($user['is_admin'] == 1){
				return true;
			}
			return false;
		};
		return true;
	}

	public function beforeFilter() {
		$this->Maintenance->checkMaintenance();

		$this->Cookie->name = APP_NAME;
		$this->Cookie->type('rijndael');

		if(isset($this->request->params['prefix'])&&($this->request->params['prefix'] == 'admin')){
			$this->layout = 'admin';
			$this->Auth->authenticate = Hash::insert($this->Auth->authenticate, "{s}.scope", array('User.is_admin' => 1));
			$this->Auth->loginAction = array('prefix' => 'admin', 'plugin' => '', 'controller' => 'user', 'action' => 'login');
			$this->Auth->loginRedirect = array('prefix' => 'admin', 'plugin' => '', 'controller' => 'dashboard', 'action' => 'index');
			$this->Auth->logoutRedirect = '/admin';
			$this->Auth->flash = array(
				'element' => 'alert',
				'key' => 'auth',
				'params' => array(
					'plugin' => 'BoostCake',
					'class' => 'alert-error'
				)
			);
			$this->helpers['Html'] = array('className' => 'BoostCake.BoostCakeHtml');
			$this->helpers['Form'] = array('className' => 'BoostCake.BoostCakeForm');
			$this->helpers['Paginator'] = array('className' => 'BoostCake.BoostCakePaginator');

		}

		$this->_checkAuth();

        if(!$this->Auth->loggedIn() && !in_array($this->request->params['action'], array('register', 'login', 'fbAuth', 'fbAuthCheck', 'confirm', 'forgetPassword', 'passwordRequest', 'ipnPaypal', ))){
            $this->layout = 'unregistered';
            $this->set('currUserID', $this->currUserID);
            $this->set('currUser', $this->currUser);
        }
        $this->Auth->authError = __('You must log in to access this page');
        $this->Auth->allow(array('register', 'login', 'fbAuth', 'fbAuthCheck', 'fbAccessToken', 'confirm', 'forgetPassword', 'passwordRequest', 'ipnPaypal', 'error404', 'vacancies'));
//        $this->_checkAuth();
        $this->_updateUser();
    }

    protected function _initTimezone($timezone) {
        date_default_timezone_set(($timezone) ? $timezone : 'UTC');
        $this->User->query('SET `time_zone`= "'.date('P').'"');
    }

    protected function _initLang($lang = NULL) {
        $default = $this->Lang->detect();
		if($lang === NULL) {
            Configure::write('Config.language', $default);
            return $default;
        }
		$Langs = $this->Lang->options();
        $lang = (isset($Langs[$lang])) ? $lang : $default;
        Configure::write('Config.language', $lang);
    }

    protected function _checkAuth() {
        /*
        if (!$this->Auth->loggedIn()) {
            return $this->redirect('/');
        }
        */
        //restore user session from cookie
        if (!$this->Auth->loggedIn()&&($userLogin = $this->Cookie->read('User'))) {
            $this->currUser = $this->User->findByUsername($userLogin['username']);
            if(isset($this->currUser['User']['id'])){
                $this->Auth->login($this->currUser['User']);
            }
        }
        if ($this->Auth->loggedIn()) {
            $this->loadModel('Group');

			$this->currUserID = $this->Auth->user('id');
			if(empty($this->currUser)){
				$this->currUser = $this->User->findById($this->currUserID);
			}
			$this->_initTimezone($this->currUser['User']['timezone']);
			$this->_initLang($this->currUser['User']['lang']);
			$this->_addStatistic();

			$conditions = array(
				'Group.owner_id' => $this->currUserID,
			);

            $this->userGroups = $this->Group->find('all', compact('conditions', 'order') );
            $groupDreamInfo= Hash::combine($this->userGroups, '{n}.Group.id', '{n}.Group.is_dream');
            $this->userGroups = Hash::combine($this->userGroups, '{n}.Group.id', '{n}.Group.title');
			$ids = Hash::combine($this->userGroups, '{n}.Group.id');
			$conditions = array(
					'GroupMember.approved' => '1',
					'GroupMember.is_deleted' => '0',
					'GroupMember.user_id' => $this->currUserID,
			);
			$groups = $this->userGroups;
			$member = $this->GroupMember->find('all', compact('conditions', 'order') );
            $groupDreamInfoMembers = Hash::combine($member, '{n}.Group.id', '{n}.Group.is_dream');

			$member = Hash::combine($member, '{n}.Group.id', '{n}.Group.title');
			$this->userGroups  = Hash::mergeDiff($member, $groups);
            $groupDreamInfo = Hash::mergeDiff($groupDreamInfo, $groupDreamInfoMembers);
			$this->userGroups['create'] = __('Create group');

			if ( isset($_COOKIE['Group']) ) {
				$this->set('groupHeader', $this->Group->findById($_COOKIE['Group']));
			}

			$conditions = array(
					'GroupMember.is_invited' => '1',
					'GroupMember.is_deleted' => '0',
					'GroupMember.user_id' => $this->currUserID
			);
			$ids = $this->GroupMember->find('all', compact('conditions'));
			$ids = Hash::extract($ids, '{n}.GroupMember.group_id');

			$conditions = array(
				'Group.id' => $ids,
			);
			$invites['Groups'] = $this->Group->find('all', compact('conditions', 'order') );

			$this->set('invites', $invites);
			$this->set('currUser', $this->currUser);
			$this->set('currUserID', $this->currUserID);
			$this->set('pageTitle', $this->pageTitle);
			$this->set('userGroups', $this->userGroups);
            $this->set(compact('groupDreamInfo'));
		} else {
            $this->_initLang();
        }
	}

	protected function _updateUser() {
		if($this->currUserID) {
			$date = new DateTime();
			$user = array('id' => $this->currUserID, 'last_update' => $date->format('Y-m-d H:i:s'));
			$this->User->save($user);
		}
	}

	private function _addStatistic() {
		$this->loadModel('Statistic');
		$userId = $this->currUserID ? $this->currUserID : 0;
		$this->Statistic->addData($userId, $this->request->params);
	}
}
