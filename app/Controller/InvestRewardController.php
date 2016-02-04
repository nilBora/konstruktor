<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');

/**
 * Class InvestRewardController
 * @property InvestReward InvestReward
 */
class InvestRewardController extends SiteController {
	public $name = 'InvestReward';
	public $layout = 'profile_new';

	/**
	 * Delete reward
	 * @param $id
	 */
	public function delete($id) {
		$this->InvestReward->delReward($this->currUserID, (int) $id);
		exit;
	}
}
