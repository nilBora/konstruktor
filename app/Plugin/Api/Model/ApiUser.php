<?php
/**
* файл модели ApiUser
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
App::uses('UserAcnievement', 'Model');
App::uses('Group', 'Model');
App::uses('GroupCategory', 'Model');
App::uses('Country', 'Model');
App::uses('CakeEmail', 'Network/Email');

/**
* Модель ApiUser. Обертка под модель User
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class ApiUser extends AppModel {

	public $useTable = 'users';
	public $validate = array(
		'username' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			),
			'checkEmail' => array(
				'rule' => 'email',
				'message' => 'Email is incorrect'
			),
		),
		'password' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'checkPswLen' => array(
				'rule' => array('between', 4, 15),
				'message' => 'The password must be between 4 and 15 characters'
			),
		),
		'search_query' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'queryLen' => array(
				'rule' => array('minLength', 3),
				'message' => 'Minimum Length is 3 characters'
			),
		),
		'birthday' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
		'live_country' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'countryCheck' => array(
				'rule' => 'inCountryList',
				'message' => 'Incorrect Country'
			),
		),
		'lang' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'langCheck' => array(
				'rule' => array('inList',array('rus','eng')),
				'message' => 'Incorrect Language'
			),
		),
		'timezone' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'timezoneCheck' => array(
				'rule' => 'inTimeZoneCheck',
				'message' => 'Incorrect Timezone'
			),
		)
	);

	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('Group');
	}

	public function inCountryList($check){
		$this->loadModel('Country');
		$countryList = $this->Country->find('list', array('fields'=>array('Country.country_code')));
		return in_array(strtoupper($check['live_country']), $countryList);
	}

	public function inTimeZoneCheck($check){
		$this->loadModel('Timezone');
		$timezones = $this->Timezone->options();
		return array_key_exists($check['timezone'], $timezones);
	}

	/**
	* Выдает информацию о пользователе
	*
	* @param array $data
	* @return array
	*/
	public function register($data) {
		$this->User->set($data);
		if(!$this->User->validates()){
			throw new ApiIncorrectRequestException($this->User->validationErrors);
		}

		if(!in_array($data['lang'],array('eng','rus'))){
			$errors = array('lang'=>array('Incorrect Lang'));
			throw new ApiIncorrectRequestException($errors);
		}

		if($data['full_name']) {
			if(isset($data['surname']) and $data['surname']) {
				$data['full_name'] = $data['full_name'].' '.$data['surname'];
			}
		} else {
			list($userName) = explode('@', $data['username']);
			$data['full_name'] = $userName;
		}
		unset($data['surname']);

		if(isset($data['latitude']) && isset($data['longitude'])) {
			$data['lat'] = $data['latitude'];
			$data['lng'] = $data['longitude'];
			unset($data['latitude']);
			unset($data['longitude']);
		}
		//load on Register GeoLocation Behaviour
		$this->User->Behaviors->load('OnRegisterGeoLocation');

		if ($this->User->save($data)) {

				$user = $this->User->findByUsername($data['username']);
				$userId = Hash::get($user, 'User.id');
				$userName = Hash::get($user, 'User.full_name');
				$userMail = Hash::get($user, 'User.username');

				$pass = substr($user['User']['password'], 0, 5);
				$pass = md5('iP5UxZWIbVJ1XJIW'.$pass);

				$Email = new CakeEmail();
				$Email->template('reg_confirm', 'mail')->viewVars( array('userId' => $userId, 'userName' => $userName, 'userMail' => $userMail, 'token' => $pass))
					->emailFormat('html')
					->from('support@konstruktor.com')
					->to($data['username'])
					->subject('Verify registration on Konstruktor.com')
					->send();
				return $userId;
			}else{
				throw new Exception('Registration Error');
			}
	}

	public function isActive($userId){
		$result = $this->User->field('id',array('is_confirmed'=>1,'id'=>$userId));
		if(!$result){
			return false;
		}
		return true;
	}

	public function getFavList($userId) {
		$this->loadModel('Apiv2.ApiFavouriteUser');
		return array_values($this->ApiFavouriteUser->getUserFavourites($userId));
	}

	/**
	* Выдает информацию о пользователе
	*
	* @param int $userId
	* @param bool $full полная информация о пользовалетеле(true-полная, false - сокращенная)
	* @return array
	*/
	public function getInfo($userId,$full=false) {

		$fields = array('User.id','User.full_name','User.skills','User.video_url','User.phone','User.birthday as birth_date',
							'User.live_country as country','User.live_place as city','User.is_confirmed','User.university','User.speciality','User.lang','User.timezone',
                            'User.interests','User.rating','UserMedia.*','UniversityMedia.*'
					);
		if($full){
			$this->User->bindModel(
				array('hasMany' => array(
						'Article' => array(
							'className' => 'Article',
							'foreignKey' => 'owner_id',
							'fields' =>array('Article.id','Article.title','Article.cat_id'),
							'conditions' => 'Article.published = 1'
						),
					)
				)
			);
		}
		$userData = $this->User->findById($userId,$fields);

		return $this->formatUserData($userData);
	}


	/**
	* Форматирует ответ для информации о пользователе
	*
	* @param array $userData
	* @return array
	*/
	private function formatUserData($userData) {

		if (!$userData) {
			return array();
		}

		if ($userData['User']['birth_date']) {
			$userData['User']['birth_date'] = $userData['User']['birth_date'].'T00:00:00Z';
		}

		$userData['User']['fullsize_image'] = $userData['UserMedia']['url_download'];
		$userData['User']['url_image'] = $userData['UserMedia']['url_img'];
		unset($userData['UserMedia']);

		$userData['User']['university_image'] = $userData['UniversityMedia']['url_img'];
		unset($userData['UniversityMedia']);

		if (isset($userData['UserAchievement'])) {
			foreach ($userData['UserAchievement'] as &$achievment) {
				unset($achievment['created']);
				unset($achievment['user_id']);
			}
		}
		if (isset($userData['Article'])) {
			foreach ($userData['Article'] as &$article) {
				unset($article['owner_id']);
			}
		}
		return $userData;
	}

	/**
	* Поиск по группам и/или юзерам
	*
	* @param int $userId
	* @param string $query
	* @param array $models Указывается список моделей, по которым ведеться поиск
	* @return array
	*/
	public function search($userId,$query,$models=array('User','Group')){

		$this->Group->unbindModel(
			array('hasMany' => array('GroupAddress','GroupAchievement','GroupGallery','GroupVideo'))
		);
		$this->User->unbindModel(
			array('hasMany' => array('UserAchievement'))
		);
		foreach ($models as $model){
			$data[$model] = $this->{$model}->search($userId,$query);
		}
		return $this->formatSearchResults($data);

	}

	/**
	* Форматирует ответ для поиска по группам и/или пользователям
	*
	* @param array $data
	* @return array
	*/
	private function formatSearchResults($data) {
		if(isset($data['Group'])){
		$aResult['Groups'] = array();

		$this->loadModel('GroupCategory');
		$fields = array('GroupCategory.id','GroupCategory.name');
		$groupCategories = $this->GroupCategory->find('list',  compact('fields'));

			foreach ($data['Group'] as $id => $group) {
				$aResult['Groups'][$id]['id'] = $group['Group']['id'];
				$aResult['Groups'][$id]['title'] = $group['Group']['title'];
				$aResult['Groups'][$id]['headline'] = $group['Group']['descr'];
				$aResult['Groups'][$id]['image'] = $group['GroupMedia']['url_img'];
				$aResult['Groups'][$id]['rating'] = $group['Group']['rating'];
				if(isset($group['Group']['cat_id'])){
					$aResult['Groups']['category_name'] = $groupCategories[$group['Group']['cat_id']];
				}
			}
		}
		if(isset($data['User'])){
		$aResult['Users'] = array();
			foreach ($data['User'] as $id => $user) {
				$aResult['Users'][$id]['id'] = $user['User']['id'];
				$aResult['Users'][$id]['title'] = $user['User']['full_name'];
				$aResult['Users'][$id]['headline'] = $user['User']['skills'];
				$aResult['Users'][$id]['image'] = $user['UserMedia']['url_img'];
				$aResult['Users'][$id]['rating'] = $user['User']['rating'];
			}
		}

		return $aResult;
	}

	/**
	* Сохранение в таблицу
	*
	* @param array $data
	* @return bool
	*/
	public function saveInfo($data){
		//при обновлении вычиляем те айдишники адресов и достижений, которые не указаны в запросе удаляются
		if(isset($data['User']['id'])){
			$userAchIds = Hash::extract($data, 'UserAchievement.{n}.id');

			$conditions = array('user_id'=>$data['User']['id']);
			$fields = array('id');

			$this->loadModel('UserAchievement');
			$savedAchIds = $this->UserAchievement->find('list',compact('conditions','fields'));
			$deleteAchIds = array_diff($savedAchIds,$userAchIds);
			$this->UserAchievement->delete($deleteAchIds);
		}
		if(!$this->User->saveAll($data)){
			throw new Exception('Server Error');
		}
		return true;
	}

	 /**
	 * Установить пользователю навыки
	 *
	 * @param array $data
	 * @return bool
	 */
	public function setSkills($userId, $skills) {
		$this->User->id=$userId;
		if(!$this->User->saveField("skills",$skills)) {
			throw new Exception('Server Error');
		}
		return true;
	}
	/**
	 * Установить пользователю интересы
	 *
	 * @param array $data
	 * @return bool
	 */
	public function setInterest($userId, $interest) {
		$this->User->id=$userId;
		if(!$this->User->saveField("interests",$interest)) {
			throw new Exception('Server Error');
		}
		return true;
	}

	public function fbAuth($fb_user) {
		$user = $this->User->findByFbid($fb_user['id']);
		if(!$user)
			$user = $this->User->findByUsername($fb_user['email']);

		if(!$user) {
			$timezones = array(
					'-12'=>'Pacific/Kwajalein',
					'-11'=>'Pacific/Samoa',
					'-10'=>'Pacific/Honolulu',
					'-9'=>'America/Juneau',
					'-8'=>'America/Los_Angeles',
					'-7'=>'America/Denver',
					'-6'=>'America/Mexico_City',
					'-5'=>'America/New_York',
					'-4'=>'America/Caracas',
					'-3.5'=>'America/St_Johns',
					'-3'=>'America/Argentina/Buenos_Aires',
					'-2'=>'Atlantic/Azores',
					'-1'=>'Atlantic/Azores',
					'0'=>'Europe/London',
					'1'=>'Europe/Paris',
					'2'=>'Europe/Helsinki',
					'3'=>'Europe/Moscow',
					'3.5'=>'Asia/Tehran',
					'4'=>'Asia/Baku',
					'4.5'=>'Asia/Kabul',
					'5'=>'Asia/Karachi',
					'5.5'=>'Asia/Calcutta',
					'6'=>'Asia/Colombo',
					'7'=>'Asia/Bangkok',
					'8'=>'Asia/Singapore',
					'9'=>'Asia/Tokyo',
					'9.5'=>'Australia/Darwin',
					'10'=>'Pacific/Guam',
					'11'=>'Asia/Magadan',
					'12'=>'Asia/Kamchatka'
			);

			$username = $fb_user['email'];

			if(isset($fb_user['first_name']) && $fb_user['last_name'])
				$full_name = $fb_user['first_name']." ".$fb_user['last_name'];
			else
				$full_name = list($userList) = explode('@', $fb_user['email']);

			$tz = $fb_user['timezone'];
			$timezone = isset($timezones[$tz]) ? $timezones[$tz] : null;
			$fbid = $fb_user['id'];

			$password = rand(1000000000, 9999999999);
			$if_confirmed = '1';
			$data = array(
					'fbid' => $fbid,
					'is_confirmed' => $if_confirmed,
					'username' => $username,
					'password' =>$password,
					'full_name' => $full_name,
					'timezone' => $timezone,
			);
			if(isset($fb_user['lat'])) {
				$data['lat'] = $fb_user['lat'];
			}
			if(isset($fb_user['lng'])) {
				$data['lng'] = $fb_user['lng'];
			}
			//load on Register GeoLocation Behaviour
			$this->User->Behaviors->load('OnRegisterGeoLocation');

			if(!$this->User->save($data)) {
				throw new ApiIncorrectRequestException();
			}

			$token = $this->getCheckToken($this->User->id);

			return $token;
		} else {
			$this->User->Behaviors->load('OnRegisterGeoLocation');

			if( (isset($user['User']['fbid']) && !empty($user['User']['fbid'])) || $user['User']['fbid'] != null ) {
				$user = $this->User->findByFbid($user['User']['fbid']);
				if(!$user) {
					throw new ApiIncorrectRequestException();
				}

				if(isset($fb_user['lat']) && isset($fb_user['lng'])) {
					$this->User->updateAll(
							array('User.lat' => $fb_user['lat'],
								  'User.lng' => $fb_user['lng']),
							array('User.id' => $user['User']['id'])
					);
				}
				$token = $this->getCheckToken($user['User']['id']);
				return $token;
			} else {
				if(isset($fb_user['lat']) && isset($fb_user['lng'])) {
					$this->User->updateAll(
							array('User.lat' => $fb_user['lat'],
								  'User.lng' => $fb_user['lng'],
								  'User.fbid' => $fb_user['id']),
							array('User.id' => $user['User']['id'])
					);
				} else {
					$this->User->updateAll(
							array('User.fbid' => $fb_user['id']),
							array('User.id' => $user['User']['id'])
					);
				}
				$token = $this->getCheckToken($user['User']['id']);
				return $token;
			}
		}
	}

	public function getCheckToken($userId) {
		$this->loadModel('ApiAccess');
		$apiAccess = $this->ApiAccess->findByUserId($userId);

		// if(!$apiAccess) {
		// 	//формирование и отправка токена
		// 	$token = $this->ApiAccess->getToken($userId);
		// } else {
		// 	$token = $apiAccess['ApiAccess']['token'];
		// }
		$token = $this->ApiAccess->getToken($userId);
		return $token;
	}
}
