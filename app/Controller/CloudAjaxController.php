<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

class CloudAjaxController extends PAjaxController {
	public $name = 'CloudAjax';
	public $helpers = array('Media', 'File');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	/**
	 * Only for load js initialisation
	 */
	public function jsSettings() {
	}

	/**
	 * Provides data for the rendering of the cloud panel
	 */
	public function panel() {
		$this->loadModel('Cloud');
		$this->request->data('q', htmlspecialchars( $this->request->data('q') ));
		$q = $this->request->data('q');
		$id = $this->request->data('id');
		$result = $this->Cloud->search($this->currUserID, $id, $q);
		$this->set($result + array('id' => $id));
	}

	public function panelMove()
	{
		$this->panel();
	}

	/**
	 * New Folder
	 */
	public function addFolder() {
		try {
			$this->loadModel('Cloud');
			$this->request->data('Cloud.user_id', $this->currUserID);
			$this->Cloud->save($this->request->data);
			exit;
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	/**
	 * Delete Folder
	 * @throws Exception
	 */
	public function delFolder() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->loadModel('Cloud');
			$this->Cloud->deleteFolder($this->currUserID, $id);
			exit;
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	/**
	 * Move folder or file to new parent
	 * @throws Exception
	 */
	public function move() {
		try {
			$id = $this->request->data('id');
			$parentId = $this->request->data('parentId');
			if (!$id || $parentId === null) {
				throw new Exception('Incorrect request');
			}
			$this->loadModel('Cloud');
			$this->Cloud->move($this->currUserID, $id, $parentId);
			exit;
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

    public function ShareByLink() {
        $this->autoRender = false;
        $result = array('success' => false);
        $this->loadModel('Share');
        if($this->request->is('ajax')) {

					$cloudIds = $this->request->data('cloud_ids');
					$documentIds = $this->request->data('document_ids');
        	if(!empty($cloudIds)||!empty($documentIds)) {
        		if(!empty($cloudIds)) {
                $conditions = array(
                    'Share.object_id' => $cloudIds,
                    'Share.share_type' => Share::LINK_ACCESS,
                    'Share.target' => Share::CLOUD
                );

                $already_exists = $this->Share->find('all', compact('conditions'));
                $already_exists_media = Hash::extract($already_exists, '{n}.Share.object_id');
                $diff_cloud = array_diff($cloudIds, $already_exists_media);
            }

            if(!empty($documentIds)) {
                $conditions = array(
                    'Share.object_id' => $documentIds,
                    'Share.share_type' => Share::LINK_ACCESS,
                    'Share.target' => Share::DOCUMENT
                );

                $already_exists = $this->Share->find('all', compact('conditions'));
                $already_exists_media = Hash::extract($already_exists, '{n}.Share.object_id');
                $diff_doc = array_diff($documentIds, $already_exists_media);
            }

                $this->Share->create();
                $data = [];
                if(!empty($diff_cloud)) {
                    foreach ($diff_cloud as $cloudId) {
                        $data[] = array(
                            'object_id' => $cloudId,
                            'share_type' => Share::LINK_ACCESS,
                            'target' => Share::CLOUD
                        );
                    }
                }
                if(!empty($diff_doc)) {
                    foreach ($diff_doc as $docId) {
                        $data[] = array(
                            'object_id' => $docId,
                            'share_type' => Share::LINK_ACCESS,
                            'target' => Share::DOCUMENT
                        );
                    }
                }

                if($this->Share->saveMany($data) || empty($data)) {
                    $result['success'] = true;
                }
                echo json_encode($result);
            }
        }
        die();
    }
		public function getCount() {

      $this->loadModel('Share');
			$conditions = array(
					'Share.user_id' => $this->currUserID,
					'Share.share_type' => Share::INDIVIDUAL_ACCESS,
					'Share.active' => '1',
			);

				$activeShare = $this->Share->find('all', compact('conditions'));

        $cloudCount = count($activeShare);
        echo $cloudCount;
        exit;
    }
		public function deActive(){
			$this->loadModel('Share');
			$file = $this->request->data('id');
			$conditions = array(
					'Share.user_id' => $this->currUserID,
					'Share.share_type' => Share::INDIVIDUAL_ACCESS,
					'Share.active' => '1',
			);

			$activeShare = $this->Share->find('all', compact('conditions'));
			if(!empty($activeShare)){
				$this->Share->read(null, $activeShare['0']['Share']['id']);
				$this->Share->set(array(
				      'active' => 0,
				));
				$this->Share->save();
				$conditions = array(
						'Share.user_id' => $this->currUserID,
						'Share.share_type' => Share::INDIVIDUAL_ACCESS,
						'Share.active' => '1',
				);

				$activeShare = $this->Share->find('all', compact('conditions'));

	      $cloudCount = count($activeShare);
				echo $cloudCount;
			}else{
				echo '-1';
			}

			exit;
		}
    public function ShareIndividual() {
        $this->autoRender = false;
        $result = array('success' => false);
        $this->loadModel('Share');
        if($this->request->is('ajax')) {
					$users = $this->request->data('user_list');
            if(!empty($users) && ($this->request->data('cloud_ids') || $this->request->data('document_ids'))) {

                $cloud_ids = $this->request->data('cloud_ids');
                $documentIds = $this->request->data('document_ids');
                $conditions = array(
                    'Share.object_id' => $cloud_ids,
                    'Share.user_id' => $users,
                    'Share.share_type' => Share::INDIVIDUAL_ACCESS,
                    'Share.target' => Share::CLOUD
                );
                $already_exists_cloud = $this->Share->find('all', compact('conditions'));

                $data_cloud = [];
                $for_save_cloud = [];
                if(!empty($cloud_ids)) {
                    foreach ($users as $user) {
                        foreach ($cloud_ids as $cloudId) {
                            $data_cloud[] = array(
                                'Share' => array(
                                    'user_id' => intval($user),
                                    'object_id' => $cloudId,
                                    'share_type' => Share::INDIVIDUAL_ACCESS,
                                    'target' => Share::CLOUD
                                )
                            );
                        }
                    }

                    $for_save_cloud = $this->Share->remove_already_shared($already_exists_cloud, $data_cloud, Share::CLOUD);

                }

                $data_doc = [];
                $for_save_doc = [];
                $conditions['Share.target'] = Share::DOCUMENT;
                $conditions['Share.object_id'] = $documentIds;
                $already_exists_doc = $this->Share->find('all', compact('conditions'));

                if(!empty($documentIds)) {
                    foreach ($users as $user) {
                        foreach ($documentIds as $docId) {
                            $data_doc[] = array(
                                'Share' => array(
                                    'user_id' => intval($user),
                                    'object_id' => $docId,
                                    'share_type' => Share::INDIVIDUAL_ACCESS,
                                    'target' => Share::DOCUMENT
                                )
                            );
                        }
                    }

                    $for_save_doc = $this->Share->remove_already_shared($already_exists_doc, $data_doc, Share::DOCUMENT);
                }

                $for_save = array_merge($for_save_cloud,$for_save_doc);

                if($this->Share->saveMany($for_save) || empty($for_save)) {
                    $result['success'] = true;
                }

                echo json_encode($result);
            }
        }
        die();

    }

    public function ShareEdit() {
        $this->autoRender = false;
        $result = array('success' => false);
        $this->loadModel('Share');
        if($this->request->is('ajax')) {
					$users = $this->request->data('user_list');
					$documentIds = $this->request->data('document_ids');
            if(!empty($users) && !empty($documentIds)) {


                $data_doc = [];
                $conditions = array(
                    'Share.object_id' => $documentIds,
                    'Share.user_id' => $users,
                    'Share.share_type' => Share::EDIT_ACCESS,
                    'Share.target' => Share::DOCUMENT
                );
                $for_save_doc = [];
                $already_exists_doc = $this->Share->find('all', compact('conditions'));
                if(!empty($documentIds)) {
                    foreach ($users as $user) {
                        foreach ($documentIds as $docId) {
                            $data_doc[] = array(
                                'Share' => array(
                                    'user_id' => intval($user),
                                    'object_id' => $docId,
                                    'share_type' => Share::EDIT_ACCESS,
                                    'target' => Share::DOCUMENT
                                )
                            );
                        }
                    }

                    $for_save_doc = $this->Share->remove_already_shared($already_exists_doc, $data_doc, Share::DOCUMENT);
                }

                if($this->Share->saveMany($for_save_doc) || empty($for_save_doc)) {
                    $result['success'] = true;
                }

                echo json_encode($result);
            }
        }
        die();
    }

	/**
	 * For article editor show video from user cloud
	 */
	public function showUserCloudVideo()
	{
		/** @var Cloud $cloud */
		$this->loadModel('Cloud');
		$cloud = $this->Cloud;
		$files = $cloud->search($this->currUserID, null, null);
		$video = $cloud->getOnlyVideo($files['aClouds']);

		echo json_encode($video);
		exit;
	}
}
