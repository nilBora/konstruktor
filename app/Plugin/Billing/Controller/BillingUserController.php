<?php
App::uses('BillingAppController', 'Billing.Controller');

class BillingUserController extends BillingAppController {

	public $uses = array('User', 'Billing.BillingSubscription', 'Billing.BillingGroup', 'Billing.BillingPlan');

	public function subscriptions(){
		/*
		//test for members operate after subscription change
		$this->BillingSubscription->Behaviors->load('Billing.Limitable', array(
			'remoteModel' => 'GroupLimit',
			'remoteField' => 'members_limit',
			'scope' => 'owner_id',
		));
		$this->BillingSubscription->membersOperate(71);
		exit();
		*/
		$this->layout = 'profile_new';
		$this->BillingSubscription->recursive = -1;
		$subscriptions = $this->BillingSubscription->find('all', array(
			'conditions' => array(
				'BillingSubscription.user_id' => $this->currUser['User']['id'],
				'BillingSubscription.active' => true,
			)
		));
		//maybe buggy
		foreach($subscriptions as $key=>$subscription){
			if(isset($subscription['BraintreeSubscription']->status) && $subscription['BraintreeSubscription']->status == 'Canceled'){
				unset($subscriptions[$key]);
				$sameGroupCount = Hash::extract($subscriptions, "{n}.BillingSubscription[group_id=".$subscription['BillingSubscription']['group_id']."]");
				if(count($sameGroupCount) > 0){
					$this->BillingSubscription->cancel($subscription['BillingSubscription']['id']);
				}
			} else {
				$this->BillingGroup->recursive = -1;
				$this->BillingGroup->unbindTranslations();
				$subscriptions[$key] = Hash::merge($subscriptions[$key], $this->BillingGroup->find('first', array(
					'conditions' => array('BillingGroup.id' => $subscription['BillingSubscription']['group_id']),
					'callbacks' => false
				)));
				$this->BillingPlan->recursive = -1;
				$this->BillingPlan->unbindTranslations();
				$billingPlans = $this->BillingPlan->find('first', array(
					'conditions' => array('BillingPlan.id' => $subscription['BillingSubscription']['plan_id']),
				));
				unset($billingPlans['BraintreePlan']);
				$subscriptions[$key] = Hash::merge($subscriptions[$key], $billingPlans);
			}
		}

		$this->BillingPlan->recursive = 0;
		$plans = $this->BillingPlan->find('all');
		foreach($plans as $plan){
			$subscribedInGroup = Hash::extract($subscriptions, "{n}.BillingSubscription[group_id=".$plan['BillingPlan']['group_id']."]");
			if(($plan['BillingPlan']['free'] == true)&&empty($subscribedInGroup)){
				$subscriptions[] = Hash::merge(
					array('BillingSubscription' => array(
						'group_id' => $plan['BillingPlan']['group_id'],
						'plan_id' => $plan['BillingPlan']['id'],
						'user_id' => $this->currUser['User']['id'],
						'remote_subscription_id' => null,
						'remote_plan_id' => null,
						'limit_value' => $plan['BillingPlan']['limit_value'],
						'active' => true,
						'status' => 'Active',
						'expires' => null,
						'created' => $this->currUser['User']['created'],
						'modified' => $this->currUser['User']['modified'],
					)),
					$plan
				);
			}
		}
		$subscriptions = Hash::sort($subscriptions, '{n}.BillingSubscription.group_id', 'asc');

		$transactions = Braintree_Transaction::search([
			Braintree_TransactionSearch::customerId()->is('konstruktor-'.$this->currUser['User']['id']),
		]);

		$this->set(compact('subscriptions', 'transactions', 'plans'));
	}

	public function payment(){
		$this->request->allowMethod('post');
		if(!isset($this->request->data['amount'])||empty($this->request->data['amount'])){
			$this->redirect($this->referer());
		}

		$firstName = $lastName = '';
		$name = explode(' ', $this->currUser['User']['full_name']);
		if(count($name) > 0){
			$firstName = array_shift($name);
			$lastName = implode(' ', $name);
		}
		$customerData = array(
			'firstName' => $firstName,
			'lastName' => $lastName,
			'email' => $this->currUser['User']['username'],
			'phone' => $this->currUser['User']['phone'],
		);
		try {
			$customer = Braintree_Customer::find('konstruktor-'.$this->currUser['User']['id']);
			$customer = Braintree_Customer::update('konstruktor-'.$this->currUser['User']['id'], $customerData);
		} catch(Exception $e) {
			$customer = Braintree_Customer::create(Hash::merge(array('id' => 'konstruktor-'.$this->currUser['User']['id']), $customerData));
		}
		if($customer->success){
			$customer = $customer->customer;
		} else {
			throw new NotFoundException(__d('billing', 'Invalid billing group'));
		}
		$this->Session->write('Billing', array(
			'amount' => $this->request->data['amount'],
		));
		$this->layout = 'profile_new';
		$clientToken = Braintree_ClientToken::generate();
        $this->set('clientToken', $clientToken);
		$this->set('customer', $customer);
	}

	public function checkout(){
		$this->layout = 'profile_new';
		if (!$this->request->is('post')) {
			throw new NotFoundException(__d('billing', 'Incorrect request type'));
		}
		$amount = $this->Session->read('Billing.amount');

		$customer = Braintree_Customer::find('konstruktor-'.$this->currUser['User']['id']);
		if(isset($this->request->data['payment_method_nonce'])){
			$nonceFromTheClient = $this->request->data['payment_method_nonce'];
			$payment = Braintree_PaymentMethod::create([
				'customerId' => 'konstruktor-'.$this->currUser['User']['id'],
				'paymentMethodNonce' => $nonceFromTheClient
			]);
			if(!$payment->success){
				$this->Session->setFlash($payment->message);
				$this->redirect(array('action' => 'payment'));
			}
			$payment = $payment->paymentMethod;
		} elseif(isset($this->request->data['payment_method'])
			&&!empty($this->request->data['payment_method'])) {
			$payment = null;
			foreach($customer->paymentMethods as $payment){
				if($payment->token == $this->request->data['payment_method']){
					break;
				}
			}
			if(empty($payment)){
				throw new NotFoundException(__d('billing', 'Payment method not found'));
			}
		} else {
			throw new NotFoundException(__d('billing', 'Unable to create subscription'));
		}
		$result = Braintree_Transaction::sale(array(
		    'paymentMethodToken' => $payment->token,
		    'amount' => $amount,
			'options' => array(
				'submitForSettlement' => true
			),
		));
		if($result->success){
			$result = $result->transaction;
			$this->User->id = $this->currUser['User']['id'];
			$balance = $this->User->field('balance') + $amount;
			$userResult = $this->User->save(array(
				'id' => $this->currUser['User']['id'],
				'balance' => $balance
			));
			//if(!$userResult){
				//maybe support notification here
			//}
		}
		$this->redirect(array('plugin' => false, 'controller' => 'User', 'action' => 'view'));
	}

}
