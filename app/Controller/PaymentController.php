<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class PaymentController extends SiteController {
	public $name = 'Payment';
	public $layout = 'device';
	public $uses = array('Balance', 'User');
	
	public function beforeRender() {
		$this->set('balance', $this->User->getBalance($this->currUserID));
		$this->set('PU_', '$');
		$this->set('_PU', '');
		
		$this->set('pageTitle', $this->pageTitle);
	}
	
	public function index() {
		// $this->redirect(array('controller' => $this->name, 'action' => 'orders'));
	}
	
	public function recharge() {
		$this->pageTitle = __('Recharge balance');
	}

	public function paypal() {
		$this->layout = 'ajax';
		$this->set('item_number', $this->currUserID);
		$this->set('total', $this->request->data('total'));
	}
	
	public function paid() {
		$this->redirect(array('action' => 'recharge', '?' => array('paid' => 1)));
	}
	
	public function cancel() {
		$this->redirect(array('action' => 'recharge', '?' => array('cancel' => 1)));
	}
	
	public function ipnPaypal() {
		$this->autoRender = false;
		file_put_contents('paypal_ipn.log', date('Y-m-d H:i:s')." ".$response." ".json_encode($_POST)."\r\n", FILE_APPEND);
		
		// verify transaction with the same data
		$postdata = '';
		foreach ($_POST as $key => $value) {
			$postdata.= $key.'='.urlencode($value).'&'; 
		}
		$postdata .= 'cmd=_notify-validate'; 
		$curl = curl_init(PAYPAL_URL);
		curl_setopt($curl, CURLOPT_HEADER, 0); 
		curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); 
		// curl_setopt($curl, CURLOPT_REFERER, 'http://'.DOMAIN_NAME.'/Payment/ipnPaypal'); 
		
		$response = curl_exec($curl); 
		curl_close ($curl); 
		
		if ($response == "VERIFIED") { // transaction is verified
			if ($_POST['receiver_email'] == MERCHANT_EMAIL && $_POST["txn_type"] == "web_accept") { // verify our busyness email
				// check currency
				// if ($_POST["mc_currency"] == $this->currencyISO) {
					
					// verification - OK
					$user_id = $_POST['item_number'];
					$sum = $_POST["mc_gross"];
					$this->Balance->change($user_id, $sum, Balance::CREDIT, 'Recharge balance by user (PayPal IPN)');
					// exit;
				// }
			}
		}
				
	}
}
