<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

/**
 * Class InvestAjaxController
 * @property InvestCategory InvestCategory
 */
class InvestAjaxController extends PAjaxController {
	public $name = 'InvestAjax';
	public $helpers = array(
        'Media',
        'Redactor.Redactor'
    );
	public function beforeFilter() {
		parent::beforeFilter();
		$this->_checkAuth();
	}

	/**
	 * Only for load js initialisation
	 */
	public function jsSettings() {

	}

	/**
	 * Provides data for the rendering of the Invest panel
	 */
	public function panel() {
		$this->loadModel('InvestCategory');
		$result = $this->InvestCategory->search();
		$this->set($result);
	}
	public function hasProject(){
		$id = $this->request->query('id');
		$this->loadModel('InvestProject');
		$investProject = $this->InvestProject->findById($id);
	}
	public function editForm(){
		$id = $this->request->query('id');
		$this->loadModel('InvestProject');
		$investProject = $this->InvestProject->findById($id);
        //$showNav = $this->_isShowNav($investProject);
        $rewards = Hash::extract($investProject, 'Rewards.{n}.id');
        $this->loadModel('InvestSponsor');
        $this->InvestSponsor->Behaviors->load('Containable');
        $sponsors = $this->InvestSponsor->find('all', array(
            'fields' => array('InvestSponsor.*', 'InvestReward.name', 'User.full_name'),
            'contain' => array('User', 'InvestReward'),
            'conditions' => array('InvestSponsor.reward_id' => $rewards),
            'order' => array('InvestSponsor.created' => 'DESC'),
        ));
		//var_dump($this->request);exit;
        $this->set(compact('investProject', 'id', 'showNav', 'sponsors'));
	}
}
