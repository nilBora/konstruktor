<?php
class GroupDream extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'group_dream';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_field' => array(
                'groups' => array(
                    'is_dream' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
                )
            )
		),
		'down' => array(
            'drop_field' => array(
                'groups' => array('is_dream'),
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
