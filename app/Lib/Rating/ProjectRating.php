<?php
App::uses('AbstractRating', 'Lib/Rating');

class ProjectRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Project creation',
			'createdOnly' => true,
			'target' => array(
				'Group' => 'group_id',
				'User' => 'owner_id'
			),
			'value' => 1
		),
	);
}
