<?
App::uses('AppModel', 'Model');
class Task extends AppModel {

    public $hasOne = 'CrmTask';

    public $actsAs = array('Ratingable');

    public function beforeSave($options = array()) {
        if (empty($this->data['Task']['deadline'])) {
            $this->data['Task']['deadline'] = '00-00-00 00:00:00';
        }
        return true;
    }

    public function getProject($task) {
        $this->loadModel('Project');
        $this->loadModel('Subproject');

        $return = $this->Subproject->findById( $task['Task']['subproject_id'] );
        $return = $this->Project->findById( $return['Subproject']['project_id'] );

        return $return;
    }

    public function getMyTasksJson($openOnly = true) {
        $aTasks = $this->getMyTasks($openOnly);

        $return = array();
        foreach($aTasks as $data => $value ) {
            array_push($return, compact('data', 'value') );
        }

        return json_encode($return);
    }

    public function getTimeMyTasks($openOnly = true, $aProjects) {
        $this->loadModel('GroupMember');
        $this->loadModel('Project');
        $this->loadModel('ProjectMember');
        $this->loadModel('Subproject');

        //Собираем подпроекты и названия проектов, в которых мы, всё же, учавствуем
        $aSubprojects = $this->Subproject->findAllByProjectId( Hash::extract($aProjects, '{n}.ProjectMember.project_id') );

        $conditions = array( 'subproject_id' => Hash::extract($aSubprojects, '{n}.Subproject.id') );
        if($openOnly) {
            $conditions['closed'] = 0;
        }
        $order = 'Task.title';
        $aTasks = $this->find( 'all', array(
            'conditions' => $conditions,
            'order' => $order,
            'recursive' => -1,
            'fields' => array('id', 'title')
        ) );
        $aTasks = Hash::combine($aTasks, '{n}.Task.id', '{n}.Task.title');

        return $aTasks;
    }

    public function getMyTasks($openOnly = true) {
        $this->loadModel('GroupMember');
        $this->loadModel('Project');
        $this->loadModel('ProjectMember');
        $this->loadModel('Subproject');

        //Находим группы/проекты, из которых исключены, что бы "списать" проекты, в которых мы уже не учавствуем
        $conditions = array(
            'GroupMember.user_id' => AuthComponent::user('id'),
            'OR' => array(
                'GroupMember.is_deleted' => '1',
                'GroupMember.approved' => '0'
            )
        );
        $aExcludeProjects = $this->GroupMember->find('all', compact('conditions'));
        $aExcludeProjects = $this->Project->findAllByGroupId( Hash::extract($aExcludeProjects, '{n}.GroupMember.group_id') );

        //C учётом того, откуда мы исключены, собираем проекты, в которых мы учавствуем
        $conditions = array(
            'ProjectMember.user_id' => AuthComponent::user('id'),
            'NOT' => array(
                'ProjectMember.project_id' => Hash::extract($aExcludeProjects, '{n}.Project.id')
            )
        );
        $aProjects = $this->ProjectMember->find('all', compact('conditions'));

        //Собираем подпроекты и названия проектов, в которых мы, всё же, учавствуем
        $aSubprojects = $this->Subproject->findAllByProjectId( Hash::extract($aProjects, '{n}.ProjectMember.project_id') );

        $conditions = array( 'subproject_id' => Hash::extract($aSubprojects, '{n}.Subproject.id') );
        if($openOnly) {
            $conditions['closed'] = 0;
        }
        $order = 'Task.title';
        $aTasks = $this->find( 'all', compact('conditions', 'order') );
        $aTasks = Hash::combine($aTasks, '{n}.Task.id', '{n}.Task.title');

        return $aTasks;
    }

    public function getIncomeByID($id) {
        $this->loadModel('CrmTask');
        $this->loadModel('Project');
        $this->loadModel('Subproject');
        $this->loadModel('FinanceAccount');
        $this->loadModel('FinanceOperation');

        $task = $this->findById($id);
        $subproject = $this->Subproject->findById($task['Task']['subproject_id']);
        $project_id = $subproject['Subproject']['project_id'];
        $project = $this->Project->findById($project_id);
        $owner_id = Hash::get($project, 'Project.owner_id');

        if(!$task['CrmTask']['id']) {
            $crmData = array(
                'task_id' => $id,
                'contractor_id' => null,
                'money' => 0,
                'currency' => 'USD',
            );
            $this->CrmTask->save($crmData);

            $this->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
            $task = $this->findById($id);
        }

        if( !$task['CrmTask']['account_id'] ) {
            $this->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
            $task = $this->findById($id);
        }
        $accoundID = Hash::get($task, 'CrmTask.account_id');

        $expense = $this->FinanceAccount->fullExpense( $accoundID );
        $income = $this->FinanceAccount->fullIncome( $accoundID );
        $balance = $this->FinanceOperation->accountCurrentBalance( $accoundID );
        $currency = Hash::get($task, 'CrmTask.currency');

        if(!$expense) $fullExpense = 0.00;
        if(!$income) $fullIncome = 0.00;

        return compact('income', 'expense', 'balance', 'currency');
    }

    public function createFinanceAccount($taskID, $userID) {
        $this->loadModel('CrmTask');
        $this->loadModel('Group');
        $this->loadModel('Project');
        $this->loadModel('ProjectFinance');
        $this->loadModel('Subproject');
        $this->loadModel('FinanceAccount');
        $this->loadModel('FinanceBudget');

        $task = $this->findById($taskID);
        $crmTask = Hash::get($task, 'CrmTask');

        $subproject = $this->Subproject->findById( $task['Task']['subproject_id'] );
        $project = $this->Project->findById( $subproject['Subproject']['project_id'] );
        $group = $this->Group->findById( $project['Project']['group_id'] );

        if( !$group['Group']['finance_project_id'] ) {

            $fpID = $this->Group->addFinanceProject($group['Group'], $group['Group']['owner_id'], 1);
            $group['Group']['finance_project_id'] = $fpID;
            $this->Group->save($group);

            $group = $this->Group->findById( $project['Project']['group_id'] );
        }

        if( !$project['ProjectFinance']['id'] ) {

            $this->Project->createFinanceCategories($project['Project'], $project['Project']['owner_id']);
            $project = $this->Project->findById( $subproject['Subproject']['project_id'] );
        }
        //OLD Hash::get($crmTask, 'CrmTask.money')
        $account = array(
            'FinanceAccount' => array(
                'balance' => $crmTask['money'] ? $crmTask['money'] : 0,
                'type' => 3,
                'name' => Hash::get($task, 'Task.title').' '.__('account'),
                'currency' => $crmTask['currency'] ? $crmTask['currency'] : 'USD',
                'user_id' => $userID,
                'project_id' => Hash::get($group, 'Group.finance_project_id')
            )
        );

        $this->FinanceAccount->addAccount($account);

        $crmTask['account_id'] = $this->FinanceAccount->id;
        $crmTask['task_id'] = $taskID;
        $crmTask['currency'] = Hash::get($account, 'FinanceAccount.currency');
        $crmTask['money'] = Hash::get($account, 'FinanceAccount.balance');

        $crmTask = $this->CrmTask->save( $crmTask );

        //TODO привязки к балансам

        $pFinance = $this->ProjectFinance->findByProjectId( $project['Project']['id'] );

        $budget = array(
            'FinanceBudget' => array(
                'project_id' => Hash::get($group, 'Group.finance_project_id'),
                'account_id' => $this->FinanceAccount->id,
                'category_id' => $pFinance['ProjectFinance']['income_id'],
                'plan' => 0,
                'is_repeat' => 1
            )
        );

        $this->FinanceBudget->addBudget($budget);
        $budget['FinanceBudget']['category_id'] = $pFinance['ProjectFinance']['expense_id'];
        $this->FinanceBudget->clear();
        $this->FinanceBudget->addBudget($budget);
        $budget['FinanceBudget']['category_id'] = $pFinance['ProjectFinance']['tax_id'];
        $this->FinanceBudget->clear();
        $this->FinanceBudget->addBudget($budget);
        $budget['FinanceBudget']['category_id'] = $pFinance['ProjectFinance']['percent_id'];
        $this->FinanceBudget->clear();
        $this->FinanceBudget->addBudget($budget);
    }

    public function getTaskGroup($id) {
        $this->loadModel('Group');
        $this->loadModel('Project');
        $this->loadModel('Subproject');

        $return = $this->findById( $id );
        $return = $this->Subproject->findById( $return['Task']['subproject_id'] );
        $return = $this->Project->findById( $return['Subproject']['project_id'] );
        $return = $this->Group->findById( $return['Project']['group_id'] );

        return $return;
    }

    public function getTaskProject($id) {
        $this->loadModel('Project');
        $this->loadModel('Subproject');

        $return = $this->findById( $id );
        $return = $this->Subproject->findById( $return['Task']['subproject_id'] );
        $return = $this->Project->findById( $return['Subproject']['project_id'] );

        return $return;
    }

    public function remove($id, $user_id) {

        $group = $this->getTaskGroup($id);

        if( !$group || $group['Group']['owner_id'] != $user_id ) { return false; }
        if($this->save( array('id' => $id, 'deleted' => '1' ) )) { return true; }

        return false;
    }

}
