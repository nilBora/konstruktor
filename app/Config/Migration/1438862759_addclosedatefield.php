<?php
class Addclosedatefield extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'addclosedatefield';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tasks' => array(
					'close_date' => array('type' => 'timestamp', 'null' => true, 'default' => null, 'after' => 'deleted'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'tasks' => array('close_date'),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
