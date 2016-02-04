<?php
App::uses('AppModel', 'Model');
/**
 * GroupLimit Model
 *
 * @property Owner $Owner
 * @property Group $Group
 */
class GroupLimit extends AppModel {

    public $useTable = 'group_limits';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'owner_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

}
