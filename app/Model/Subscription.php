<?
App::uses('AppModel', 'Model');
class Subscription extends AppModel {

	public $actsAs = array('Ratingable');
	
	public function beforeSave($options = array()) {
		$this->loadModel('User');
		$uid = $this->data['Subscription']['subscriber_id'];
		$user = array('id' => $uid, 'news_update' => date('Y-m-d H:i:s'));
		$this->User->save($user);
		return true;
	}















}
