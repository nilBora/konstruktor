<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiTaskCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Создание задачи
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function task_create(){
			$fields = array('subproject_id','title','deadline','manager_id','user_id','descr');
			foreach ($fields as $item){
				if (!isset($this->_controller->request->data[$item]) or !$this->_controller->request->data[$item]) {
					throw new ApiIncorrectRequestException();
				}
			}
			
			$projectId = $this->_controller->ApiSubproject->getProjectId($this->_controller->request->data['subproject_id']);
			if(!$projectId){
				throw new ApiAccessDeniedException();
			}

			if(!$this->_controller->ApiSubproject->checkAccess($this->_userId,$projectId)){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiProject->checkAccessToProject($this->_controller->request->data['user_id'],$projectId) 
				or !$this->_controller->ApiProject->checkAccessToProject($this->_controller->request->data['manager_id'],$projectId)){
					throw new ApiAccessDeniedException();
			}
			$this->_controller->request->data['creator_id'] = $this->_userId;
			$taskId = $this->_controller->ApiTask->createTask($this->_userId,$this->_controller->request->data,$projectId);
			if(!$taskId){
				throw new Exception();
			}
			$this->_controller->setResponse(array('Task'=>array('id'=>$taskId)));
	}
	
	/**
	* Информация по задаче
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function task_info(){
			if (!isset($this->_controller->request->data['task_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$projectId = $this->_controller->ApiTask->getProjectIdByTaskId($this->_controller->request->data['task_id']);
			if(!$projectId){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiProject->checkAccessToProject($this->_userId,$projectId)){
				throw new ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiTask->getInfo($this->_controller->request->data['task_id'],$projectId);
			$this->_controller->setResponse($result);
	}

	/**
	* Отправить комментарий к задаче
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function add_task_comment(){
			if (!isset($this->_controller->request->data['task_id']) or !isset($this->_controller->request->data['message']) or !trim($this->_controller->request->data['message'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$projectId = $this->_controller->ApiTask->getProjectIdByTaskId($this->_controller->request->data['task_id']);
			if(!$projectId){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiProject->checkAccessToProject($this->_userId,$projectId)){
				throw new ApiAccessDeniedException();
			}
			
			$result = $this->_controller->ApiTask->addComment($this->_userId,$this->_controller->request->data['task_id'],$this->_controller->request->data['message'],$projectId);
			if(!$result){
				$this->_controller->setError('Server Error');
				return;
			}
			$this->_controller->setResponse($result);
	}
	
	/**
	* Отправить файл к задаче
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function add_task_file(){
			if (!isset($this->_controller->request->data['task_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			if(!isset($this->_controller->request->data['message'])){
				$this->_controller->request->data['message'] = '';
			}
			
			$projectId = $this->_controller->ApiTask->getProjectIdByTaskId($this->_controller->request->data['task_id']);
			if(!$projectId){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiProject->checkAccessToProject($this->_userId,$projectId)){
				throw new ApiAccessDeniedException();
			}
			$mediaId = $this->_controller->sendFile('TaskComment', $this->_controller->request->data['task_id']);
			$result = $this->_controller->ApiTask->addComment($this->_userId,$this->_controller->request->data['task_id'],$this->_controller->request->data['message'],$projectId,array($mediaId));
			if(!$result){
				$this->_controller->setError('Server Error');
				return;
			}
			$this->_controller->setResponse($result);
	}
	
	/**
	* Закрыть задачу 
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function close_task(){
			if (!isset($this->_controller->request->data['task_id'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$projectId = $this->_controller->ApiTask->getProjectIdByTaskId($this->_controller->request->data['task_id']);
			if(!$projectId){
				throw new ApiAccessDeniedException();
			}
			
			if(!$this->_controller->ApiTask->checkAccessToTask($this->_userId,$this->_controller->request->data['task_id'])){
				throw new ApiAccessDeniedException();
			}
			
			$this->_controller->ApiTask->closeTask($this->_userId,$this->_controller->request->data['task_id'],$projectId);
			$this->_controller->setResponse();
	}
	
	/**
	* Поиск по задачам пользователя 
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function search_my_tasks(){
			if (!isset($this->_controller->request->data['search_query'])) {
				$this->_controller->request->data['search_query'] = '';
			}
			
			$result = $this->_controller->ApiTask->getMyTasks($this->_userId,$this->_controller->request->data['search_query']);
			$this->_controller->setResponse($result);
	}

}
?>
