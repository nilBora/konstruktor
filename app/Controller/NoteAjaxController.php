<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

class NoteAjaxController extends PAjaxController {
	public $name = 'NoteAjax';
	public $helpers = array('Redactor.Redactor', 'Form', 'Html');
		
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
	 * Provides data for the rendering of the note panel
	 */
	public function panel() {
		$this->loadModel('Note');
		$this->request->data('q', htmlspecialchars( $this->request->data('q') ));
		$q = $this->request->data('q');
		$id = $this->request->data('id');
		$result = $this->Note->search($this->currUserID, $id, $q);
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
			$this->loadModel('Note');
			$this->request->data('Note.user_id', $this->currUserID);
			$this->request->data('Note.is_folder', '1');
			$this->Note->save($this->request->data);
			exit;
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	/**
	 * Delete Folder
	 */
	public function delFolder() {
		try {
			$id = $this->request->data('id');
			if (!$id) {
				throw new Exception('Incorrect request');
			}
			$this->loadModel('Note');
			$this->Note->deleteFolder($id);
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
			$this->loadModel('Note');
			$this->Note->move($this->currUserID, $id, $parentId);
			exit;
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}
	
	
	public function editPanel($id = null) {
        $result = array('success' => false, 'data' => array());
		$this->loadModel('Note');
		$this->loadModel('Media');
        $this->loadModel('Share');
        $this->loadModel('DocumentVersion');

        if($id) {
            $conditions = array(
                'Share.user_id' => $this->currUserID,
                'Share.object_id' => $id,
                'Share.target' => Share::DOCUMENT,
                'Share.share_type' => Share::EDIT_ACCESS
            );

            $shared = $this->Share->find('all', compact('conditions'));

            $note = $this->Note->findById($id);
            $owner_id = Hash::get($note, 'Note.user_id');
            if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
                if(empty($shared))
                    return;
            }
        }

		if ($this->request->is(array('post', 'put'))) {

            $this->autoRender = false;

            $data = array(
                'Note' => array(
                    'is_folder' => 0,
                    'body' => $this->request->data['body'],
                    'title' => $this->request->data['title'],
                    'parent_id' => $this->request->data['parent_id'],
                    'type' => "text",
                    'last_updated_by' => $this->currUserID
                )
            );
            if(!empty($id)) {
                $data['Note']['id'] = $id;
                $data['Note']['user_id'] = $owner_id;
            }
            else {
                $data['Note']['user_id'] = $this->currUserID;
            }

			$this->request->data('Note.is_folder', 0);
			$this->request->data('Note.user_id', $this->currUserID);
			$this->request->data('Note.id', $id);
            if ($this->Note->save($data)) {
				if($id) {
					$note = $this->Note->findById($id);
				} else {
					$id = $this->Note->getLastInsertId();
					$note = $this->Note->findById($id);
				}
                $note = $this->Note->findById($id);
                $this->DocumentVersion->updateLastVersion($note);
				//$note = $this->Note->find('first', array('conditions' => $this->request->data('Note')));
				$this->request->data = $note;
                $result['success'] = true;
                $result['data']['id'] = $note['Note']['id'];
                echo json_encode($result);
                die();
			}
		}
        else {
			$this->request->data = $note;
		}
	}
	
	public function delete($id) {
		$this->loadModel('Note');
		$this->loadModel('Media');
		
		$this->autoRender = false;
		
		$note = $this->Note->findById($id);
		if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
			return false;
		}
		
		$this->Note->delete($id);
		return true;
	}
}