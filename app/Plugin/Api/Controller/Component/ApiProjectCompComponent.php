<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiProjectCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Информация по проекту
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function project_info(){
			if (!isset($this->_controller->request->data['project_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiProject->checkAccessToProject($this->_userId,$this->_controller->request->data['project_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiProject->getInfo($this->_controller->request->data['project_id']);
			$this->_controller->setResponse($result);
	}
	
	/**
	* Создание проекта
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function project_create(){
			$saveData = $this->_projectDataProccess();
			if(!$saveData){
				throw new Exception();
			}
			
			$projectId = $this->_controller->ApiProject->createProject($saveData);
			if(!$projectId){
				throw new Exception();
			}			
			$this->_controller->setResponse(array('Project'=>array('id'=>$projectId)));
	}
	
	/**
	* Редактирование проекта
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function project_edit(){			
			if(!isset($this->_controller->request->data['id'])){
				throw new ApiIncorrectRequestException(); 
			}
			
			if(!$this->_controller->ApiProject->isProjectOwner($this->_userId,$this->_controller->request->data['id'])){
				throw new ApiAccessDeniedException();
			}
			
			$saveData = $this->_projectDataProccess();
			if(!$saveData){
				throw new Exception();
			}
			
			$this->_controller->ApiProject->updateProject($saveData);
			$this->_controller->setResponse();
	}
	
	/**
	* Валидация и форматирование данных 
	* для создания/редактирвоания проекта
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	private function _projectDataProccess(){
		if($this->_controller->_action == 'project_edit'){
			$this->_controller->request->data['group_id'] = $this->_controller->ApiProject->getGroupByProjectId($this->_controller->request->data['id']);
		}
		
		if (!isset($this->_controller->request->data['group_id']) or !isset($this->_controller->request->data['responsible_id'])
					or !isset($this->_controller->request->data['deadline']) or !isset($this->_controller->request->data['title'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$this->_controller->ApiProject->set($this->_controller->request->data);
			if(!$this->_controller->ApiProject->validates()) {			
				throw new ApiIncorrectRequestException($this->_controller->ApiProject->validationErrors);
			}
			
			if(!$this->_controller->ApiGroup->isAdmin($this->_controller->request->data['group_id'],$this->_userId)){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiGroupMember->checkInGroup($this->_controller->request->data['responsible_id'],$this->_controller->request->data['group_id'])){
				throw new ApiAccessDeniedException();
			}
			
			if(!isset($this->_controller->request->data['hidden'])){
				$this->_controller->request->data['hidden'] = 0;
			}
			
			if(!isset($this->_controller->request->data['descr'])){
				$this->_controller->request->data['descr'] = '';
			}
			
			if($this->_controller->_action == 'project_create'){
				$saveData['group_id'] = $this->_controller->request->data['group_id'];
				$saveData['owner_id'] = $this->_userId;
			}
			if($this->_controller->_action == 'project_edit'){
				$saveData['id'] = $this->_controller->request->data['id'];
			}
			$saveData['responsible_id'] = $this->_controller->request->data['responsible_id'];
			$saveData['hidden'] = $this->_controller->request->data['hidden'];
			$saveData['title'] = $this->_controller->request->data['title'];
			$saveData['deadline'] = date("Y-m-d H:i:s",strtotime($this->_controller->request->data['deadline']));
			$saveData['descr'] = $this->_controller->request->data['descr'];
			
			return $saveData;
	}
	
		/**
	* Создание подпроекта
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function subproject_create(){
			if (!isset($this->_controller->request->data['project_id']) or !isset($this->_controller->request->data['title']) or !trim($this->_controller->request->data['title'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!$this->_controller->ApiSubproject->checkAccess($this->_userId,$this->_controller->request->data['project_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$data['project_id'] = $this->_controller->request->data['project_id'];
			$data['user_id'] = $this->_userId;
			$data['title'] = $this->_controller->request->data['title'];
			$subprojectId = $this->_controller->ApiSubproject->createSubproject($data);
			if(!$subprojectId){
				$this->_controller->setError('Server Error');
				return;
			}
			$this->_controller->setResponse(array('Subproject'=>array('id'=>$subprojectId)));
	}
	
	public function add_project_member(){
		if (!isset($this->_controller->request->data['project_id']) or !isset($this->_controller->request->data['user_id'])) {
				throw new ApiIncorrectRequestException();
		}
		
		if ($this->_controller->request->data['user_id'] == $this->_userId) {
				throw new ApiIncorrectRequestException();
		}
		
		$groupId = $this->_controller->ApiProject->getGroupByProjectId($this->_controller->request->data['project_id']);
		if(!$groupId){
			throw new ApiIncorrectRequestException();
		}
		
		//может добавить только создатель или ответсвенный
		if(!$this->_controller->ApiProject->isProjectOwner($this->_userId,$this->_controller->request->data['project_id'])
			or !$this->_controller->ApiProjectMember->isProjectMember($this->_userId,$this->_controller->request->data['project_id'],1)){
				throw new ApiAccessDeniedException();
		}
		
		//можно добавлять только пользователя из группы
		if(!$this->_controller->ApiGroupMember->checkInGroup($this->_controller->request->data['user_id'],$groupId)){
			throw new ApiAccessDeniedException();
		}
		
		//проверка на существование юзера в проекта
		if($this->_controller->ApiProjectMember->isProjectMember($this->_controller->request->data['user_id'],$this->_controller->request->data['project_id'])){
			throw new ApiIncorrectRequestException();
		}
		
		$saveData['project_id'] = $this->_controller->request->data['project_id'];
		$saveData['user_id'] = $this->_controller->request->data['user_id'];
		$saveData['is_responsible'] = 0;
		
		$projectMemberId = $this->_controller->ApiProjectMember->saveMember($saveData);
		
		$this->_controller->setResponse(array('ProjectMember'=>array('id'=>$projectMemberId)));
		
	}
}
?>
