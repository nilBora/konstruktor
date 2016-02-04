<?php
class IndexesRework extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'IndexesRework';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'billing_subscriptions' => array('indexes' => array('billing_subscriptions_group_id_user_id_idx')),
			),
			'create_field' => array(
				'billing_subscriptions' => array(
					'indexes' => array(
						'billing_subscriptions_group_id_idx' => array('column' => 'group_id', 'unique' => 0),
						'billing_subscriptions_user_id_idx' => array('column' => 'id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'billing_subscriptions' => array(
					'indexes' => array(
						'billing_subscriptions_group_id_user_id_idx' => array('column' => array('group_id', 'user_id'), 'unique' => 1),
					),
				),
			),
			'drop_field' => array(
				'billing_subscriptions' => array('indexes' => array('billing_subscriptions_group_id_idx', 'billing_subscriptions_user_id_idx')),
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
