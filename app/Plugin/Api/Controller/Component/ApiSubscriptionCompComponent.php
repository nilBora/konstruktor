<?php

App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class ApiSubscriptionCompComponent extends Component {
	
	private $_controller;
	private $_subscribeType;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	/**
	* Проверка подписки
	* 
	* @uses ApiController::_userId 
	* @uses ApiController::_subscribeType
	* @uses Controller::request
	* @return void
	*/
	protected function _checkSubscription(){
		
		$objectId = $this->_controller->{'Api'.ucfirst($this->_subscribeType)}->field('id',array('id'=>$this->_controller->request->data['object_id']));
		//такой группы или юзера нет
		if(!$objectId){
			throw new ApiIncorrectRequestException();
		}
			
		$subscription = $this->_controller->ApiSubscription->findByObjectIdAndSubscriberIdAndType($this->_controller->request->data['object_id'], $this->_userId,$this->_subscribeType);
		//уже подписан
		if($subscription){
			throw new ApiIncorrectRequestException();
		}
		return true;
	}
	
	/**
	* Сохранение подписки
	* 
	* @param bool $delete //флаг удаления подписки 
	* @uses ApiController::_userId 
	* @uses ApiController::_subscribeType
	* @uses Controller::request
	* @return void
	*/
	protected function _saveSubscription($delete = false){
		$this->_controller->request->data['subscriber_id'] = $this->_userId;
		$this->_controller->request->data['type'] = $this->_subscribeType;
		if($delete){
			unset($this->_controller->request->data['access_token']);
			$this->_controller->ApiSubscription->deleteAll($this->_controller->request->data,false);
		}else{
			$this->_controller->ApiSubscription->save($this->_controller->request->data);
		}
		$this->_controller->setResponse();
	}

	/**
	* Подписка на статьи группы
	* 
	* @uses ApiController::_subscribeType 
	* @uses Controller::request
	* @return void
	*/
	public function group_subscribe(){
			if (!isset($this->_controller->request->data['object_id'])) {
				throw new ApiIncorrectRequestException();
			}
		
			$this->_subscribeType = $this->_controller->ApiSubscription->getGroupType();
			
			$this->_checkSubscription();
			$this->_saveSubscription();
	}
	
	/* Отменить подписку на статьи группы
	* 
	* @uses ApiController::_subscribeType 
	* @uses Controller::request
	* @return void
	*/
	public function group_unsubscribe(){
			if (!isset($this->_controller->request->data['object_id'])) {
				throw new ApiIncorrectRequestException();
			}
		
			$this->_subscribeType = $this->_controller->ApiSubscription->getGroupType();			
			$this->_saveSubscription(true);
	}
	
	/**
	* Подписка на статьи пользователя
	* 
	* @uses ApiController::_subscribeType
	* @uses Controller::request
	* @return void
	*/
	public function user_subscribe(){
			if (!isset($this->_controller->request->data['object_id'])) {
				throw new ApiIncorrectRequestException();
			}
		
			$this->_subscribeType = $this->_controller->ApiSubscription->getUserType();
			
			$this->_checkSubscription();
			$this->_saveSubscription();
	}
	
	/* Отменить подписку на статьи пользователя
	* 
	* @uses ApiController::_subscribeType 
	* @uses Controller::request
	* @return void
	*/
	public function user_unsubscribe(){
			if (!isset($this->_controller->request->data['object_id'])) {
				throw new ApiIncorrectRequestException();
			}
		
			$this->_subscribeType = $this->_controller->ApiSubscription->getUserType();
			$this->_saveSubscription(true);
	}
	
	/**
	* Статьи из подписок пользовалеля
	* 
	* @uses ApiController::_userId
	* @uses Controller::request
	* @return void
	*/
	public function subscription_articles(){
			$result = $this->_controller->ApiSubscription->getUserSubcsribedArticles($this->_userId);
			$this->_controller->setResponse($result);
	}
	
}
?>
