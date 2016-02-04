<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class AjaxController extends PAjaxController {
	public $name = 'Ajax';
	// public $components = array('Core.PCAuth');
	public $uses = array('Media.Media', 'StorageLimit');

	protected $ProjectEvent;

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	public function upload() {
		$this->autoRender = false;
		App::uses('UploadHandler', 'Media.Vendor');
		$uploadHandler = new UploadHandler(array(), false);
		$files = $uploadHandler->post(false);
		$totalSize = 0;
		foreach($files['files'] as $file){
			$totalSize += (int)$file->size;
		}
		if($totalSize <= 0){
			throw new CakeException('Uploaded files is zero valued. Please try again', 500);
		}
		if(!$this->StorageLimit->checklimit($totalSize, $this->currUser['User']['id'])){
			throw new CakeException('Cloud limit reached. Upgrade you subscription please', 403);
		}
		echo(json_encode($files));
	}

	public function froalaUpload() {
		$this->autoRender = false;
		$allowedExts = array("gif", "jpeg", "jpg", "png");

		// Get filename.
		$temp = explode(".", $_FILES["file"]["name"]);

		// Get extension.
		$extension = end($temp);

		// An image check is being done in the editor but it is best to
		// check that again on the server side.
		// Do not use $_FILES["file"]["type"] as it can be easily forged.
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

		if ((($mime == "image/gif")
		|| ($mime == "image/jpeg")
		|| ($mime == "image/pjpeg")
		|| ($mime == "image/x-png")
		|| ($mime == "image/png"))
		&& in_array($extension, $allowedExts)) {
			// Generate new random name.
			$name = $_FILES["file"]["name"];

			// Save file in the uploads folder.
			move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/files/" . $name);

			// Generate response.
			$response = new StdClass;
			$response->link = "/files/" . $name;
			echo stripslashes(json_encode($response));
		}
	}

	public function move() {
		$orig_fname = $this->request->data('name');
		$tmp_name = PATH_FILES_UPLOAD.$orig_fname;
		list($media_type) = explode('/', $this->request->data('type'));
		if (!in_array($media_type, $this->Media->types)) {
		    $media_type = 'bin_file';
		}
		$object_type = $this->request->data('object_type');
		$object_id = $this->request->data('object_id');
		$path = pathinfo($tmp_name);
		$file = $media_type; // $path['filename'];
		$ext = '.'.$path['extension'];

		if ($crop = $this->request->data('crop')) {
			$crop = (is_array($crop)) ? implode(',', $crop): $crop;
		}

		if (in_array($object_type, array('User', 'Group', 'UserUniversity', 'Article'))) {
			$aMedia = $this->Media->getObjectList($object_type, $object_id);
			foreach($aMedia as $media) {
				$this->Media->delete($media['Media']['id']);
			}
		}

		$data = compact('media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext', 'orig_fname', 'crop');
		$media_id = $this->Media->uploadMedia($data);

		if ($object_type == 'ProjectEvent') {
			$this->loadModel('ProjectEvent');
			$this->ProjectEvent->addTaskFile($this->currUserID, $object_id, $media_id);
		}

		$this->getList($object_type, $object_id);
	}

	public function getList($object_type, $object_id) {
		$list = $this->Media->getList(compact('object_type', 'object_id'), array('Media.id' => 'DESC'));
		if(empty($object_id)){
			$orig_fname = $this->request->data('name');
			$_list = Hash::extract($list, "{n}.Media[orig_fname=".$orig_fname."]");
			$list = array();
			foreach($_list as $key=>$item){
				$list[$key] = array('Media' => $item);
			}
		}
		$this->setResponse($list);
	}

	public function delete($object_type = '', $object_id = '', $id = '') {
		if (!$object_type) {
			$object_type = $this->request->data('object_type');
		}
		if (!$object_id) {
			$object_id = $this->request->data('object_id');
		}
		if (!$id) {
			$id = $this->request->data('id');
		}
		$this->Media->delete($id);
		$this->Media->initMain($object_type, $object_id);
		// if($object_type == 'Cloud') {
		// 	$this->loadModel('Cloud');
		// 	$this->Cloud->deleteAll(array('media_id' => $id), false);
		// }
		$this->setResponse($this->Media->getList(compact('object_type', 'object_id')));
	}

	public function deleteObjectId( $id = '') {
		if (!$id) {
			$id = $this->request->data('id');
		}
		$this->Media->updateAll(array('object_id' => null),array('id' => $id));
		$this->setResponse();
	}



	public function setMain($object_type, $object_id, $id) {
		$this->Media->setMain($id, $object_type, $object_id);
		$this->setResponse($this->Media->getList(compact('object_type', 'object_id')));
	}

}
