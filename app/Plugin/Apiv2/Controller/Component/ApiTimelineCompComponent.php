<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiTimelineCompComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* таймдайн лента
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function get_timeline(){
			if (!isset($this->_controller->request->data['start_date']) or !isset($this->_controller->request->data['end_date'])) {
				throw new ApiIncorrectRequestException();
			}
			
			$startDate = str_replace('T', ' ', $this->_controller->request->data['start_date']);
			$startDate = str_replace('Z', '', $startDate);
			$startDate = date('Y-m-d',  strtotime($startDate));
			
			$endDate = str_replace('T', ' ', $this->_controller->request->data['end_date']);
			$endDate = str_replace('Z', '', $endDate);
			$endDate = date('Y-m-d',  strtotime($endDate));
			
			$this->_controller->ApiTimeline->set(array('start_date' => $startDate,'end_date' => $endDate));
			if (!$this->_controller->ApiTimeline->validates()) {
				throw new ApiIncorrectRequestException($this->_controller->ApiTimeline->validationErrors);
			}
			
			if(strtotime($startDate)>strtotime($endDate)){
				throw new ApiIncorrectRequestException();
			}
			
			//ограничение запроса
			$daysDiff = date_diff(new DateTime($endDate), new DateTime($startDate))->days;
			if($daysDiff > 30){
				throw new ApiIncorrectRequestException();
			}
			
			$result = $this->_controller->ApiTimeline->getTimeline($this->_userId,$startDate,$endDate);
			$this->_controller->setResponse($result);
	}
}
?>
