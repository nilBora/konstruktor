<?php
App::uses('AppController', 'Controller');
class GroupLimitsController extends AppController {

    public $uses = array('StorageLimit');
    public $layout = 'profile_new';

    public function buyMoreMembers() {
		$this->redirect(array(
			'plugin' => 'billing',
			'controller' => 'billing_subscriptions',
			'action' => 'plans',
			'members'
		));
    }

}
