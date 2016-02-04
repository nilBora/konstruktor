<?php
class StorageLimit extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'StorageLimit';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'cloud' => array(
					'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'contractors' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
				'finance_share' => array(
					'accounts' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'operations' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'categories' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'budgets' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'goals' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'order_products' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
					'distrib_date' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
				'order_reports' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
				'projects' => array(
					'deadline' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
				),
				'sheet_data' => array(
					'sheetid' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'sheet_header' => array(
					'sheetid' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'tasks' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
					'deadline' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
				'users' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
				'vacancy_response' => array(
					'modified' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
				),
			),
			'create_table' => array(
				'storage_limit' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'message_file_size' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
					'project_file_size' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
					'cloud_size' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
					'storage_limit' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'storage_limit_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'cloud' => array(
					'name' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'contractors' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
				'finance_share' => array(
					'accounts' => array('type' => 'string', 'null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'operations' => array('type' => 'string', 'null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'categories' => array('type' => 'string', 'null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'budgets' => array('type' => 'string', 'null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'goals' => array('type' => 'string', 'null' => false, 'length' => 2048, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'order_products' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
					'distrib_date' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
				'order_reports' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
				'projects' => array(
					'deadline' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
				),
				'sheet_data' => array(
					'sheetid' => array('type' => 'string', 'null' => false, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'sheet_header' => array(
					'sheetid' => array('type' => 'string', 'null' => false, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'tasks' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
					'deadline' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
				'users' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
				'vacancy_response' => array(
					'modified' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00'),
				),
			),
			'drop_table' => array(
				'storage_limit'
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
