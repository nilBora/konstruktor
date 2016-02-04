<?php
class NotificationsAndCharsets extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'NotificationsAndCharsets';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'group_vacancy' => array(
					'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'descr' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2047, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'country' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1023, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'currency' => array('type' => 'string', 'null' => false, 'default' => 'USD', 'length' => 12, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci'),
				),
				'vacancy_response' => array(
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci'),
				),
			),
			'create_field' => array(
				'share' => array(
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'after' => 'share_type'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'active'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'group_vacancy' => array(
					'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'descr' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2047, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'country' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1023, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'currency' => array('type' => 'string', 'null' => false, 'default' => 'USD', 'length' => 12, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
				),
				'vacancy_response' => array(
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_field' => array(
				'share' => array('active', 'created'),
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
		if($direction == 'up'){
			$shareModel = Classregistry::init('Share');
			$shareModel->updateAll(
				array('Share.active' => 0),
				array('Share.active' => 1)
			);
			$userModel = Classregistry::init('User');
			$userModel->updateAll(
				array('User.news_update' => 'User.modified'),
				array('User.is_deleted' => 0)
			);
		}
		return true;
	}
}
