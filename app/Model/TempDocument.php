<?
App::uses('AppModel', 'Model');
class TempDocument extends AppModel {
	public $name = 'TempDocument';
	
	public function createTableTemp($user_id, $type) {
		$this->LoadModel('Spreadsheet');
		$this->LoadModel('SpreadsheetData');
		$this->LoadModel('SpreadsheetHeader');
		$this->LoadModel('SpreadsheetTrigger');
		
		$conditions = compact('user_id', 'type');
		$temp = $this->find('first', array( 'conditions' => $conditions));
		if($temp) {
			$id = Hash::get($temp, 'TempDocument.id');
			$this->Spreadsheet->delete($id);
			$this->SpreadsheetData->deleteAll(array('SpreadsheetData.sheetid' => $id), false);
			$this->SpreadsheetHeader->deleteAll(array('SpreadsheetHeader.sheetid' => $id), false);
			//$this->SpreadsheetTrigger->deleteAll(array('SpreadsheetTrigger.sheetid' => $id), false);
			$this->delete( Hash::get($temp, 'TempDocument.id') );	
		}
		$this->save($conditions);
		$temp = $this->find('first', array( 'conditions' => $conditions));
		return( Hash::get($temp, 'TempDocument.id') );
	}
	
	public function createTempArticle($user_id) {
		$this->loadModel('Media.Media');
		
		$type = 'article';
		$conditions = compact('user_id', 'type');
		$temp = $this->find('first', array( 'conditions' => $conditions));
		//TODO удаление медиа-файлов
		if($temp) {
			$this->delete( Hash::get($temp, 'TempDocument.id') );	
		}
		$this->save($conditions);
		$temp = $this->find('first', array( 'conditions' => $conditions));
		return( Hash::get($temp, 'TempDocument.id') );
	}
	
	public function createNoteTemp($user_id, $type) {
		//TODO для документов
	}
	
}