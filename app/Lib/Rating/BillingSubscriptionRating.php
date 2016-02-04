<?php
App::uses('AbstractRating', 'Lib/Rating');

class BillingSubscriptionRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Subscribe for service',
			'createdOnly' => true,
			'target' => array(
				'User' => 'user_id'
			),
			'value' => 5
		),
	);

}
