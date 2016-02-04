<?php

App::uses('ApiUser', 'Api.Model');
App::uses('ApiGroup', 'Api.Model');
App::uses('Group', 'Model');
App::uses('ApiGroupMember', 'Api.Model');
App::uses('ApiAccess', 'Api.Model');
App::uses('User', 'Model');
App::uses('Media', 'Media.Model');
App::uses('ApiArticle', 'Api.Model');
App::uses('ApiArticleEvent', 'Api.Model');
App::uses('ApiSubscription', 'Api.Model');
App::uses('ApiProject', 'Api.Model');
App::uses('ApiProjectMember', 'Api.Model');
App::uses('ApiProjectEvent', 'Api.Model');
App::uses('ApiTask', 'Api.Model');
App::uses('ApiSubproject', 'Api.Model');
App::uses('ApiNote', 'Api.Model');
App::uses('ApiChatContact', 'Api.Model');
App::uses('ApiChatEvent', 'Api.Model');
App::uses('ApiChatMember', 'Api.Model');
App::uses('ApiChatRoom', 'Api.Model');
App::uses('ApiTimeline', 'Api.Model');
App::uses('ApiUserEvent', 'Api.Model');

class TestController extends AppController {

	public $uses = array(
		'Api.ApiUser','Api.ApiGroup','Group','Api.ApiGroupMember', 'ApiAccess', 'User', 'Media.Media',
		'Api.ApiSubscription','Api.ApiArticle','Api.ApiArticleEvent','Api.ApiProject','Api.ApiProjectMember',
		'Api.ApiProjectEvent','Api.ApiTask','Api.ApiSubproject','Api.ApiNote','Api.ApiChatContact','Api.ApiChatEvent',
		'Api.ApiChatMember','Api.ApiChatRoom','Api.ApiTimeline','Api.ApiUserEvent'
	);
	public $tests = array();
	public $layout = 'test';

	private $_startTime;
	private $_totalCount = 0;
	private $_successCount = 0;

	public function beforeFilter() {
		$this->layout = 'test';
		$this->Auth->allow();
		$this->_startTime = microtime();
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->set('exec_time', microtime()-$this->_startTime);
		$this->set('totalCount', $this->_totalCount);
		$this->set('successCount', $this->_successCount);
	}

//----------------------------------------------------Login------------------------//

	public function login() {
		$testDescription = 'Login Api';


		$this->testApiLogin();
		$this->testApiRegister();

		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);
	}


	private function testApiLogin() {
		//дынные
		$username = 'answer3ster@gmail.com';
		$password = '12345';

		$token = false;
		$userId = $this->ApiUser->field('ApiUser.id', array(
			'ApiUser.username' => $username,
			'ApiUser.password' => AuthComponent::password($password),
			'ApiUser.is_confirmed' => 1
		));
		$token = $this->ApiAccess->getToken($userId);


		$testItemDescr = 'Get Token';
		$this->dataTests($testItemDescr, true, $token, $this->assertTrue($token));

		//обновление токена
		$newToken = $token;
		$testItemDescr = 'Update Token';
		$newToken = $this->ApiAccess->getToken($userId);
		$this->dataTests($testItemDescr, $token, $newToken, $this->assertNotEqual($newToken, $token));
	}

		private function testApiRegister() {
		//дынные некорректны
		$data = array (
			'full_name' => 'Nikolai',
			'surname' => 'Doronin',
			'skills' => 'Chess player',
			'username' => 'answer3ster@gmail.com',
			'lang' => 'rus',
			'password' => 'qwerty1234599999999999999999999999999999',
			'timezone' => 'Europe/London'
		);

		$result = $this->ApiUser->register($data);

		$expected = Array(
					'username' => Array(
							0 => 'This email has already been used' ),
					'password' => Array(
							0 => 'The password must be between 4 and 15 characters'));

		$testItemDescr = 'Incorrect Register';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		//корректная регистрация
		$data = array (
			'full_name' => 'Nikolai',
			'surname' => 'Doronin',
			'skills' => 'Chess player',
			'username' => 'nikolai1@softinvest.by',
			'lang' => 'rus',
			'password' => 'qwerty12345',
		);

		$this->ApiUser->register($data);
		$result = $this->ApiUser->findAllByUsername('nikolai1@softinvest.by',array('username'));

		$expected = Array (
				'0' => Array (
					'ApiUser' => Array (
							'username' => 'nikolai1@softinvest.by'
						)
				)
		);
		$testItemDescr = 'Correct Register';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiUser->deleteAll(array('username'=>'nikolai1@softinvest.by'));
	}


	//---------------------------Users---------------------------------//

	public function users() {
		$testDescription = 'Api User';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));

		$this->testApiInfo();
		$this->testApiSearch();
		$this->testApiUserSearch();
		$this->testApiGroupSearch();
		$this->testApiUserInfo();
		$this->testApiEditUser();
		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);
	}

	private function testApiInfo() {

		$userData = $this->ApiUser->getInfo($this->userId);
		$expected = array(
			'User' => array(
				'id' => '183',
				'full_name' => 'алекс',
				'skills' => 'PHP , все подряд',
				'video_url' => '',
				'phone' => '',
				'birth_date' => '',
				'country' => 'AR',
				'city' => '',
				'university' => '',
				'speciality' => '',
				'lang'=>'rus',
				'timezone'=>'Europe/Paris',
				'fullsize_image' => '/files/user/8/819/image.jpg',
				'url_image' => '/media/router/index/user/819/noresize/image.jpg.png',
				'university_image' => '/img/no-photo.jpg'),
			'UserAchievement' => array(
				array(
					'id' => 32,
					'title' => 'все сделано',
					'url' => 'http://ляллял пруфа не будет'
				)
			)
		);
		$testItemDescr = 'Get Current User Info';
		$this->dataTests( $testItemDescr, $expected, $userData, $this->assertArray($expected, $userData));
	}

	private function testApiUserInfo() {

		$searchUserId = 76;

		$userData = $this->ApiUser->getInfo($searchUserId);
		$expected = array(
			'User' => array(
					'id' => 76,
					'full_name' => 'Fyr Fayar',
					'skills' => 'PHP, хрюковство',
					'video_url' => 'http://youtu.be/VtTSPap5Msg',
					'phone' => 'phone Fyr',
					'birth_date' => '1986-08-16T00:00:00Z',
					'country' => 'AF',
					'city' => 'Брест, Белорусь',
					'university' => 'Политех',
					'speciality' => 'Инженер',
					'lang' => 'rus',
					'timezone' => 'Europe/Helsinki',
					'fullsize_image' => '/files/user/8/800/image.jpg',
					'url_image' => '/media/router/index/user/800/noresize/image.jpg.png',
					'university_image' => '/media/router/index/useruniversity/796/noresize/image.jpg.png'
					),
		);
		$testItemDescr = 'Get User Info';
		$this->dataTests( $testItemDescr, $expected, $userData, $this->assertArray($expected, $userData));
	}

	private function testApiSearch() {

		$search = 'tes';
		$searchResult = $this->ApiUser->search($this->userId, $search);

		$expected = array(
			'Groups' => array(),
			'Users' => array(
				'0' => array(
					'id' => 159,
					'title' => 'ietest001@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'),
				'1' => array(
					'id' => 79,
					'title' => 'life4testing@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'),
				'2' => array(
					'id' => 67,
					'title' => 'Vlad Krishtop',
					'headline' => 'CEO of KONSTRUKTOR',
					'image' => '/media/router/index/user/647/noresize/image.jpg.png'),
				'3' => array(
					'id' => 163,
					'title' => 'Тестировщик системы',
					'headline' => 'Тестирование',
					'image' => '/media/router/index/user/711/noresize/image.jpg.png'
				)
			)
		);

		$testItemDescr = 'Search Users and Groups';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));

		//тестим для кириллицы
		$search = 'тес';
		$searchResult = $this->ApiUser->search($this->userId, $search);

		$expected = array(
			'Groups' => array
				(
				'0' => array
					(
					'id' => 26,
					'title' => 'Тест группа',
					'headline' => 'Тесто',
					'image' => '/img/no-photo.jpg'
				),
			),
			'Users' => array
				(
				'0' => array
					(
					'id' => 159,
					'title' => 'ietest001@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'
				),
				'1' => array
					(
					'id' => 79,
					'title' => 'life4testing@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'
				),
				'2' => array
					(
					'id' => 163,
					'title' => 'Тестировщик системы',
					'headline' => 'Тестирование',
					'image' => '/media/router/index/user/711/noresize/image.jpg.png'
				)
			)
		);

		$testItemDescr = 'Search Users and Groups Ciryllic';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));
	}

	private function testApiUserSearch() {

		$search = 'tes';
		$searchResult = $this->ApiUser->search($this->userId, $search, array('User'));
		//print_r($searchResult);
		$expected = array(
			'Users' => array(
				'0' => array(
					'id' => 159,
					'title' => 'ietest001@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'),
				'1' => array(
					'id' => 79,
					'title' => 'life4testing@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'),
				'2' => array(
					'id' => 67,
					'title' => 'Vlad Krishtop',
					'headline' => 'CEO of KONSTRUKTOR',
					'image' => '/media/router/index/user/647/noresize/image.jpg.png'),
				'3' => array(
					'id' => 163,
					'title' => 'Тестировщик системы',
					'headline' => 'Тестирование',
					'image' => '/media/router/index/user/711/noresize/image.jpg.png'
				)
			)
		);

		$testItemDescr = 'Search Users';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));

		$search = 'тес';
		$searchResult = $this->ApiUser->search($this->userId, $search,array('User'));

		$expected = array(
			'Users' => array
				(
				'0' => array
					(
					'id' => 159,
					'title' => 'ietest001@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'
				),
				'1' => array
					(
					'id' => 79,
					'title' => 'life4testing@gmail.com',
					'headline' => '',
					'image' => '/img/no-photo.jpg'
				),
				'2' => array
					(
					'id' => 163,
					'title' => 'Тестировщик системы',
					'headline' => 'Тестирование',
					'image' => '/media/router/index/user/711/noresize/image.jpg.png'
				)
			)
		);

		$testItemDescr = 'Search Users Ciryllic';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));
	}

	private function testApiGroupSearch() {

		$search = 'group 3';
		$searchResult = $this->ApiUser->search($this->userId, $search,array('Group'));

		$expected = array(
			'Groups' => array(
				'0' => array(
					'id' => 24,
					'title' => 'Group 3',
					'headline' => '',
					'image' => '/img/no-photo.jpg')),
		);

		$testItemDescr = 'Search Groups';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));

		//тестим для кириллицы
		$search = 'тес';
		$searchResult = $this->ApiUser->search($this->userId, $search,array('Group'));

		$expected = array(
			'Groups' => array
				(
				'0' => array
					(
					'id' => 26,
					'title' => 'Тест группа',
					'headline' => 'Тесто',
					'image' => '/img/no-photo.jpg'
				),
			),
		);

		$testItemDescr = 'Search Groups Ciryllic';
		$this->dataTests($testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));
	}


	private function testApiEditUser() {

		$saveData = array(
						'User' => array(
										'id'=>$this->userId,
										'full_name'=>'alex',
										'skills'=>'PHP and all',
										'video_url'=>'',
										'phone'=>'1234567',
										'birthday'=>'',
										'live_country'=>'RS',
										'live_place'=>'Beograd',
										'university'=>'MUni,Brno,CZ',
										'speciality'=>'Math',
										'lang'=>'rus',
										'timezone'=>'Europe/Paris'
							),
						'UserAchievement'=>array(
									'0'=>array(
										'profile_id'=>301,
										'title'=>'достижение',
										'url'=>'',
									)
						)
					);
		$this->ApiUser->saveInfo($saveData);

		$userData = $this->ApiUser->getInfo($this->userId);

		$expected = array(
			 'User' => array (
				 'id' => 183,
				 'full_name' => 'alex',
				 'skills' => 'PHP and all',
				 'video_url' =>'',
				 'phone' => '1234567',
				 'birth_date' => '',
				 'country' => 'RS',
				 'city' => 'Beograd',
				 'university' => 'MUni,Brno,CZ',
				 'speciality' => 'Math',
				 'lang'=>'rus',
				 'timezone'=>'Europe/Paris',
				 'fullsize_image' => '/files/user/8/819/image.jpg',
				 'url_image' => '/media/router/index/user/819/noresize/image.jpg.png',
				 'university_image' => '/img/no-photo.jpg'
			),
			'UserAchievement' => array(
				'0' => array (
					'title' => 'достижение',
					'url' =>'' ),
				'1' => array (
					'title' => 'все сделано',
					'url' => 'http://ляллял пруфа не будет'
					)
				)
		);

		if(isset($userData['UserAchievement'])){
			$achievementId = $userData['UserAchievement'][0]['id'];
			foreach ($userData['UserAchievement'] as &$item){
				unset($item['id']);
			}
		}

		$testItemDescr = 'Update User Info';
		$this->dataTests($testItemDescr, $expected, $userData, $this->assertArray($expected, $userData));

		$this->User->query('DELETE FROM user_achievements WHERE user_id = 183 AND id ='.$achievementId);
		unset($saveData);
		$saveData = Array (
					'User' => Array (
						'id' => 183, 'full_name' => 'алекс',
						'skills' => 'PHP , все подряд',
						'video_url' =>'',
						'phone' =>'',
						'birthday' =>'',
						'live_country' => 'AR',
						'live_place' =>'',
						'university' =>'',
						'speciality' =>''),
			);
		$userData = $this->ApiUser->saveInfo($saveData);
	}


	//-------------------------------------------Groups--------------------------------------------------//

	public function groups() {
		$testDescription = 'Api Groups';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));

		$this->testApiGroupAccess();
		$this->testApiUsersGroups();
		$this->testApiGroupInfo();
		$this->testApiGroupMembers();
		$this->testApiJoinGroup();
		$this->testApiCreateAndUpdateGroup();
		$this->testApiGroupTeam();
		$this->testApiDeleteFromGroup();
		$this->testApiInviteGroup();
		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);
	}

		private function testApiGroupAccess(){
			//является ли админом
			$group_id = 26;
			$result = $this->ApiGroup->isAdmin($group_id,$this->userId);

			$testItemDescr = "Is Group Admin";
			$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));

			$group_id = 7;
			$result = $this->ApiGroup->isAdmin($group_id,$this->userId);

			$testItemDescr = "Isn't Group Admin";
			$this->dataTests( $testItemDescr, true, $result, $this->assertFalse($result));

			//является ли членом
			$group_id = 27;
			$result = $this->ApiGroupMember->checkInGroup($this->userId,$group_id);

			$testItemDescr = "Is Group Memeber";
			$this->dataTests( $testItemDescr, true, $result, $this->assertTrue($result));

			$group_id = 7;
			$result = $this->ApiGroupMember->checkInGroup($this->userId,$group_id);

			$testItemDescr = "Isn't Group Member";
			$this->dataTests($testItemDescr, true, $result, $this->assertFalse($result));
		}

		private function testApiUsersGroups() {

		$searchResult = $this->ApiGroup->getUserAdminGroups($this->userId);

		$expected = array(
				'Group' => array(
							'0' => array(
								'id' => 26,
								'image' => '/img/no-photo.jpg',
								'title' => 'Тест группа',
								'headline' => 'Тесто',
							)
				)
		);

		$testItemDescr = "User's Groups";
		$this->dataTests( $testItemDescr, $expected, $searchResult, $this->assertArray($expected, $searchResult));
	}

	private function testApiGroupInfo() {

		$group_id = 7;
		$user_id = 183;
		$groupData = $this->ApiGroup->getInfo($group_id,$user_id);

		$expected = array(
				'Group' => Array(
						'id' => 7,
						'title' => 'KONSTRUKTOR',
						'headline' => 'Creative environment',
						'video_url' => '',
						'user_status' => 0,
						'user_role' =>'',
						'is_subscribed' => 1,
						'image' => '/media/router/index/group/713/noresize/image.jpg.png'
				),
				'GroupAddress' => Array(
						'0' => Array(
                                    'id' => 16,
                                    'group_id' => 7,
                                    'country' => '',
                                    'zip_code' => '',
                                    'address' => '303 Twin Dolphine drive ,6th floor,Redwood city , CA , 94065',
                                    'phone' => '+1 650 6324308',
                                    'email' => 'info@konstruktor.com',
                                    'url' => 'www.konstruktor.com',
                                    'fax' => '',
                                    'head_office' => false,
					)
			),
			'GroupAchievement' => Array(
						'0' => Array(
							'id' => 8,
							'created' => '2015-02-03T17:12:32Z',
							'group_id' => 7,
							'title' => '',
							'url' => 'http://'
						)
			),
			'GroupGallery' => Array(
						'0' => Array(
							'image' => '/media/router/index/groupgallery/684/noresize/image.jpg.png'
						)
			),
			'Article' => Array(
						'0' => Array(
							'id' => 28,
							'title' => 'название 1',
							'cat_id' => 2,
							'group_id' => 7,
						),
			),
			'Project' => Array(),
			'Team' => Array(
					'0' => Array(
						'user_id' => 67,
						'is_team' => 1,
						'role' => 'Administrator',
						'full_name' => 'Vlad Krishtop',
						'url_img' => '/media/router/index/user/647/noresize/image.jpg.png',
					)
			)

		);

		$testItemDescr = "Group Info";
		$this->dataTests($testItemDescr, $expected, $groupData, $this->assertArray($expected, $groupData));

	}

	private function testApiGroupMembers() {

		$group_id = 7;
		$result = $this->ApiGroupMember->getGroupMemberList($group_id);

		$expected = array(
			'0' => Array(
				'user_id' => 67,
				'is_team' => 1,
				'role' => 'Administrator',
				'full_name' => 'Vlad Krishtop',
				'url_img' => '/media/router/index/user/647/noresize/image.jpg.png'
			)
		);

		$testItemDescr = "Members List";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

	}

	private function testApiJoinGroup() {

			$saveData['user_id'] = $this->userId;
			$saveData['group_id'] = 7;
			$saveData['approved'] = 0;
			$saveData['is_deleted'] = 0;

			$this->ApiGroupMember->save($saveData);

			$member = $this->ApiGroupMember->findByGroupIdAndUserId(7, $this->userId);
			if($member){
				unset($member['ApiGroupMember']['id']);
			}
			$expected = array(
				'ApiGroupMember' => Array(
						'created' => date('Y-m-d H:i:s'),
						'group_id' => 7,
						'user_id' => 183,
						'role' =>'',
						'approved' => false,
						'approve_date' => '0000-00-00 00:00:00',
						'sort_order' => 1,
						'show_main' => false,
						'is_invited' =>false,
						'is_deleted' =>false
					)
				);
			$testItemDescr = "JoinGroup";
			$this->dataTests($testItemDescr, $expected, $member, $this->assertArray($expected, $member));

			$this->ApiGroupMember->query('DELETE FROM group_members WHERE user_id = 183 AND group_id = 7');
	}

	private function testApiCreateAndUpdateGroup() {

			//создание
			$saveData = array (
					'Group' => array (
						'title' => 'title',
						'video_url' => 'http://url.com',
						'descr' => 'descr',
						'hidden' => 0,
						'owner_id' => $this->userId,
					),
					'GroupAddress' =>array (
						0 => array (
							'head_office' => 1,
							'country' => 'AR',
							'zip_code' => '12243',
							'address' => 'ewferg',
							'phone' => '13124124',
							'fax' => '2124214',
							'url' => 'http://url',
							'email' => 'email',
						),
					),
					'GroupAchievement' =>array (
						0 => array (
							'title' => 'title',
							'url' => 'http://url',
						),
					),
				);
			$groupId = $this->ApiGroup->saveInfo($saveData);
			$savedMemberData['role'] = 'Administrator';
			$savedMemberData['group_id'] = $groupId;
			$savedMemberData['user_id'] = $this->userId;
			$savedMemberData['approved'] = 1;
			$savedMemberData['sort_order'] = 0;
			$savedMemberData['show_main'] = 1;
			$savedMemberData['approve_date'] = date('Y-m-d');

			$this->ApiGroupMember->saveRow($savedMemberData);
			$groupData = $this->Group->findById($groupId);
			if($groupData){
				$groupData['Team'] = $this->ApiGroupMember->getGroupMemberList($groupId,true);
			}

			$expected = Array (
				'Group' => Array(
					'id' => $groupId,
					'owner_id' => $this->userId,
					'title' => 'title',
					'descr' => 'descr',
					'hidden' => false,
					'video_url' => 'http://url.com'
				),
				'GroupMedia' => Array(
					'id' =>'',
					'object_type' =>'',
					'object_id' =>'',
					'media_type' => '',
					'ext' => '',
					'url_img' => '/img/no-photo.jpg',
					'url_download' => '',
				),
				'GroupAddress' => Array(
					'0' => Array(
						'group_id' => $groupId,
						'country' => 'AR',
						'zip_code' => '12243',
						'address' => 'ewferg',
						'phone' => '13124124',
						'email' => 'email',
						'url' => 'http://url',
						'fax' => '2124214',
						'head_office' => 1,
					)
				),
				'GroupAchievement' => Array(
					'0' => Array(
						'group_id' => $groupId,
						'title' => 'title',
						'url' => 'http://url',
					),
				),
				'GroupGallery' => Array(),
				'GroupVideo' => Array(),
				'Team' => Array(
						'0' => Array(
							'user_id' => $this->userId,
							'is_team' => 1,
							'role' => 'Administrator',
							'full_name' => 'алекс',
							'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
						)
				)

		);


		$addressId = $groupData['GroupAddress'][0]['id'];
		unset($groupData['GroupAddress'][0]['id']);
		$achievementId = $groupData['GroupAchievement'][0]['id'];
		unset($groupData['GroupAchievement'][0]['id']);
		unset($groupData['Group']['created']);
		unset($groupData['GroupAchievement'][0]['created']);

		$testItemDescr = "Create Group";
		$this->dataTests($testItemDescr, $expected, $groupData, $this->assertArray($expected, $groupData));

		//обновление группы

		$saveData = array (
					'Group' => array (
						'id'=>$groupId,
						'title' => 'new title',
						'video_url' => 'http://newurl.com',
						'descr' => 'new descr',
						'hidden' => 0,
					),
					'GroupAddress' =>array (
						0 => array (
							'group_id' =>$groupId,
							'head_office' => 1,
							'country' => 'RU',
							'zip_code' => '33333',
							'address' => 'new address',
							'phone' => '999999',
							'fax' => '8888',
							'url' => 'http://urlnew.com',
							'email' => 'new_email',
						),
						1 => array (
							'id' => $addressId,
							'group_id' =>$groupId,
							'head_office' => 0,
							'country' => 'US',
							'zip_code' => '11111',
							'address' => 'new address US',
							'phone' => '44444',
							'fax' => '666666',
							'url' => 'http://urlnewus.com',
							'email' => 'new_email_us',
						),
					),
					'GroupAchievement' =>array (
						0 => array (
							'title' => 'new ach',
							'group_id'=>$groupId,
							'url' => 'http://urlnew.com',
						),
						1 => array (
							'id' => $achievementId,
							'group_id'=>$groupId,
							'title' => 'upd ach',
							'url' => 'http://urlupd.com',
						),
					),
				);
				$this->ApiGroup->saveInfo($saveData);
				$groupData = $this->Group->findById($groupId);


				$expected = $expected = Array (
				'Group' => Array(
					'id' => $groupId,
					'owner_id' => $this->userId,
					'title' => 'new title',
					'descr' => 'new descr',
					'hidden' => false,
					'video_url' => 'http://newurl.com'
				),
				'GroupMedia' => Array(
					'id' =>'',
					'object_type' =>'',
					'object_id' =>'',
					'media_type' => '',
					'ext' => '',
					'url_img' => '/img/no-photo.jpg',
					'url_download' => '',
				),
				'GroupAddress' => Array(
					'0' => Array(
						'group_id' =>$groupId,
						'head_office' => 1,
						'country' => 'RU',
						'zip_code' => '33333',
						'address' => 'new address',
						'phone' => '999999',
						'fax' => '8888',
						'url' => 'http://urlnew.com',
						'email' => 'new_email',
					),
					'1' => Array(
						'group_id' =>$groupId,
						'head_office' => false,
						'country' => 'US',
						'zip_code' => '11111',
						'address' => 'new address US',
						'phone' => '44444',
						'fax' => '666666',
						'url' => 'http://urlnewus.com',
						'email' => 'new_email_us',
					),

				),
				'GroupAchievement' => Array(
					'0' => Array(
						'group_id' => $groupId,
						'title' => 'new ach',
						'url' => 'http://urlnew.com'
					),
					'1' => Array(
						'group_id' => $groupId,
						'title' => 'upd ach',
						'url' => 'http://urlupd.com',
					),
				),
				'GroupGallery' => Array(),
				'GroupVideo' => Array(),
		);

		unset($groupData['Group']['created']);
		unset($groupData['GroupAddress'][0]['id']);
		unset($groupData['GroupAddress'][1]['id']);
		unset($groupData['GroupAchievement'][0]['id']);
		unset($groupData['GroupAchievement'][1]['id']);
		unset($groupData['GroupAchievement'][0]['created']);
		unset($groupData['GroupAchievement'][1]['created']);

		$testItemDescr = "Update Group";
		$this->dataTests($testItemDescr, $expected, $groupData, $this->assertArray($expected, $groupData));

		$this->ApiGroupMember->query('DELETE FROM group_members WHERE group_id = '.$groupId);
		$this->ApiGroup->query('DELETE FROM groups WHERE id = '.$groupId);
		$this->ApiGroup->query('DELETE FROM group_addresses WHERE group_id = '.$groupId);
		$this->ApiGroup->query('DELETE FROM group_achievements WHERE group_id = '.$groupId);
	}

	private function testApiGroupTeam() {
			//удаляем из команды
			$saveData['id'] = 40;
			$saveData['show_main'] = 0;


			$this->ApiGroupMember->save($saveData);

			$groupTeam = $this->ApiGroupMember->getGroupMemberList(22,true);
			$result = Hash::extract($groupTeam,'{n}.user_id');
			$expected = array(76,175);
			$testItemDescr = "Remove From Team";
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

			//добавляем
			$saveData['show_main'] = 1;
			$this->ApiGroupMember->saveInfo($saveData);

			$groupTeam = $this->ApiGroupMember->getGroupMemberList(22,true);
			$result = Hash::extract($groupTeam,'{n}.user_id');
			$expected = array(76,175,176);
			$testItemDescr = "Remove From Team";
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

	}

	private function testApiDeleteFromGroup() {
			//удаляем из команды
			$param['user_id'] = 176;
			$param['group_id'] = 22;
			$saveData['is_deleted'] = 1;

			$this->ApiGroupMember->updateInfo($saveData,$param);

			$groupTeam = $this->ApiGroupMember->getGroupMemberList(22);
			$result = Hash::extract($groupTeam,'{n}.user_id');
			$expected = array(76,71,175);
			$testItemDescr = "Remove From Group";
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

			$saveData['is_deleted'] = 0;
			$this->ApiGroupMember->updateInfo($saveData,$param);

	}

	private function testApiInviteGroup() {

			$saveData['user_id'] = 183;
			$saveData['group_id'] = 7;
			$saveData['is_invited'] = 1;
			$saveData['show_main'] = 1;
			$saveData['is_deleted'] = 0;
			$saveData['role'] = 'testrole';

			$this->ApiGroupMember->saveInfo($saveData);

			$result = $this->ApiGroupMember->findByGroupIdAndUserId(7,183,array('user_id','group_id','is_invited'));
			$testItemDescr = "Invite to Group";
			$expected = array('ApiGroupMember' => Array ( 'user_id' => 183, 'group_id' => 7, 'is_invited' => 1 ));
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

			//принимаем приглашение
			$member = $this->ApiGroupMember->findByGroupIdAndUserId(7,183,array('id'));
			$param['group_id'] = 7;
			$param['user_id'] = 183;
			$param['accept'] = 1;
			$param['id'] = $member['ApiGroupMember']['id'];
			$this->ApiGroupMember->inviteAnswer($param);
			$fields = array('group_id','user_id','is_invited','approved');
			$conditions = array('group_id'=>7,'user_id'=>183,'is_invited'=>0,'approved'=>1);
			$result = $this->ApiGroupMember->find('first',  compact('fields','conditions'));
			$testItemDescr = 'Accept Invite';
			$expected = Array (
						'ApiGroupMember' => Array (
								'group_id' => 7,
								'user_id' => 183,
								'is_invited' =>'',
								'approved' => 1
							));
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));
			$this->ApiGroupMember->query('DELETE FROM group_members WHERE group_id = 7 AND user_id = 183');

			//отклоняем приглашение
			$saveData['user_id'] = 183;
			$saveData['group_id'] = 7;
			$saveData['is_invited'] = 1;
			$saveData['show_main'] = 1;
			$saveData['is_deleted'] = 0;
			$saveData['role'] = 'testrole';

			$this->ApiGroupMember->saveInfo($saveData);
			$member = $this->ApiGroupMember->findByGroupIdAndUserId(7,183,array('id'));
			$param['group_id'] = 7;
			$param['user_id'] = 183;
			$param['accept'] = 0;
			$param['id'] = $member['ApiGroupMember']['id'];
			$this->ApiGroupMember->inviteAnswer($param);

			$conditions = array('group_id'=>7,'user_id'=>183);
			$result = $this->ApiGroupMember->find('first',  compact('conditions'));
			$testItemDescr = 'Decline Invite';
			$expected = Array();
			$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected, $result));

	}

//-----------------------------------------------Статьи и подписки--------------------------------//

	public function articles() {
		$testDescription = 'Api Articles & Subscriptions';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));

		$this->testApiArticlesAccess();
		$this->testApiCategoryArticles();
		$this->testApiArticleContent();
		$this->testApiSubscriptionArticles();
		$this->testApiCommentsList();
		$this->testApiArticlesSearch();
		$this->testApiCommentArticle();
		$this->testApiSubscribe();
		$this->testApiUsersArticles();

		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);
	}

	private function testApiArticlesAccess(){

		//не подписан на пользователя
		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(71, $this->userId,'user');
		$testItemDescr = "Is Not Subscribed on User";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//не подписан на пользователя
		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(76, $this->userId,'user');
		$testItemDescr = "Is Subscribed on User";
		$this->dataTests($testItemDescr, false, $result, $this->assertTrue($result));

		//не подписан на группу
		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(16, $this->userId,'group');
		$testItemDescr = "Is Not Subscribed on Group";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//подписан на группу
		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(7, $this->userId,'group');
		$testItemDescr = "Is Subscribed on Group";
		$this->dataTests($testItemDescr, false, $result, $this->assertTrue($result));

		//опубликованная статья
		$result = $this->ApiArticle->articleCheckAccess($this->userId,34);
		$testItemDescr = "Access To Published";
		$this->dataTests($testItemDescr, false, $result, $this->assertTrue($result));

		//неопубликованная статья
		$result = $this->ApiArticle->articleCheckAccess($this->userId,18);
		$testItemDescr = "No Access To Not Published";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		// своя неопубликованная статья
		$result = $this->ApiArticle->articleCheckAccess($this->userId,36);
		$testItemDescr = "Access To Own Not Published";
		$this->dataTests($testItemDescr, false, $result, $this->asserttrue($result));
	}

		private function testApiCategoryArticles(){

		$result = $this->ApiArticle->categoryArticles($this->userId,2);

		$expected = array(
			'Articles' => Array(
					'0' => Array (
						'id' => 37,
						'title' => 'testing sadf',
						'author_id' => 183,
						'author_entity' => 'user',
						'group_id' =>false,
						'published' => 0,
						'created' => '2015-01-27T15:49:24Z',
						'comments' => 0
					),
					'1' => Array (
						'id' => 34,
						'title' => 'dsfasfsdfsadf',
						'author_id' => 176,
						'author_entity' => 'user',
						'group_id' =>false,
						'published' => 1,
						'created' => '2014-12-10T14:00:24Z',
						'comments' => 0
					),
					'2' => Array (
						'id' => 28,
						'title' => 'название 1',
						'author_id' => 76,
						'author_entity' => 'user',
						'group_id' => 7,
						'published' => 1,
						'created' => '2014-12-09T18:43:47Z',
						'comments' => 0
					)
				)
			);

		$testItemDescr = "Category Articles";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}

	private function testApiArticleContent(){

		$result = $this->ApiArticle->getArticleBody(32);

		$expected = array(
				'Article' => Array(
					'id' => 32,
					'body' => '<p>надеемся и ждёмasdasdasd</p>'
				)
		);

		$testItemDescr = "Category Articles";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}

	private function testApiSubscriptionArticles(){

		$result = $this->ApiSubscription->getUserSubcsribedArticles($this->userId);

		$expected = array(
				'Article' => Array(
                            '0' => Array(
                                    'id' => 33,
                                    'title' => 'рас рас два три два рас',
                                    'cat_id' => 0,
                                    'category_title' => '',
                                    'created' => '2014-12-09T20:56:06Z',
                                    'comments' => 0
                                ),
                            '1' => Array(
                                    'id' => 32,
                                    'title' => 'Windows 10',
                                    'cat_id' => 5,
                                    'category_title' => 'Hi-tech',
                                    'created' => '2014-12-09T19:02:47Z',
                                    'comments' => 2
                                ),
							'2' => Array(
                                    'id' => 28,
                                    'title' => 'название 1',
                                    'cat_id' => 2,
                                    'category_title' => 'Community',
                                    'created' => '2014-12-09T18:43:47Z',
                                    'comments' => 0
                                ),
                        )
			);

		$testItemDescr = "Subscription Articles";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}

	private function testApiCommentsList(){

		$result = $this->ApiArticleEvent->getCommentsList(32);


		$expected = array(
				'Comments' => Array(
                            '0' => Array(
                                    'comment_id' => 16,
                                    'text' => 'десяточко',
									'parent_id'=>'',
                                    'author_id' => 183,
                                    'created' => '2015-01-18T16:50:30Z',
                                    'author_name' => 'алекс',
                                    'author_image' => '/media/router/index/user/819/noresize/image.jpg.png',
                                ),
                            '1' => Array(
                                    'comment_id' => 15,
                                    'text' => 'десятка',
									'parent_id'=>'',
                                    'author_id' => 183,
                                    'created' => '2015-01-18T16:50:02Z',
                                    'author_name' => 'алекс',
                                    'author_image' => '/media/router/index/user/819/noresize/image.jpg.png'
                                )
                        )
			);

		$testItemDescr = "Article's Comments List";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}

		private function testApiArticlesSearch(){

		//поиск на латинице
		$result = $this->ApiArticle->search('sadf');


		$expected = array(
				 'Article' => Array(
                            '0' => Array(
                                    'id' => 34,
                                    'title' => 'dsfasfsdfsadf'
                                )

                        )
			);

		$testItemDescr = "Article - lat search";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		//поиск на кириллице
		$result = $this->ApiArticle->search('рас');


		$expected = array(
				 'Article' => Array(
                            '0' => Array(
                                    'id' => 33,
                                    'title' => 'рас рас два три два рас'
                                )

                        )
			);

		$testItemDescr = "Article - cyr search";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}

	private function testApiCommentArticle(){

		$this->ApiArticleEvent->addComment($this->userId, 'test123', 37,'');

		$parent_comment = $this->ApiArticleEvent->getCommentsList(37);
		$comment_id = $parent_comment['Comments'][0]['comment_id'];
		$this->ApiArticleEvent->addComment($this->userId, 'test1234', 37,$comment_id);

		$result = $this->ApiArticleEvent->getCommentsList(37);
		if($result['Comments']){
			foreach($result['Comments'] as &$comment){
				unset($comment['comment_id']);
				unset($comment['created']);
			}
		}

		$expected = array(
				'Comments' => Array(
							'0' => Array(
                                    'text' => 'test1234',
									'parent_id' => $comment_id,
                                    'author_id' => 183,
                                    'author_name' => 'алекс',
                                    'author_image' => '/media/router/index/user/819/noresize/image.jpg.png',
                                ),
                            '1' => Array(
                                    'text' => 'test123',
									'parent_id' => '',
                                    'author_id' => 183,
                                    'author_name' => 'алекс',
                                    'author_image' => '/media/router/index/user/819/noresize/image.jpg.png',
                                ),
                        )
			);

		$testItemDescr = "Comment Article";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$result = $this->ApiArticleEvent->query('DELETE FROM article_events WHERE article_id = 37 AND user_id = 183');

	}

	private function testApiSubscribe(){

		$saveData['subscriber_id'] = $this->userId;
		$saveData['type'] = 'user';
		$saveData['object_id'] = 176;
		$this->ApiSubscription->save($saveData);

		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(176, $this->userId,'user');

		$testItemDescr = "Subscribe on User";
		$this->dataTests($testItemDescr, false, $result, $this->assertTrue($result));
		$this->ApiSubscription->deleteAll(array('subscriber_id'=>$this->userId,'object_id'=>176,'type'=>'user'),false);

		$saveData['type'] = 'group';
		$saveData['object_id'] = 16;
		$this->ApiSubscription->save($saveData);

		$result = $this->ApiSubscription->findByObjectIdAndSubscriberIdAndType(16, $this->userId,'group');

		$testItemDescr = "Subscribe on Group";
		$this->dataTests($testItemDescr, false, $result, $this->assertTrue($result));
		$this->ApiSubscription->deleteAll(array('subscriber_id'=>$this->userId,'object_id'=>16,'type'=>'group'),false);

	}

	private function testApiUsersArticles(){

		$result = $result = $this->ApiArticle->usersArticles($this->userId);
		$expected = Array (
					'Article' => Array (
							'0' => Array (
									'id' => 36,
									'title' => 'про культуру',
									'published' => 0,
									'created' => '2015-01-16T17:57:00Z',
									'category_id' => 4,
									'category_title' => 'Sports',
									'comments' => 13 ),
							'1' => Array (
									'id' => 37,
									'title' => 'testing sadf',
									'published' => 0,
									'created' => '2015-01-27T15:49:24Z',
									'category_id' => 2,
									'category_title' => 'Community',
									'comments' => 0 )
						)
			);
		$testItemDescr = "User's Articles";
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

	}
//---------------------------------Проекты----------------------------------------------------//

	public function projects() {
		$testDescription = 'Api Projects,Tasks,Docs';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));

		$this->testApiProjectsAccess();
		$this->testApiProjectInfo();
		$this->testApiProjectCreateAndUpdate();
		$this->testApiTasks();
		$this->testApiSubprojectCreate();
		$this->testApiDocuments();
		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);
	}

	private function testApiProjectsAccess(){
		//не пользователь проекта
		$result = $this->ApiProject->checkAccessToProject(183,1);
		$testItemDescr = "Is Not Project User";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//пользователь проекта
		$result = $this->ApiProject->checkAccessToProject(76,1);
		$testItemDescr = "Is Project User";
		$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));

		//не создатель проекта
		$result = $this->ApiProject->isProjectOwner(71,1);
		$testItemDescr = "Is Not Project Owner";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//создатель проекта
		$result = $this->ApiProject->isProjectOwner(76,1);
		$testItemDescr = "Is Project Owner";
		$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));

		//создание подпроекта - не в группе
		$result = $this->ApiSubproject->checkAccess(183,1);
		$testItemDescr = "No Access To Create Subproject(not group user)";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//создание подпроекта - не ответсвенный
		$result = $this->ApiSubproject->checkAccess(71,1);
		$testItemDescr = "No Access To Create Subproject(not responsible user)";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//создание подпроекта - ответсвенный
		$result = $this->ApiSubproject->checkAccess(76,1);
		$testItemDescr = "Access To Create Subproject";
		$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));

		//доступа к задаче нет
		$result = $this->ApiTask->checkAccessToTask(176,1);
		$testItemDescr = "No Access To Task";
		$this->dataTests($testItemDescr, false, $result, $this->assertFalse($result));

		//доступ к задаче - менеджер
		$result = $this->ApiTask->checkAccessToTask(71,3);
		$testItemDescr = "Access To Task - manager";
		$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));

		//доступ к задаче - исполнитель
		$result = $this->ApiTask->checkAccessToTask(71,1);
		$testItemDescr = "Access To Task - user";
		$this->dataTests($testItemDescr, true, $result, $this->assertTrue($result));
	}

	private function testApiProjectInfo(){

		$project_id = 1;
		$result = $this->ApiProject->getInfo($project_id);
		$expected = array (
		'Project' => array (
					'id' => '1',
					'title' => 'Проект 1',
					'descr' => 'Описане проекта 1',
					'group_id' => '22',
					'created' => '2014-11-29T23:38:54Z',
					'deadline' => '2014-09-10T00:00:00Z',
					'closed' => false,
				),
		'ProjectMember' => array (
			0 => array (
				'user_id' => '76',
				'is_responsible' => true,
				'full_name' => 'Fyr Fayar',
				'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'role' => 'Administrator',
			),
			1 => array (
				'user_id' => '71',
				'is_responsible' => false,
				'full_name' => 'Ярослав Ерошенко',
				'url_img' => '/img/no-photo.jpg',
				'role' => 'Role 1',
			),
			2 => array (
				'user_id' => '176',
				'is_responsible' => false,
				'full_name' => '',
				'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
				'role' => 'Помощник Хрюшана',
			),
		),
		'Events' => array (
			0 => array (
				'id' => '13',
				'created' => '2014-12-23T02:06:07Z',
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '8',
			),
			1 => array (
				'id' => '12',
				'created' => '2014-12-22T23:59:13Z',
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '7',
			),
			2 => array (
				'id' => '11',
				'created' => '2014-12-06T17:26:00Z',
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '3',
				'subproject_id' => NULL,
				'event_type' => '6',
			),
			3 => array (
				'id' => '10',
				'created' => '2014-12-06T15:38:03Z',
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '8',
			),
			4 => array (
				'id' => '9',
				'created' => '2014-12-06T15:37:47Z',
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '7',
			),
		),
		'Subproject' => array (
			0 => array (
			'id' => '1',
			'title' => 'Proj1 Subproject 1',
			'Task' => array (
				0 => array (
					'id' => '1',
					'title' => 'proj1 Subproject 1 task 1',
					'deadline' => '2014-11-26T23:00:00Z',
					'assignee_user_id' => '71',
					'assignee_full_name' => 'Ярослав Ерошенко',
					'assignee_url_img' => '/img/no-photo.jpg',
					'closed' => 0,
				),
				1 => array (
					'id' => '2',
					'title' => 'proj1 Subproject 1 task 2',
					'deadline' => '2014-11-19T23:00:00Z',
					'assignee_user_id' => '175',
					'assignee_full_name' => 'Fyr 2',
					'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
					'closed' => 0,
				),
				2 => array (
					'id' => '3',
					'title' => 'proj1 Subproject 1 task 3',
					'deadline' => '2014-11-20T23:00:00Z',
					'assignee_user_id' => '175',
					'assignee_full_name' => 'Fyr 2',
					'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
					'closed' => 1,
						),
					),
				),
			1 => array (
				'id' => '2',
				'title' => 'proj1 Subproject 2',
			),
		));
		$testItemDescr = 'Project Info';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

	private function testApiProjectCreateAndUpdate(){

		$saveData = array (
			'group_id' => '22',
			'owner_id' => '76',
			'responsible_id' => '71',
			'title' => 'title',
			'hidden' => 0,
			'descr' => 'descr',
			'deadline' => '2012-03-03T12:00:00Z',
		);

		$projectId = $this->ApiProject->createProject($saveData);
		$result = $this->ApiProject->getInfo($projectId);
		unset($result['Project']['id']);
		unset($result['Project']['created']);
		unset($result['Events'][0]['id']);
		unset($result['Events'][0]['created']);
		$expected = array (
		'Project' => array (
			'title' => 'title',
			'descr' => 'descr',
			'group_id' => '22',
			'deadline' => '2012-03-03T12:00:00Z',
			'closed' => false,
		),
		'ProjectMember' => array (
			0 => array (
				'user_id' => '71',
				'is_responsible' => true,
				'full_name' => 'Ярослав Ерошенко',
				'url_img' => '/img/no-photo.jpg',
				'role' => 'Role 1',
			),
			1 => array (
				'user_id' => '76',
				'is_responsible' => false,
				'full_name' => 'Fyr Fayar',
				'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'role' => 'Administrator',
			),
		),
		'Events' => array (
				0 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => NULL,
					'subproject_id' => NULL,
					'event_type' => '1',
				),
			),
			'Subproject' => array (),
		);
		$testItemDescr = 'Project Create';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$saveData = array (
			'id'=>$projectId,
			'responsible_id' => '176',
			'title' => 'title',
			'hidden' => 0,
			'descr' => 'описание',
			'deadline' => '2012-03-05T12:00:00Z',
		);
		$this->ApiProject->updateProject($saveData);

		$result = $this->ApiProject->getInfo($projectId);
		unset($result['Project']['id']);
		unset($result['Project']['created']);
		unset($result['Events'][0]['id']);
		unset($result['Events'][0]['created']);
		$expected = array (
			'Project' => array (
				'title' => 'title',
				'descr' => 'описание',
				'group_id' => '22',
				'deadline' => '2012-03-05T12:00:00Z',
				'closed' => false,
			),
			'ProjectMember' => array (
				0 => array (
					'user_id' => '76',
					'is_responsible' => false,
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'role' => 'Administrator',
				),
				1 => array (
					'user_id' => '71',
					'is_responsible' => false,
					'full_name' => 'Ярослав Ерошенко',
					'url_img' => '/img/no-photo.jpg',
					'role' => 'Role 1',
				),
				2 => array (
					'user_id' => '176',
					'is_responsible' => true,
					'full_name' => '',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
					'role' => 'Помощник Хрюшана',
				),
			),
			'Events' => array (
				0 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => NULL,
					'subproject_id' => NULL,
					'event_type' => '1',
				),
			),
			'Subproject' => array (),
		);
		$testItemDescr = 'Project Update';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
		$this->ApiProject->delete($projectId);
		$this->ApiProjectMember->deleteAll(array('project_id'=>$projectId));
		$this->ApiProjectEvent->deleteAll(array('project_id'=>$projectId));
	}

	private function testApiTasks(){

		$taskId = 3;
		$projectId = 1;
		$result = $this->ApiTask->getInfo($taskId,$projectId);
		$expected = array (
			'Task' => array (
				'id' => '3',
				'created' => '2014-11-30T04:49:49Z',
				'title' => 'proj1 Subproject 1 task 3',
				'subproject_id' => '1',
				'descr' => 'proj1 Subproject 1 task 3 descr',
				'manager_id' => '71',
				'user_id' => '175',
				'closed' => true,
				'deadline' =>  '2014-11-20T23:00:00Z' ,
				'project_id' => 1,
				'project_title' => 'Проект 1',
				'user_full_name' => 'Fyr 2',
				'user_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
				'manager_full_name' => 'Ярослав Ерошенко',
				'manager_url_img' => '/img/no-photo.jpg',
			),
			'Comments' => array (),
		);
		$testItemDescr = 'Task Info';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$saveData = array (
			'subproject_id' => '2',
			'creator_id' => '76',
			'title' => 'testtitle',
			'descr' => 'description',
			'deadline' => '2015-02-19T23:00:00Z',
			'user_id' => '71',
			'manager_id' => '176',
		);
		$taskId = $this->ApiTask->createTask(76,$saveData,$projectId);
		$result = $this->ApiTask->getInfo($taskId,$projectId);
		unset($result['Task']['created']);
		$expected = array (
		'Task' => array (
			'id' => $taskId,
			'title' => 'testtitle',
			'subproject_id' => '2',
			'descr' => 'description',
			'manager_id' => '176',
			'user_id' => '71',
			'closed' => false,
			'deadline' => '2015-02-19T23:00:00Z',
			'project_id' => 1,
			'project_title' => 'Проект 1',
			'user_full_name' => 'Ярослав Ерошенко',
			'user_url_img' => '/img/no-photo.jpg',
			'manager_full_name' => '',
			'manager_url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
		),
			'Comments' => array (),
		);

		$testItemDescr = 'Task Create';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiTask->addComment(76,$taskId,'test message',$projectId);
		$result = $this->ApiTask->getInfo($taskId,$projectId);
		unset($result['Task']['created']);
		unset($result['Comments'][0]['created']);
		unset($result['Comments'][0]['id']);
		unset($result['Comments'][0]['msg_id']);
		$expected = array (
			'Task' => array (
				'id' => $taskId,
				'title' => 'testtitle',
				'subproject_id' => '2',
				'descr' => 'description',
				'manager_id' => '176',
				'user_id' => '71',
				'closed' => false,
				'deadline' => '2015-02-19T23:00:00Z',
				'project_id' => 1,
				'project_title' => 'Проект 1',
				'user_full_name' => 'Ярослав Ерошенко',
				'user_url_img' => '/img/no-photo.jpg',
				'manager_full_name' => '',
				'manager_url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
			),
		'Comments' =>array (
			0 =>array (
				'user_id' => '76',
				'event_type' => '7',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'message' => 'test message',
			),
		),
	);

		$testItemDescr = 'Task - Add Comment';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiTask->closeTask(76,$taskId,1);
		$result = $this->ApiProject->getInfo($projectId);
		$expected = array (
			'Project' => array (
				'id' => '1',
				'title' => 'Проект 1',
				'descr' => 'Описане проекта 1',
				'group_id' => '22',
				'created' => '2014-11-29T23:38:54Z',
				'deadline' => '2014-09-10T00:00:00Z',
				'closed' => false,
			),
			'ProjectMember' => array (
				0 => array (
					'user_id' => '76',
					'is_responsible' => true,
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'role' => 'Administrator',
				),
				1 => array (
					'user_id' => '71',
					'is_responsible' => false,
					'full_name' => 'Ярослав Ерошенко',
					'url_img' => '/img/no-photo.jpg',
					'role' => 'Role 1',
				),
				2 => array (
					'user_id' => '176',
					'is_responsible' => false,
					'full_name' => '',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
					'role' => 'Помощник Хрюшана',
				),
			),
			'Events' => array (
				0 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => $taskId,
					'subproject_id' => NULL,
					'event_type' => '6',
				),
				1 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => $taskId,
					'subproject_id' => NULL,
					'event_type' => '7',
				),
				2 =>array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => $taskId,
					'subproject_id' => NULL,
					'event_type' => '3',
				),
				3 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => '1',
					'subproject_id' => NULL,
					'event_type' => '8',
				),
				4 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => '1',
					'subproject_id' => NULL,
					'event_type' => '7',
				),
			),
			'Subproject' => array (
				0 => array (
					'id' => '1',
					'title' => 'Proj1 Subproject 1',
				'Task' => array (
					0 => array (
						'id' => '1',
						'title' => 'proj1 Subproject 1 task 1',
						'deadline' => '2014-11-26T23:00:00Z',
						'assignee_user_id' => '71',
						'assignee_full_name' => 'Ярослав Ерошенко',
						'assignee_url_img' => '/img/no-photo.jpg',
						'closed' => 0,
					),
					1 => array (
						'id' => '2',
						'title' => 'proj1 Subproject 1 task 2',
						'deadline' => '2014-11-19T23:00:00Z',
						'assignee_user_id' => '175',
						'assignee_full_name' => 'Fyr 2',
						'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
						'closed' => 0,
					),
					2 => array (
						'id' => '3',
						'title' => 'proj1 Subproject 1 task 3',
						'deadline' => '2014-11-20T23:00:00Z',
						'assignee_user_id' => '175',
						'assignee_full_name' => 'Fyr 2',
						'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
						'closed' => 1,
					),
				),
			),
			1 => array (
				'id' => '2',
				'title' => 'proj1 Subproject 2',
				'Task' => array (
					0 => array (
					'id' => $taskId,
					'title' => 'testtitle',
					'deadline' => '2015-02-19T23:00:00Z',
					'assignee_user_id' => '71',
					'assignee_full_name' => 'Ярослав Ерошенко',
					'assignee_url_img' => '/img/no-photo.jpg',
					'closed' => 1,
					),
				),
			),
		),
	);
	foreach ($result['Events'] as &$event){
		unset($event['id']);
		unset($event['created']);
	}
		$testItemDescr = 'Task - Close Task';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
		$this->ApiTask->delete($taskId);
		$this->ApiProjectEvent->deleteAll(array('task_id'=>$taskId));

		$userId = 76;
		$search_query = '';
		$result = $this->ApiTask->getMyTasks($userId,$search_query);

		$expected =array (
			'Task' => array (
				0 => array (
					'id' => '1',
					'title' => 'proj1 Subproject 1 task 1',
				),
				1 => array (
					'id' => '2',
					'title' => 'proj1 Subproject 1 task 2',
				),
			),
		);
		$testItemDescr = 'My tasks - all';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$userId = 76;
		$search_query = 'task 2';
		$result = $this->ApiTask->getMyTasks($userId,$search_query);
		$expected =array (
			'Task' => array (
				0 => array (
					'id' => '2',
					'title' => 'proj1 Subproject 1 task 2',
				),
			),
		);
		$testItemDescr = 'My tasks - search';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

	private function testApiSubprojectCreate(){
		$data['project_id'] = 1;
		$data['user_id'] = 76;
		$data['title'] = 'new supbroject';
		$subprojectId = $this->ApiSubproject->createSubproject($data);

		$result = $this->ApiProject->getInfo(1);
		$expected = array (
			'Project' => array (
				'id' => '1',
				'title' => 'Проект 1',
				'descr' => 'Описане проекта 1',
				'group_id' => '22',
				'created' => '2014-11-29T23:38:54Z',
				'deadline' => '2014-09-10T00:00:00Z',
				'closed' => false,
			),
			'ProjectMember' => array (
				0 => array (
					'user_id' => '76',
					'is_responsible' => true,
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'role' => 'Administrator',
				),
				1 => array (
					'user_id' => '71',
					'is_responsible' => false,
					'full_name' => 'Ярослав Ерошенко',
					'url_img' => '/img/no-photo.jpg',
					'role' => 'Role 1',
				),
				2 => array (
					'user_id' => '176',
					'is_responsible' => false,
					'full_name' => '',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
					'role' => 'Помощник Хрюшана',
				),
			),
		'Events' =>  array (
			0 => array (
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => NULL,
				'subproject_id' => $subprojectId,
				'event_type' => '2',
			),
			1 => array (
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '8',
			),
			2 => array (
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '1',
				'subproject_id' => NULL,
				'event_type' => '7',
			),
			3 => array (
				'user_id' => '76',
				'full_name' => 'Fyr Fayar',
				'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'task_id' => '3',
				'subproject_id' => NULL,
				'event_type' => '6',
			),
			4 => array (
					'user_id' => '76',
					'full_name' => 'Fyr Fayar',
					'user_url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'task_id' => '1',
					'subproject_id' => NULL,
					'event_type' => '8',
				),
			),
			'Subproject' => array (
				0 => array (
					'id' => '1',
					'title' => 'Proj1 Subproject 1',
				'Task' => array (
					0 => array (
						'id' => '1',
						'title' => 'proj1 Subproject 1 task 1',
						'deadline' => '2014-11-26T23:00:00Z',
						'assignee_user_id' => '71',
						'assignee_full_name' => 'Ярослав Ерошенко',
						'assignee_url_img' => '/img/no-photo.jpg',
						'closed' => 0,
					),
					1 => array (
						'id' => '2',
						'title' => 'proj1 Subproject 1 task 2',
						'deadline' => '2014-11-19T23:00:00Z',
						'assignee_user_id' => '175',
						'assignee_full_name' => 'Fyr 2',
						'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
						'closed' => 0,
					),
					2 => array (
						'id' => '3',
						'title' => 'proj1 Subproject 1 task 3',
						'deadline' => '2014-11-20T23:00:00Z',
						'assignee_user_id' => '175',
						'assignee_full_name' => 'Fyr 2',
						'assignee_url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
						'closed' => 1,
					),
				),
			),
			1 => array (
				'id' => '2',
				'title' => 'proj1 Subproject 2',
			),
			2 => array (
				'id' => $subprojectId,
				'title' => 'new supbroject',
			),
		),
	);
	foreach ($result['Events'] as &$event){
		unset($event['id']);
		unset($event['created']);
	}
		$testItemDescr = 'Subproject Create';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
		$this->ApiSubproject->delete($subprojectId);
		$this->ApiProjectEvent->deleteAll(array('subproject_id'=>$subprojectId));
	}

	private function testApiDocuments(){
		$userId = 71;
		$parentId = '';
		$result = $this->ApiNote->search($userId,$parentId);
		$expected = array (
			'Note' => array (
				0 => array (
					'id' => '19',
					'title' => 'New',
					'is_folder' => '1',
					'parent_id' => NULL,
					'fileCount' => 1,
				),
				1 => array (
					'id' => '21',
					'title' => 'а',
					'is_folder' => '1',
					'parent_id' => NULL,
					'fileCount' => 0,
				),
				2 => array (
					'id' => '8',
					'title' => 'Новый документ',
					'is_folder' => '0',
					'parent_id' => NULL,
					'fileCount' => 0,
				),
				3 => array (
					'id' => '11',
					'title' => 'wfe',
					'is_folder' => '0',
					'parent_id' => NULL,
					'fileCount' => 0,
				),
				4 => array (
					'id' => '12',
					'title' => 'wef',
					'is_folder' => '0',
					'parent_id' => NULL,
					'fileCount' => 0,
				),
				5 => array (
					'id' => '13',
					'title' => 'msn',
					'is_folder' => '0',
					'parent_id' => NULL,
					'fileCount' => 0,
				),
			),
		);

		$testItemDescr = 'Document List - Root';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$userId = 71;
		$search_query = 'wef';
		$result = $this->ApiNote->search($userId,'',$search_query);
		$expected = array (
					'Note' => array (
						0 => array (
							'id' => '12',
							'title' => 'wef',
							'is_folder' => '0',
							'parent_id' => NULL,
							'fileCount' => 0,
						),
					),
		);

		$testItemDescr = 'Document Search - Lat';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$userId = 71;
		$search_query = 'нов';
		$result = $this->ApiNote->search($userId,'',$search_query);
		$expected = Array (
			'Note' => Array(
				'0' => Array (
					'id' => 8,
					'title' => 'Новый документ',
					'is_folder' => 0,
					'parent_id' =>'',
					'fileCount' => 0
				),
				'1' => Array (
					'id' => 20,
					'title' => 'НОВАЯ',
					'is_folder' => 0,
					'parent_id' => 19,
					'fileCount' => 0
				)
			)
		);

		$testItemDescr = 'Document Search - Cyr';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$result = $this->ApiNote->getDocumentBody(4);
		$expected = '<p>wefe fewf wef wef</p>';

		$testItemDescr = 'Get Document Body';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertEqual($expected,$result));
	}

//--------------------------------------------Чат--------------------------------------------------//

	public function chat() {
		$testDescription = 'Api Chat';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));


		$this->testApiChatCreate();
		$this->testApiChatRoomMembers();
		$this->testApiChatSendMessage();
		$this->testApiChatUserEvents();
		$this->testApiChatContactList();

		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);

	}

	public function testApiChatCreate(){
		$userId = 76;
		$result = $this->ApiChatContact->openRoom($this->userId,$userId);
		$id = $result['ChatRoom']['id'];
		unset($result['ChatRoom']['created']);
		$expected = array (
			'ChatRoom' => array (
				'id' => $id,
				'initiator_id' => '183',
				'recipient_id' => '76',
				'can_add_member' => true,
				'can_delete_member' => false,
			),
			'Member' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '183',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
			),
		);

		$testItemDescr = 'Chat User Create';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));


		$newRoomId = $this->ApiChatMember->addChatMember($this->userId,$id,176);
		$result = $this->ApiChatContact->openRoom($this->userId,0,null,$newRoomId);
		unset($result['ChatRoom']['created']);

		$expected = array (
			'ChatRoom' => array (
				'id' => $newRoomId,
				'initiator_id' => '76',
				'recipient_id' => '183',
				'can_add_member' => true,
				'can_delete_member' => true,
			),
			'Member' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '176',
					'full_name' => 'fyr3@tut.by',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
				),
				2 => array (
					'id' => '183',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
			),
		);

		$testItemDescr = 'Add 3rd User in Chat';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiChatMember->addChatMember($this->userId,$newRoomId,175);
		$result = $this->ApiChatContact->openRoom($this->userId,0,null,$newRoomId);
		unset($result['ChatRoom']['created']);

		$expected = array (
			'ChatRoom' => array (
				'id' => $newRoomId,
				'initiator_id' => '76',
				'recipient_id' => '183',
				'can_add_member' => true,
				'can_delete_member' => true,
			),
			'Member' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '175',
					'full_name' => 'Fyr 2',
					'url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
				),
				2 => array (
					'id' => '176',
					'full_name' => 'fyr3@tut.by',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
				),
				3 => array (
					'id' => '183',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
			),
		);

		$testItemDescr = 'Add 4th User in Chat';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$result = $this->ApiChatContact->openRoom($this->userId,0,7);
		$groupRoomId = $result['ChatRoom']['id'];
		unset($result['ChatRoom']['created']);
		$expected = array (
			'ChatRoom' => array (
				'id' => $groupRoomId,
				'initiator_id' => '183',
				'recipient_id' => '67',
			'Group' =>array (
				'id' => '7',
				'title' => 'KONSTRUKTOR ',
				'url_img' => '/media/router/index/group/713/noresize/image.jpg.png',
			),
			'can_add_member' => false,
			'can_delete_member' => false,
			),
			'Member' =>array (
				0 => array (
					'id' => '67',
					'full_name' => 'Vlad Krishtop',
					'url_img' => '/media/router/index/user/647/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '183',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
			),
		);

		$testItemDescr = 'Create Group Chat';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiChatMember->removeChatMember($this->userId,$newRoomId,175);
		$result = $this->ApiChatContact->openRoom($this->userId,0,null,$newRoomId);
		unset($result['ChatRoom']['created']);

		$expected = array (
			'ChatRoom' => array (
				'id' => $newRoomId,
				'initiator_id' => '76',
				'recipient_id' => '183',
				'can_add_member' => true,
				'can_delete_member' => true,
			),
			'Member' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '176',
					'full_name' => 'fyr3@tut.by',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
				),
				2 => array (
					'id' => '183',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
			),
		);

		$testItemDescr = 'Delete User From Chat';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$deleteRooms = array($id,$newRoomId,$groupRoomId);
		$this->ApiChatRoom->delete($deleteRooms);
		$this->ApiChatMember->deleteAll(array('room_id'=>$deleteRooms));
		$this->ApiChatContact->deleteAll(array('room_id'=>$deleteRooms));
		$this->ApiChatEvent->deleteAll(array('room_id'=>$deleteRooms));
	}

	private function testApiChatRoomMembers(){
		$result = $this->ApiChatMember->getMembers(79);
		$expected = array (
			0 => array (
				'id' => '76',
				'full_name' => 'Fyr Fayar',
				'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
			),
			1 => array (
				'id' => '175',
				'full_name' => 'Fyr 2',
				'url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
			),
			2 => array (
				'id' => '176',
				'full_name' => 'fyr3@tut.by',
				'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
			),
			3 => array (
				'id' => '177',
				'full_name' => 'fyr4@tut.by',
				'url_img' => '/img/no-photo.jpg',
			),
		);

		$testItemDescr = 'Chat Members';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

	private function testApiChatSendMessage(){

		$userId = 76;
		$roomId = 79;
		$message = 'test';

		$result = $this->ApiChatEvent->addMessage($userId, $roomId, $message);
		$msgEventId = $result['ChatEvent']['id'];
		$msgId = $result['ChatEvent']['msg_id'];
		unset($result['ChatEvent']['created']);

		$expected = array (
			'ChatEvent' => array (
				'id' => $msgEventId,
				'user_id' => '76',
				'room_id' => '79',
				'active' => false,
				'event_type' => '1',
				'initiator_id' => '76',
				'initiator_name' => 'Fyr Fayar',
				'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
				'recipient_id' => NULL,
				'file_id' => NULL,
				'msg_id' => $msgId,
				'message' => 'test',
			),
		);

		$testItemDescr = 'Send Message';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		//$deleteEvents = array($msgEventId);
		$this->ApiChatEvent->deleteAll(array('msg_id'=>$msgId));
	}

	private function testApiChatUserEvents(){

		$userId = 175;
		$roomId = 78;

		$result = $this->ApiChatEvent->getUpdates($userId,$roomId);

		$expected = array (
			'Events' => array (
				0 => array (
					'id' => '1603',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-19T03:28:53Z',
					'event_type' => '2',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => NULL,
					'msg_id' => '688',
					'message' => 'test mesg',
				),
				1 => array (
					'id' => '1605',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-27T00:54:20Z',
					'event_type' => '7',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => '804',
					'msg_id' => NULL,
					'media_type' => 'image',
					'ext' => '.jpg',
					'url' => '/files/chat/8/804/image.jpg',
					'image' => '/media/router/index/chat/804/100x100/image.jpg',
				),
				2 => array (
					'id' => '1607',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-27T00:55:09Z',
					'event_type' => '7',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => '805',
					'msg_id' => NULL,
					'media_type' => 'image',
					'ext' => '.jpg',
					'url' => '/files/chat/8/805/image.jpg',
					'image' => '/media/router/index/chat/805/100x100/image.jpg',
				),
			),
			'UpdateRooms' => array (),
			'Contacts' => array (
				1 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'contact_id' => '68',
					'chat_event_id' => '1607',
					'active_count' => '3',
					'msg' => 'Вы получили файл',
					'ChatRoom' => array (
						'room_id' => '78',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => false,
					),
					'members_count' => 2,
				),
			),
		);

		$testItemDescr = 'Chat Updates';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$userId = 175;
		$eventIds = array(1605,1603);
		$this->ApiChatEvent->markRead($userId, $eventIds);

		$result = $this->ApiChatEvent->getUpdates($userId,$roomId);

		$expected = array (
			'Events' => array (
				0 => array (
					'id' => '1607',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-27T00:55:09Z',
					'event_type' => '7',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => '805',
					'msg_id' => NULL,
					'media_type' => 'image',
					'ext' => '.jpg',
					'url' => '/files/chat/8/805/image.jpg',
					'image' => '/media/router/index/chat/805/100x100/image.jpg',
				),
			),
			'UpdateRooms' => array (),
			'Contacts' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'contact_id' => '68',
					'chat_event_id' => '1607',
					'active_count' => '0',
					'msg' => 'Вы получили файл',
					'ChatRoom' => array (
						'room_id' => '78',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => false,
					),
					'members_count' => 2,
				),
			),
		);

		$testItemDescr = 'Chat Mark as Read';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
		$this->ApiChatEvent->updateAll(array('active'=>1),array('id'=>$eventIds));
		$this->ApiChatContact->updateAll(array('active_count'=>3),array('id'=>$result['Contacts'][0]['contact_id']));

		$userId = 175;
		$roomId = 78;
		$lastEventId = 1608;

		$result = $this->ApiChatEvent->loadEvents($userId,$roomId,$lastEventId);

		$expected = array (
			'Events' => array (
				0 => array (
					'id' => '1605',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-27T00:54:20Z',
					'event_type' => '7',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => '804',
					'msg_id' => NULL,
					'media_type' => 'image',
					'ext' => '.jpg',
					'url' => '/files/chat/8/804/image.jpg',
					'image' => '/media/router/index/chat/804/100x100/image.jpg',
				),
				1 => array (
					'id' => '1607',
					'user_id' => '175',
					'room_id' => '78',
					'active' => true,
					'created' => '2014-12-27T00:55:09Z',
					'event_type' => '7',
					'initiator_id' => '76',
					'initiator_name' => 'Fyr Fayar',
					'initiator_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'recipient_id' => NULL,
					'file_id' => '805',
					'msg_id' => NULL,
					'media_type' => 'image',
					'ext' => '.jpg',
					'url' => '/files/chat/8/805/image.jpg',
					'image' => '/media/router/index/chat/805/100x100/image.jpg',
				),
			),
			'UpdateRooms' => array (),
			'Contacts' => array (
				0 => array (
					'id' => '76',
					'full_name' => 'Fyr Fayar',
					'url_img' => '/media/router/index/user/800/noresize/image.jpg.png',
					'contact_id' => '68',
					'chat_event_id' => '1607',
					'active_count' => '0',
					'msg' => 'Вы получили файл',
					'ChatRoom' => array (
						'room_id' => '78',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => false,
					),
					'members_count' => 2,
				),
			),
		);

		$testItemDescr = 'Chat Load Previous Events By Last Event ID';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

	public function testApiChatContactList(){

		$userId = 76;
		$search_query = '';
		$result = $this->ApiChatContact->getList($userId,$search_query);
		foreach ($result['User'] as &$item){
			$item['chat_event_id'] = 'stub_event_id';
		}
		$expected = array (
			'User' => array (
				0 => array (
					'id' => '175',
					'full_name' => 'Fyr 2',
					'url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
					'contact_id' => '64',
					'chat_event_id' => 'stub_event_id',
					'active_count' => '0',
					'msg' => 'test',
					'ChatRoom' => array (
						'room_id' => '79',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => true,
					),
					'members_count' => 4,
				),
				1 => array (
					'id' => '175',
					'full_name' => 'Fyr 2',
					'url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
					'contact_id' => '63',
					'chat_event_id' => 'stub_event_id',
					'active_count' => '0',
					'msg' => 'test mesg',
					'ChatRoom' => array (
						'room_id' => '78',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => false,
					),
					'members_count' => 2,
				),
			),
		);

		$testItemDescr = 'Get Users Contact List';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$userId = 76;
		$search_query = 'fyr';
		$result = $this->ApiChatContact->getList($userId,$search_query);
		$expected = array (
			'User' => array (
				0 => array (
					'id' => '176',
					'full_name' => 'fyr3@tut.by',
					'url_img' => '/media/router/index/user/802/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '177',
					'full_name' => 'fyr4@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				2 => array (
					'id' => '178',
					'full_name' => 'fyr5@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				3 => array (
					'id' => '180',
					'full_name' => 'fyr7@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				4 => array (
					'id' => '181',
					'full_name' => 'fyr8@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				5 => array (
					'id' => '175',
					'full_name' => 'Fyr 2',
					'url_img' => '/media/router/index/user/770/noresize/image.jpg.png',
					'contact_id' => '63',
					'chat_event_id' => '1602',
					'active_count' => '0',
					'msg' => 'test mesg',
					'ChatRoom' => array (
						'room_id' => '78',
						'recipient_id' => '175',
						'initiator_id' => '76',
						'group_id' => NULL,
						'can_add_member' => true,
						'can_delete_member' => false,
					),
					'members_count' => 2,
				),
				6 => array (
					'id' => '179',
					'full_name' => 'fyr6@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
			),
		);

		$testItemDescr = 'Get Users Contact List - search';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

//-------------------------------Тамйлайн--------------------------------------------------------//

	public function timeline() {
		$testDescription = 'Api TimeLine';

		$this->access_token = '6abdbdc0be964271f8d0ce2a1f5e27be';
		$this->userId = '183';

		$this->ApiAccess->query('TRUNCATE TABLE api_accesses');
		$this->ApiAccess->save(array('token' => $this->access_token, 'user_id' => $this->userId));

		$this->testApiTimelineEvents();
		$this->testApiUserEvents();

		$this->set('tests', $this->tests);
		$this->set('title_for_layout', 'Test');
		$this->set('testDescription', $testDescription);

	}

	private function testApiTimelineEvents(){

		$userId = 76;
		$startDate = '2014-12-01';
		$endDate = '2015-01-01';
		$result = $this->ApiTimeline->getTimeline($userId,$startDate,$endDate);

		$expected = array (
			'LastUsers' => array (
				0 => array (
					'id' => '183',
					'created' => '2015-01-13T10:20:35Z',
					'full_name' => 'алекс',
					'url_img' => '/media/router/index/user/819/noresize/image.jpg.png',
				),
				1 => array (
					'id' => '181',
					'created' => '2014-12-22T10:36:53Z',
					'full_name' => 'fyr8@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				2 => array (
					'id' => '180',
					'created' => '2014-12-22T10:33:44Z',
					'full_name' => 'fyr7@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				3 => array (
					'id' => '179',
					'created' => '2014-12-10T13:24:49Z',
					'full_name' => 'fyr6@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
				4 => array (
					'id' => '178',
					'created' => '2014-12-10T09:26:24Z',
					'full_name' => 'fyr5@tut.by',
					'url_img' => '/img/no-photo.jpg',
				),
			),
			'LastGroups' => array (
				0 => array (
					'id' => '26',
					'created' => '2015-01-05T16:24:37Z',
					'title' => 'Тест группа',
					'url_img' => '/img/no-photo.jpg',
				),
				1 => array (
					'id' => '25',
					'created' => '2014-11-29T23:14:43Z',
					'title' => 'Group 4',
					'url_img' => '/img/no-photo.jpg',
				),
			),
			'Events' => array (
				0 => array (
					'event_datetime' => '2014-12-23T02:06:07Z',
					'type' => 3,
					'ProjectEvent' => array (
						'id' => '13',
						'created' => '2014-12-23T02:06:07Z',
						'project_id' => '1',
						'project_title' => 'Проект 1',
						'user_id' => '76',
						'event_type' => '8',
						'file' => array (
							'media_type' => 'image',
							'ext' => '.jpg',
							'image' => '/media/router/index/projectevent/803/100x100/image.jpg',
							'url_download' => '/files/projectevent/8/803/image.jpg',
						),
						'task' => array (
							'id' => '1',
							'created' => '2014-11-29T23:52:03Z',
							'deadline' => '2014-11-26T23:00:00Z',
							'subproject_id' => '1',
							'user_id' => '71',
							'manager_id' => '76',
							'closed' => false,
							'descr' => 'proj1 Subproject 1 task 1 description',
						),
						'subproject_id' => NULL,
					),
				),
				1 => array (
					'event_datetime' => '2014-12-22T23:59:13Z',
					'type' => 3,
					'ProjectEvent' => array (
						'id' => '12',
						'created' => '2014-12-22T23:59:13Z',
						'project_id' => '1',
						'project_title' => 'Проект 1',
						'user_id' => '76',
						'event_type' => '7',
						'message' => 'new message for task 1',
						'task' => array (
							'id' => '1',
							'created' => '2014-11-29T23:52:03Z',
							'deadline' => '2014-11-26T23:00:00Z',
							'subproject_id' => '1',
							'user_id' => '71',
							'manager_id' => '76',
							'closed' => false,
							'descr' => 'proj1 Subproject 1 task 1 description',
						),
						'subproject_id' => NULL,
					),
				),
				2 => array (
					'event_datetime' => '2014-12-16T21:00:00Z',
					'type' => 4,
					'UserEvent' => array (
						'id' => '94',
						'created' => '2014-12-16T07:31:24Z',
						'event_time' => '2014-12-16T21:00:00Z',
						'event_end_time' => '1970-01-01T00:00:00Z',
						'user_event_type' => false,
						'recipient_id' => NULL,
						'task_id' => NULL,
						'title' => 'Event 22:00 ',
						'descr' => '',
					),
				),
				3 => array (
					'event_datetime' => '2014-12-09T20:56:06Z',
					'type' => 5,
					'Article' => array (
						'id' => '33',
						'created' => '2014-12-09T20:56:06Z',
						'title' => 'рас рас два три два рас',
						'owner_id' => '76',
						'author_name' => 'Fyr Fayar',
						'author_img' => '/media/router/index/user/800/noresize/image.jpg.png',
						'group_id' => NULL,
						'cat_id' => '0',
					),
				),
				4 => array (
					'event_datetime' => '2014-12-09T19:02:47Z',
					'type' => 5,
					'Article' => array (
						'id' => '32',
						'created' => '2014-12-09T19:02:47Z',
						'title' => 'Windows 10',
						'owner_id' => '76',
						'author_name' => 'Fyr Fayar',
						'author_img' => '/media/router/index/user/800/noresize/image.jpg.png',
						'group_id' => NULL,
						'cat_id' => '5',
					),
				),
				5 => array (
					'event_datetime' => '2014-12-09T18:43:47Z',
					'type' => 5,
					'Article' => array (
						'id' => '28',
						'created' => '2014-12-09T18:43:47Z',
						'title' => 'название 1',
						'owner_id' => '76',
						'group_id' => '7',
						'author_name' => 'KONSTRUKTOR ',
						'author_img' => '/media/router/index/group/713/noresize/image.jpg.png',
						'cat_id' => '2',
					),
				),
				6 => array (
					'event_datetime' => '2014-12-09T18:27:07Z',
					'type' => 5,
					'Article' => array (
						'id' => '26',
						'created' => '2014-12-09T18:27:07Z',
						'title' => 'Русская статья',
						'owner_id' => '76',
						'group_id' => '13',
						'author_name' => 'Pos&service',
						'author_img' => '/media/router/index/group/688/noresize/image.jpg.png',
						'cat_id' => '0',
					),
				),
				7 => array (
					'event_datetime' => '2014-12-06T15:38:03Z',
					'type' => 3,
					'ProjectEvent' => array (
						'id' => '10',
						'created' => '2014-12-06T15:38:03Z',
						'project_id' => '1',
						'project_title' => 'Проект 1',
						'user_id' => '76',
						'event_type' => '8',
						'file' => array (
							'media_type' => 'bin_file',
							'ext' => '.sql',
							'image' => '/media/img/bin_file.png',
							'url_download' => '/files/projectevent/7/775/bin_file.sql',
						),
						'task' => array (
							'id' => '1',
							'created' => '2014-11-29T23:52:03Z',
							'deadline' => '2014-11-26T23:00:00Z',
							'subproject_id' => '1',
							'user_id' => '71',
							'manager_id' => '76',
							'closed' => false,
							'descr' => 'proj1 Subproject 1 task 1 description',
						),
						'subproject_id' => NULL,
					),
				),
				8 => array (
					'event_datetime' => '2014-12-06T15:37:47Z',
					'type' => 3,
					'ProjectEvent' => array (
						'id' => '9',
						'created' => '2014-12-06T15:37:47Z',
						'project_id' => '1',
						'project_title' => 'Проект 1',
						'user_id' => '76',
						'event_type' => '7',
						'message' => 'task message',
						'task' => array (
							'id' => '1',
							'created' => '2014-11-29T23:52:03Z',
							'deadline' => '2014-11-26T23:00:00Z',
							'subproject_id' => '1',
							'user_id' => '71',
							'manager_id' => '76',
							'closed' => false,
							'descr' => 'proj1 Subproject 1 task 1 description',
						),
						'subproject_id' => NULL,
					),
				),
				9 => array (
					'event_datetime' => '2014-12-01T05:00:00Z',
					'type' => 4,
					'UserEvent' => array (
						'id' => '93',
						'created' => '2014-11-30T05:25:08Z',
						'event_time' => '2014-12-01T05:00:00Z',
						'event_end_time' => '1970-01-01T00:00:00Z',
						'user_event_type' => false,
						'recipient_id' => '71',
						'recipient_name' => 'Ярослав Ерошенко',
						'recipient_img' => '/img/no-photo.jpg',
						'task_id' => NULL,
						'title' => 'Test event 01.12.2014 - 05:00',
						'descr' => 'Test event 01.12.2014 - 05:00 description',
					),
				),
			),
		);

		$testItemDescr = 'User Timeline';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));
	}

	private function testApiUserEvents(){

		$saveData['user_id'] = 76;
		$saveData['title'] = 'title';
		$saveData['descr'] = 'descr';
		$saveData['type'] = 'call';
		$saveData['recipient_id'] = 183;
		$saveData['event_time'] = '2015-03-01 17:55:00';
		$saveData['event_end_time'] = '2015-03-01 18:55:00';

		$id = $this->ApiUserEvent->saveUserEvent($saveData);
		$result = array();
		if(!$id){
			die('save user event error');
		}
		$result = $this->ApiUserEvent->findById($id);
		unset($result['ApiUserEvent']['created']);
		$expected = array (
			'ApiUserEvent' => array (
				'id' => $id,
				'event_time' => '2015-03-01 17:55:00',
				'event_end_time' => '2015-03-01 18:55:00',
				'type' => 'call',
				'task_id' => NULL,
				'user_id' => '76',
				'recipient_id' => '183',
				'title' => 'title',
				'descr' => 'descr',
			),
		);

		$testItemDescr = 'Add User Event';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$saveData['id'] = $id;
		$saveData['user_id'] = 76;
		$saveData['title'] = 'title new';
		$saveData['descr'] = 'descr new';
		$saveData['recipient_id'] = 71;
		$saveData['task_id'] = 1;
		$saveData['type'] = 'meet';
		$saveData['event_time'] = '2015-03-01 19:55:00';
		$saveData['event_end_time'] = '2015-03-01 20:55:00';

		$this->ApiUserEvent->saveUserEvent($saveData);

		$result = $this->ApiUserEvent->findById($id);
		unset($result['ApiUserEvent']['created']);
		$expected = array (
			'ApiUserEvent' => array (
				'id' => $id,
				'event_time' => '2015-03-01 19:55:00',
				'event_end_time' => '2015-03-01 20:55:00',
				'type' => 'meet',
				'task_id' => 1,
				'user_id' => '76',
				'recipient_id' => '71',
				'title' => 'title new',
				'descr' => 'descr new',
			),
		);

		$testItemDescr = 'Update User Event';
		$this->dataTests($testItemDescr, $expected, $result, $this->assertArray($expected,$result));

		$this->ApiUserEvent->delete($id);
	}

//-------------------------------------------------------------------------------------------------//

	private function dataTests($testItemDescr, $expected, $result, $assert) {
		$this->tests[] =  array(
							'test' => $testItemDescr,
							'expected' => $expected,
							'result' => $result,
							'assert' => $assert
						);
		$this->_totalCount++;
		if($assert=="OK"){
			$this->_successCount++;
		}
	}

	private function assertEqual($expected, $result) {
		return $this->assertTrue($expected == $result);
	}

	private function assertNotEqual($expected, $result) {
		return $this->assertTrue($expected != $result);
	}

	private function assertArray($expected, $result) {
		$diff = $this->array_diff_assoc_recursive($expected, $result);
		//echo '<pre>';print_r($diff);
		return $this->assertTrue($diff ? false : true);
	}

	//рекурсивное сравнение массивов
	private function array_diff_assoc_recursive($array1, $array2) {
		$difference = array();
		foreach ($array1 as $key => $value) {
			if (is_array($value)) {
				if (!isset($array2[$key]) || !is_array($array2[$key])) {
					$difference[$key] = $value;
				} else {
					$new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
					if (!empty($new_diff)) {
						$difference[$key] = $new_diff;
					}
				}
			} else if (!array_key_exists($key, $array2) || trim($array2[$key]) != trim($value)) {
				$difference[$key] = $value;
			}
		}
		return $difference;
	}

	private function assertTrue($result) {
		return ($result) ? 'OK' : 'FAILED';
	}

	private function assertFalse($result) {
		return ($result) ? 'FAILED' : "OK";
	}

}

?>
