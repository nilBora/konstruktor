<?php
App::uses('AbstractRating', 'Lib/Rating');

class UserEventRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Add event at my time',
			'createdOnly' => true,
			'target' => array(
				'User' => 'user_id'
			),
			'value' => 1
		),
	);

}
