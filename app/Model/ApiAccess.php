<?php
/**
* файл модели ApiAccess 
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');

/**
* Модель ApiAccess
*
* Авторизация, выдача, обновление, проверка токенов
*  
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiAccess extends AppModel {

	public $validate = array(
		'token' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		)
	);
	
	/**
	* Выдает новый токен, записывает его в таблицу api accesses
	*  
	* @param int $userId
	* @throws Exception при ошибки сохранения в таблицу 
	* @return string
	*/
	public function getToken($userId) {

		$token = md5(microtime() . Configure::read('api_token_salt') . $userId);

		$id = $this->field('id', array(
			'ApiAccess.user_id' => $userId,
			'ApiAccess.created >= ' => date('Y-m-d H:i:s', strtotime("-" . Configure::read('api_token_expire_days') . " days"))
		));

		$data = array('user_id' => $userId, 'token' => $token);
		if ($id) {
			$data['id'] = $id;
		}

		if (!$this->save($data)) {
			throw new Exception("Data cannot be saved");
		}
		return $token;
	}

	/**
	* Ищет пользователя в таблице api_accesses по токену 
	*  
	* @param string $token 
	* @return int
	*/
	public function getUserByToken($token) {
		$userId = $this->field('user_id', array(
			'ApiAccess.token' => $token,
			'ApiAccess.created >= ' => date('Y-m-d H:i:s', strtotime("-" . Configure::read('api_token_expire_days') . " days"))
		));
		return $userId;
	}

}

?>
