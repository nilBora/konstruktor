<?php
class RatingsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'RatingsTable';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'groups' => array(
					'karma' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'active_members'),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'karma'),
				),
				'users' => array(
					'karma' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'last_update'),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'karma'),
				),
			),
			'create_table' => array(
				'ratings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'foreign_model' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'context' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'context_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'idx_ratings_goreign_id_foreign_model' => array('column' => array('foreign_id', 'foreign_model'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'groups' => array('karma', 'rating'),
				'users' => array('karma', 'rating'),
			),
			'drop_table' => array(
				'ratings'
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
