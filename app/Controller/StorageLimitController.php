<?php
App::uses('AppController', 'Controller');
class StorageLimitController extends AppController {

    public $uses = array('StorageLimit');
    public $layout = 'profile_new';
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function buyMoreSpace() {
		/*
        $result = array('success' => false);
        if($this->request->is('ajax')) {
            $space = $this->request->data('buy_storage');
            if(!empty($space) && is_numeric($space)) {
                $user_id = $this->Auth->user('id');
                if($this->StorageLimit->updateAll(
                    array(
                        "StorageLimit.storage_limit" => "StorageLimit.storage_limit + $space"
                    ),
                    array("StorageLimit.user_id" => $user_id))) {
                    $result['success'] = true;
                }
            }
            echo json_encode($result);
            die();
        }
		*/

		$this->redirect(array(
			'plugin' => 'billing',
			'controller' => 'billing_subscriptions',
			'action' => 'plans',
			'disc-space'
		));
    }

}
