<?php

class ApiIncorrectRequestException extends CakeException{
	public function __construct(array $errorList = array()) {
		parent::__construct('Incorrect Request', 102);
		$this->errorList = $errorList;
	}
}
?>

