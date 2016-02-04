<?php

/**
* файл модели ApiStatistic
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('Statistic', 'Model');

/**
* Модель ApiStatistic. Обертка под модель Statistic
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

class ApiStatistic extends AppModel {

	//Oldstyle table name
	//public $useTable = 'statistics';
	public $useTable = 'statistic';

	protected function _afterInit() {
		$this->loadModel('Statistic');
	}

	public function addData($userId,$params){
		$this->Statistic->addData($userId,$params);
	}
}
?>
