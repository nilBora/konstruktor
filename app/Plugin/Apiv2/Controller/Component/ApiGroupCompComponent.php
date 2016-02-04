<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiGroupCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Поиск по группам
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function search_groups(){
			if (!isset($this->_controller->request->data['search_query'])) {
				throw new ApiIncorrectRequestException();
			}

			$this->_controller->ApiUser->set(array('search_query' => $this->_controller->request->data['search_query']));
			if (!$this->_controller->ApiUser->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiUser->validationErrors);
			}
			$searchResult = $this->_controller->ApiUser->search($this->_userId, $this->_controller->request->data['search_query'],array('Group'));
			$this->_controller->setResponse($searchResult);
	}
	
	/**
	* Отправляет список групп, 
	* в которых пользователь числится как администратор
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function user_groups(){
			//тут код, где выбираются группы пользователя, где он администратор или пользователь - на всякий случай
			//$searchResult = $this->_controller->ApiGroupMember->getUserGroups($this->_userId);
			
			$searchResult = $this->_controller->ApiGroup->getUserAdminGroups($this->_userId);
			$this->_controller->setResponse($searchResult);
	}
	

	
	/**
	* Поиск группы по ID
	* 
	* @uses Controller::request
	* @return void
	*/
	public function group_info(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}
		
			$groupData = $this->_controller->ApiGroup->getInfo($this->_controller->request->data['group_id'],$this->_userId);
			$this->_controller->_setStatParams('Group', 'view', $this->_controller->request->data['group_id']);
			$this->_controller->setResponse($groupData);
	}
	
	/**
	* Запрос на вступление в группу
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function join_group(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}
			$groupId = $this->_controller->ApiGroup->field('id',array('id'=>$this->_controller->request->data['group_id']));
			//такой группы нет
			if(!$groupId){
				throw new ApiIncorrectRequestException();
			}
			
			$member = $this->_controller->ApiGroupMember->findByGroupIdAndUserId($this->_controller->request->data['group_id'], $this->_userId);
			
			$this->_controller->request->data['user_id'] = $this->_userId;
			$this->_controller->request->data['approved'] = 0;
			$this->_controller->request->data['is_deleted'] = 0;
			
			if($member){
				//если удален - записываем в участники заново
				if($member['ApiGroupMember']['is_deleted']==1){
					unset($this->_controller->request->data['approved']);
				//если уже состоит и не удален	
				}else if($member['ApiGroupMember']['approved']==1){
					throw new ApiIncorrectRequestException();
				//ожидает подтверждения	
				}else if($member['ApiGroupMember']['approved']==0){
					throw new ApiIncorrectRequestException();
				}
			}

			$this->_controller->ApiGroupMember->save($this->_controller->request->data);
			$this->_controller->setResponse();
	}
	
	/**
	* Устанавливает изображение аватарки группы
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function set_group_image(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}
			$ownerId = $this->_controller->ApiGroup->field('owner_id',array('id'=>$this->_controller->request->data['group_id']));
			//не является админом
			if($ownerId != $this->_userId){
				throw new ApiAccessDeniedException();
			}
				
			$this->_controller->saveImage('Group', $this->_controller->request->data['group_id']);
	}
	
	/**
	* Участники группы
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function group_members(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiGroupMember->checkInGroup($this->_userId,$this->_controller->request->data['group_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$data['GroupMembers'] = $this->_controller->ApiGroupMember->getGroupMemberList($this->_controller->request->data['group_id']);		
			$this->_controller->setResponse($data);
	}
	
	/**
	* Создание группы
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function create_group(){
			$this->_controller->request->data('data.Group.owner_id', $this->_userId);
			$this->_controller->ApiGroup->set($this->_controller->request->data['data']['Group']);
			if(!$this->_controller->ApiGroup->validates()){
				throw new ApiIncorrectRequestException($this->_controller->ApiGroup->validationErrors);
			}
			
			$saveData = $this->_prepareGroupData();
			$groupId = $this->_controller->ApiGroup->saveInfo($saveData);
			
			if(!$this->_controller->request->data('data.GroupMember.role')){
				$this->_controller->request->data('data.GroupMember.role','Administrator');
			}
			$this->_controller->request->data('data.GroupMember.group_id', $groupId);
			$this->_controller->request->data('data.GroupMember.user_id', $this->_userId);
			$this->_controller->request->data('data.GroupMember.approved', 1);
			$this->_controller->request->data('data.GroupMember.sort_order', 0);
			$this->_controller->request->data('data.GroupMember.show_main', 1);
			$this->_controller->request->data('data.GroupMember.approve_date', date('Y-m-d'));
				
			$this->_controller->ApiGroupMember->saveRow($this->_controller->request->data['data']);
		
			$response = array('Group'=>array('id'=>$groupId));
			$this->_controller->setResponse($response);
	}
	
	/**
	* Редактирование группы
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function update_group(){
			if (!isset($this->_controller->request->data['group_id'])) {
				throw new ApiIncorrectRequestException();
			}

			if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
				throw new ApiAccessDeniedException();
			}
			
			if(isset($this->_controller->request->data['data']['GroupMember']['role'])){
				$memberId = $this->_controller->ApiGroupMember->field('',array('group_id'=>$this->_controller->request->data['group_id'],'user_id'=>$this->_userId));
				$this->_controller->request->data('data.GroupMember.id',$memberId);
				if(!$this->_controller->request->data('data.GroupMember.role')){
					$this->_controller->request->data('data.GroupMember.role','Administrator');
				}
			}
			
			$this->_controller->request->data('data.Group.id', $this->_controller->request->data['group_id']);			
			$this->_controller->request->data('data.Group.owner_id', $this->_userId);
		
			$this->_controller->ApiGroup->set($this->_controller->request->data['data']['Group']);
			if(!$this->_controller->ApiGroup->validates()){
				throw new ApiIncorrectRequestException($this->_controller->ApiGroup->validationErrors);
			}
			
			$saveData = $this->_prepareGroupData(true);		
			$this->_controller->ApiGroup->saveInfo($saveData);

			$this->_controller->setResponse();
	}
	
	/**
	* Форматирует данные для обновления/создания группы
	* вот тут мне тоже не очень нравиться - рефакторинг нужен 6.02.2015
	* @uses Controller::request
	* @return array
	*/
	private function _prepareGroupData($for_update=false){
		if ($this->_controller->request->data('data.GroupAchievement')) {
			foreach($this->_controller->request->data('data.GroupAchievement') as $i => $data) {
				if($for_update){
					$this->_controller->request->data('data.GroupAchievement.'.$i.'.group_id', $this->_controller->request->data('data.Group.id'));
				}
				if(isset($this->_controller->request->data['data']['GroupAchievement'][$i]['url'])){
					$url = $this->_controller->request->data('data.GroupAchievement.'.$i.'.url');
					$url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
					$this->_controller->request->data('data.GroupAchievement.'.$i.'.url', $url);
				}
			}
		}
		if ($this->_controller->request->data('data.GroupAddress')) {
			foreach($this->_controller->request->data('data.GroupAddress') as $i => $data) {
				if($for_update){
					$this->_controller->request->data('data.GroupAddress.'.$i.'.group_id', $this->_controller->request->data('Group.id'));
				}
				if(isset($this->_controller->request->data['data']['GroupAddress'][$i]['url'])){
					$url = $this->_controller->request->data('data.GroupAddress.'.$i.'.url');
					$url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
					$this->_controller->request->data('data.GroupAddress.'.$i.'.url', $url);
				}
			}
		}
		
		$saveData['Group'] = $this->_controller->request->data['data']['Group'];
		if(isset($this->_controller->request->data['data']['GroupAddress'])){
			$saveData['GroupAddress'] = $this->_controller->request->data['data']['GroupAddress'];
		}
		if(isset($this->_controller->request->data['data']['GroupAchievement'])){
			$saveData['GroupAchievement'] = $this->_controller->request->data['data']['GroupAchievement'];
		}
		return $saveData;
	}

	/**
	* Обновление команды группы
	* 
	* @uses ApiController::_userId 
	* @uses Controller::request
	* @return void
	*/
	public function update_group_team(){
			if (!isset($this->_controller->request->data['group_id']) or !isset($this->_controller->request->data['data']['GroupMember'])) {
				throw new ApiIncorrectRequestException();
			}
		
			if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
				throw new ApiAccessDeniedException();
			}
			
			foreach($this->_controller->request->data('data.GroupMember') as $i => $data) {
				$param['group_id'] = $this->_controller->request->data['group_id'];
				$param['user_id'] = $this->_controller->request->data('data.GroupMember.'.$i.'.user_id');
				$saveInfo['show_main'] = $this->_controller->request->data('data.GroupMember.'.$i.'.show_main');
				$this->_controller->ApiGroupMember->updateInfo($saveInfo,$param);
			}			
			$this->_controller->setResponse();
	}
	
	/**
	* удаление пользователя администратором
	* 
	* @uses ApiController::_userId 
	* @uses Controller::request
	* @return void
	*/
	public function delete_user_from_group(){
			if (!isset($this->_controller->request->data['group_id']) or !isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
			}

			if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiGroupMember->inGroup($this->_controller->request->data['group_id'],$this->_controller->request->data['user_id'])){
				throw new ApiIncorrectRequestException();
			}
			
			$param['user_id'] = $this->_controller->request->data['user_id'];
			$param['group_id'] = $this->_controller->request->data['group_id'];
			$saveData['is_deleted'] = 1;
			
			$this->_controller->ApiGroupMember->updateInfo($saveData,$param);
			$this->_controller->setResponse();
	}
	
	/**
	* Выслать инвайт
	* 
	* @uses ApiController::_userId 
	* @uses Controller::request
	* @return void
	*/
	public function send_invite(){
			if (!isset($this->_controller->request->data['group_id']) or !isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if ($this->_controller->request->data['user_id'] == $this->_userId) {
				throw new ApiIncorrectRequestException();
			}

			if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
				throw new ApiAccessDeniedException();
			}
			
			$member = $this->_controller->ApiGroupMember->findByGroupIdAndUserId($this->_controller->request->data['group_id'], $this->_controller->request->data['user_id']);
			//если invite отправлен активному участнику - шлем ошибку
			if($member && $member['ApiGroupMember']['approved'] && !$member['ApiGroupMember']['is_deleted']){
				throw new ApiIncorrectRequestException();
			}
			//если пользователь был, то послать ему инвайт заново
			if($member) {
				  $saveData['id'] =  $member['ApiGroupMember']['id'];
			}
			$saveData['user_id'] = $this->_controller->request->data['user_id'];
			$saveData['group_id'] = $this->_controller->request->data['group_id'];
			$saveData['show_main'] = 1; 
			$saveData['is_invited'] = 1; 
			$saveData['is_deleted'] = 0; 
			
			$this->_controller->ApiGroupMember->saveInfo($saveData);
			$this->_controller->setResponse();
	}
	
	/**
	* Приглашения из групп
	* 
	* @uses ApiController::_userId 
	* @uses Controller::request
	* @return void
	*/
	public function invite_list(){
		$result = $this->_controller->ApiGroupMember->getInvites($this->_userId);
		$this->_controller->setResponse($result);
	}

	/**
	* ответить на инвайт
	* 
	* @uses ApiController::_userId 
	* @uses Controller::request
	* @return void
	*/
	public function invite_answer(){
			if (!isset($this->_controller->request->data['group_id']) or !isset($this->_controller->request->data['accept'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!in_array($this->_controller->request->data['accept'],array(0,1))){
				throw new ApiIncorrectRequestException();
			}
			$this->_controller->request->data['id'] = $this->_controller->ApiGroupMember->field('id',array('group_id'=>$this->_controller->request->data['group_id'],
																'user_id'=>$this->_userId,
																'is_invited'=>1,
																'is_deleted'=>0
													));
			if(!$this->_controller->request->data['id']){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->ApiGroupMember->inviteAnswer($this->_controller->request->data);
			$this->_controller->setResponse();	
	}
}
?>
