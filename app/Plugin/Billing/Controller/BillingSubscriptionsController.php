<?php
App::uses('BillingAppController', 'Billing.Controller');
/**
 * BillingSubscriptions Controller
 *
 * @property BillingSubscription $BillingSubscription
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BillingSubscriptionsController extends BillingAppController {

	public $components = array('BraintreePayments.BraintreeCustomer');

	public $uses = array(
		'Billing.BillingSubscription',
		'Billing.BillingGroup',
		'Billing.BillingPlan',
		'Billing.BillingCustomer',
	);

	public $paginate = array(
		'order' => array('BillingSubscription.modified' => 'desc')
	);

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->BillingSubscription->recursive = 0;
		$this->Paginator->settings = $this->paginate;
		$this->set('billingSubscriptions', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->BillingSubscription->exists($id)) {
			throw new NotFoundException(__d('billing', 'Invalid billing subscription'));
		}
		$options = array('conditions' => array('BillingSubscription.' . $this->BillingSubscription->primaryKey => $id));
		$this->set('billingSubscription', $this->BillingSubscription->find('first', $options));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_cancel($id = null) {
		$this->BillingSubscription->id = $id;
		if (!$this->BillingSubscription->exists()) {
			throw new NotFoundException(__d('billing', 'Invalid billing plan'));
		}
		$this->request->allowMethod('post', 'delete');

		//canceling sibscribtion
		$options = array('conditions' => array('BillingSubscription.' . $this->BillingSubscription->primaryKey => $id));
		$subscription = $this->BillingSubscription->find('first', $options);
		$result = Braintree_Subscription::cancel($subscription['BillingSubscription']['remote_subscription_id']);
		if ($result->success) {
			$data = Hash::merge($subscription['BillingSubscription'], array(
				'active' => false,
				'status' => $result->subscription->status,
				'modified' => $result->subscription->updatedAt->format('Y-m-d H:i:s'),
			));
			$this->BillingSubscription->save($data);
			$this->Session->setFlash(__d('billing', 'The subscription has been canceled.'));
		} else {
			$this->Session->setFlash(__d('billing', 'The subscription could not be canceled. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function plans($group = null){
		$this->Session->write('Billing.plan', '');
		$this->BillingGroup->recursive = -1;
		$group = $this->BillingGroup->findByIdOrSlug($group, $group);
		if(!empty($group)){
			$this->BillingGroup->BillingPlan->recursive = -1;
			$plans = $this->BillingGroup->BillingPlan->find('all', array(
				'conditions' => array('BillingPlan.group_id' => $group['BillingGroup']['id'])
			));
			foreach($plans as $key=>$plan){
				$_plan = Hash::extract($plan, "BillingPlan");
				unset($plan['BillingPlan']);
				$group['BillingPlan'][$key] = Hash::merge($_plan, $plan);
			}
		}
		if(empty($group)){
			throw new NotFoundException(__d('billing', 'Invalid billing group'));
		}
		$this->BillingSubscription->Behaviors->load('Containable');
		$currentSubscription = $this->BillingSubscription->find('first', array(
			'contain' => array('BillingGroup', 'BillingPlan'),
			'conditions' => array(
				'BillingSubscription.user_id' => $this->currUser['User']['id'],
				'BillingSubscription.active' => true,
				'BillingGroup.slug LIKE' => $group['BillingGroup']['slug']
			)
		));

		$this->layout = 'profile_new';
		$this->set('group', $group);
		$this->set('currentSubscription', $currentSubscription);
		$this->Tools->viewFallback(array(
			'plans_' . $group['BillingGroup']['slug'],
			'plans',
		));
	}

	public function payment($plan = null){
		if ($this->request->is('get')) {
			if(empty($plan)){
				$plan = $this->Session->read('Billing.plan');
			}
			if(empty($plan)){
				throw new NotFoundException(__d('billing', 'Incorrect request type'));
			} else {
				$this->request->data['plan'] = $plan;
			}
		} elseif ($this->request->is('post')) {
			if(!isset($this->request->data['plan'])
				||empty($this->request->data['plan'])){
				throw new NotFoundException(__d('billing', 'Invalid billing group'));
			}
		}
		$userSubscriptions = $this->BillingSubscription->find('all', array(
			'conditions' => array(
				'BillingSubscription.user_id' => $this->currUser['User']['id'],
				'BillingSubscription.active' => true
			)
		));
		//Check for free subscription
		$freePlan = $this->BillingPlan->find('first', array(
			'conditions' => array(
				'BillingPlan.id' => $this->request->data['plan'],
				'BillingPlan.free' => true,
			)
		));
		if(!empty($freePlan)){
			$match = Hash::extract($userSubscriptions, "{n}.BillingPlan[id=".$freePlan['BillingPlan']['id']."].free");
			if(!empty($match)){
				$this->Session->setFlash(__d('billing', 'You can not subscribe to same plan twice while it is active'));
				$this->redirect(array('action' => 'plans', $freePlan['BillingGroup']['slug']));
			}
			$subscriptionData = array(
				'group_id' => $freePlan['BillingGroup']['id'],
				'plan_id' => $freePlan['BillingPlan']['id'],
				'user_id' => $this->currUser['User']['id'],
				'active' => true,
				'status' => 'Active',
				'remote_subscription_id' => '',
				'remote_plan_id' => '',
				'expires' => ''
			);

			$subscriptionIds = Hash::extract($userSubscriptions, "{n}.BillingSubscription[group_id=".$freePlan['BillingGroup']['id']."].id");
			if(isset($subscriptionIds[0])&&!empty($subscriptionIds[0])){
				$subscriptionData['id'] = $subscriptionIds[0];
			}

			$unit = Configure::read('Billing.units.'.$freePlan['BillingGroup']['limit_units']);
			if(empty($unit['model'])||empty($unit['field'])){
				throw new NotFoundException(__d('billing', 'Invalid billing plan'));
			}
			$this->BillingSubscription->Behaviors->load('Billing.Limitable', array(
				'remoteModel' => $unit['model'],
				'remoteField' => $unit['field'],
				'scope' => (isset($unit['scope']) ? $unit['scope'] : 'user_id'),
			));
			if($this->BillingSubscription->setLimit($subscriptionData)){
				$this->BillingSubscription->cancelAll($subscriptionData['group_id']);
				$braintreeSubscriptionIds = Hash::extract($userSubscriptions, "{n}.BillingSubscription[group_id=".$freePlan['BillingGroup']['id']."].remote_subscription_id");
				foreach($braintreeSubscriptionIds as $braintreeSubscriptionId){
					$result = Braintree_Subscription::cancel($braintreeSubscriptionId);
				}
			} else {
				$this->Session->setFlash(__d('billing', 'The subscription could not be downgraded. Please, try again later.'));
			}
			$this->redirect(array('action' => 'plans', $freePlan['BillingGroup']['slug']));
		}

		//prevent subscription to same plan
		$plan = $this->request->data['plan'];
		$match = Hash::extract($userSubscriptions, "{n}.BillingSubscription[remote_plan_id=".$plan."]");
		if(!empty($match)&&!isset($this->request->data['qty'])){
			$plan = $this->BillingPlan->findByRemotePlan($plan);
			$this->Session->setFlash(__d('billing', 'You can not subscribe to same plan twice while it is active'));
			$this->redirect(array('action' => 'plans', $plan['BillingGroup']['slug']));
		}

		//currently user data required for check action
		$customer = $this->BraintreeCustomer->check($this->currUser);
		if(empty($customer)){
			throw new NotFoundException(__d('billing', 'Invalid customer for checkout'));
		}
		$this->Session->write('Billing', array(
			'plan' => $this->request->data['plan'],
			'qty' => 0
		));
		if(isset($this->request->data['qty'])&&!empty($this->request->data['qty'])){
			$this->Session->write('Billing.qty', $this->request->data['qty']);
		}
		$this->layout = 'profile_new';
		$clientToken = Braintree_ClientToken::generate();
        $this->set('clientToken', $clientToken);
		$this->set('customer', $customer);
		$this->set('plan', $plan);
	}

	public function checkout(){
		$this->layout = 'profile_new';
		if (!$this->request->is('post')) {
			throw new NotFoundException(__d('billing', 'Incorrect request type'));
		}

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

		$braintreePlanId = $this->Session->read('Billing.plan');
		$plan = $this->BillingPlan->findByRemotePlan($braintreePlanId);
		$braintreePlans = Braintree_Plan::all();
		$braintreePlan = null;
		foreach($braintreePlans as $_braintreePlan){
			if($_braintreePlan->id == $braintreePlanId){
				$braintreePlan = $_braintreePlan;
				break;
			}
		}
		if(empty($braintreePlan)){
			throw new NotFoundException(__d('billing', 'Unable to create subscription'));
		}
		//Important! unit setup for model must be here. Before creating Braintree subscription
		$unit = Configure::read('Billing.units.'.$plan['BillingGroup']['limit_units']);
		if(empty($unit['model'])||empty($unit['field'])){
			throw new NotFoundException(__d('billing', 'Invalid billing plan'));
		}
		$this->BillingSubscription->Behaviors->load('Billing.Limitable', array(
			'remoteModel' => $unit['model'],
			'remoteField' => $unit['field'],
			'scope' => (isset($unit['scope']) ? $unit['scope'] : 'user_id'),
		));

		//Precreate subscription
		$braintreeData = array(
            'paymentMethodToken' => $payment->token,
            'planId' => $braintreePlanId
        );
		$qty = $this->Session->read('Billing.qty');
		if(!empty($qty)){
			if(empty($braintreePlan->addOns)){
				throw new NotFoundException(__d('billing', 'Unable to create subscription'));
			}
			foreach($braintreePlan->addOns as $addOn){
				$braintreeData['addOns']['update'][] = array(
					'existingId' => $addOn->id,
					'quantity' => $qty
				);
			}
		}

		$billingSubscription = $this->BillingSubscription->find('first', array(
			'conditions' => array(
				'BillingSubscription.group_id' => $plan['BillingGroup']['id'],
				'BillingSubscription.user_id' => $this->currUser['User']['id'],
				'BillingSubscription.active' => true,
			)
		));
		//braintree unable to update subscription to a plan with a different billing frequency So we need to cancel current
		if(!empty($billingSubscription)){
			if(($braintreePlan->billingFrequency != $billingSubscription['BraintreePlan']->billingFrequency)
				||($billingSubscription['BraintreeSubscription']->status == 'Canceled')
				||($billingSubscription['BraintreeSubscription']->status == 'Expired')
				){
				if(($braintreePlan->billingFrequency != $billingSubscription['BraintreePlan']->billingFrequency)
					||($billingSubscription['BraintreeSubscription']->status != 'Canceled')){
					try{
						$result = Braintree_Subscription::cancel($billingSubscription['BraintreeSubscription']->id);
						if($result->success){
							$billingSubscription['BraintreeSubscription'] = $result->subscription;
						}
					} catch(Exception $e){}
				}
				$status = isset($billingSubscription['BraintreeSubscription']->status) ? $billingSubscription['BraintreeSubscription']->status : 'Canceled';
				$this->BillingSubscription->cancel($billingSubscription['BillingSubscription']['id'], $status);
				$billingSubscription = null;
			}
		}
		if(!isset($billingSubscription['BillingSubscription'])){
			$data = array(
				'group_id' => $plan['BillingGroup']['id'],
				'plan_id' => $plan['BillingPlan']['id'],
				'user_id' => $this->currUser['User']['id'],
				'limit_value' => (!empty($qty) ? $qty : $plan['BillingPlan']['limit_value']),
				'active' => false,
			);
		} else {
			$data = $billingSubscription['BillingSubscription'];
			$data['limit_value'] = (!empty($qty) ? $qty : $plan['BillingPlan']['limit_value']);
		}

		//No Exceptions anymore!
		if(!isset($data['remote_subscription_id'])||empty($data['remote_subscription_id'])){
			//Subscribe user by create
	        $result = Braintree_Subscription::create($braintreeData);
		} else {
			$data['plan_id'] = $plan['BillingPlan']['id'];
			//Subscribe user by update
	        $result = Braintree_Subscription::update($data['remote_subscription_id'], $braintreeData);
		}

		if(!$result->success){
			$this->Session->setFlash(__d('billing', 'Unable to subscribe on chosen plan. Please contact with resorce administration'));
			$this->redirect(array('action' => 'plans', $plan['BillingGroup']['slug']));
		}
		$data = Hash::merge($data, array(
			'remote_subscription_id' => $result->subscription->id,
			'remote_plan_id' => $result->subscription->planId,
			'active' => (($result->subscription->status === 'Active') ? true : false),
			'status' => $result->subscription->status,
			'expires' => $result->subscription->billingPeriodEndDate->format('Y-m-d H:i:s'),
			'created' => $result->subscription->createdAt->format('Y-m-d H:i:s'),
			'modified' => $result->subscription->updatedAt->format('Y-m-d H:i:s'),
		));
		if(!isset($data['id'])){
			$this->BillingSubscription->create();
		}
		if($this->BillingSubscription->save($data)){
			$this->Session->write('Billing');
			if(!isset($data['id'])||empty($data['id'])){
				$data['id'] = $this->BillingSubscription->getInsertID();
			}
			$this->redirect(array('action' => 'success', $data['id']));
		} else {
			$this->Session->setFlash(__d('billing', 'Unable to subscribe on chosen plan. Please contact with resorce administration'));
			$this->redirect(array('action' => 'plans', $plan['BillingGroup']['slug']));
		}
    }

	public function success($id = null){
		$subscription = $this->BillingSubscription->findById($id);
		if (empty($subscription)||($subscription['User']['id'] != $this->currUser['User']['id'])) {
			throw new NotFoundException(__d('billing', 'Subscription can not be found'));
		}
		$this->layout = 'profile_new';
		$this->set('subscription', $subscription);
	}
}
