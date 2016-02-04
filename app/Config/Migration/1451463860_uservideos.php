<?php
class UserVideos extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'UserVideos';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'group_videos' => array(
					'media_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'video_id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'media_id'),
				),
				'media' => array(
					'converted' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'after' => 'orig_h'),
				),
			),
			'create_table' => array(
				'user_videos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'created' => array('type' => 'timestamp', 'null' => false, 'default' => null),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'media_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'object_type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'group_videos' => array('media_id', 'user_id'),
				'media' => array('converted'),
			),
			'drop_table' => array(
				'user_videos'
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
