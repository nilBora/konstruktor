<?php
class IndexesForSubscriptions extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'IndexesForSubscriptions';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'billing_subscriptions' => array(
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'plan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'billing_subscriptions' => array(
					'indexes' => array(
						'billing_subscriptions_group_id_user_id_idx' => array('column' => array('group_id', 'user_id'), 'unique' => 1),
						'billing_subscriptions_plan_id_idx' => array('column' => 'plan_id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'billing_subscriptions' => array(
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'plan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
			),
			'drop_field' => array(
				'billing_subscriptions' => array('indexes' => array('billing_subscriptions_group_id_user_id_idx', 'billing_subscriptions_plan_id_idx')),
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
