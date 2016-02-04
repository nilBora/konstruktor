<?php
App::uses('AppModel', 'Model');
App::import('Model', 'Share');
class Cloud extends AppModel {
	/**
	 * @var array
	 */
    public $actsAs = array('Tree');
	/**
	 * @var string
	 */
    public $useTable = 'cloud';
	/**
	 * @var array
	 */
    public $belongsTo = array(
        'Media' => array(
            'conditions' => array('Media.object_type' => 'Cloud'),
        )
    );

	/**
	 * @param $fileId
	 * @return string
	 */
    public function getFilePreviewUrl( $fileId ) {
        return Router::url( '/File/preview/' . $fileId , true);
    }

	/**
	 * @param $fileId
	 * @return mixed
	 */
    public function getFileConverted( $fileId ) {
		$this->loadModel('Media');
		/** @var Media $media */
		$media = $this->Media;
		$data = $media->findById($fileId);
		if ($data) {
			$converted = $data['Media']['converted'];
		} else {
			$converted = 0;
		}

        return $converted;
    }

	/**
	 * @param $userId
	 * @param $parent
	 * @param $q
	 * @param null $sort
	 * @param null $shared_id
	 * @return array
	 */
	public function search($userId, $parent, $q, $sort = null, $shared_id = null) {
        $this->loadModel('Share');
        if ($sort) {
            if ($sort == 'created') {
                $sort = "Cloud.$sort";
            }
            $cloudOrder = array($sort, 'media_id');
        } else {
            $cloudOrder = 'Cloud.media_id, Cloud.created, Cloud.name';
        }
        $cloudConditions = [];
        $parent_conditions = [];
        if($parent != 'shared') {
            if(isset($parent) && !is_numeric($parent)) {

                if (is_numeric(base64_decode($parent))) {

                    $parent = base64_decode($parent);

                    $shared_by_link = $this->Share->sharedByLink($parent);
                    $cloudConditions = array(
                        'Cloud.parent_id' => !empty($shared_by_link) ? $shared_by_link : -1
                    );

                }

                $cloudConditions['Cloud.user_id'] = $userId;

                $clouds = $this->find('all', array('conditions' => $cloudConditions, 'order' => $cloudOrder));

                $aCloud = null;
                if(empty($clouds))
                    throw new NotFoundException();
            }
            else {
                $cloudConditions = array('Cloud.user_id' => $userId);
                if ($q) {
                    $cloudConditions['Cloud.name LIKE ?'] = '%' . $q . '%';
                } else {
                    $cloudConditions['Cloud.parent_id'] = $parent;
                }
                $parent_conditions = array(
                    'Cloud.id' => $parent,
                    'Cloud.user_id' => $userId
                );
                $clouds = $this->find('all', array('conditions' => $cloudConditions, 'order' => $cloudOrder));
                $aCloud = $parent ? $this->find('first',
                    array('conditions' => $parent_conditions)) : null;
            }


            $shared_cloud = $this->Share->CloudSharedBy($userId);

            //echo $this->getDatasource()->getLog()['log'][3]['query']; exit;

            foreach ($clouds as &$cloud) {
                $cloud['Media']['url_preview'] = $this->getFilePreviewUrl($cloud['Media']['id']);
                $cloud['Media']['converted'] = $this->getFileConverted($cloud['Media']['id']);
                $cloud['Media']['size'] = ($cloud['Media']['media_type'] == 'image') ? $cloud['Media']['orig_w'] . 'x' . $cloud['Media']['orig_h'] : false;
                $cloud['Cloud']['fileCount'] = $this->find('count', array(
                    'conditions' => array(
                        'Cloud.parent_id' => $cloud['Cloud']['id'],
                        'NOT' => array('Cloud.media_id' => 0),
                    )
                ));
                if(in_array($cloud['Cloud']['id'], $shared_cloud))
                    $cloud['Cloud']['is_shared'] = true;
            }

            return array(
                'aCloud' => $aCloud,
                'aClouds' => $clouds,
            );
        }
        else {
            $final_result = [];
            $conditions = array(
                'Share.user_id' => $userId,
                'Share.target' => Share::CLOUD,
                'Share.share_type' => array(Share::INDIVIDUAL_ACCESS, Share::EDIT_ACCESS),
            );
            if ($q) {
                $conditions['Cloud.name LIKE ?'] = '%' . $q . '%';
            }
            if ($sort) {
                if ($sort == 'created') {
                    $sort = "Cloud.$sort";
                }
                $order = array($sort, 'media_id');
            } else {
                $order = 'Cloud.media_id, Cloud.created, Cloud.name';
            }

            $result = $this->Share->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'media',
                        'alias' => 'Media',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Media.id = Cloud.media_id'
                        )
                    )
                ),
                'conditions' => $conditions,
                'fields' => array('Share.*' ,'Cloud.*', 'Media.*', 'CONCAT("/files/cloud/0/", Media.id, "/image.jpg") AS url_download'),
            ));

            $conditions = [];
            $clouds = [];
            if($shared_id) {
                if(!empty($result)) {
                    $conditions['Cloud.parent_id'] = $shared_id;
                    $clouds = $this->find('all', compact('conditions', 'order'));
                }
            }

            else {
                $final_result['share_back'] = 'index';
                $clouds = Hash::remove($result, '{n}.Share');
            }

            foreach ($clouds as &$cloud) {
                if(!is_null($cloud['Media']['id'])) {
                    $cloud['Media']['ext'] = str_replace('.', '', $cloud['Media']['ext']);
                    $cloud['Media']['url_preview'] = $this->getFilePreviewUrl($cloud['Media']['id']);
                    $cloud['Cloud']['fileCount'] = $this->find('count', array(
                        'conditions' => array(
                            'Cloud.parent_id' => $cloud['Cloud']['id'],
                            'NOT' => array('Cloud.media_id' => 0),
                        )
                    ));
                    $cloud['Media']['url_download'] = $cloud[0]['url_download'];
                }
            }

            $flag_shared = true;

            if($parent) {
                if($parent != 'shared') {
                    $aCloud = $this->find('first',
                        array('conditions' => array('Cloud.user_id' => $userId, 'Cloud.id' => $parent)));
                }
                else {
                    if($shared_id) {
                        $aCloud = $this->find('first',
                            array('conditions' => array('Cloud.id' => $shared_id)));
                    }
                    else {
                        $aCloud = [];
                        $aCloud['Cloud']['parent_id'] = '';
                        $aCloud['Cloud']['name'] = 'Shared';
                    }
                }
            }
            else {
                $aCloud =- null;
            }

            $final_result['aCloud'] = $aCloud;
            $final_result['aClouds'] = $clouds;
            $final_result['flag_shared'] = $flag_shared;

            return $final_result;

        }
    }

	/**
	 * @param $search
	 * @param $userId
	 * @return array
	 */
    public function searchByName($search, $userId) {
        $this->loadModel('Project');
        $this->loadModel('ProjectEvent');
        $this->loadModel('Subproject');
        $this->loadModel('Task');
        $this->loadModel('Media');

        $aProjects = $this->Project->userProjects($userId);
        $aSubprojects = $this->Subproject->find('all', array(
            'conditions' => array(
                'Subproject.project_id' => array_keys($aProjects),
            )
        ));
        $aTasks = $this->Task->find('all', array(
            'conditions' => array(
                'Task.subproject_id' => Hash::extract($aSubprojects, '{n}.Subproject.id'),
            )
        ));

        $TaskIDs = Hash::extract($aTasks, '{n}.Task.id');

        $aEvents = $this->ProjectEvent->find('all', array(
            'conditions' => array(
                'ProjectEvent.task_id' =>  $TaskIDs,
                'NOT' => array(
                    'ProjectEvent.msg_id' => null,
                )
            )
        ));
        $MsgIDs = Hash::extract($aEvents, '{n}.ProjectEvent.msg_id');
        if(empty($MsgIDs)){
            return array();
        }

        $conditions = array(
            'Media.orig_fname LIKE ?' => '%'.$search.'%',
            'Media.object_type' => 'TaskComment',
            'Media.object_id' => $MsgIDs,
        );

        if(mb_strlen($search) == 1) {
            $conditions['Media.orig_fname LIKE ?'] = $search.'%';
        } else if(mb_strlen($search) > 1) {
            $conditions['Media.orig_fname LIKE ?'] = '%'.$search.'%';
        }

        $medias = $this->Media->find('all', array(
            'conditions' => $conditions,
            'limit' => 20
        ));

        foreach ($medias as &$cloud) {
            $cloud['Media']['url_preview'] = $this->getFilePreviewUrl($cloud['Media']['id']);
        }

        return $medias;


        /*
        if(!empty($search) && mb_strlen($search) > 1) {
            $cloudConditions = array('Cloud.user_id' => $userId);
        }
        $cloudConditions['Cloud.name LIKE ?'] = '%' . $search . '%';

        $cloudOrder = 'Cloud.media_id, Cloud.created, Cloud.name';
        $clouds = $this->find('all', array('conditions' => $cloudConditions, 'order' => $cloudOrder));

        foreach ($clouds as &$cloud) {
            $cloud['Media']['url_preview'] = $this->getFilePreviewUrl($cloud['Media']['id']);
            $cloud['Cloud']['fileCount'] = $this->find('count', array(
                'conditions' => array(
                    'Cloud.parent_id' => $cloud['Cloud']['id'],
                    'NOT' => array('Cloud.media_id' => 0),
                )
            ));
        }

        return $clouds;
        */
    }

	/**
	 * @param $userId
	 * @param $id
	 * @throws Exception
	 */
    public function deleteFolder($userId, $id) {
        $this->loadModel('Media.Media');
        $item = $this->find('first', array('conditions' => array('Cloud.id' => $id, 'Cloud.user_id' => $userId)));
        if (!$item) {
            throw new Exception('Folder or file is not found');
        }
        if ($item['Cloud']['media_id']) { // this is file
            $this->Media->delete($item['Cloud']['media_id']);
        }
        $this->id = $id;
        $childrenIds = array($id);
        foreach ($this->children() as $child) {
            $childrenIds[] = $child['Cloud']['id'];
        }
        $this->Media->deleteAll(array('Media.object_type' => 'Cloud', 'Media.object_id' => $childrenIds));
        $this->delete($id);
    }

	/**
	 * @param $folder_ids
	 * @return array
	 */
    public function find_all_files($folder_ids) {
        $file_ids = [];
        foreach($folder_ids as $id) {
            $this->id = $id;
            foreach($this->children() as $child) {
                if($child['Cloud']['media_id'] != 0) {
                    if(!in_array($child['Cloud']['media_id'], $file_ids))
                        $file_ids[] = $child['Cloud']['media_id'];
                }
            }
        }
        return $file_ids;
    }

	/**
	 * @param $userId
	 * @param $id
	 * @param int $parentId
	 * @throws Exception
	 */
    public function move($userId, $id, $parentId = 0)
	{
        $item = $this->find('first', array('conditions' => array('Cloud.id' => $id, 'Cloud.user_id' => $userId)));
        if (!$item) {
            throw new Exception('Folder or file is not found');
        }
        if ($parentId) {
            $parent = $this->find('first',
                array('conditions' => array('Cloud.id' => $parentId, 'Cloud.user_id' => $userId)));
            if (!$parent) {
                throw new Exception('Folder or file is not found');
            }
        }
        $this->id = $id;
        $this->parent_id = $parentId ? $parentId : '';
        $this->saveField('parent_id', $this->parent_id);
    }

	/**
	 * Choose only video files from Cloud
	 *
	 * @param $files
	 * @return array
	 */
	public function getOnlyVideo($files)
	{
		/** @var array $video */
		$video = [];
		foreach ($files as $file) {
			if ($file['Media']['media_type'] == 'video' && $file['Media']['converted'] > 0 ) {
				$video[] = $file['Media'];
			}
		}

		return $video;
	}

}
