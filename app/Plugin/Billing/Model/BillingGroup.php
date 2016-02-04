<?php
App::uses('BillingAppModel', 'Billing.Model');
/**
 * BillingGroup Model
 *
 */
class BillingGroup extends BillingAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

	public $hasMany = array(
        'BillingPlan' => array(
            'className' => 'Billing.BillingPlan',
            'foreignKey' => 'group_id'
        )
    );

	public $actsAs = array(
		'Tools.Slugged' => array(
        	'length'  => 255,
        	'label' => 'title',
			'unique' => true,
			'case' => 'low',
    	),
		'Translate.KonstruktorTranslate' => array(
			'fields' => array('title')
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public function afterFind($results, $primary = false){
		if(!empty($results)){
			$braintreePlans = Braintree_Plan::all();
			foreach($results as $resultKey=>$result){
				if(isset($result['BillingPlan'])&&!empty($result['BillingPlan'])){
					foreach($result['BillingPlan'] as $key=>$plan){
						$_arr = array();
						$_arr[0]['BillingPlan'] = $plan;
						$_arr = Hash::extract($this->BillingPlan->decodeItems($_arr), "0.BillingPlan");
						foreach($_arr['remote_plans'] as $remotePlan){
							foreach($braintreePlans as $braintreePlan){
								if($remotePlan == $braintreePlan->id){
									$_arr['BraintreePlan'][] = $braintreePlan;
								}
							}
						}
						$result['BillingPlan'][$key] = $_arr;
					}
				}
				$results[$resultKey] = $result;
			}
		}
		return $results;
	}
}
