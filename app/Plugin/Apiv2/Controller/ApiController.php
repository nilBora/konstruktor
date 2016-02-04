<?php
/**
* Файл родительского контроллера API
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
App::uses('AppModel', 'Model');
App::uses('ApiAccessDeniedException', 'Apiv2.Vendor');
App::uses('ApiIncorrectRequestException', 'Apiv2.Vendor');
App::uses('ApiUnauthorizedException', 'Apiv2.Vendor');
/**
* Базовый класс для API
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class ApiController extends PAjaxController {

	public $uses =	array(
							'ApiAccess',
							'Apiv2.ApiUser',
							'Apiv2.ApiGroupMember',
							'Apiv2.ApiGroup',
							'Apiv2.ApiSubscription',
							'Apiv2.ApiArticle',
							'Apiv2.ApiArticleEvent',
							'Apiv2.ApiProject',
							'Apiv2.ApiSubproject',
							'Apiv2.ApiProjectMember',
							'Apiv2.ApiTask',
							'Apiv2.ApiNote',
							'Apiv2.ApiChatContact',
							'Apiv2.ApiChatRoom',
							'Apiv2.ApiChatMember',
							'Apiv2.ApiChatEvent',
							'Apiv2.ApiTimeline',
							'Apiv2.ApiUserEvent',
		                    'Apiv2.ApiStatistic',
							'Apiv2.ApiFavouriteUser',
							'Apiv2.ApiFavouriteList',
							'Apiv2.ApiCloud',
							'Media.Media'
	);

	public $components = array('Download','Apiv2.ApiUserComp','Apiv2.ApiGroupComp','Apiv2.ApiArticleComp','Apiv2.ApiChatComp','Apiv2.ApiDocumentComp',
							'Apiv2.ApiProjectComp','Apiv2.ApiSubscriptionComp','Apiv2.ApiTaskComp','Apiv2.ApiTimelineComp','Apiv2.ApiUserEventComp',
							'Apiv2.ApiFavouriteUserComp','Apiv2.ApiFavouriteListComp', 'Apiv2.ApiCloudComp');
	/**
	* Вызываемый метод(так как все идут через index)
	*
	* @var int
	*/
	protected $_action = null;

	/**
	* Идентификатор пользователя из таблицы users
	*
	* @var int
	*/
	protected $_userId = null;

	/**
	* Контроллер для статистики
	*
	* @var string
	*/
	protected $_statController = false;

	/**
	* Экшн для статистики
	*
	* @var string
	*/
	protected $_statAction = false;

	/**
	* Айди сущности для статистики
	*
	* @var int
	*/
	protected $_statPass = false;

	/**
	* Записывает статистику
	* @return void
	*/
	public function _setStatParams($controller,$action,$pass) {
		$this->_statController = $controller;
		$this->_statAction = $action;
		$this->_statPass = $pass;
	}

	/**
	* Записывает статистику
	* @return void
	*/
	protected function _addStatistic() {
		$param['controller'] = $this->_statController;
		$param['action'] = $this->_statAction;
		$param['pass'][0] = $this->_statPass;
		$this->ApiStatistic->addData($this->_userId,$param);
	}

	/**
	* beforeFilter (выставляет разрешение для работы всех actions в котроллере)
	* @return void
	*/
	public function beforeFilter() {
		$this->Auth->allow();
	}

	/**
	* afterFilter (записывает в лог и статистику)
	* @return void
	*/
	public function afterFilter() {
		parent::afterFilter();
		$this->writeLog();
		$this->_addStatistic();
	}

	/**
	* Устанавливает код ошибки и сообщение
	* @uses PAjaxController::_response
	* @return void
	*/
	public function setError($errMsg, $code = 100,$details=array()) {
		$this->_response = array('status' => self::STATUS_ERROR, 'code' => $code, 'errMsg' => $errMsg);
		if($details){
			$this->_response['details'] = $details;
		}
	}

	/**
	* Формирует ответ
	* @uses PAjaxController::_response
	* @return void
	*/
	public function setResponse($data = array()) {
	    $this->_response = array('status' => self::STATUS_OK);
		if(!$data){
			$data = new stdClass(); //для формирования корректного общего ответа "data":{}
		}
	    $this->_response['data'] = $data;
	}

	/**
	* Берет имя action из запроса
	* @uses Controller::request
	* @return string
	*/
	protected function getActionFromRequest() {
		return preg_filter(array('/(^apiv\d{1}\/)/', '(\.json.*)'), '', $this->request->url);
	}

	/**
	* Проверяет токен(на наличие и в таблице api_access)
	* @uses Controller::request
	* @return bool
	*/
	protected function checkToken() {
		if (!isset($this->request->data['access_token'])) {
			return false;
		}
		$this->_userId = $this->ApiAccess->getUserByToken($this->request->data['access_token']);
		if (!$this->_userId) {
			return false;
		}
		return true;
	}

	/**
	* Проверяет токен(на наличие и в таблице api_access)
	* @uses Controller::request
	* @return bool
	*/
	protected function getComponent() {
		$componentsArr = array('ApiUserComp','ApiGroupComp','ApiArticleComp','ApiChatComp','ApiDocumentComp',
							'ApiProjectComp','ApiSubscriptionComp','ApiTaskComp','ApiTimelineComp','ApiUserEventComp',
							'ApiFavouriteUserComp','ApiFavouriteListComp','ApiCloudComp');
		foreach($componentsArr as $component){
			if(method_exists($this->{$component}, $this->_action)){
				return $this->{$component};
			}
		}
		return false;
	}

	/**
	* Входной action для котроллера, в него перенаправляются все запросы,
	* вычисляется и вызывается action
	*
	* @return void
	*/
	public function index() {
		try {

			$this->_action = $this->getActionFromRequest();

			if($this->_action == 'download'){
				$this->download();
				return;
			}

			if (!$this->request->is('post')) {
				$this->setError('Incorrect Request', 102);
				return;
			}


			if (!in_array($this->_action, array('login','register')) and !$this->checkToken()) {
				$this->setError('Unauthorized', 101);
				return;
			}
			if($this->_userId){
				$timezone = $this->User->field('timezone',array('id'=>$this->_userId));
				$this->_initTimezone($timezone);
			}

			$component = $this->getComponent();
			if(!$component){
				$this->setError('Incorrect Request', 102);
				return;
			}
			$component->_userId = $this->_userId;
			$component->{$this->_action}();

		} catch (ApiUnauthorizedException $e){
			$this->setError($e->getMessage(),$e->getCode());
		} catch (ApiIncorrectRequestException $e){
			$this->setError($e->getMessage(),$e->getCode(),$e->errorList);
		} catch (ApiAccessDeniedException $e){
			$this->setError($e->getMessage(),$e->getCode());
		} catch (Exception $e) {
			print_r($e);
			$this->processServerError($e);
		}
	}

	/**
	* Запись в лог-файл для АПИ
	* для кажой версии свой лог-файл
	*
	* @uses ApiController::_logFile
	* @return void
	*/
	public function writeLog() {
		CakeLog::write($this->logFile, date("d-m-Y H:i:s") . ' Request ' . $this->request->controller . '/' . $this->_action . ' ' . $this->request->method() . ' ' . json_encode($this->request->data) . "\n");
		CakeLog::write($this->logFile, date("d-m-Y H:i:s") . ' Response ' . $this->request->controller . '/' . $this->_action . ' ' . json_encode($this->_response) . "\n");
	}

	/**
	* обработка серверной ошибки - запись в лог и формирование ответа
	*
	* @uses ApiController::_logFile
	* @uses Controller::request
	* @return void
	*/
	public function processServerError($e) {
		$this->setError('Server Error');
		CakeLog::write('error', date("d-m-Y H:i:s") . ' Api Error: ' . $this->request->controller . '/' . $this->_action . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(). "\n");
	}

	public function saveImage($mediaType,$objectId){

			$crop = array();

			if(isset($this->request->data['from_x'])
					and isset($this->request->data['from_y'])
					and isset($this->request->data['size_x'])
					and isset($this->request->data['size_y'])){

				$cropArray = array($this->request->data['from_x'],
								$this->request->data['from_y'],
								$this->request->data['size_x'],
								$this->request->data['size_y']
				);
				foreach($cropArray as $param){
					if(!preg_match('/(^\d{1,4}$)/', $param)){
						throw new ApiIncorrectRequestException();
					}
				}
				$crop = implode(',', $cropArray);
			}

			App::uses('UploadHandler', 'Media.Vendor');
			$uploadHandler = new UploadHandler(null,false);
			$fileResonse = $uploadHandler->initialize(false);
			$userFile = $fileResonse['files'][0];

			$orig_fname = $userFile->name;
			$tmp_name = PATH_FILES_UPLOAD.$orig_fname;

			list($media_type) = explode('/', $userFile->type);
			if ($media_type != 'image') {
				throw new ApiIncorrectRequestException();
			}

			$object_type = $mediaType;
			$object_id = $objectId;
			$path = pathinfo($tmp_name);
			$file = $media_type;
			$ext = '.'.$path['extension'];

			$aMedia = $this->Media->getObjectList($object_type, $object_id);
			foreach($aMedia as $media) {
				$this->Media->delete($media['Media']['id']);
			}

			$data = compact('media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext', 'orig_fname', 'crop');
			$this->Media->uploadMedia($data);

			$result = $this->Media->getList(compact('object_type', 'object_id'), array('Media.id' => 'DESC'));
			if(!$result){
				$this->setError('Server Error');
				return;
			}
			$response['Media'][0]['url_img'] = $result[0]['Media']['url_img'];
			$this->setResponse($response);
	}

	public function sendFile($mediaType,$objectId){

			App::uses('UploadHandler', 'Media.Vendor');
			$uploadHandler = new UploadHandler(null,false);
			$fileResonse = $uploadHandler->initialize(false);
			$userFile = $fileResonse['files'][0];

			$orig_fname = $userFile->name;
			$tmp_name = PATH_FILES_UPLOAD.$orig_fname;

			list($media_type) = explode('/', $userFile->type);
			if (!in_array($media_type, $this->Media->types)) {
				$media_type = 'bin_file';
			}

			$crop = false;
			$object_type = $mediaType;
			$object_id = $objectId;
			$path = pathinfo($tmp_name);
			$file = $media_type;
			$ext = '.'.$path['extension'];

			$data = compact('media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext', 'orig_fname','crop');
			$id = $this->Media->uploadMedia($data);

			return $id;
	}

	/**
	* Скачивание
	*
	* @uses Controller::request
	* @return void
	*/
	public function download(){
		try{
			if(!isset($this->request->params['named']['access_token']) or !$this->request->params['named']['access_token']){
				throw new ApiAccessDeniedException();
			}

			$userId = $this->ApiAccess->getUserByToken($this->request->params['named']['access_token']);
			if(!$userId){
				throw new ApiAccessDeniedException();
			}

			if(!isset($this->request->params['named']['entity']) or !isset($this->request->params['named']['id'])){
				throw new ApiAccessDeniedException();
			}

			$entity = $this->request->params['named']['entity'];
			$id = $this->request->params['named']['id'];
			if($entity == 'note'){
				if(!$this->ApiNote->checkAccessToDoc($userId,$id)){
					throw new ApiAccessDeniedException();
				}
				$this->Download->download($id);
			}

		} catch (Exception $e) {
			$this->response->statusCode(404);
			$this->render('/Errors/error404');
		}
	}
}
