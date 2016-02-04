<?php

App::uses('Component', 'Controller');
App::uses('StorageLimitController', 'Controller');
App::uses('AppModel', 'Model');
class ApiCloudCompComponent extends Component {

	private $_controller;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}

	/**
	* Список файлов и папок Cloud
	* @uses Controller::request
	* @return void
	*/
	public function cloud_list() {

		if(isset($this->_controller->request->data['directory_id']))
			$directory_id = $this->_controller->request->data['directory_id'];
		else
			$directory_id = null;
		$this->_controller->request->data['only_directories'];
		if(isset($this->_controller->request->data['only_directories']))
			$only_directories = $this->_controller->request->data['only_directories'];
		else
			$only_directories = false;
		$only_directories = filter_var($only_directories, FILTER_VALIDATE_BOOLEAN);

		$result = $this->_controller->ApiCloud->getList($this->_userId, $directory_id, null, $only_directories);
		$data = array();
		if(isset($result) && !empty($result)) {
			$data['parent_directory_id'] = $result['files']['aCloud']['Cloud']['parent_id'];
			/* insert clod files and folders to array */
			foreach($result['files']['aClouds'] as $key=>$item) {
				if($item['Cloud']['media_id'] == 0) {
					$children = $this->_controller->ApiCloud->getChildren($this->_userId, $item['Cloud']['id']);

					$data['directories'][] = array(
						"id" => $item['Cloud']['id'],
						"name" => $item['Cloud']['name'],
						"childs" => sizeof($children),
						"datetime" => date(DateTime::ISO8601, strtotime($item['Cloud']['created']))
					);
				} else {
					$data['files'][] = array(
						"id" => $item['Cloud']['id'],
						"name" => $item['Cloud']['name'],
						"type" => $item['Media']['ext'],
						"size" => $item['Media']['orig_fsize'],
						"datetime" => date(DateTime::ISO8601, strtotime($item['Cloud']['created']))
					);
				}
			}

			if($only_directories) {
				unset($data['files']);
			}
			/* insert note to array */
			/*
			foreach($result['docs']['aNotes'] as $key=>$item) {
				if($item['Note']['is_folder'] == 0) {
					$data['files'][] = array(
						"id" => $item['Note']['id'],
						"name" => $item['Note']['title'],
						"type" => '',
						"size" => '',
						"datetime" => date(DateTime::ISO8601, strtotime($item['Note']['created']))
					);
				}
			}
			*/
			$this->_controller->setResponse($data);
		}
	}


	/**
	 * Удаление файла или папки Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_delete() {
		if(isset($this->_controller->request->data['id']))
			$id = (int)$this->_controller->request->data['id'];

		$this->_controller->ApiCloud->deleteFolder($this->_userId, $id);
		$this->_controller->setResponse();
	}

	/**
	 * Получение share ссылки Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_share() {
		if(isset($this->_controller->request->data['id']))
			$id = (int)$this->_controller->request->data['id'];
		$this->_controller->ApiCloud->deleteFolder($this->_userId, $id);
		$this->_controller->setResponse();
	}

	/**
	 * Переместить файл в директорию Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_move() {
		if(isset($this->_controller->request->data['id']))
			$id = (int)$this->_controller->request->data['id'];
		if(isset($this->_controller->request->data['directory_id']))
			$directory_id = (int)$this->_controller->request->data['directory_id'];
		else $directory_id = 0;

		$this->_controller->ApiCloud->move($this->_userId, $id, $directory_id);
		$this->_controller->setResponse();
	}

	/**
	 * Получить ссылку на скачивание Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_download() {
		$id = $this->_controller->ApiCloud->download($this->_userId, $this->_controller->request->data['id']);
		$url =  Router::url('/', true).'File/download/'.$id;
		$this->_controller->setResponse(array('download_url' => $url));
	}

	/**
	 * Получить share‐ссылку Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_share_link() {
		$id = $this->_controller->ApiCloud->download($this->_userId, $this->_controller->request->data['id']);
		$url =  Router::url('/', true).'File/preview/'.base64_encode($id);
		$this->_controller->setResponse(array('sharable_url' => $url));
	}

	/**
	 * Загрузить файл в директорию Cloud
	 * @uses Controller::request
	 * @return void
	 */
	public function cloud_upload() {

		if(isset($this->_controller->request->data['directory_id']))
			$directory_id = (int)$this->_controller->request->data['directory_id'];
		if(isset($this->_controller->request->data['files']))
			$files = (int)$this->_controller->request->data['files'];

		$response = $this->_controller->ApiCloud->upload($this->_userId, $_FILES, $directory_id);
		$data = array(
			"id" => $response['Cloud']['id'],
			"name" => $response['Cloud']['name'],
			"type" => $response['Media']['media_type'],
			"size" => $response['Media']['orig_fsize'],
			"datetime" => date(DateTime::ISO8601, strtotime($response['Cloud']['created']))

		);

		$this->_controller->setResponse($data);
	}



}
?>
