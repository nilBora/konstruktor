<?php
class LimitsFragmentedToTables extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'LimitsFragmentedToTables';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'billing_groups' => array(
					'limit_units' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'slug'),
				),
				'billing_subscriptions' => array(
					'limit_value' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'remote_plan_id'),
				),
			),
			'drop_field' => array(
				'billing_plans' => array('limit_unit'),
			),
			'alter_field' => array(
				'billing_plans' => array(
					'limit_value' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'billing_groups' => array('limit_units'),
				'billing_subscriptions' => array('limit_value'),
			),
			'create_field' => array(
				'billing_plans' => array(
					'limit_unit' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
			'alter_field' => array(
				'billing_plans' => array(
					'limit_value' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
				),
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
