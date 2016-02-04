<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');

/**
 * Class FinanceAjaxController
 * @property FinanceProject FinanceProject
 */
class FinanceAjaxController extends PAjaxController {
    public $name = 'FinanceAjax';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_checkAuth();

        //Собственный фин. проект
        $this->loadModel('FinanceProject');
        $this->loadModel('Group');
        $this->loadModel('GroupMember');

        $conditions = array(
            'FinanceProject.user_id' => $this->currUserID,
            'FinanceProject.name' => 'My finances'
        );
        $ownProject = $this->FinanceProject->find('first', compact('conditions'));
        if (!$ownProject) {
            $data['FinanceProject'] = array(
                'name' => 'My finances',
                'user_id' => $this->currUserID,
                'hidden' => '0'
            );
            $this->FinanceProject->addProject($this->currUserID, $data);
            $ownProject = $this->FinanceProject->find('first', compact('conditions'));
        }

        //Собственный фин. счёт
        $this->loadModel('FinanceAccount');
        $conditions = array(
            'FinanceAccount.project_id' => Hash::get($ownProject, 'FinanceProject.id'),
            'FinanceAccount.name' => 'My account'
        );
        $ownAccount = $this->FinanceAccount->find('first', compact('conditions'));
        if (!$ownAccount) {
            $data = array();
            $data['FinanceAccount'] = array(
                'name' => 'My account',
                'project_id' => Hash::get($ownProject, 'FinanceProject.id'),
                'currency' => 'USD',
                'type' => '0',
                'balance' => '0',
            );
            $this->FinanceAccount->addAccount($data);
        }

        $conditions = array(
            'Group.owner_id' => $this->currUserID,
            'Group.title' => 'My group'
        );
        $ownGroup = $this->Group->find('first', compact('conditions'));
        if (!$ownGroup) {

            $group = array(
                'title' => 'My group',
                'descr' => '',
                'owner_id' => $this->currUserID,
                'finance_project_id' => Hash::get($ownProject, 'FinanceProject.id'),
                'hidden' => 1,
                'video_url' => '',
                'cat_id' => 33
            );

            if( $this->Group->save($group) ) {
                $groupMember = array(
                    'group_id' => $this->Group->id,
                    'user_id' => $this->currUserID,
                    'approved' => $this->currUserID,
                    'sort_order' => 1,
                    'show_main' => 1,
                    'approve_date' => 1,
                    'approve_date' => date('Y-m-d H:i:s')
                );
                $this->GroupMember->save($groupMember);
            }
        }
    }

    /**
     * Only for load js initialisation
     */
    public function jsSettings() {

    }

    /**
     * Provides data for the rendering of the finance panel
     */
    public function panel() {
        $this->loadModel('FinanceProject');
        $this->request->data('q', htmlspecialchars( $this->request->data('q') ));
        $q = $this->request->data('q');
        $result = $this->FinanceProject->search($this->currUserID, $q);
        $this->set($result);
    }

    /**
     * New Project
     */
    public function addProject() {
        try {
            $this->loadModel('FinanceProject');
            $this->FinanceProject->addProject($this->currUserID, $this->request->data);
            exit;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
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
            $this->loadModel('FinanceProject');
            $this->FinanceProject->deleteProject($this->currUserID, $id);
            exit;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}
