<?php
class Filesharing extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'filesharing';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_table' => array(
                'share' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
                    'target' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),

                    'object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
                    'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
                    'share_type' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
                ),
            ),
		),
		'down' => array(
            'drop_table' => array(
                'share'
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
