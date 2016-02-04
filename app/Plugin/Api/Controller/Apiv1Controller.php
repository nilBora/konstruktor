<?php
/**
* файл контроллера API версии 1
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppController', 'Controller');
App::uses('ApiController', 'Api.Controller');

/**
* контроллер для API версии 1
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class Apiv1Controller extends ApiController {
	
	/**
	* файл лога для версии АПИ
	* 
	* @var string
	*/
	public $logFile = 'api_1';

}

?>
