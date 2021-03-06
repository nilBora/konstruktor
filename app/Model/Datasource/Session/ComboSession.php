<?php
App::uses('DatabaseSession', 'Model/Datasource/Session');

class ComboSession extends DatabaseSession implements CakeSessionHandlerInterface {
    public $cacheKey = null;

    public function __construct() {
        $this->cacheKey = Configure::read('Session.handler.cache');
        parent::__construct();
    }

    // read data from the session.
    public function read($id) {
		if(!empty($this->cacheKey)){
			$result = Cache::read($id, $this->cacheKey);
	        if ($result) {
	            return $result;
	        }
		}
        return parent::read($id);
    }

    // write data into the session.
    public function write($id, $data) {
		if(!empty($this->cacheKey)){
        	Cache::write($id, $data, $this->cacheKey);
		}
        return parent::write($id, $data);
    }

    // destroy a session.
    public function destroy($id) {
		if(!empty($this->cacheKey)){
        	Cache::delete($id, $this->cacheKey);
		}
        return parent::destroy($id);
    }

    // removes expired sessions.
    public function gc($expires = null) {
		if(!empty($this->cacheKey)){
        	Cache::gc($this->cacheKey);
		}
        return parent::gc($expires);
    }
}
