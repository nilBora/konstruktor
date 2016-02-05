<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class CloudController extends AppController {
    public $name = 'Cloud';
    public $layout = 'profile_new';
    public $uses = array('Cloud', 'Note', 'StorageLimit', 'Share');
    public $helpers = array('Form', 'Html');

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('documentView');
    }

	/**
	 * Provides data for the rendering of the file manager
	 *
	 * @param null $id
	 * @param null $shared_id
	 */
	public function index($id = null, $shared_id = null) {
        $storage_stats = $this->StorageLimit->getStats($this->Auth->user('id'));
        $q = isset($this->request->query['search']) ? $this->request->query['search'] : null;
        $filter = isset($this->request->query['filter']) ? $this->request->query['filter'] : '0';
		$dateFrom = null;
		switch ($filter) {
			case 'day':
				$last = time();	break;
			case 'week':
				$last = time() - (7 * 24 * 60 * 60);	break;
			case 'month':
				$last = time() - (30 * 24 * 60 * 60); 	break;
			case 'year':
				$last = time() - (365 * 24 * 60 * 60); 	break;
			case '0':
				$last = 0; break;
		}
		if ($last) {
			$dateFrom = date('Y-m-d', $last);
		}

        if (isset($this->request->query['view'])) {
            $view = $this->request->query['view'];
            $this->Session->write('Cloud.view', $view);
        } else {
            $view = $this->Session->read('Cloud.view');
        }
        if (isset($this->request->query['sort'])) {
            $sort = $this->request->query['sort'];
            $this->Session->write('Cloud.sort', $sort);
        } else {
            $sort = $this->Session->read('Cloud.sort');
        }
        $this->Session->write('Cloud.sort', $sort);

        $result['files'] = $this->Cloud->search($this->currUserID, $id, $q, $sort, $shared_id, $dateFrom);
        $result['docs'] = $this->Note->search($this->currUserID, $id, $q, $sort, $shared_id, $dateFrom);

        $folders = $this->Cloud->find('all', array(
            'sort' => 'Cloud.lft ASC',
            'conditions' => array(
                'Cloud.user_id' => $this->currUserID,
                'Cloud.media_id' => 0
            ),
            'fields' => array(
                'Cloud.id',
                'Cloud.name',
                'Cloud.lft',
                'Cloud.rght'
            )
        ));
        $this->set(compact('id','view', 'sort', 'storage_stats', 'result', 'folders'));
    }

	public function fortiny($id = null, $shared_id = null) {
		$storage_stats = $this->StorageLimit->getStats($this->Auth->user('id'));
        $q = isset($this->request->query['q']) ? $this->request->query['q'] : null;
        if (isset($this->request->query['view'])) {
            $view = $this->request->query['view'];
            $this->Session->write('Cloud.view', $view);
        } else {
            $view = $this->Session->read('Cloud.view');
        }
        if (isset($this->request->query['sort'])) {
            $sort = $this->request->query['sort'];
            $this->Session->write('Cloud.sort', $sort);
        } else {
            $sort = $this->Session->read('Cloud.sort');
        }
        $this->Session->write('Cloud.sort', $sort);
        $result['files'] = $this->Cloud->search($this->currUserID, $id, $q, $sort, $shared_id);
        $result['docs'] = $this->Note->search($this->currUserID, $id, $q, $sort, $shared_id);

        $folders = $this->Cloud->find('all', array(
            'sort' => 'Cloud.lft ASC',
            'conditions' => array(
                'Cloud.user_id' => $this->currUserID,
                'Cloud.media_id' => 0
            ),
            'fields' => array(
                'Cloud.id',
                'Cloud.name',
                'Cloud.lft',
                'Cloud.rght'
            )
        ));
        $this->set(compact('id','view', 'sort', 'storage_stats', 'result', 'folders'));
		$this->render('fortiny','cloud_ajax');
    }

	/**
	 * @param $id
	 */
    public function realTimeEdit($id) {
        if(!empty($id) && is_numeric($id)) {
            $this->loadModel('Media');
            $this->loadModel('Share');
            $this->loadModel('DocumentVersion');

            $versions = $this->DocumentVersion->getVersions($id);

            $last_updated = $this->DocumentVersion->lastUpdatedBy($id);

            $conditions = array(
                'Share.user_id' => $this->currUserID,
                'Share.object_id' => $id,
                'Share.target' => Share::DOCUMENT,
                'Share.share_type' => Share::EDIT_ACCESS
            );

            $shared = $this->Share->find('all', compact('conditions'));

            $note = $this->Note->findById($id);
            if ($id && (Hash::get($note, 'Note.user_id') != $this->currUserID)) {
                if (empty($shared))
                    $this->redirect(array('controller' => 'Cloud', 'action' => 'documentView', $id));
            }
            $parent_id = null;
            $parent_name = '';
            if (!empty($note['Note']['parent_id']) && is_null($note['Note']['parent_id'])) {
                $parent = $this->Note->findById($note['Note']['parent_id']);
                if (!empty($parent))
                    $parent_name = $parent['Note']['title'];
            }
            $title = __('Document editor');
            $this->set(compact('id', 'parent_id', 'parent_name', 'title', 'last_updated', 'versions'));

            if ($this->request->is(array('post', 'put'))) {

                if (isset($this->request->named['Note.parent_id'])) {
                    $parent_id = $this->request->named['Note.parent_id'];
                    $this->request->data('Note.parent_id', $this->request->named['Note.parent_id']);
                } else {
                    $parent_id = null;
                    $this->request->data('Note.parent_id', null);
                }
                $this->request->data('Note.is_folder', 0);
                $this->request->data('Note.user_id', $this->currUserID);
                $this->request->data('Note.id', $id);


                if ($this->Note->save($this->request->data)) {
                    $this->redirect(array('action' => 'documentView', $this->Note->id));
                }
            } else {

                $this->set(array('Note' => $note));
                $this->request->data = $note;
            }
            $user = $this->User->findById($this->currUserID);
            $username = $user['User']['full_name'];
            $this->set(array('user_id' => $this->currUserID, 'user_name' => $username));
        }
    }

	/**
	 *
	 */
    public function usage() {
        $storage_stats = $this->StorageLimit->getStats($this->Auth->user('id'));
        if(intval($storage_stats['already_used_bytes'])) {
            $storage_stats['Messages'] = $this->StorageLimit->human_filesize($storage_stats['message_file_size']);
            $storage_stats['Cloud'] = $this->StorageLimit->human_filesize($storage_stats['cloud_size']);
            $storage_stats['Projects'] = $this->StorageLimit->human_filesize($storage_stats['project_file_size']);

            $percents = array();

            $percents['Messages'] = round(100*$storage_stats['message_file_size']/$storage_stats['already_used_bytes'], 2);
            $percents['Cloud'] = round(100*$storage_stats['cloud_size']/$storage_stats['already_used_bytes'], 2);
            $percents['Projects'] = round(100*$storage_stats['project_file_size']/$storage_stats['already_used_bytes'], 2);
            $data = array();

            $data[] = array('name' => __('Usage'), 'y' => null,'y_label'=> 100, 'color'=> 'transparent','size' => $storage_stats['already_used'] . " ".__('OF')." " . $storage_stats['limit'], 'style' => 'color: red' );

            foreach($percents as $key => $percent) {
                $data[] = array('name' => $key, 'y' => $percent,'size' => $storage_stats[$key] );
            }
        }

        else {
            $data[] = array('name' => __('Usage'), 'y' => 0, 'y_label'=> 100, 'color'=> 'transparent', 'size' => $storage_stats['already_used'] . " ".__('OF')." " . $storage_stats['limit'], 'style' => 'color: red' );
            $data[] = array('name' => 'Free', 'y' => 100, 'y_label'=> 100, 'size' => $storage_stats['limit']);
        }


        $data = json_encode($data);

        $this->set(compact('storage_stats', 'data'));
    }

	/**
	 * @param null $id
	 */
    public function documentEdit($id = null) {
        $this->loadModel('Media');
        $this->loadModel('Share');
        $this->loadModel('DocumentVersion');

        $versions = $this->DocumentVersion->getVersions($id);

        $last_updated = $this->DocumentVersion->lastUpdatedBy($id);

        $conditions = array(
            'Share.user_id' => $this->currUserID,
            'Share.object_id' => $id,
            'Share.target' => Share::DOCUMENT,
            'Share.share_type' => Share::EDIT_ACCESS
        );

        $shared = $this->Share->find('all', compact('conditions'));

        $note = $this->Note->findById($id);
		$this->set('note', $note);
        if ($id && (Hash::get($note, 'Note.user_id') != $this->currUserID)) {
            if(empty($shared))
                $this->redirect(array('controller' => 'Cloud', 'action' => 'documentView', $id));
        }
        $parent_id = null;
        $parent_name = '';
        if(!empty($note['Note']['parent_id']) && is_null($note['Note']['parent_id'])) {
            $parent = $this->Note->findById($note['Note']['parent_id']);
            if(!empty($parent))
                $parent_name = $parent['Note']['title'];
        }
        $this->set(compact('id', 'parent_id', 'parent_name'));
        $title = __('Document editor');
        $this->set(compact('title', 'last_updated'));

        if(!empty($id))
            $this->set(compact('versions'));

        if ($this->request->is(array('post', 'put'))) {

            if(isset($this->request->named['Note.parent_id'])) {
                $parent_id = $this->request->named['Note.parent_id'];
                $this->request->data('Note.parent_id', $this->request->named['Note.parent_id']);
            } else {
                $parent_id = null;
                $this->request->data('Note.parent_id', null);
            }
            $this->request->data('Note.is_folder', 0);
            $this->request->data('Note.user_id', $this->currUserID);
            $this->request->data('Note.id', $id);


            if ($this->Note->save($this->request->data)) {
                $this->redirect(array('action' => 'documentView', $this->Note->id));
            }
        } else {

            $this->set(array('Note' => $note));
            $this->request->data = $note;
        }

    }

	/**
	 * @param null $id
	 */
    public function documentSpreadsheet($id = null) {
        $this->loadModel('Spreadsheet');
        $this->loadModel('TempDocument');

        $note = $this->Note->findById($id);
        if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
            $this->redirect(array('controller' => 'Cloud', 'action' => 'documentView', $id));
        }

        if ($this->request->is(array('post', 'put'))) {
            if(isset($this->request->named['Note.parent_id'])) {
                $this->request->data('Note.parent_id', $this->request->named['Note.parent_id']);
            } else {
                $this->request->data('Note.parent_id', null);
            }
            $this->request->data('Note.is_folder', 0);
            $this->request->data('Note.user_id', $this->currUserID);

            $this->TempDocument->delete( $this->request->data('Note.table_id') );

            if ($this->Note->save($this->request->data)) {
                $this->redirect(array('action' => 'documentSpreadsheet', $this->Note->id));
            }
        } else {

            if(!$id) {
                $tid = $this->TempDocument->createTableTemp($this->currUserID, 'table');
                $note['Note']['table_id'] = $tid;
            }

            $this->request->data = $note;
        }

    }

	/**
	 * @param null $id
	 * @return bool
	 */
    public function documentDownload($id = null) {
        $this->layout = 'ajax';
        if(!$id) {
            return false;
        }

        $document = $this->Note->findById($id);
        $document = $document['Note'];

        header('Content-Description: File Transfer');
        //header('Content-type: application/msword');
        header('Content-Type: application/octet-stream');
        //header('Content-Type: text/html; charset=utf8');
        header('Content-Disposition: attachment; filename="'.$document['title'].'.doc"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $document['body'] = str_replace('"/files/usermedia', '"http://'.$_SERVER['HTTP_HOST'].'/files/usermedia', $document['body']);

        $this->set('document', $document);
    }

	/**
	 * @param $id
	 */
    public function documentView($id) {
        $collective_edit = false;
        $this->set( 'loggedIn', $this->Auth->loggedIn() );
        $this->loadModel('User');
        $this->loadModel('Share');

        $conditions = array(
            'OR' => array(
                array(
                    'Share.user_id' => $this->currUserID,
                    'Share.object_id' => $id,
                    'Share.target' => Share::DOCUMENT,
                    'Share.share_type' => array(Share::INDIVIDUAL_ACCESS,Share::EDIT_ACCESS)
                ),

            )
        );


        if(!is_numeric($id)) {
            $id = base64_decode($id);
            if(is_numeric($id)) {
                $conditions['OR'][] = array(
                    'Share.user_id' => null,
                    'Share.object_id' => $id,
                    'Share.target' => Share::DOCUMENT,
                    'Share.share_type' => Share::LINK_ACCESS
                );
                $conditions['OR'][0]['Share.object_id'] = $id;
            }
        }
        else {
            if (!$this->Auth->User('id')) {
                $this->redirect('/');
            }
        }

        $order = array('Share.share_type DESC');
        $limit = 1;
        $edit_doc = false;

        $shared = $this->Share->find('all', compact('conditions', 'order', 'limit'));
        if(!empty($shared)) {
            $shareObject = Hash::extract($shared, '{n}.Share');
            if($shareObject[0]['share_type'] == Share::EDIT_ACCESS)
                $edit_doc = true;
        }

        $sharedBy = $this->Share->DocumentSharedBy($this->currUserID);

        if(!empty($sharedBy)) {
            if(in_array($id, $sharedBy))
                $collective_edit = true;
        }

        $conditions = array(
            'Note.id' => $id,
            'Note.user_id' => $this->currUserID
        );
        $note = $this->Note->find('first', compact('conditions'));

        if(empty($note)) {
            if(empty($shared))
            $this->redirect(array('controller' => 'Error', 'action' => '404'));
            else
                $note = $this->Note->findById($id);
        }
        else {
            $edit_doc = true;
        }

        $parent_id = null;
        $parent_name = '';
        if(!empty($note['Note']['parent_id']) && is_null($note['Note']['parent_id'])) {
            $parent = $this->Note->findById($note['Note']['parent_id']);
            if(!empty($parent))
                $parent_name = $parent['Note']['title'];
        }

        $title = __('Document').': '.$note['Note']['title'];
        $this->set(compact('title', 'edit_doc'));

        $this->set('note', $note);
        $isNoteAdmin = $note['Note']['user_id'] == $this->currUserID;
        if (!Hash::get($note, 'Note.published') && !$isNoteAdmin) {
            if(empty($shared))
                $this->redirect(array('controller' => 'User', 'action' => 'view'));
        }
        $this->set('isNoteAdmin', $isNoteAdmin);
        $this->set(compact('id','parent_id', 'parent_name', 'collective_edit'));
        $this->set('user', $this->User->findById(Hash::get($note, 'Note.user_id')));
    }

	/**
	 * @param $id
	 */
    public function documentDelete($id) {
        $this->autoRender = false;

        $note = $this->Note->findById($id);
        if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
            $this->redirect(array('controller' => 'Cloud', 'action' => 'documentView', $id));
        }

        $this->Note->delete($id);
        $this->redirect(array('controller' => 'User', 'action' => 'view'));
    }
}
