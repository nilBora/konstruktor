<?php


/**
* файл модели ApiProjectEvent
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');

/**
* Модель ApiProjectEvent. Обертка под модель ProjectEvent
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiProjectEvent extends AppModel {

	public $useTable = 'project_events';
}
?>
