<?php
App::uses('AppModel', 'Model');

class UserEventEvent extends AppModel {

	public $useTable = 'user_event_events';

	public $belongsTo = array(
		'ChatMessage' => array(
			'className' => 'ChatMessage',
			'foreignKey' => 'msg_id',
		),
		'Media' => array(
			'className' => 'Media',
			'foreignKey' => 'file_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Recepient' => array(
			'className' => 'User',
			'foreignKey' => 'recepient_id',
		)
  );

	public function addComment($data) {
		$this->loadModel('ChatMessage');
		$this->loadModel('User');
		$this->loadModel('Article');

		$event_id = Hash::get($data, 'UserEventEvent.event_id');
		$user_id = Hash::get($data, 'UserEventEvent.user_id');
		$recepient_id = Hash::get($data, 'UserEventEvent.recepient_id');
		$message = Hash::get($data, 'UserEventEvent.description');
		if(!empty($message)){
			if (!$this->ChatMessage->save($data = compact('message'))) {
				throw new Exception("Message cannot be saved\n".print_r($data, true));
			}
			$msg_id = $this->ChatMessage->id;
		}else{
			$msg_id = null;
		}
		$file_id= Hash::get($data, 'UserEventEvent.file_id');
		if(!empty($file_id)){
			/*if (!$this->ChatMessage->save($data = compact('message'))) {
				throw new Exception("Message cannot be saved\n".print_r($data, true));
			}
			$file_id = $this->ChatMessage->id;*/
			$file_id = null;
		}else{
			$file_id = null;
		}

		//$file_id = Hash::get($data, 'UserEventEvent.description');

		$this->addEvent($event_id, $user_id, $recepient_id, $msg_id,  $file_id);
		return true;
	}

	public function addEvent($event_id, $user_id, $recepient_id, $msg_id, $file_id = null) {
		$data = compact('event_id', 'user_id','recepient_id', 'msg_id', 'file_id');
		$this->clear();
		if (!$this->save($data)) {
			throw new Exception("Article event cannot be saved\n".print_r($data, true));
		}
		echo  $this->id;
	}

}
