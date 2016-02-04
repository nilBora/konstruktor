<?php
App::uses('Component', 'Controller');

class BraintreeCustomerComponent extends Component {

	public function startup(Controller $controller) {
		$this->_controller = $controller;
	}

	public function check($customer){
		if(empty($customer)){
			$customer = $this->_controller->currUser;
		}
		$firstName = $lastName = '';
		$name = explode(' ', $customer['User']['full_name']);
		if(count($name) > 0){
			$firstName = array_shift($name);
			$lastName = implode(' ', $name);
		}
		$customerData = array(
			'firstName' => $firstName,
			'lastName' => $lastName,
			'email' => $customer['User']['username'],
			'phone' => $customer['User']['phone'],
		);
		try {
			$_customer = Braintree_Customer::find('konstruktor-'.$customer['User']['id']);
			$_customer = Braintree_Customer::update('konstruktor-'.$customer['User']['id'], $customerData);
		} catch(Exception $e) {
			$_customer = Braintree_Customer::create(Hash::merge(array('id' => 'konstruktor-'.$customer['User']['id']), $customerData));
		}
		if($_customer->success){
			return $_customer->customer;
		}
		return array();
	}

}
