<?php
App::uses('BillingAppModel', 'Billing.Model');
/**
 * BillingPlan Model
 *
 */
class BillingPlan extends BillingAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

	public $belongsTo = array(
        'BillingGroup' => array(
            'className' => 'Billing.BillingGroup',
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
		'Tools.Jsonable' => array(
			'fields' => array('remote_plans')
		),
		'Translate.KonstruktorTranslate' => array(
			'fields' => array('title', 'description')
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

	public function findByRemotePlan($remotePlan){
		return $this->find('first', array(
			'conditions' => array(
				'BillingPlan.remote_plans LIKE' => '%'.$remotePlan.'%',
			)
		));
	}

	public function afterFind($results, $primary = false){
		if(!empty($results)&&($primary == true)){
			$braintreePlans = Braintree_Plan::all();
			foreach($results as $key=>$result){
				foreach($braintreePlans as $braintreePlan){
					if(!empty($result['BillingPlan']['remote_plans'])
						&&is_array($result['BillingPlan']['remote_plans'])
						&&in_array($braintreePlan->id, $result['BillingPlan']['remote_plans'])){
						$results[$key]['BraintreePlan'][] = $braintreePlan;
					}
				}
			}
		}
		return $results;
	}

}
