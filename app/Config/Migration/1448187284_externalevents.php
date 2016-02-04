<?php
class ExternalEvents extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'ExternalEvents';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'app_sessions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'groups' => array(
					'is_dream' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'unsigned' => false),
				),
			),
			'create_table' => array(
				'user_event_categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'user_event_events' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
					'created' => array('type' => 'timestamp', 'null' => true, 'default' => null),
					'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'recepient_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'event_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'msg_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'file_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'user_event_events_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
						'user_event_events_recipient_id_idx' => array('column' => 'recepient_id', 'unique' => 0),
						'user_event_events_event_id_idx' => array('column' => 'event_id', 'unique' => 0),
						'user_event_events_msg_id_idx' => array('column' => 'msg_id', 'unique' => 0),
						'user_event_events_file_id_idx' => array('column' => 'file_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
				),
				'user_event_requests' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'event_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'status' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'price' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'duration' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'description' => array('type' => 'text', 'null' => false, 'default' => null, 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'user_event_requests_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
						'user_event_requests_event_id_idx' => array('column' => 'event_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
				),
				'user_event_request_limits' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'requests_used' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'requests_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'user_event_request_limits_user_id_idx' => array('column' => 'user_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'user_events' => array(
					'external' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'after' => 'finance_operation_id'),
					'external_time' => array('type' => 'timestamp', 'null' => true, 'default' => '0000-00-00 00:00:00', 'after' => 'external'),
					'price' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'external_time'),
					'hits' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'after' => 'price'),
					'event_category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'Категория события', 'after' => 'hits'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'app_sessions' => array(
					'id' => array('type' => 'string', 'null' => false, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'groups' => array(
					'is_dream' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
				),
			),
			'drop_table' => array(
				'user_event_categories', 'user_event_events', 'user_event_requests', 'user_event_request_limits'
			),
			'drop_field' => array(
				'user_events' => array('external', 'external_time', 'price', 'hits', 'event_category_id'),
			),
		),
	);

	public $billingGroup = array(
		'eng' => array('title' => 'Proposals for tasks'),
		'rus' => array('title' => 'Заявки в задачи'),
		'slug' => 'proposals',
		'limit_units' => 'proposals',
		'active' => 1
	);

	public $billingPlan = array(
		'eng' => array('title' => 'Proposals for tasks'),
		'rus' => array('title' => 'Заявки в задачи'),
		'slug' => 'proposals',
		//'group_id' => '3',
		'description' => '',
		'limit_value' => 0,
		'free' => 0,
		'remote_plans' => array(
			'task-proposals-monthly',
			'task-proposals-yearly'
		)
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
		if ($direction == 'up'){
			$userEventCategoryModel = ClassRegistry::init('UserEventCategory');
			$categories = array(
				'Design', 'Programming', 'Marketing', 'Photography', 'Architecture / Interior',
				'Translations', 'Management', 'Copyright', 'Engineering', 'Education and consultations',
				'SEO', 'Accounting', 'HR', 'Articles', 'IT', 'Outsource and consulting'
			);
			foreach($categories as $title){
				$userEventCategoryModel->create();
				$userEventCategoryModel->save(array('title' => $title));
			}
			//Add proposals limits
			$userEventRequestLimitModel = ClassRegistry::init('UserEventRequestLimit');
			$userModel = ClassRegistry::init('User');
			$limitData = array(
				'requests_used' => -5,
				'requests_limit' => 0
			);
			$users = $userModel->find('list', array(
				'fields' => array('User.id', 'User.full_name'),
			));
			foreach($users as $userId=>$userFullName){
				$userEventRequestLimitModel->create();
				$userEventRequestLimitModel->save(array_merge($limitData, array('user_id' => $userId)));
			}
			//Add billing Group and Plan
			$billingGroupModel = ClassRegistry::init('Billing.BillingGroup');
			$billingGroupModel->create();
			if($billingGroupModel->save($this->billingGroup)){
				$this->billingPlan['group_id'] = $billingGroupModel->getLastInsertID();
				$billingPlanModel = ClassRegistry::init('Billing.BillingPlan');
				$billingPlanModel->save($this->billingPlan);
			}
		} elseif($direction == 'down'){
			//destroy billing Group and dependent Plans
			$billingGroupModel = ClassRegistry::init('Billing.BillingGroup');
			$billingGroup = $billingGroupModel->findBySlug($this->billingGroup['slug']);
			if($billingGroupModel->delete($billingGroup['BillingGroup']['id'])){
				$billingGroupModel->BillingPlan->deleteAll(
					array('BillingPlan.group_id' => $billingGroup['BillingGroup']['id']),
					false
				);
				$billingSubscriptionModel = ClassRegistry::init('Billing.BillingSubscription');
				$billingSubscriptionModel->deleteAll(
					array('BillingSubscription.group_id' => $billingGroup['BillingGroup']['id']),
					false
				);
			}
		}
		return true;
	}
}
