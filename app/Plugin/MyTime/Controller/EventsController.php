<?php
App::uses('MyTimeAppController', 'MyTime.Controller');

class EventsController extends MyTimeAppController {

	public $name = 'Events';

	public function update(){
		$this->loadModel('User');
        try {
            $view = $this->request->data('view') ? $this->request->data('view') : 0;
            $data = $this->User->getTimeline($this->currUserID, $this->request->data('date'), $this->request->data('date2'), $view, false, $this->request->data('search'));
            $this->setResponse($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
	}

}
