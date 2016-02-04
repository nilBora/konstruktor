<?php

use AD7six\Dsn\Wrapper\CakePHP\V2\DbDsn;

class DATABASE_CONFIG {

/**
 * Define connections using environment variables
 *
 * @return void
 */
	public function __construct() {
		$settings = DbDsn::parse(env('DATABASE_URL'));
		$this->default = $this->_adoptSettings($settings);

		$settings = DbDsn::parse(env('DATABASE_TICKETS_URL'));
		$this->tickets = $this->_adoptSettings($settings);
	}

	protected function _adoptSettings($settings){
		if(!isset($settings['password'])){
			$settings['password'] = '';
		}
		if(isset($settings['persistent'])
			&&(($settings['persistent'] == true)||($settings['persistent'] == 'true'))){
			$settings['persistent'] = true;
		} else {
			$settings['persistent'] = false;
		}
		return $settings;
	}

}
