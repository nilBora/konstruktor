<?php
class GroupLimitsTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'GroupLimitsTable';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'group_limits' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'owner_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'unique'),
					'members_used' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'members_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'group_limits_owner_id_idx' => array('column' => 'owner_id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'create_field' => array(
				'groups' => array(
					'active_members' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'cat_id'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'group_limits'
			),
			'drop_field' => array(
				'groups' => array('active_members'),
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
		if($direction === 'up'){
			$group = ClassRegistry::init('Group');
			$groupLimit = ClassRegistry::init('GroupLimit');

			$group->Behaviors->load('Containable');
			$groups = $group->find('all',array(
				'contain' => array('GroupMember')
			));
			foreach($groups as $key=>$_group){
				$result = $group->saveAssociated($_group, array(
					'validate' => false,
					'counterCache' => true,
				));
			}
			$group->Behaviors->unload('Containable');
			$groups = $group->find('list',array(
				'fields' => array('Group.id', 'Group.owner_id'),
			));
			$groups = array_unique($groups);
			foreach($groups as $ownerId){
				$memberCounts = $group->find('list',array(
					'fields' => array('Group.id', 'Group.active_members'),
					'conditions' => array('Group.owner_id' => $ownerId),
				));
				$payedMembersCount = 0;
				foreach($memberCounts as $count){
					if($count - 6 > 0){
						$payedMembersCount += $count - 6;
					}
				}
				$groupLimit->create();
				$groupLimit->save(array(
					'owner_id' => $ownerId,
					'members_used' => $payedMembersCount,
					'members_limit' => (($ownerId == 67) ? 199999999999 : 0),
				));
			}

		}
		return true;
	}
}
