<?php
App::uses('AppController', 'Controller');
class AuthController extends AppController {
	public $name = 'Auth';

	public function index($id) {
		if (TEST_ENV) {
			$this->Session->write('currUser.id', $id);
		}
		$this->redirect('/');
	}
}
