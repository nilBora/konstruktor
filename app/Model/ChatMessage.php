<?php
App::uses('AppModel', 'Model');
class ChatMessage extends AppModel {
	public $validate = array(
		'message' => array(
			'rule' => 'notEmpty'
		),
	);
}
