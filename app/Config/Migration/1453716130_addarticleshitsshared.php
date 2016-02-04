<?php
class AddArticlesHitsShared extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'AddArticlesHitsShared';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'articles' => array(
					'hits'   => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'cat_id' ),
					'shared' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'cat_id' )
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'articles' => array('hits','shared')
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
