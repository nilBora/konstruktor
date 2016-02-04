<?php
class AddDocVersion extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_doc_version';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'notes' => array(
                    'last_updated_by' => array('type' => 'integer', 'null' => false, 'unsigned' => true, 'after' => 'rght'),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'notes' => array('last_updated_by'),
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
