<?php
/**
* файл модели ApiUserEvent
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('UserEvent', 'Model');

/**
* Модель ApiUserEvent.
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiUserEvent extends AppModel {
	
	const MEET = 1;
	const MAIL = 2;
	const CALL = 3;
	
	static $types = array(self::MEET=>'meet',self::MAIL=>'mail',self::CALL=>'call');

	public $useTable = 'user_events';
	
	public $validate = array(
		'event_time' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'isoDateTimeCheck' => array(
				'rule' => 'isoDateTimeCheck',
				'message' => 'Incorrect datetime format'
			),
		),
		'event_end_time' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'isoDateTimeCheck' => array(
				'rule' => 'isoDateTimeCheck',
				'message' => 'Incorrect datetime format'
			),
		),
		'recipient_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'checkRecipient' => array(
				'rule' => 'numeric',
				'message' => 'Only digits'
			),
		),
		'task_id' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'checkTask' => array(
				'rule' => 'numeric',
				'message' => 'Only digits'
			),
		),
		'type' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'typeCheck' => array(
				'rule' => array('inList',array(self::MEET,self::MAIL,self::CALL)),
				'message' => 'Incorrect event type'
			),
		),
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
		'descr' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
	);
	
	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('UserEvent');
	}
	
	public function isoDateTimeCheck($check){
		$field = (isset($check['event_time']))? $check['event_time']:$check['event_end_time'];
		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $field, $parts) == true) {
			$time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

			$input_time = strtotime($field);
			if ($input_time === false){ 
				return false;	
			}
			return $input_time == $time;
		} 
		return false;
	}
	
	/**
	* Проверка существования эвента
	* 
	* @param int $userId 
	* @param int $eventId  
	* @return bool
	*/
	public function isUserEventExist($userId,$eventId){
		$result = $this->UserEvent->field('id',array('user_id'=>$userId,'id'=>$eventId));
		if($result){
			return true;
		}
		return false;
	}
	
	/**
	* Сохранить эвент
	* 
	* @param array $data   
	* @return int
	*/
	public function saveUserEvent($data){
		$this->UserEvent->save($data);
		return $this->UserEvent->id;
	}
	
	/**
	* Удалить эвент
	* 
	* @param int $eventId  
	* @return void
	*/
	public function deleteUserEvent($eventId){
		$this->UserEvent->delete($eventId);
	}
}
?>
