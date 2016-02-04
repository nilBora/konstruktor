<?php
App::uses('AppModel', 'Model');

class UserEventRequest extends AppModel {

	public $useTable = 'user_event_requests';

	public $belongsTo = array(
		'UserEvent' => array(
			'className' => 'UserEvent',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public $actsAs = array('EventRequestsLimitable');

	public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['user_id'])){
			return $this->checkUsedRequests($this->data[$this->alias]['user_id']);
        }
		return false;
    }

	public function afterSave($created, $options = array()){
		if(isset($this->data[$this->alias]['user_id'])){
			$this->countUsedRequests($this->data[$this->alias]['user_id']);
        }
		return true;
    }
}
