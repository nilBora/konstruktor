<?php
App::uses('BillingAppModel', 'Billing.Model');
/**
 * BillingSubscription Model
 *
 * @property Plan $Plan
 * @property User $User
 * @property RemotePlan $RemotePlan
 */
class BillingSubscription extends BillingAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'plan_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'BillingGroup' => array(
			'className' => 'Billing.BillingGroup',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BillingPlan' => array(
			'className' => 'Billing.BillingPlan',
			'foreignKey' => 'plan_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public $actsAs = array('Ratingable');

	public function afterFind($results, $primary = false){
		if($primary){
			$results = Hash::insert($results, "{n}.BraintreeSubscription", array());
			$results = Hash::insert($results, "{n}.BraintreePlan", array());

			$braintreeSubscriptions = Braintree_Subscription::search([
				Braintree_SubscriptionSearch::ids()->in(Hash::extract($results, "{n}.BillingSubscription.remote_subscription_id"))
			]);

			$braintreePlans = Braintree_Plan::all();
			foreach($results as $key=>$result){
				foreach($braintreeSubscriptions as $braintreeSubscription){
					if($braintreeSubscription->id == $result['BillingSubscription']['remote_subscription_id']){
						$result['BraintreeSubscription'] = $braintreeSubscription;
						break;
					}
					//$results = Hash::insert($results, "{n}.BillingSubscription[remote_subscription_id=".$braintreeSubscription->id."]", array('BraintreeSubscription' => $braintreeSubscription));
				}
				foreach($braintreePlans as $braintreePlan){
					if($braintreePlan->id == $result['BillingSubscription']['remote_plan_id']){
						$result['BraintreePlan'] = $braintreePlan;
						break;
					}
				}
				$results[$key] = $result;
			}
		}
		return $results;
	}

	public function cancel($id, $status = 'Canceled'){
		$data = $this->read(array(), $id);
		if(!empty($data)){
			return $this->save(Hash::merge(
				$data[$this->alias],
				array(
					'active' => false,
					'status' => $status,
				)
			));
		}
		return false;
	}

	public function cancelAll($group, $status = 'Canceled'){
		$db = $this->getDataSource();
		$this->updateAll(
			array(
				'BillingSubscription.active' => false,
				'BillingSubscription.status' => $db->value($status, 'string'),
			),
			array('BillingSubscription.group_id' => $group)
		);
	}

}
