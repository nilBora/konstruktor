<?php
App::uses('AppController', 'Controller');
class SiteController extends AppController {
	public $name = 'Site';
	
	public function _beforeInit() {
		// $this->components = array_merge(array('Table.PCTableGrid'), $this->components);
	    $this->helpers = array_merge(array('Html', 'Form', 'Core.PHTime', 'Media'), $this->helpers);
	    // $this->uses = array_merge(array('Settings', 'Media.Media'), $this->uses);
	}
	
	/*
	public function _afterInit() {
		// $this->Settings->initData();
	}
	
	public function beforeFilter() {
	}
	
	*/
	/*
	public function isAuthorized($user) {
		return true;
	}
	*/
	/*
	public function beforeFilter() {
		$this->Auth->allow(array('index', 'view', 'register'));
		$this->_checkAuth();
	}
	*/
	/*
	public function beforeRender() {
		parent::beforeRender();
		$this->set('balance', '0');
		$this->set('PU_', '$');
		$this->set('_PU', '');
		
		$this->set('currUser', $this->currUser);
		$this->set('currUserID', $this->currUserID);
		
		$this->set('pageTitle', $this->pageTitle);
	}
	*/
}
