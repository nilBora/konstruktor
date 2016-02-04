<?php

class ApiUnauthorizedException extends CakeException{
	public function __construct($message = 'Unauthorized', $code = 101) {
		parent::__construct($message, $code);
	}
}
?>
