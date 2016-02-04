<?php
App::uses('AppController', 'Controller');

class FileController extends AppController
{
    public $name = 'File';
    public $layout = 'profile_new';

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('preview');
    }
    /**
     * Provides data for the rendering of the file manager
     */
    public function preview($fileId = NULL)
    {

        if (!$fileId) {
            // TODO: redirect to 404
        }

        $fileTypes = [
            'google-view' => [ '.doc', '.docx', '.pdf', '.xls', '.xlsx', '.txt' ],
            //'thumbnailed' => [ '.tiff', '.tif', '.bmp' ],
            'image-view' => [ '.png', '.gif', '.jpeg', '.jpg' ],
            'video-view' => [ '.avi', '.wmv', '.mkv', '.mp4' ]
        ];

        $this->set( 'loggedIn', $this->Auth->loggedIn() );
        $this->set($fileTypes);
        $this->loadModel('MediaFile');

        $file = $this->MediaFile->getMedia($fileId);
		if(!empty($file)) {
            //проверка на разрешение файла
            if ($this->Auth->User('id')) {
                switch ($file['Media']['object_type']) {
                    case 'Chat':
                        $this->loadModel('ChatEvent');
                        $this->loadModel('ChatMember');
                        $event = $this->ChatEvent->findByFileId($fileId);
                        if (!$event) {
                            //throw new Exception(__('Chat event not found'));
                            throw new NotFoundException();
                        }
                        $conditions = array(
                            'ChatMember.user_id' => $this->currUserID,
                            'ChatMember.room_id' => $event['ChatEvent']['room_id'],
                            'ChatMember.is_deleted' => '0'
                        );
                        $member = $this->ChatMember->find('first', compact('conditions'));
                        if (!$member) {
                            //throw new Exception(__('Restricted file'));
                            throw new NotFoundException();
                        }
                        break;
                    case 'TaskComment':
                        $this->loadModel('ProjectEvent');
                        $this->loadModel('Project');
                        $event = $this->ProjectEvent->findByMsgId($file['Media']['object_id']);
                        if (!$event) {
                            //throw new Exception(__('Project event not found'));
                            throw new NotFoundException();
                        }
                        $projMembers = $this->Project->getProjectMembers($event['ProjectEvent']['project_id']);
                        if (!in_array($this->currUserID, $projMembers)) {
							//Проверка на разрешения файла, если файл Таска передан в чат,
							//тогда надо проверить наличие его в событиях чата
							$this->loadModel('ChatEvent');
							$conditions = array(
								'ChatEvent.user_id' => $this->currUserID,
								'ChatEvent.file_id' => $fileId
							);
							$events = $this->ChatEvent->find('all', compact('conditions'));

							if (!$events) {
								throw new NotFoundException();
							}
                            //throw new Exception(__('Restricted file'));
//                            throw new NotFoundException();
                        }
                        break;
                }
            }
            else {
                $this->redirect('/');
            }
        }

        if(!is_numeric($fileId)) {
            $fileId = base64_decode($fileId);
            if(is_numeric($fileId)) {
                $file = $this->MediaFile->getMedia($fileId);
                if(empty($file)) {
                    throw new NotFoundException();
                }
            }
            else {
                $this->redirect('/');
            }
        }
        else {
            if (!$this->Auth->User('id')) {
                $this->redirect('/');
            }
        }

//        echo "<pre>";
//        var_dump($file['Media']['object_type']);
//        die;

		if( $file['Media']['object_type'] == 'Cloud' ) {

            $this->loadModel('Cloud');
            $this->loadModel('Share');

            $cloudRecords = $this->Cloud->findAllByMediaId($fileId);

            $cloudUsers = Hash::extract($cloudRecords, '{n}.Cloud.user_id');

            $merged = $cloudUsers;

            if(!empty($cloudRecords) && isset($cloudRecords[0]['Cloud']['id'])) {
                $media_id = $cloudRecords[0]['Cloud']['id'];
                $conditions = array(
                    'Share.target' => Share::CLOUD,
                    'Share.object_id' => $media_id,
                );

                $folder_files = $this->Share->find_folder_items($this->currUserID, Share::CLOUD);

                $cloudRecords = $this->Share->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'media',
                            'alias' => 'Media',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Media.id = Cloud.media_id'
                            )
                        )
                    ),
                    'conditions' => $conditions,
                    'fields' => array('Share.*' ,'Cloud.*', 'Media.*'),
                ));

                if(!empty($cloudRecords)) {
                    $cloudUsers[] = $this->currUserID;
                    $flag = true;
                }
                elseif(!empty($folder_files)) {

                    if(in_array($fileId, $folder_files)) {
                        $cloudUsers[] = $this->currUserID;
                        $flag = true;
                    }
                }

                $cloudSharedUsers = Hash::extract($cloudRecords, '{n}.Share.user_id');
                $merged = array_merge($cloudUsers, $cloudSharedUsers);

            }
            if(!in_array($this->currUserID, $merged)) {
                $flag = false;
                if(isset($cloudRecords)) {
                    foreach ($cloudRecords as $share) {
                        if ($share['Share']['share_type'] == Share::LINK_ACCESS) {
                            $flag = true;
                            break;
                        }
                    }
                }

                if(!$flag)
                    throw new NotFoundException();
            }

			$conditions = array(
				'Cloud.user_id' => $this->currUserID,
				'Cloud.media_id' => $file['Media']['id']
			);
			$CloudObject = $this->Cloud->find('first', compact('conditions'));
			if($CloudObject) {
				$this->set('fileOwner', true);
			}
		}
        if (in_array( strtolower($file['Media']['ext']),  $fileTypes['google-view'])) {
            $this->set(['file_url' => $this->MediaFile->getFileGooglePreviewUrl($file['Media']['url_download']), 'cloud' => $file, 'fileType' => 'google-view']);
        }
        elseif (in_array( strtolower($file['Media']['ext']),  $fileTypes['image-view'])) {
            $this->set(['file_url' => $file['Media']['url_download'], 'cloud' => $file, 'fileType' => 'image-view']);
        }
		/*
        elseif (in_array( strtolower($file['Media']['ext']),  $fileTypes['thumbnailed'])) {
            $file_url = ImageMagic::open($_SERVER['DOCUMENT_ROOT'].'/app/webroot/'.$file['Media']['url_download'])->png();
            $this->set(['file_url' => $file_url, 'cloud' => $file, 'fileType' => 'thumbnailed']);
        }
		*/
        elseif (in_array( strtolower($file['Media']['ext']),  $fileTypes['video-view'])) {
            $this->set(['file_url' => $file['Media']['url_download'], 'cloud' => $file, 'fileType' => 'video-view']);
        }
        else {
            $this->set(['file_url' => $file['Media']['url_download'], 'cloud' => $file, 'fileType' => 'others']);
        }

		$this->set('file', $file['Media']);
        $this->set('page', 'file/preview');

    }

    public function save_copy($fileId)
    {
        $this->loadModel('MediaFile');
        $this->loadModel('Media');
        $this->loadModel('Cloud');
        $file = $this->MediaFile->getMedia($fileId);
        $file['Media']['old_id'] = $file['Media']['id'];
        $file['Media']['old_object_type'] = $file['Media']['object_type'];
        $file['Media']['object_type'] = 'Cloud';
        unset($file['Media']['id']);
        $media_id = $this->Media->cloneMedia($file['Media']);
        $cloud = [
            "Cloud" => [
                "media_id" => $media_id,
                "name" => $file['Media']['orig_fname'],
                "parent_id" => '',
                "user_id" => $this->currUserID
        ]];
        $this->Cloud->save($cloud);
        return $this->redirect(['controller' => 'Cloud', 'action' => 'index']);
    }

    public function download($fileId) {
        $this->autoRender = false;
        $this->loadModel('MediaFile');
        $file = $this->MediaFile->getMedia($fileId);
        $file_path = ROOT.DS.'webroot'.$file['Media']['url_download'];
        $this->response->file($file_path, array('download' => true, 'name' => $file['Media']['orig_fname']));
        return $this->response;
    }
}
