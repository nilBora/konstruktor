<?php
App::uses('FinanceController', 'Controller');

/**
 * Class FinanceProjectController
 * @property FinanceProject FinanceProject
 * @property FinanceAccount FinanceAccount
 * @property FinanceOperation FinanceOperation
 * @property FinanceCategory FinanceCategory
 */
class FinanceProjectController extends FinanceController {
    public $name = 'FinanceProject';
    public $layout = 'profile_new';

    /**
     * Provides data for the project by id
     */
    public function index($id) {
        $project = $this->FinanceProject->getProject((int)$id, true);
		$this->set('project',$project);
        $this->loadModel('FinanceAccount');
        $accounts = $this->FinanceAccount->search((int)$id);

        $balances = $this->FinanceAccount->lastBalances((int)$id);
        foreach ($accounts['aFinanceAccount'] as &$item) {
            $item['FinanceAccount']['balance'] = $balances[$item['FinanceAccount']['id']];
        }
        $currencyBalances = $this->FinanceAccount->currencyBalances( (int)$id);
        $this->loadModel('FinanceCategory');
        $categories = $this->FinanceCategory->search((int)$id);
        $this->loadModel('FinanceOperation');
        $regularPayments = $this->FinanceOperation->regularPayments((int)$id);

		$group = $this->Group->findByFinanceProjectId($id);
        if( isset($this->request->params['named']['group_id']) ) {
            $this->loadModel('Group');
            $group = $this->Group->findById($this->request->params['named']['group_id']);
        }
		if (!$group) {
			$group = null;
		}

        $this->set($project + $accounts + $categories + $currencyBalances + $regularPayments + compact('id', 'group'));
    }

    /**
     * Delete Project
     * @throws Exception
     */
    public function delProject() {
        try {
            $id = $this->request->data('id');
            if (!$id) {
                throw new Exception('Incorrect request');
            }
            $this->FinanceProject->deleteProject($this->currUserID, $id);
            $this->redirect('successDeleted');
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Alert
     */
    public function successDeleted() {

    }
}
