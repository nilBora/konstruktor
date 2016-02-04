<?php
/*
 * Компонент для скачивания документов в формате doc
 * по мере надобности допилю - Alexander B
 */
App::uses('Component', 'Controller');
App::uses('AppModel', 'Model');
class DownloadComponent extends Component {
	
	private $_controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->_controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
    public function download($docId) {
		
		$noteModel = ClassRegistry::init('Note');
		
        $document = $noteModel->findById($docId);
		$document = $document['Note'];
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$document['title'].'.doc"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		
		$document['body'] = str_replace('"/files/usermedia', '"http://'.$_SERVER['HTTP_HOST'].'/files/usermedia', $document['body']);	
		$this->_controller->set('document', $document);
		$this->_controller->render('/Note/download');
    }
}
?>
