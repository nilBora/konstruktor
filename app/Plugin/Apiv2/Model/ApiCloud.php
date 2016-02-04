<?php
/**
* файл модели ApiCloud
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('UserAcnievement', 'Model');
App::uses('Group', 'Model');
App::uses('GroupCategory', 'Model');
App::uses('Country', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('AjaxController', 'Controller');
App::import('Model', 'Share');
/**
* Модель ApiCloud. Обертка под модель User
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class ApiCloud extends AppModel {

	protected $PHMedia;
	public $actsAs = array('Tree');
	public $belongsTo = array(
		'Media' => array(
			'conditions' => array('Media.object_type' => 'Cloud'),
		)
	);
	public $useTable = 'cloud';
	public $validate = array(
		'user_id' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		),
		'name' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			)
		)
	);

	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('Cloud');
		$this->loadModel('Note');
		$this->loadModel('Media.Media');
		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();
	}

	public function getList($userId, $parent, $shared_id = null, $only_directories=false) {

		$result['files'] = $this->Cloud->search($userId, $parent, $q=null, $sort=null, $shared_id);
		$result['docs'] = $this->Note->search($userId, $parent, $q=null, $sort=null, $shared_id);

		return $result;
	}

	public function getChildren($userId, $id) {
		$child = $this->Cloud->find('all', array(
			'conditions' => array(
				'Cloud.user_id' => $userId,
				'Cloud.media_id' => 0,
				'Cloud.parent_id' => $id,
			),
			'fields' => array(
				'Cloud.id',
			)
		));
		return $child;
	}

	public function deleteFolder($userId, $id) {
		$item = $this->Cloud->find('first', array('conditions' => array('Cloud.id' => $id, 'Cloud.user_id' => $userId)));
		if (!$item) {
			throw new ApiIncorrectRequestException();
		}
		$this->Cloud->deleteFolder($userId, $id);

	}

	public function move($userId, $id, $parentId = 0) {
		$item = $this->Cloud->find('first', array('conditions' => array('Cloud.id' => $id, 'Cloud.user_id' => $userId)));
		if (!$item) {
			throw new ApiIncorrectRequestException();
		}

		if ($parentId) {
			$parent = $this->Cloud->find('first',
				array('conditions' => array('Cloud.id' => $parentId, 'Cloud.user_id' => $userId)));
			if (!$parent) {
				throw new ApiIncorrectRequestException();
			}
		}

		$this->id = $id;
		$this->parent_id = $parentId ? $parentId : '';
		$this->saveField('parent_id', $this->parent_id);
	}

	public function download($userId, $id) {
		$item = $this->Cloud->find('first', array('conditions' => array('Cloud.id' => $id, 'Cloud.user_id' => $userId)));
		if(!$item) {
			throw new ApiIncorrectRequestException();
		}
		return $item['Media']['id'];
	}

	public function upload($userId, $FILES, $directory_id=0) {

		$uploadfile = PATH_FILES_UPLOAD . basename($FILES['file']['name']);
		if(move_uploaded_file($FILES['file']['tmp_name'], $uploadfile)) {

			$orig_fname = basename($FILES['file']['name']);
			$tmp_name = PATH_FILES_UPLOAD.$orig_fname;
			list($media_type) = explode('/', $FILES['file']['type']);
			if (!in_array($media_type, $this->Media->types)) {
				$media_type = 'bin_file';
			}
			$object_type = 'Cloud';
			$object_id = $directory_id;
			$path = pathinfo($tmp_name);
			$file = $media_type; // $path['filename'];
			$ext = '.'.$path['extension'];

//			if ($crop = $this->request->data('crop')) {
//				$crop = (is_array($crop)) ? implode(',', $crop): $crop;
//			}

			if (in_array($object_type, array('User', 'Group', 'UserUniversity', 'Article'))) {
				$aMedia = $this->Media->getObjectList($object_type, $object_id);
				foreach($aMedia as $media) {
					$this->Media->delete($media['Media']['id']);
				}
			}

			$data = compact('media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext', 'orig_fname', 'crop');

			$media_id = $this->Media->uploadMedia($data);
			$res = array(
				'user_id'=>$userId,
				'parent_id'=>$directory_id,
				'name'=>$orig_fname,
				'media_id'=>$media_id,

			);

			$this->Cloud->save($res);
			$item = $this->Cloud->find('first', array('conditions' => array('Cloud.media_id' => $media_id, 'Cloud.user_id' => $userId)));
			if(!$item) {
				throw new ApiIncorrectRequestException();
			}

			return $item;


		}
	}


}
