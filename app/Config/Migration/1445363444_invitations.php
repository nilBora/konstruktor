<?php
class Invitations extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'invitations';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_table' => array(
                'invitations' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
                    'object_id' => array('type' => 'integer', 'null' => false),
                    'object_type' => array('type' => 'biginteger', 'null' => false, 'default' => 1),
                    'email' => array('type' => 'string', 'null' => false, 'key' => 'SECONDARY'),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'SECONDARY' => array('column' => 'email', 'unique' => 1)
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
                ),
            ),
		),
		'down' => array(
            'drop_table' => array(
                'invitations'
            )
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
