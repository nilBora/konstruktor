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

	const CALL = 0; /* Телефонный звонок */
	const CONFERENCE = 1; /* Conference */
	const MAIL = 2; /* Email */
	const ENTERTAINMENT = 3; /* Развлечения */
	const MEET = 4;
	const PAYMENT = 5; /* Оплата */
	const PURCHARES = 6; /* Покупка */
	const SPORT = 7; /* Спорт */
	const TASK = 8; /* Задача */


	
	static $types = array(self::CALL=>'call',
						self::CONFERENCE=>'conference',
						self::MAIL=>'mail',
						self::ENTERTAINMENT=>'entertain',
						self::MEET=>'meet',
						self::PAYMENT=>'payment',
						self::PURCHARES=>'Purchase',
						self::SPORT=>'sport',
						self::TASK=>'task');

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

	public function saveUserEventShares($data) {
		$this->loadModel('UserEventShare');
		$this->UserEventShare->save($data);
		return $this->UserEventShare->id;
	}

	/**
	 * Принятие/отклонение чужого события
	 *
	 * @param int $userId
	 * @param void $data
	 * @return void
	 */
	public function acceptUserEvent($userId, $data) {
		$this->loadModel('UserEventShare');
		$this->UserEventShare->updateAll(
				array('UserEventShare.acceptance' => ($data['accept']) ? 1 : -1),
				array('UserEventShare.user_event_id' => $data['user_event_id'],
						'UserEventShare.user_id' => $userId)
		);
		$result = $this->UserEventShare->field('id',array('user_id'=>$userId,'user_event_id'=>$data['user_event_id']));
		return $result;
	}
}
?>
