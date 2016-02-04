<?php
App::uses('BillingAppController', 'Billing.Controller');

class BillingCallbackController extends BillingAppController {

	public $uses = array('Billing.BillingSubscription');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow(array('processSubscription'));
		//webhook validation
		$btChallenge = '';
		if(isset($this->request->query['bt_challenge'])){
			$btChallenge = $this->request->query['bt_challenge'];
		} elseif(isset($_GET["bt_challenge"])) {
			$btChallenge = $_GET["bt_challenge"];
		}
		if(!empty($btChallenge)) {
			echo Braintree_WebhookNotification::verify($btChallenge);
			exit();
		}
	}

	public function processSubscription(){
		if(!$this->request->is('post')||!isset($this->request->data['bt_signature'])||!isset($this->request->data['bt_payload'])){
			$this->response->statusCode(404);
			return $this->response;
		}
		$webhookNotification = Braintree_WebhookNotification::parse(
			$this->request->data['bt_signature'], $this->request->data['bt_payload']
		);
		if(!isset($webhookNotification->subscription)){
			$this->response->statusCode(404);
			return $this->response;
		}
		CakeLog::write('debug', __d('billing', '%s Braintree webhook for subscription %s: %s', $webhookNotification->timestamp, $webhookNotification->subscription->id, $webhookNotification->kind));
		$braintreeSubscription = $webhookNotification->subscription;
		CakeLog::write('debug', json_encode($webhookNotification->subscription));

		$subscription = $this->BillingSubscription->findByRemoteSubscriptionId($braintreeSubscription->id);
		if(empty($subscription)){
			$this->response->statusCode(404);
			return $this->response;
		}
		switch ($braintreeSubscription->status){
			case 'Canceled':
				$result = $this->BillingSubscription->cancel($subscription['BillingSubscription']['id']);
				break;
			default:
				$result = true;
		}
		if($result){
			$this->response->statusCode(200);
		} else {
			$this->response->statusCode(500);
		}
		return $this->response;
	}

}
