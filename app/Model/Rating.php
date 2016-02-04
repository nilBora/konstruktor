<?php
App::uses('AppModel', 'Model');
/**
 * Rating Model
 *
 */
class Rating extends AppModel {

	public $belongsTo = array(
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'foreign_id',
			'conditions' => array('foreign_model' => 'User'),
			'sumCache' => 'karma',
			'sumField' => 'value',
			'sumScope' => array('foreign_model' => 'User'),
        ),
		'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'foreign_id',
			'conditions' => array('foreign_model' => 'Group'),
			'sumCache' => 'karma',
			'sumField' => 'value',
			'sumScope' => array('foreign_model' => 'Group'),
        ),
	);


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'foreign_model' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'foreign_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

}
