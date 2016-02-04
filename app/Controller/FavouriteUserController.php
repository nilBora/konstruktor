<?php
App::uses('AppController', 'Controller');
class FavouriteUserController extends AppController {
	
	public function edit() {
		$this->FavouriteUser->save($this->request->data);
		$this->redirect($this->referer());
	}
	
	public function delete() {
		$this->FavouriteUser->delete($this->request->data);
		$this->redirect($this->referer());
	}
	
	public function deleteByUserId($id) {
		$favUser = $this->FavouriteUser->findByUserIdAndFavUserId($this->currUserID, $id);
		$favUser = Hash::get($favUser, 'FavouriteUser.id');
		$this->FavouriteUser->delete($favUser);
		$this->redirect($this->referer());
	}
		
	public function add() {
		$this->autoRender = false;
		try {
			$this->FavouriteUser->save($this->request->data);
			//$this->setResponse();
			$this->response->statusCode(200);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}	
	
	public function move() {
		$user = $this->FavouriteUser->findByUserIdAndFavUserId($this->request->data['FavouriteUser']['user_id'], $this->request->data['FavouriteUser']['fav_user_id']);
		$user['FavouriteUser']['favourite_list_id'] = $this->request->data['FavouriteUser']['favourite_list_id'];
		$this->FavouriteUser->save($user);
		$this->redirect($this->referer());
	}
}