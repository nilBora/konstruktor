<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

class JsController extends PAjaxController {

    public $name = 'Js';

    public function beforeFilter() {
        parent::beforeFilter();
		//$this->Auth->allow(array('settings'));
    }

	public function settings() {
		$this->response->type(array('type' => 'text/javascript'));

		$this->loadModel('User');
        //TODO: Maybe not needed anymore notifications for profile incompleteness
		//$this->set('notifyProfile', !$this->User->checkData($this->currUserID));
	}
}
