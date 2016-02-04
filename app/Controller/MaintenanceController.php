<?php
App::uses('AppController', 'Controller');

class MaintenanceController extends AppController {

	public $name = 'Maintenance';
    public $layout = 'home';
	public $uses = array('Lang');

	public function beforeFilter(){
		$this->Auth->allow(array('index'));
		parent::beforeFilter();
	}

	public function index(){
		Configure::write('Config.language', $this->Lang->detect());
		$this->set('title_for_layout', __('Komstruktor: Maintenance'));
		$this->render('../User/login');
	}
}
