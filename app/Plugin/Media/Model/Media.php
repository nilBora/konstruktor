<?php
App::uses('AppModel', 'Model');
class Media extends AppModel {
    const MKDIR_MODE = 0777;

    public $types = array('image', 'audio', 'video', 'bin_file');
    protected $PHMedia;

    protected function _afterInit() {
    	App::uses('MediaPath', 'Media.Vendor');
		  $this->PHMedia = new MediaPath();
    }

	/**
	 * @param mixed $results
	 * @param bool $primary
	 * @return mixed
	 */
    public function afterFind($results, $primary = false) {
    	foreach($results as &$_row) {
    		$row = @$_row[$this->alias];
    		if (!$primary) {
    			unset($_row[$this->alias]);
    			$_row[$this->alias]['id'] = $row['id'];
    			$_row[$this->alias]['object_type'] = $row['object_type'];
    			$_row[$this->alias]['object_id'] = $row['object_id']; // required for relations btw/ models :(
    			$_row[$this->alias]['media_type'] = $row['media_type'];
    			$_row[$this->alias]['ext'] = str_replace('.', '', $row['ext']);
				$_row[$this->alias]['orig_fsize'] = $row['orig_fsize'];
                $_row[$this->alias]['orig_fname'] = $row['orig_fname'];
    		}

    		if ($row['id']) {
	    		if ($row['media_type'] == 'image') {
	            	$_row[$this->alias]['url_img'] = $this->PHMedia->getImageUrl($row['object_type'], $row['id'], 'noresize', $row['file'].$row['ext'].'.png');
                $_row[$this->alias]['orig_w'] = $row['orig_w'];
                $_row[$this->alias]['orig_h'] = $row['orig_h'];
	    		}
	    		$_row[$this->alias]['url_download'] = $this->PHMedia->getRawUrl($row['object_type'], $row['id'], $row['file'].$row['ext']);
    		} else  {
    			$_row[$this->alias]['url_img'] = '/img/no-photo.jpg';
    			if (in_array($row['object_type'], array('User', 'Group'))) {
    				$_row[$this->alias]['url_img'] = '/img/noimage-'.strtolower($row['object_type']).'.jpg';
    			}
    			$_row[$this->alias]['url_download'] = '';
    		}
    	}
    	return $results;
    }

	/**
	 * @param $user_id
	 * @param $object_type
	 * @param $size
	 * @param string $operation
	 */
	public function updateStorageData($user_id, $object_type, $size, $operation = 'add') {
        $StorageLimit = ClassRegistry::init('StorageLimit');
        if(!empty($user_id) && is_numeric($user_id)) {
            $conditions = array(
                'StorageLimit.user_id' => $user_id
            );
            $result = $StorageLimit->find('first', compact('conditions'));
            $data = $result['StorageLimit'];
            if(!empty($result)) {
                switch($object_type) {
                    case 'Chat':
                        if($operation == 'add')
                            $data['message_file_size'] += $size;
                        else
                            $data['message_file_size'] -= $size;
                        break;
                    case 'Cloud':
                        if($operation == 'add')
                            $data['cloud_size'] += $size;
                        else
                            $data['cloud_size'] -= $size;
                        break;
                    case 'TaskComment':
                        if($operation == 'add')
                            $data['project_file_size'] += $size;
                        else
                            $data['project_file_size'] -= $size;
                        break;
                }
            }
            $StorageLimit->save($data);
        }
    }


    /**
     * Removes actual media-files before delete a record
     *
     * @param bool $cascade
     * @return bool
     */
	public function beforeDelete($cascade = true) {
		App::uses('Path', 'Core.Vendor');

		$media = $this->findById($this->id);

        if(!empty($media['Media']['orig_fsize'])) {
            $file_size = $media['Media']['orig_fsize'];
            if($file_size >0) {
                App::uses('CakeSession', 'Model/Datasource');
                $user_id = CakeSession::read('Auth.User.id');
                $this->updateStorageData($user_id, 'Cloud', $file_size, $operation = 'subtract');
            }
        }

		if ($media) {
			$path = $this->PHMedia->getPath($media[$this->alias]['object_type'], $this->id);

			if (file_exists($path)) {
				// remove all files in folder
				$aPath = Path::dirContent($path);
				if (isset($aPath['files']) && $aPath['files']) {
					foreach($aPath['files'] as $file) {
						unlink($aPath['path'].$file);
					}
				}
				rmdir($path);
			}
		}
		return true;
	}

	/**
	 * @return mixed
	 */
    public function getPHMedia() {
    	return $this->PHMedia;
    }

    /**
     * Uploades media file into auto-created folder
     *
     * @param array $data - array. Must contain elements: 'media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext'
     *                      tmp_name - temp file to rename to media folders
     *                      real_name - if image is relocated or copied
     *                      file.ext - final name of file
     */
    public function uploadMedia($data) {

    	$this->clear();
		$this->save($data);
		$id = $this->id;

		extract($data);

        App::uses('CakeSession', 'Model/Datasource');
        $user_id = CakeSession::read('Auth.User.id');

		// Create folders if not exists
		$path = $this->PHMedia->getTypePath($object_type);
		if (!file_exists($path)) {
		    mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
		}

		$path = $this->PHMedia->getPagePath($object_type, $id);
		if (!file_exists($path)) {
		    mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
		}

		$path = $this->PHMedia->getPath($object_type, $id);
		if (!file_exists($path)) {
			mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
		}
		$filepath = $path;
		if (isset($real_name)) { // if image is simply relocated
			copy($real_name, $path.$file.$ext);

			$res = false;
		} else { // image was uploaded
			// TODO: handle rename error
			$res = rename($tmp_name, $path.$file.$ext);
		}

        $file_size = filesize($path.$file.$ext);
		if ($res) {
		    // remove auto-thumb
		    $path = pathinfo($tmp_name);

		    @unlink($path['dirname'].'/thumbnail/'.$path['basename']);
		}

		if (!isset($media_type) || $media_type == 'image') {
			// Save original image resolution and file size
			$file = $this->PHMedia->getFileName($object_type, $id, null, $file.$ext);

			App::uses('Image', 'Media.Vendor');
			$image = new Image();
			$image->load($file);

			$this->save(array('id' => $id, 'orig_w' => $image->getSizeX(), 'orig_h' => $image->getSizeY(), 'orig_fsize' => $file_size));

            $this->updateStorageData($user_id, $object_type, $file_size);
			if (isset($crop) and $crop) {
				//prepare thumb for future operations
				list($x, $y, $sizeX, $sizeY) = explode(',', $crop);
				$image->crop($x, $y, $sizeX, $sizeY);
				$image->outputPng($this->PHMedia->getFileName($object_type, $id, null, 'thumb.png'));
			}

			// Set main image if it was first image
			$this->initMain($object_type, $object_id);
		}
        else {

			if ('video' == $media_type ) {
				/** @var QueuedTask $QueuedTask */
				$QueuedTask = ClassRegistry::init('Queue.QueuedTask');
				//480 x 360 (360p)
				$QueuedTask->createJob('ConvertVideo360mp4', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 480, 'height' => 360, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo360ogg', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 480, 'height' => 360, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo360webm', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 480, 'height' => 360, 'media_id' => $id));
				//858 x 480 (480p)
				$QueuedTask->createJob('ConvertVideo480mp4', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 858, 'height' => 480, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo480ogg', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 858, 'height' => 480, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo480webm', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 858, 'height' => 480, 'media_id' => $id));
				//1280 x 720 (720p)
				$QueuedTask->createJob('ConvertVideo720mp4', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1280, 'height' => 720, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo720ogg', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1280, 'height' => 720, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo720webm', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1280, 'height' => 720, 'media_id' => $id));
				//1920 x 1080 (1080p)
				$QueuedTask->createJob('ConvertVideo1080mp4', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1920, 'height' => 1080, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo1080ogg', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1920, 'height' => 1080, 'media_id' => $id));
				$QueuedTask->createJob('ConvertVideo1080webm', array('filepath' => $filepath, 'filename' => $file, 'ext' => $ext, 'width' => 1920, 'height' => 1080, 'media_id' => $id));
			}
            $data = array('id' => $id, 'orig_fsize' => $file_size);
            $this->save($data);
            $this->updateStorageData($user_id, $object_type, $file_size);
        }

		return $id;
    }

	/**
	 * @param $id_list
	 * @return bool
	 * @throws Exception
	 */
    public function updateFileSize($id_list) {
        $flag = true;
        $conditions = array(
            'Media.id' => array_values($id_list),
            'Media.orig_fsize' => null,
        );
        $result = $this->find('all', compact('conditions'));
        $file_paths =Hash::combine($result, '{n}.Media.id', '{n}.Media.url_download');
        foreach($file_paths as $id => $file_path) {
            $path = WWW_ROOT . substr($file_path ,1);
            if(file_exists($path)) {
                if(filesize($path)) {
                    $data = array(
                        'id' => $id,
                        'orig_fsize' => filesize($path)
                    );
                    if(!$this->save($data)) {
                        $flag = false;
                    }
                }

            }
        }
        return $flag;
    }


    /**
     * Uploades media file into auto-created folder
     *
     * @param array $data - array. Must contain elements: 'media_type', 'object_type', 'object_id', 'tmp_name', 'file', 'ext'
     *                      tmp_name - temp file to rename to media folders
     *                      real_name - if image is relocated or copied
     *                      file.ext - final name of file
     */
    public function cloneMedia($data) {
        $this->clear();
        $this->save($data);
        $id = $this->id;

        extract($data);

        // Create folders if not exists
        $path = $this->PHMedia->getTypePath($object_type);
        if (!file_exists($path)) {
            mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
        }
        $path = $this->PHMedia->getPagePath($object_type, $id);
        $old_path = $this->PHMedia->getPagePath($old_object_type, $old_id);
        if (!file_exists($path)) {
            mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
        }
        $path = $this->PHMedia->getPath($object_type, $id);
        if (!file_exists($path)) {
            mkdir($path, self::MKDIR_MODE);
			chmod($path, self::MKDIR_MODE);
        }
        if (isset($orig_fname)) { // if image is simply relocated
            copy($old_path.$old_id.'/'.$file.$ext, $path.$file.$ext);
            $res = false;
        }

        return $id;
    }


    /**
     * Return list of media data with additional stats
     *
     * @param array $findData - conditions
     * @param mixed $order
     * @return array
     */
    public function getList($findData = array(), $order = array('Media.main' => 'DESC', 'Media.id' => 'DESC')) {
        $aRows = $this->find('all', array('conditions' => $findData, 'order' => $order));
        foreach($aRows as &$_row) {
            $row = $_row[$this->alias];
            if ($row['media_type'] == 'image') {
            	$_row[$this->alias]['image'] = $this->PHMedia->getImageUrl($row['object_type'], $row['id'], '100x100', $row['file'].$row['ext']);
            } elseif ($row['ext'] == '.pdf') {
            	$_row[$this->alias]['image'] = '/media/img/pdf.png';
            } else {
            	$_row[$this->alias]['image'] = '/media/img/'.$row['media_type'].'.png';
            }
            $_row[$this->alias]['url_download'] = $this->PHMedia->getRawUrl($row['object_type'], $row['id'], $row['file'].$row['ext']);
        }
        return $aRows;
    }

    /*
    public function typeOf($mediaRow) {
        return (isset($mediaRow[$this->alias]) && isset($mediaRow[$this->alias]['media_type'])) ? $mediaRow[$this->alias]['media_type'] : '';
    }
    */

    /**
     * Set main image
     *
     * @param int $id
     * @param str $object_type
     * @param int $object_id
     */
	public function setMain($id , $object_type = null, $object_id = null) {
		// Clear main flag for all media
		if ($object_id && $object_type) {
			$conditions = compact('object_type', 'object_id');
			$conditions['media_type'] = 'image';
			$this->updateAll(array('main' => 0), $conditions);
		} else {
			$media = $this->findById($id);
			$this->setMain($id, $media[$this->alias]['object_type'], $media[$this->alias]['object_id']);
			return;
		}
		$this->save(array('id' => $id, 'main' => 1));
	}

	/**
	 * Set main image for media
	 *
	 * @param str $object_type
	 * @param int $object_id
	 */
	public function initMain($object_type, $object_id) {
		$media = $this->find('first', array(
			'conditions' => array('object_type' => $object_type, 'object_id' => $object_id, 'media_type' => 'image'),
			'order' => array('main' => 'DESC', 'id'  => 'ASC')
		));
		if ($media) {
			// we have some media records but no main
			if (!$media[$this->alias]['main']) {
				$media[$this->alias]['main'] = 1;
				$this->save($media);
			}
		} // no records
	}

}
