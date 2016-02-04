<?php

/**
* файл модели ApiFavouriteUser
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
* Модель ApiFavouriteUser. Обертка под модель FavouriteUser
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiFavouriteUser extends AppModel {

	public $useTable = 'favourite_users';
	
	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('FavouriteUser');
		$this->loadModel('FavouriteList');
	}
	
	public function inList($userId,$listId,$ownerId){
		$id = $this->FavouriteUser->field('id',array('user_id'=>$ownerId,'favourite_list_id'=>$listId,'fav_user_id'=>$userId));
		if($id){
			return true;
		}
		return false;
	}
	
	public function canAddToList($userId,$ownerId){
		$id = $this->FavouriteUser->field('id',array('user_id'=>$ownerId,'fav_user_id'=>$userId));
		if($id){
			return false;
		}
		return true;
	}
	
	public function deleteUser($userId,$listId,$ownerId){
		$this->FavouriteUser->deleteAll(array('user_id'=>$ownerId,'favourite_list_id'=>$listId,'fav_user_id'=>$userId));
	}

	public function saveUser($data){
		$this->FavouriteUser->save($data);
	}
	
	public function updateUser($data,$conditions){
		$this->FavouriteUser->updateAll($data,$conditions);
	}

	public function getUserFavourites($userId){
		
		$this->FavouriteUser->bindModel(
				array('belongsTo' => array(
						'FavouriteList' => array(
							'className' => 'FavouriteList',
							'foreignKey' => 'favourite_list_id',
							'conditions' => 'FavouriteList.user_id = '.$userId
						),
					)
				)				
			);
		
		$fields = array('FavouriteUser.fav_user_id','FavouriteUser.favourite_list_id','FavouriteList.*');
		$conditions = array('FavouriteUser.user_id'=>$userId);
		$favouriteUsers = $this->FavouriteUser->find('all',  compact('fields','conditions'));
		
		$aId =  Hash::extract($favouriteUsers, '{n}.FavouriteUser.favoured_list_id');
		$conditions = array('user_id'=>$userId,'NOT'=>array('id'=>$aId));
		$emptyLists = $this->FavouriteList->find('all',compact('conditions'));
		
		$userIds = Hash::extract($favouriteUsers, '{n}.FavouriteUser.fav_user_id');
		$fields = array(
						'User.id',
						'User.full_name',
						'UserMedia.*'
		);
		$users = $this->User->findAllById($userIds,$fields);
		$this->users = Hash::combine($users, '{n}.User.id','{n}');
		
		$list = array_merge($favouriteUsers,$emptyLists);
		
		return $this->formatUserFavourites($list);
	}
	
	public function formatUserFavourites($data){
		$aResult = array();
		foreach ($data as $id=>$content){
			$listId = ($content['FavouriteList']['id']) ? $content['FavouriteList']['id'] : 0;
			$aResult[$listId]['id'] = $listId;
			$aResult[$listId]['title'] = ($content['FavouriteList']['title']) ? $content['FavouriteList']['title'] : 'Common';
			
			if(isset($content['FavouriteUser'])){
				$userId = $content['FavouriteUser']['fav_user_id'];
				$userInfo['id'] = $userId;
				$userInfo['full_name'] = $this->users[$userId]['User']['full_name'];
				$userInfo['user_url_img'] = $this->users[$userId]['UserMedia']['url_img'];
				$aResult[$listId]['Users'][] = $userInfo;
			}
			
		}
		
		//для массива в АПИ
		$output = array();
		foreach ($aResult as $item){
			$output['FavouriteList'][] = $item;
		}

		return $output;
	}
	
	public function getFavouriteInfo($userId,$ownerId){
		$listId = $this->FavouriteUser->field('favourite_list_id',array('user_id'=>$ownerId,'fav_user_id'=>$userId));
		if($listId===false){
			return false;
		}else if((int)$listId===0){
			return array('id'=>0,'title'=>'Common');
		}else{
			$aResult = $this->FavouriteList->findById($listId,array('id','title'));
			return $aResult['FavouriteList'];
		}
	}
}

?>
