<?php
use Firebase\JWT\JWT;

App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

class MyTimeAppController extends PAjaxController {

	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = 'ajax';

		JWT::$leeway = 5; // $leeway in seconds
		try {
			$token = JWT::decode($this->request->header('Server-Token'), Configure::read('Autobahn.key'), array('HS256'));
		} catch (Exception $e) {
			throw new ForbiddenException('Could not auth your request');
		}
		$this->loadModel('User');
		if(!isset($token->userId)||!$this->User->exists($token->userId)){
			throw new ForbiddenException('Could not auth your request or user does not exists');
		}

		$this->currUserID = $token->userId;
		$this->currUser = $this->User->findById($token->userId);
		$this->Auth->login($this->currUser['User']);
		if ($this->Auth->loggedIn()) {
			$this->_initTimezone($this->currUser['User']['timezone']);
			$this->_initLang($this->currUser['User']['lang']);
			$this->Auth->allow('*');
		}
	}

}
