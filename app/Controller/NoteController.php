<?php
App::uses('AppController', 'Controller');
class NoteController extends AppController {
	public $name = 'Note';
	public $uses = array('Note');
	public $helpers = array('Form', 'Html');
	public $layout = 'profile_new';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	public function edit($id = null) {
		$this->loadModel('Media');
		$note = $this->Note->findById($id);
		if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Note', 'action' => 'view', $id));
		}

		$title = __('Document editor');
		$this->set(compact('title'));

		if ($this->request->is(array('post', 'put'))) {
			if(isset($this->request->named['Note.parent_id'])) {
				$this->request->data('Note.parent_id', $this->request->named['Note.parent_id']);
			} else {
				$this->request->data('Note.parent_id', null);
			}
			$this->request->data('Note.is_folder', 0);
			$this->request->data('Note.user_id', $this->currUserID);
			$this->request->data('Note.id', $id);
			if ($this->Note->save($this->request->data)) {
				return $this->redirect(array('action' => 'view', $this->Note->id));
			}
		} else {

            $this->set(array('Note' => $note));
			$this->request->data = $note;
		}
	}

	public function spreadsheet($id = null) {
		$this->loadModel('Spreadsheet');
		$this->loadModel('TempDocument');

		$note = $this->Note->findById($id);
		if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Note', 'action' => 'view', $id));
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
				return $this->redirect(array('action' => 'spreadsheet', $this->Note->id));
			}
		} else {

			if(!$id) {
				$tid = $this->TempDocument->createTableTemp($this->currUserID, 'table');
				$note['Note']['table_id'] = $tid;
			}

			$this->request->data = $note;
		}

	}

	public function download($id = null) {
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

	public function view($id) {
		$this->loadModel('User');

		$note = $this->Note->findById($id);
		if(!$note || ($note['Note']['user_id'] != $this->currUserID)) {
			return $this->redirect(array('controller' => 'Error', 'action' => '404'));
		}

		$title = __('Document').': '.$note['Note']['title'];
		$this->set(compact('title'));

		$this->set('note', $note);
		$isNoteAdmin = $note['Note']['user_id'] == $this->currUserID;
		if (!Hash::get($note, 'Note.published') && !$isNoteAdmin) {
			return $this->redirect(array('controller' => 'User', 'action' => 'view'));
		}
		$this->set('isNoteAdmin', $isNoteAdmin);
		$this->set('user', $this->User->findById(Hash::get($note, 'Note.user_id')));
	}

	public function delete($id) {
		$this->autoRender = false;

		$note = $this->Note->findById($id);
		if ($id && Hash::get($note, 'Note.user_id') != $this->currUserID) {
			return $this->redirect(array('controller' => 'Note', 'action' => 'view', $id));
		}

		$this->Note->delete($id);
		$this->redirect(array('controller' => 'User', 'action' => 'view'));
	}
}
