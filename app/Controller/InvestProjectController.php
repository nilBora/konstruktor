<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');

/**
 * Class InvestProjectController
 * @property InvestProject InvestProject
 * @property InvestCategory InvestCategory
 * @property Media Media
 */
class InvestProjectController extends SiteController {

    public $name = 'InvestProject';

    public $layout = 'profile_new';

    public $components = array(
        'RequestHandler',
        'BraintreePayments.BraintreeCustomer'
    );

    public $helpers = array(
        'Media',
        'Redactor.Redactor'
    );

    /**
     * List of projects with filters by category, search and owner
     */
    public function listProjects() {
        $my = $this->request->query('my');
        $categoryId = $this->request->query('category');
        $q = $this->request->query('q');
        $this->loadModel('InvestProject');
        $this->loadModel('InvestCategory');
        $category = array();
        if ($categoryId) {
            $category = $this->InvestCategory->getOne($categoryId);
        }
        $userId = $my ? $this->currUserID : null;
        $result = $this->InvestProject->search($userId, $categoryId, $q);
        $this->set($result + $category + array('aSearch' => $q));

        $this->set(compact('group'));
    }

    public function searchProjects() {
        $q = $this->request->query('q');
        $this->loadModel('InvestProject');
        $data = $this->InvestProject->search(null, null, $q);
        if (!empty($data)) {
            $data = $data['aInvestProject'];
        }
        $this->set(compact('data'));
        $this->set('_serialize', array('data'));
    }

    /**
     * View project
     * @param $id - Project id
     */
    public function view($id) {
        $this->loadModel('User');
        $this->loadModel('Group');
        $investProject = $this->InvestProject->findById($id);
        if(empty($investProject)){
			throw new NotFoundException(__d('billing', 'Invalid customer for checkout'));
		}
        if($this->Session->check('InvestProject.hits.'.$id) === false) {
            $this->InvestProject->updateAll(
                array('InvestProject.hits' => 'InvestProject.hits + 1'),
                array('InvestProject.id' => $id)
            );
            $this->Session->write('InvestProject.hits.'.$id, true);
        }
        $showNav = $this->_isShowNav($investProject);
        $user = $this->User->findById($investProject['InvestProject']['user_id']);
        $investGroup = $this->Group->findById($investProject['Group']['id']);
        $this->set(compact('investProject', 'id', 'showNav','investGroup','user'));
    }

    /**
     * List of sponsors
     * @param $id - Project id
     */
    public function listSponsors($id) {
        $investProject = $this->InvestProject->findById($id);
        $showNav = $this->_isShowNav($investProject);
        $rewards = Hash::extract($investProject, 'Rewards.{n}.id');
        $this->loadModel('InvestSponsor');
        $this->loadModel('User');
        $this->InvestSponsor->Behaviors->load('Containable');
        $sponsors = $this->InvestSponsor->find('all', array(
            'fields' => array('InvestSponsor.*', 'InvestReward.name', 'User.full_name'),
            'contain' => array('User', 'InvestReward'),
            'conditions' => array('InvestSponsor.reward_id' => $rewards),
            'order' => array('InvestSponsor.created' => 'DESC'),
        ));
        $IDS = Hash::extract($sponsors, '{n}.User.id');
        $conditions = array(
            'User.id' => $IDS,
        );
        $users = $this->User->find('all', compact('conditions'));
        $users = Hash::combine($users, '{n}.User.id', '{n}');
		$investGroup = $this->Group->findById($investProject['Group']['id']);
        $this->set(compact('investProject','users', 'id', 'showNav','investGroup', 'sponsors'));
    }
    public function listAllSponsors() {
        $this->loadModel('InvestSponsor');
        $this->loadModel('User');
        $this->loadModel('InvestReward');
        $conditions = array(
        'InvestProject.user_id' => $this->currUserID,
        );
        $investProject = $this->InvestProject->find('all',compact('conditions'));
        $iDs = Hash::extract($investProject, '{n}.InvestProject.id');
        $rewards = Hash::extract($investProject, '{n}.Rewards.{n}.id');
        $my_projects = $this->InvestReward->find('all', array(
          'conditions' => array('InvestReward.id' => $rewards),
        ));
        $my_projects = Hash::combine($my_projects,'{n}.InvestReward.id','{n}','{n}.InvestProject.id');
        $user_ids = Hash::extract($my_projects, '{n}.{n}.Sponsors.{n}.user_id');

        $my_invests = $this->InvestSponsor->find('all', array(
          'conditions' => array('InvestSponsor.user_id' => $this->currUserID),
        ));
        $rewards = Hash::extract($my_invests, '{n}.InvestSponsor.reward_id');
        $my_invest_projects = $this->InvestReward->find('all', array(
          'conditions' => array('InvestReward.id' => $rewards),
        ));

        $projects = Hash::extract($my_invest_projects, '{n}.InvestProject.id');
        $users_id = Hash::extract($my_invest_projects, '{n}.InvestProject.user_id');
         // var_dump($reward);exit;
        $conditions = array(
        'InvestProject.id' => $projects,
        );
        $investedProject = $this->InvestProject->find('all',compact('conditions'));
        $user_invest_ids = Hash::extract($my_invest_projects, '{n}.{n}.Sponsors.{n}.user_id');
        $user_ids = Hash::merge($user_ids,$user_invest_ids, $users_id);
        $conditions = array(
          'User.id' => $user_ids,
        );
        $users = $this->User->find('all', compact('conditions'));
        $users = Hash::combine($users,'{n}.User.id','{n}');
        $investedProject = Hash::combine($investedProject,'{n}.InvestProject.id','{n}');
        $my_invest_projects = Hash::combine($my_invest_projects,'{n}.InvestReward.id','{n}');
        $this->set(compact('my_projects','my_invest_projects','my_invests','investedProject', 'users'));


    }
    /**
     * New Project
     */
    public function addProject() {
        try {
            if (!$this->request->is('post')) {
                $this->loadModel('InvestCategory');
                $categories = $this->InvestCategory->search();
                $this->set($categories);
                return;
            }
            $id = $this->InvestProject->addProject($this->currUserID, $this->request->data);
            return $this->redirect(array('action' => 'view', $id));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    /*public function editProject($id) {

        try {
            if (!$this->request->is('post')) {
                $project = $this->InvestProject->getOne((int)$id);
                $this->loadModel('InvestCategory');
                $categories = $this->InvestCategory->search();
                $showNav = $this->_isShowNav($project);
                $this->set($project + $categories + compact('id', 'showNav'));
                return;
            }
            $this->InvestProject->editProject((int) $id, $this->currUserID, $this->request->data);
            return $this->redirect(array('action' => 'view', $id));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }*/
    public function editProject() {
        if (!$this->request->is('post')) {
            $project = $this->InvestProject->getOne((int)$id);
            exit;
            $this->loadModel('InvestCategory');
            $categories = $this->InvestCategory->search();
            $showNav = $this->_isShowNav($project);
            $this->set($project + $categories + compact('id', 'showNav'));
            return;
        }
        $id = $this->request->data('InvestProject.id');
    //    var_dump($this->request);exit;
        $this->InvestProject->editProject((int) $id, $this->currUserID, $this->request->data);
        return $this->redirect(array('action' => 'view', $id));
    }
    /**
     * Delete project
     * @param $id
     */
    public function delete($id) {

    }

    public function removeMedia($id) {
        $this->loadModel('Media');
        $this->Media->delete((int) $id);
        exit;
    }

    public function addFunds($id) {
        $this->loadModel('InvestReward');
		$this->loadModel('Group');
        $this->InvestReward->Behaviors->load('Containable');
        $investReward = $this->InvestReward->find('first', array(
            'contain' => array('InvestProject'),
            'conditions' => array(
                'InvestReward.id' => $id
            )
        ));

        if(!$investReward){
            throw new NotFoundException('Could not find investment reward for funds transfer');
        }

        if($investReward['InvestProject']['user_id'] == $this->currUserID){
            throw new NotFoundException('Could not investment for your progect');
        }
        //currently user data required for check action
        $customer = $this->BraintreeCustomer->check($this->currUser);
		if(empty($customer)){
			throw new NotFoundException(__d('billing', 'Invalid customer for checkout'));
		}
        $clientToken = Braintree_ClientToken::generate();
        $this->Session->write('InvestProject.RewardId', $id);
		$investProject = $investReward['InvestProject'];
		$investGroup = $this->Group->findById($investProject['group_id']);
		$this->set(compact('investReward', 'customer', 'clientToken', 'investProject', 'investGroup'));
    }

    public function checkoutReward(){
        if (!$this->request->is('post')) {
			throw new NotFoundException(__d('billing', 'Incorrect request type'));
		}

        $customer = Braintree_Customer::find('konstruktor-'.$this->currUser['User']['id']);
        //TODO: payment nonce or id extrating in some places too. Refactoring needed
        if(isset($this->request->data['payment_method_nonce'])){
            $nonceFromTheClient = $this->request->data['payment_method_nonce'];
            $payment = Braintree_PaymentMethod::create([
                'customerId' => 'konstruktor-'.$this->currUser['User']['id'],
                'paymentMethodNonce' => $nonceFromTheClient
            ]);
            if(!$payment->success){
                $this->Session->setFlash($payment->message);
                $this->redirect(array('action' => 'payment'));
            }
            $payment = $payment->paymentMethod;
        } elseif(isset($this->request->data['payment_method'])
            &&!empty($this->request->data['payment_method'])) {
            $payment = null;
            foreach($customer->paymentMethods as $payment){
                if($payment->token == $this->request->data['payment_method']){
                    break;
                }
            }
            if(empty($payment)){
                throw new NotFoundException(__('Payment method not found'));
            }
        } else {
            throw new NotFoundException(__('Unable to find payment method'));
        }

        $rewardId = $this->Session->read('InvestProject.RewardId');
        $this->loadModel('InvestReward');
        $this->InvestReward->Behaviors->load('Containable');
        $investReward = $this->InvestReward->find('first', array(
            'contain' => array('InvestProject'),
            'conditions' => array(
                'InvestReward.id' => $rewardId
            )
        ));
        if(!$investReward){
            throw new NotFoundException('Could not find investment reward for funds transfer');
        }

        $result = Braintree_Transaction::sale(array(
            'paymentMethodToken' => $payment->token,
            'amount' => $investReward['InvestReward']['total'],
            //'options' => array(
            //    'submitForSettlement' => True
            //)
        ));
        if(!$result->success){
			$this->Session->setFlash(__('Unable to fund your money for chosen reward. Please contact with resource administration'));
			$this->redirect(array('action' => 'view', $investReward['InvestReward']['project_id']));
		}

        $this->loadModel('InvestSponsor');
        $this->InvestSponsor->create();
        $data = array(
            'user_id' => $this->currUserID,
            'project_id' => $investReward['InvestReward']['project_id'],
            'reward_id' => $investReward['InvestReward']['id'],
            'amount' => $investReward['InvestReward']['total'],
            'currency' => 'USD',
            'remote_transaction_id' => $result->transaction->id,
        );
        if(!$this->InvestSponsor->save($data)){
            $result = Braintree_Transaction::void($result->transaction->id);
            $this->Session->setFlash(__('There is problem with sum funding. Your transaction has been cancelled.'));
        } else {
            $this->Session->setFlash(__('You are successfully invest in project'));
        }
        $this->redirect(array('action' => 'view', $investReward['InvestReward']['project_id']));

    }

    public function refundReward($id){
        /*if (!$this->request->is('post')) {
            throw new NotFoundException(__d('billing', 'Incorrect request type'));
        }*/
        $this->loadModel('InvestSponsor');
        $this->InvestSponsor->Behaviors->load('Containable');
        $sponsor = $this->InvestSponsor->find('first', array(
            'fields' => array('InvestSponsor.*', 'InvestReward.*', 'InvestProject.user_id'),
            'contain' => array('InvestReward', 'InvestProject'),
            'conditions' => array(
                'InvestSponsor.id' => $id
            )
        ));

        if(!empty($sponsor)){
            throw new NotFoundException('Could not find funds transfer');
        }
        if(!in_array($this->currUserID, array($sponsor['InvestSponsor']['user_id'], $sponsor['InvestProject']['user_id']))){
            throw new NotFoundException('Could not find funds transfer');
        }
        if(!empty($sponsor['InvestSponsor']['remote_transaction_id'])){
            $transaction = Braintree_Transaction::find($sponsor['InvestSponsor']['remote_transaction_id']);
            if(!$transaction){
                throw new NotFoundException('Could not find your funds transfer');
            }
            if(($transaction->status == Braintree_Transaction::SETTLED)
                ||($transaction->status == Braintree_Transaction::SETTLING)){
                $result = Braintree_Transaction::refund($transaction->id);
            } else {
                $result = Braintree_Transaction::void($transaction->id);
            }
            //TODO: little buggy cancelation
            if($result->success||($transaction->status)){
                $this->InvestSponsor->id = $id;
                $this->InvestSponsor->saveField('canceled', 1);
            }
        }
        $this->redirect(array('action' => 'view', $sponsor['InvestReward']['project_id']));
    }

    private function _isShowNav($project) {
        return ($project['InvestProject']['user_id'] == $this->currUserID);
    }
}
