<?php
App::uses('AbstractRating', 'Lib/Rating');

class SubscriptionRating extends AbstractRating {

	protected $_config = array(
		'subscriptionTo' => array(
			'label' => 'Subscription to user from other user',
			'createdOnly' => true,
			'target' => array(
				'User' => 'object_id'
			),
			'value' => 3
		),
		'subscriptionFrom' => array(
			'label' => 'Subscription from user from other user',
			'createdOnly' => true,
			'target' => array(
				'User' => 'subscriber_id'
			),
			'value' => 1
		),
	);

	protected function validate($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		if($data[$this->context]['type'] != 'user'){
			$result = false;
		}
		return $result;
	}

}
