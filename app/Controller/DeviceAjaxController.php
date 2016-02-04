<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class DeviceAjaxController extends PAjaxController {
	public $name = 'DeviceAjax';
	public $uses = array('ProductType', 'User', 'OrderProduct');
	public $helpers = array('Media');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();

		$this->set('balance', $this->User->getBalance($this->currUserID));
		$this->set('PU_', '$');
		$this->set('_PU', '');
	}

	public function jsSettings() {
	}

	public function deviceList() {
		$aDevices = $this->ProductType->find('all');
		$this->set('aDevices', $aDevices);
	}

	public function panel() {
		$this->deviceList();
	}

	public function findUser() {
		$this->set('user', $this->User->findByUsername($this->request->data('email')));
	}

	public function distrib() {
		try {
			$order_id = $this->request->data('order_id');
			$user_id = $this->request->data('user_id');
			if (!$order_id) {
				throw new Exception(__('Incorrect request'));
			}

			$products = $this->request->data('products');
			// $products = (is_null($products)) ? array() : $products;
			if ($products && !is_array($products)) {
				throw new Exception(__('Incorrect request'));
			}

			if ($products) {
				foreach($products as $product_id) {
					$fields = compact('user_id');
					if ($user_id) {
						$fields['distrib_date'] = '"'.date('Y-m-d H:i:s').'"';
					}
					$this->OrderProduct->updateAll(
						$fields,
						compact('order_id', 'product_id')
					);
				}
			}

			$products = $this->OrderProduct->getByTypes($order_id, false);
			$distributed = $this->OrderProduct->getByTypes($order_id, true);

			$aID = Hash::extract($distributed, '{n}.{n}.OrderProduct.user_id');
			$users = $this->User->getUsers($aID);

			$this->setResponse(compact('products', 'distributed', 'users'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	public function block() {
		try {
			$order_id = $this->request->data('order_id');
			if (!$order_id) {
				throw new Exception(__('Incorrect request'));
			}

			$products = $this->request->data('products');
			if ($products && !is_array($products)) {
				throw new Exception(__('Incorrect request'));
			}

			$blocked = $this->request->data('blocked');
			if ($products) {
				foreach($products as $product_id) {
					$this->OrderProduct->updateAll(
						compact('blocked'),
						compact('order_id', 'product_id')
					);
				}
			}

			$products = $this->OrderProduct->getByTypes($order_id, false);
			$distributed = $this->OrderProduct->getByTypes($order_id, true);

			$aID = Hash::extract($distributed, '{n}.{n}.OrderProduct.user_id');
			$users = $this->User->getUsers($aID);

			$this->setResponse(compact('products', 'distributed', 'users'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
}
