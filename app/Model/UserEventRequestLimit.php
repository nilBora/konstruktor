<?php
App::uses('AppModel', 'Model');

class UserEventRequestLimit extends AppModel {

	public $useTable = 'user_event_request_limits';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
public $actsAs = array('EventRequestsLimitable');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

	public function checkUsedRequest($user_id){
		$response = $this->checkUsedRequests($user_id);
		return $response;
  }
}
