<?php
App::uses('BillingAppController', 'Billing.Controller');
/**
 * BillingGroups Controller
 *
 * @property BillingGroup $BillingGroup
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class BillingGroupsController extends BillingAppController {

	public $helpers = array('Translate.Translate');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->BillingGroup->recursive = 0;
		$this->set('billingGroups', $this->Paginator->paginate());
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->BillingGroup->create();
			if ($this->BillingGroup->save($this->request->data)) {
				$this->Session->setFlash(__d('billing', 'The billing group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('billing', 'The billing group could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->BillingGroup->exists($id)) {
			throw new NotFoundException(__d('billing', 'Invalid billing group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->BillingGroup->save($this->request->data)) {
				$this->Session->setFlash(__d('billing', 'The billing group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('billing', 'The billing group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('BillingGroup.' . $this->BillingGroup->primaryKey => $id));
			$this->request->data = $this->BillingGroup->find('first', $options);
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
		$this->BillingGroup->id = $id;
		if (!$this->BillingGroup->exists()) {
			throw new NotFoundException(__d('billing', 'Invalid billing group'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->BillingGroup->delete()) {
			$this->Session->setFlash(__d('billing', 'The billing group has been deleted.'));
		} else {
			$this->Session->setFlash(__d('billing', 'The billing group could not be deleted. Please, try again.'));
		}
		*/
		$this->Session->setFlash(__d('billing', 'Group removal does not supported by system.'));
		return $this->redirect(array('action' => 'index'));
	}
}
