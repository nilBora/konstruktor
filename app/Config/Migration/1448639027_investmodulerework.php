<?php
class InvestModuleRework extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'InvestModuleRework';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'invest_project' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
				),
				'invest_reward' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
				),
				'invest_sponsor' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
				),
				'invest_video' => array(
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'invest_project' => array(
					'group_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'id'),
					'funded_total' => array('type' => 'decimal', 'null' => false, 'default' => '0.00', 'length' => '15,2', 'unsigned' => false, 'after' => 'currency'),
					'funders_total' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'funded_total'),
					'hits' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'funders_total'),
					'indexes' => array(
						'invest_project_group_id_idx' => array('column' => 'group_id', 'unique' => 0),
						'invest_project_category_id_idx' => array('column' => 'category_id', 'unique' => 0),
						'invest_project_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
					),
				),
				'invest_reward' => array(
					'funded' => array('type' => 'decimal', 'null' => false, 'default' => '0.00', 'length' => '15,2', 'unsigned' => false, 'after' => 'created'),
					'funders' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'funded'),
					'indexes' => array(
						'invest_reward_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
						'invest_reward_project_id_idx' => array('column' => 'project_id', 'unique' => 0),
					),
				),
				'invest_sponsor' => array(
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'index', 'after' => 'user_id'),
					'remote_transaction_id' => array('type' => 'string', 'null' => false, 'length' => 15, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'created'),
					'canceled' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'after' => 'remote_transaction_id'),
					'indexes' => array(
						'invest_sponsor_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
						'invest_sponsor_project_id_reward_id_idx' => array('column' => array('project_id', 'reward_id'), 'unique' => 0),
					),
				),
				'invest_video' => array(
					'indexes' => array(
						'invest_video_project_id_idx' => array('column' => 'project_id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'invest_project' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
				),
				'invest_reward' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
				),
				'invest_sponsor' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
				),
				'invest_video' => array(
					'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
				),
			),
			'drop_field' => array(
				'invest_project' => array('group_id', 'funded_total', 'funders_total', 'hits', 'indexes' => array('invest_project_group_id_idx', 'invest_project_category_id_idx', 'invest_project_user_id_idx')),
				'invest_reward' => array('funded', 'funders', 'indexes' => array('invest_reward_user_id_idx', 'invest_reward_project_id_idx')),
				'invest_sponsor' => array('project_id', 'remote_transaction_id', 'canceled', 'indexes' => array('invest_sponsor_user_id_idx', 'invest_sponsor_project_id_reward_id_idx')),
				'invest_video' => array('indexes' => array('invest_video_project_id_idx')),
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
