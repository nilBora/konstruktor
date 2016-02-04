<?php
App::uses('AbstractRating', 'Lib/Rating');

class NoteRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Document creation',
			'ceateOnly' => true,
			'target' => array(
				'User' => 'user_id'
			),
			'value' => 1
		),
	);
}
