<?php

class ApiAccessDeniedException extends CakeException{
	public function __construct($message = 'Access Denied', $code = 103) {
		parent::__construct($message, $code);
	}
}
?>
