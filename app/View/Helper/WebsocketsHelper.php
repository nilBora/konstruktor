<?php
use Firebase\JWT\JWT;

App::uses('AppHelper', 'View/Helper');
App::uses('CakeSession', 'Model/Datasource');

class WebsocketsHelper extends AppHelper {

	public $helpers = array('Html');

	public function init(){
		//TODO: replace key by setting
		$secret = JWT::encode(array('id' => CakeSession::id()), Configure::read('Autobahn.key'));
		$script = "var AUTOBAHN_KEY = '".$secret."';\n";
		$script .= "var AUTOBAHN_WS_URL = '".Configure::read('Autobahn.wsUrl')."';\n";
		$script .= "var AUTOBAHN_HTTP_URL = '".Configure::read('Autobahn.httpUrl')."';\n";
		if(Configure::read('debug') > 0){
			$script .= "var AUTOBAHN_DEBUG = true;\n";
		}
		//TODO: make websockets host configurable
		return $this->Html->scriptBlock($script).
			$this->Html->script([
				'autobahn.min.js',
	        	'wamp-client.js',
			]);
	}

}
