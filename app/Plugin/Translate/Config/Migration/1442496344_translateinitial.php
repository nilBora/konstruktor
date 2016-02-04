<?php

class TranslateInitial extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'i18n' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
					'locale' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 6, 'key' => 'index'),
					'model' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
					'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
					'field' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
					'content' => array('type' => 'text', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => true),
						'i18n_locale_index' => array('column' => 'locale', 'unique' => false),
						'i18n_model_index' => array('column' => 'model', 'unique' => false),
						'i18n_row_id_index' => array('column' => 'foreign_key', 'unique' => false),
						'i18n_field_index' => array('column' => 'field', 'unique' => false),
					),
					'tableParameters' => array('engine' => 'InnoDB', 'charset' => 'utf8', 'collate' => 'utf8_general_ci')
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'i18n'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
