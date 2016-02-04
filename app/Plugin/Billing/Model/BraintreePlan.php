<?php
App::uses('BillingAppModel', 'Billing.Model');

class BraintreePlan extends BillingAppModel {

	public $useTable = false;

	public function find($type = 'first', $query = array()){
		$plans = Braintree_Plan::all();
		ksort($plans);
		switch($type){
			case 'all':
			break;

			case 'list':
				ksort($plans);
				$_plans = array();
				foreach($plans as $i=>$plan){
					$_plans[$plan->id] = $plan->name;
				}
				$plans = $_plans;
			break;

			default:
			case 'first':
				$plans = $plans[0];
			break;
		}
		return $plans;
	}
}
