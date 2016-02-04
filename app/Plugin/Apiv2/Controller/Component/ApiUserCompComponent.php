<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
App::uses('L10n', 'I18n');
class ApiUserCompComponent extends Component {

	private $_controller;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}

	/**
	* Action для логина
	* @uses Controller::request
	* @return void
	*/
	public function login() {
			if (!isset($this->_controller->request->data['username']) or !isset($this->_controller->request->data['password'])) {
				throw new ApiIncorrectRequestException();
			}

			$this->_controller->ApiUser->set(array('username' => $this->_controller->request->data['username'], 'password' => $this->_controller->request->data['password']));

			if (!$this->_controller->ApiUser->validates()) {
				throw new ApiUnauthorizedException();
			}

			//проверка существования пользователя
			$userId = $this->_controller->ApiUser->field('ApiUser.id', array(
				'ApiUser.username' => $this->_controller->request->data['username'],
				'ApiUser.password' => AuthComponent::password($this->_controller->request->data['password'])
				//'ApiUser.is_confirmed' => 1 - теперь пускаем и неподтвежденных
			));

			if (!$userId) {
				throw new ApiUnauthorizedException();
			}

			$apiAccess = $this->_controller->ApiAccess->findByUserId($userId);

			if(!$apiAccess) {
				//формирование и отправка токена
				$token = $this->_controller->ApiAccess->getToken($userId);
			} else {
				$token = $apiAccess['ApiAccess']['token'];
			}

			$this->_controller->setResponse(array('access_token' => $token));
	}

	/**
	* Регистрация
	* @uses Controller::request
	* @return void
	*/
	public function register() {
			$aFields = array('username','skills','password','lang');
			foreach ($aFields as $field){
				if (!isset($this->_controller->request->data[$field])) {
					throw new ApiIncorrectRequestException();
				}
			}
			$this->_controller->request->data('timezone', 'Europe/London');
			$userId = $this->_controller->ApiUser->register($this->_controller->request->data);

			if(!$userId){
				throw new Exception();
			}
			$token = $this->_controller->ApiAccess->getToken($userId);
			$this->_controller->setResponse(array('access_token' => $token));
	}

	/**
	* Информация о пользователе
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function info(){
		$userData = $this->_controller->ApiUser->getInfo($this->_userId);
		$this->_controller->setResponse($userData);
	}

	/**
	* Поиск по пользователям и группам
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function search() {
			if (!isset($this->_controller->request->data['search_query'])) {
				throw new ApiIncorrectRequestException();
			}

			$this->_controller->ApiUser->set(array('search_query' => $this->_controller->request->data['search_query']));
			if (!$this->_controller->ApiUser->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUser->validationErrors);
			}

			$searchResult = $this->_controller->ApiUser->search($this->_userId, $this->_controller->request->data['search_query']);
			$this->_controller->setResponse($searchResult);
	}

	/**
	* Поиск по пользователям
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function search_users(){
			if (!isset($this->_controller->request->data['search_query'])) {
				throw new ApiIncorrectRequestException();
			}

			$this->_controller->ApiUser->set(array('search_query' => $this->_controller->request->data['search_query']));
			if (!$this->_controller->ApiUser->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUser->validationErrors);
			}

			$searchResult = $this->_controller->ApiUser->search($this->_userId, $this->_controller->request->data['search_query'],array('User'));
			$this->_controller->setResponse($searchResult);
	}

	/**
	* Поиск пользователя по ID
	*
	* @uses Controller::request
	* @uses ApiController::_userId
	* @return void
	*/
	public function user_info(){
			if (!isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUser->validationErrors);
			}

			$userData = $this->_controller->ApiUser->getInfo($this->_controller->request->data['user_id'],true);
			if($userData){
				$userData['Group'] = $this->_controller->ApiGroupMember->getUserGroupsList($this->_controller->request->data['user_id']);
				if($this->_userId != $this->_controller->request->data['user_id']){
					$userData['User']['is_subscribed'] = $this->_controller->ApiSubscription->isSubscribed($this->_userId,$this->_controller->request->data['user_id'],$this->_controller->ApiSubscription->getUserType());
				}
				$favourite = $this->_controller->ApiFavouriteUser->getFavouriteInfo($this->_controller->request->data['user_id'],$this->_userId);
				if($favourite){
					$userData['FavouriteList'] = $favourite;
				}
			}
			$this->_controller->_setStatParams('User', 'view', $this->_controller->request->data['user_id']);
			$this->_controller->setResponse($userData);
	}

	/**
	* Устанавливает изображение аватарки пользователя
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function set_user_image(){
			$this->_controller->saveImage('User', $this->_userId);
	}

	/**
	* Устанавливает изображение аватарки группы
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function set_university_image(){
		$this->_controller->saveImage('UserUniversity', $this->_userId);
	}



	/**
	* Обновление профиля пользователя
	*
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function update_user_profile(){
			$this->_controller->request->data('data.User.id', $this->_userId);
			$url = $this->_controller->request->data('data.User.video_url');
			$this->_controller->request->data('data.User.video_id', str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $url));

			if ($this->_controller->request->data('data.UserAchievement')) {
				foreach($this->_controller->request->data('data.UserAchievement') as $i => $data) {
					$this->_controller->request->data('data.UserAchievement.'.$i.'.user_id',$this->_userId);
					$url = $this->_controller->request->data('data.UserAchievement.'.$i.'.url');
					$url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
					$this->_controller->request->data('data.UserAchievement.'.$i.'.url', $url);
				}
			}

			$this->_controller->ApiUser->set($this->_controller->request->data['data']['User']);
			if(!$this->_controller->ApiUser->validates()){
				throw new ApiIncorrectRequestException($this->_controller->ApiUser->validationErrors);
			}

			if(isset($this->_controller->request->data['data']['User']['birthday'])){
				$birthday = str_replace('T', ' ', $this->_controller->request->data['data']['User']['birthday']);
				$birthday = str_replace('Z', '', $birthday);
				$birthday = date('Y-m-d',  strtotime($birthday));
				$this->_controller->request->data('data.User.birthday',$birthday);
			}

			$this->_controller->ApiUser->saveInfo($this->_controller->request->data('data'));
			$this->_controller->setResponse();
	}

	/**
	 * Установить навыки
	 *
	 * @uses ApiController::_userId
	 * @uses Controller::request
	 * @return void
	 */
	public function set_user_skills() {
		if (!isset($this->_controller->request->data['skills'])) {
			throw new ApiIncorrectRequestException();
		}
		$this->_controller->ApiUser->setSkills($this->_userId, $this->_controller->request->data['skills']);
		$this->_controller->setResponse();
	}

	/**
	 * Установить интересы
	 *
	 * @uses ApiController::_userId
	 * @uses Controller::request
	 * @return void
	 */
	public function set_user_interest() {
		if (!isset($this->_controller->request->data['interest'])) {
			throw new ApiIncorrectRequestException();
		}
		$this->_controller->ApiUser->setInterest($this->_userId, $this->_controller->request->data['interest']);
		$this->_controller->setResponse();
	}

	public function get_list_interest() {
		if(!isset($this->_controller->request->data['locale']))
			$locale = 'ru';
		$locale = substr($this->_controller->request->data['locale'], 0, 2);
		CakeSession::write('Config.language', $locale);

		$data[] = array('title'=>__('Health'), 'image'=>"/img/interests/medicine.jpg");
		$data[] = array('title'=>__('Astronomy'), 'image'=>"/img/interests/astronomy.jpg");
		$data[] = array('title'=>__('Sciense'), 'image'=>"/img/interests/science.jpg");
		$data[] = array('title'=>__('Finances'), 'image'=>"/img/interests/finance.jpg");
		$data[] = array('title'=>__('Travel'), 'image'=>"/img/interests/travel.jpg");
		$data[] = array('title'=>__('Donation'), 'image'=>"/img/interests/donate.jpg");
		$data[] = array('title'=>__('Technology'), 'image'=>"/img/interests/technology.jpg");
		$data[] = array('title'=>__('Nature'), 'image'=>"/img/interests/nature.jpg");
		$data[] = array('title'=>__('Architecture'), 'image'=>"/img/interests/architecture.jpg");
		$data[] = array('title'=>__('Photography'), 'image'=>"/img/interests/photography.jpg");
		$data[] = array('title'=>__('Music'), 'image'=>"/img/interests/music.jpg");
		$data[] = array('title'=>__('Humor'), 'image'=>"/img/interests/humor.jpg");
		$data[] = array('title'=>__('Fashion'), 'image'=>"/img/interests/fashion.jpg");
		$data[] = array('title'=>__('Tattoo'), 'image'=>"/img/interests/tatoo.jpg");
		$data[] = array('title'=>__('Weapon'), 'image'=>"/img/interests/weapon.jpg");
		$data[] = array('title'=>__('Culture'), 'image'=>"/img/interests/culture.jpg");
		$data[] = array('title'=>__('Startup'), 'image'=>"/img/interests/startup.jpg");
		$data[] = array('title'=>__('Pets'), 'image'=>"/img/interests/pets.jpg");
		$data[] = array('title'=>__('Cars'), 'image'=>"/img/interests/cars.jpg");
		$data[] = array('title'=>__('Active life'), 'image'=>"/img/interests/leisure.jpg");
		$data[] = array('title'=>__('IT'), 'image'=>"/img/interests/IT.jpg");
		$data[] = array('title'=>__('Cooking'), 'image'=>"/img/interests/culinary.jpg");
		$data[] = array('title'=>__('Literature'), 'image'=>"/img/interests/literature.jpg");
		$data[] = array('title'=>__('Entertainment'), 'image'=>"/img/interests/entertainment.jpg");
		$data[] = array('title'=>__('Electronics'), 'image'=>"/img/interests/electronic.jpg");

		$this->_controller->setResponse($data);
	}

	public function get_list_skills() {
		if(!isset($this->_controller->request->data['locale']))
			$locale = 'ru';
		$locale = substr($this->_controller->request->data['locale'], 0, 2);
		CakeSession::write('Config.language', $locale);

		$data[] = array('title'=>__('Designer'), 'image'=>"/img/skills/designer.jpg");
		$data[] = array('title'=>__('IT specialist'), 'image'=>"/img/skills/it-specialist.jpg");
		$data[] = array('title'=>__('Agronomist'), 'image'=>"/img/skills/agronomist.jpg");
		$data[] = array('title'=>__('Architector'), 'image'=>"/img/skills/architect.jpg");
		$data[] = array('title'=>__('Lawyer'), 'image'=>"/img/skills/lawyer.jpg");
		$data[] = array('title'=>__('Driver'), 'image'=>"/img/skills/driver.jpg");
		$data[] = array('title'=>__('Writer'), 'image'=>"/img/skills/writer.jpg");
		$data[] = array('title'=>__('Sportsman'), 'image'=>"/img/skills/sportsman.jpg");
		$data[] = array('title'=>__('Doctor'), 'image'=>"/img/skills/doctor.jpg");
		$data[] = array('title'=>__('Scientist'), 'image'=>"/img/skills/scientist.jpg");
		$data[] = array('title'=>__('Marketer'), 'image'=>"/img/skills/marketer.jpg");
		$data[] = array('title'=>__('Businessman'), 'image'=>"/img/skills/businessman.jpg");
		$data[] = array('title'=>__('Teacher'), 'image'=>"/img/skills/teacher.jpg");
		$data[] = array('title'=>__('Cook'), 'image'=>"/img/skills/cook.jpg");
		$data[] = array('title'=>__('Manager'), 'image'=>"/img/skills/manager.jpg");
		$data[] = array('title'=>__('Artist'), 'image'=>"/img/skills/artist.jpg");
		$data[] = array('title'=>__('Photographer'), 'image'=>"/img/skills/photographer.jpg");
		$data[] = array('title'=>__('Ecologist'), 'image'=>"/img/skills/ecologist.jpg");
		$data[] = array('title'=>__('Engineer'), 'image'=>"/img/skills/engineer.jpg");
		$data[] = array('title'=>__('Economist'), 'image'=>"/img/skills/economist.jpg");
		$data[] = array('title'=>__('Soldier'), 'image'=>"/img/skills/soldier.jpg");
		$data[] = array('title'=>__('Logistician'), 'image'=>"/img/skills/logistician.jpg");
		$data[] = array('title'=>__('Journalist'), 'image'=>"/img/skills/journalist.jpg");
		$data[] = array('title'=>__('Builder'), 'image'=>"/img/skills/builder.jpg");

		$this->_controller->setResponse($data);
	}

}
?>
