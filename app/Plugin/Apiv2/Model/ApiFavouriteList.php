<?php


/**
* файл модели ApiFavouriteList
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('FavouriteUser', 'Model');
App::uses('FavouriteList', 'Model');

/**
* Модель ApiFavouriteList. Обертка под модель FavouriteList
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiFavouriteList extends AppModel {

	public $useTable = 'favourite_lists';
	
	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('FavouriteUser');
		$this->loadModel('FavouriteList');
	}
	
	public function isListOwner($userId,$listId){
		if(!$listId){
			return true;
		}
		
		$result = $this->FavouriteList->field('id',array('id'=>$listId,'user_id'=>$userId));
		if(!$result){
			return FALSE;
		}
		return true;
	}

	public function saveList($data){
		$this->FavouriteList->save($data);
		return $this->FavouriteList->id;
	}
	
	public function deleteList($listId){
		$this->FavouriteUser->updateAll(array('FavouriteUser.favourite_list_id'=>0),array('FavouriteUser.favourite_list_id'=>$listId));
		$this->FavouriteList->delete($listId);
	}
	
	public function getListNames($userId){
		$result = $this->FavouriteList->findAllByUserId($userId,array('id','title'));
		$output = array();
		foreach ($result as $item){
			$output['FavouriteList'][] = $item['FavouriteList'];
		}
		$output['FavouriteList'][] = array('id'=>0,'Title'=>'Common');
		return $output;
	}
}
?>
