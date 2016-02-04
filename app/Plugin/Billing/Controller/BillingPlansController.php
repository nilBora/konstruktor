<?php
App::uses('BillingAppController', 'Billing.Controller');
App::uses('Set', 'Utility');
/**
 * BillingPlans Controller
 *
 * @property BillingPlan $BillingPlan
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BillingPlansController extends BillingAppController {

	public $uses = array('Billing.BillingPlan', 'Billing.BraintreePlan');

	public $helpers = array('Translate.Translate');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->BillingPlan->recursive = 0;
		$remotePlans = array();
		$this->set('remote_plans', $this->BraintreePlan->find('list'));
		$this->set('billingPlans', $this->Paginator->paginate());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->BillingPlan->create();
			if ($this->BillingPlan->save($this->request->data)) {
				$this->Session->setFlash(__d('billing', 'The billing plan has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('billing', 'The billing plan could not be saved. Please, try again.'));
			}
		}
		$this->set('billingGroups', $this->BillingPlan->BillingGroup->find('list'));
		$this->set('remote_plans', $this->BraintreePlan->find('list'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->BillingPlan->exists($id)) {
			throw new NotFoundException(__d('billing', 'Invalid billing plan'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->BillingPlan->save($this->request->data)) {
				$this->Session->setFlash(__d('billing', 'The billing plan has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('billing', 'The billing plan could not be saved. Please, try again.'));
			}
		} else {
			$this->set('remote_plans', $this->BraintreePlan->find('list'));
			$this->set('billingGroups', $this->BillingPlan->BillingGroup->find('list'));
			$options = array('conditions' => array('BillingPlan.' . $this->BillingPlan->primaryKey => $id));
			$this->request->data = $this->BillingPlan->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		/*
		$this->BillingPlan->id = $id;
		if (!$this->BillingPlan->exists()) {
			throw new NotFoundException(__d('billing', 'Invalid billing plan'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->BillingPlan->delete()) {
			$this->Session->setFlash(__d('billing', 'The billing plan has been deleted.'));
		} else {
			$this->Session->setFlash(__d('billing', 'The billing plan could not be deleted. Please, try again.'));
		}
		*/
		$this->Session->setFlash(__d('billing', 'Plan removal does not supported by system.'));
		return $this->redirect(array('action' => 'index'));
	}
}
