<?php
App::uses('AppController', 'Controller');
class FavouriteListController extends AppController {
	public $uses = array('FavouriteList', 'FavouriteUser');

	public function edit() {
		$this->FavouriteList->save($this->request->data);
		$this->redirect($this->referer());
	}
	
	public function delete($id = 0) {
		if($id!==0)
		{
			$aUsers = $this->FavouriteUser->findAllByFavouriteListIdAndUserId($id, $this->currUserID);
			foreach($aUsers as $user) {
				
				$user['FavouriteUser']['favourite_list_id'] = '0';
				$this->FavouriteUser->save($user['FavouriteUser']);
				
			}
			$this->FavouriteList->delete($id);
			$this->redirect($this->referer());
		}
	}	
}