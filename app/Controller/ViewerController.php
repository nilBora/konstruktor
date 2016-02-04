<?php
App::uses('AppController', 'Controller');
class ViewerController extends AppController {
	public $name = 'Viewer';
	public $uses = array('Media.Media');
	public $layout = 'profile_new';
		
	public function pdf($id) {
		$media = $this->Media->findById($id);
		$this->set('pdf', $media);
	}
	
}