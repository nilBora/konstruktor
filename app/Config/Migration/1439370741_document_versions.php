<?php
class DocumentVersions extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'document_versions';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
            'create_table' => array(
                'document_versions' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
                    'doc_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
                    'body' => array('type' => 'text', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
                    'title' => array('type' => 'string','null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
                    'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
                ),
            ),
		),
		'down' => array(
            'drop_table' => array(
                'document_versions'
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
