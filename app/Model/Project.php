<?
App::uses('AppModel', 'Model');
App::uses('ProjectEvent', 'Model');
App::uses('ProjectFinance', 'Model');
class Project extends AppModel {

    public $hasOne = array('ProjectFinance');

    public $actsAs = array('Ratingable');

    protected $ProjectEvent;

    public function close($user_id, $id) {
        $this->save(array('id' => $id, 'closed' => 1));

        $this->loadModel('ProjectEvent');
        $this->ProjectEvent->addEvent(ProjectEvent::PROJECT_CLOSED, $id, $user_id);
    }

    public function beforeSave($options = array()) {
        if (isset($this->data['Project']['hidden']) and $this->data['Project']['hidden'] == '') {
            $this->data['Project']['hidden'] = 0;
        }
        if(!isset($this->data['Project']['deadline']) || $this->data['Project']['deadline'] == '') {
            $this->data['Project']['deadline'] = '0000-00-00 00:00:00';
        }
        return true;
    }

    public function getFinances($projectData) {
        $this->loadModel('ProjectFinance');
        return $this->ProjectFinance->findByProjectId($projectData['id']);
    }

    public function createFinanceCategories($projectData, $userID) {
        $this->loadModel('FinanceCategory');
        $this->loadModel('FinanceProject');
        $this->loadModel('Group');
        $this->loadModel('ProjectFinance');

        $group = $this->Group->findById($projectData['group_id']);

        if( !$group['Group']['finance_project_id'] ) {

            $fpID = $this->Group->addFinanceProject($group['Group'], $group['Group']['owner_id'], 1);
            $group['Group']['finance_project_id'] = $fpID;
            $this->Group->save($group);

            $group = $this->Group->findById( $project['Project']['group_id'] );
        }

        $fpID = Hash::get($group, 'Group.finance_project_id');
        $projectFinance = array('ProjectFinance' => array( 'project_id' => $projectData['id']));

        $income = array( 'FinanceCategory' => array(    'name' => $projectData['title'].' '.__('income'),     'type' => 0) );
        $expense = array( 'FinanceCategory' => array(    'name' => $projectData['title'].' '.__('expense'),     'type' => 1) );
        $tax = array( 'FinanceCategory' => array(        'name' => $projectData['title'].' '.__('tax'),         'type' => 1) );
        $percent = array( 'FinanceCategory' => array(    'name' => $projectData['title'].' '.__('percent'),     'type' => 1) );

        $this->FinanceCategory->addCategory($fpID, $income);
        $projectFinance['ProjectFinance']['income_id'] = $this->FinanceCategory->id;
        $this->FinanceCategory->clear();

        $this->FinanceCategory->addCategory($fpID, $expense);
        $projectFinance['ProjectFinance']['expense_id'] = $this->FinanceCategory->id;
        $this->FinanceCategory->clear();

        $this->FinanceCategory->addCategory($fpID, $tax);
        $projectFinance['ProjectFinance']['tax_id'] = $this->FinanceCategory->id;
        $this->FinanceCategory->clear();

        $this->FinanceCategory->addCategory($fpID, $percent);
        $projectFinance['ProjectFinance']['percent_id'] = $this->FinanceCategory->id;

        $this->ProjectFinance->save($projectFinance);
        return true;
    }

    public function updateFinanceCategories($projectData, $userID) {
        $this->loadModel('FinanceCategory');
        $this->loadModel('FinanceProject');
        $this->loadModel('Group');
        $this->loadModel('ProjectFinance');

        $group = $this->Group->findById($projectData['group_id']);
        $fpID = Hash::get($group, 'Group.finance_project_id');
        $projectFinances = $this->ProjectFinance->findByProjectId($projectData['id']);

        $income = $this->FinanceCategory->findById( Hash::get($projectFinances, 'ProjectFinance.income_id') );
        $expense = $this->FinanceCategory->findById( Hash::get($projectFinances, 'ProjectFinance.expense_id') );
        $tax = $this->FinanceCategory->findById( Hash::get($projectFinances, 'ProjectFinance.tax_id') );
        $percent = $this->FinanceCategory->findById( Hash::get($projectFinances, 'ProjectFinance.percent_id') );

        $income['FinanceCategory']['name'] = $projectData['title'].' '.__('income');
        $expense['FinanceCategory']['name'] = $projectData['title'].' '.__('expense');
        $tax['FinanceCategory']['name'] = $projectData['title'].' '.__('tax');
        $percent['FinanceCategory']['name'] = $projectData['title'].' '.__('percent');

        $this->FinanceCategory->addCategory($fpID, $income);
        $this->FinanceCategory->clear();
        $this->FinanceCategory->addCategory($fpID, $expense);
        $this->FinanceCategory->clear();
        $this->FinanceCategory->addCategory($fpID, $tax);
        $this->FinanceCategory->clear();
        $this->FinanceCategory->addCategory($fpID, $percent);

        return true;
    }

    public function shareFinanceProject($aUsers, $projectId) {
        $this->loadModel('FinanceShare');
        $this->loadModel('Group');

        $project = $this->findById($projectId);
        $group = $this->Group->findById( Hash::get($project, 'Project.group_id') );

        if( !$group['Group']['finance_project_id'] ) {
            $fpID = $this->Group->addFinanceProject($group['Group'], $group['Group']['owner_id'], 1);
            $group['Group']['finance_project_id'] = $fpID;
            $this->Group->save($group);
            $group = $this->Group->findById( $project['Project']['group_id'] );
        }

        $financeProjectId = Hash::get($group, 'Group.finance_project_id');
        $owner = Hash::get($group, 'Group.owner_id');

        if( is_array($aUsers) ) {
            foreach($aUsers as $uid) {
                if($uid != $owner) {
                    $share = $this->FinanceShare->findByUserId($uid);
                    if(!$share) {
                        $this->FinanceShare->sendInvite($financeProjectId, $uid);
                        $this->FinanceShare->acceptInvite($uid, $financeProjectId);
                        $this->FinanceShare->setFullAccess($financeProjectId, $uid);
                    }
                }
            }
        } else {
            if($uid != $owner) {
            $share = $this->FinanceShare->findByUserId($aUsers);
                if(!$share) {
                    $this->FinanceShare->sendInvite($financeProjectId, $aUsers);
                    $this->FinanceShare->acceptInvite($aUsers, $financeProjectId);
                    $this->FinanceShare->setFullAccess($financeProjectId, $aUsers);
                }
            }
        }
    }
    public function timeUserProjects($userID, $aProjects){
        $this->loadModel('Group');


        $aGroups = $this->Group->find('all', array(
            'recursive' => -1,
            'conditions' => array(
                'OR' => array(
                    'Group.responsible_id' => $userID,
                    'AND' => array(
                        'NOT' => array( 'Group.responsible_id' => null ),
                        'Group.owner_id' => $userID
                    )
                )
            ),
            'fields' => array('id')
        ));

        $conditions = array(
            'OR' => array(
                'Project.id' => Hash::extract($aProjects, '{n}.ProjectMember.project_id'),
                'Project.group_id' => Hash::extract($aGroups, '{n}.Group.id')
            )
        );
        $order = 'Project.title';
        $aProjects = $this->find('all', array(
            'conditions' => $conditions,
            'order' => $order,
            'recursive' => -1,
            'fields' => array('id','title')
        ));
        return Hash::combine($aProjects, '{n}.Project.id', '{n}.Project.title');
    }
    public function userProjects($userID) {
        $this->loadModel('Group');
        $this->loadModel('GroupMember');
        $this->loadModel('ProjectMember');

        //Находим группы/проекты, из которых исключены, что бы "списать" проекты, в которых мы уже не учавствуем
        $conditions = array(
            'GroupMember.user_id' => $userID,
            'OR' => array(
                'GroupMember.is_deleted' => '1',
                'GroupMember.approved' => '0'
            )
        );
        $aExcludeProjects = $this->GroupMember->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1,
        ));
        $aExcludeProjects = $this->findAllByGroupId( Hash::extract($aExcludeProjects, '{n}.GroupMember.group_id') );

        //C учётом того, откуда мы исключены, собираем проекты, в которых мы учавствуем
        $conditions = array(
            'ProjectMember.user_id' => $userID,
            'NOT' => array(
                'ProjectMember.project_id' => Hash::extract($aExcludeProjects, '{n}.Project.id')
            )
        );
        $aProjects = $this->ProjectMember->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1,
        ));
        $aGroups = $this->Group->find('all', array(
            'recursive' => -1,
            'conditions' => array(
                'OR' => array(
                    'Group.responsible_id' => $userID,
                    'AND' => array(
                        'NOT' => array( 'Group.responsible_id' => null ),
                        'Group.owner_id' => $userID
                    )
                )
            ),
            'fields' => array('id')
        ));

        $conditions = array(
            'OR' => array(
                'Project.id' => Hash::extract($aProjects, '{n}.ProjectMember.project_id'),
                'Project.group_id' => Hash::extract($aGroups, '{n}.Group.id')
            )
        );
        $order = 'Project.title';
        $aProjects = $this->find('all', array(
            'conditions' => $conditions,
            'order' => $order,
            'recursive' => -1,
            'fields' => array('id','title')
        ));

        //Debugger::dump( Hash::combine($aProjects, '{n}.Project.id', '{n}.Project.title') );
        //Debugger::dump( Hash::combine($aGroups, '{n}.Group.id', '{n}.Group.title') );
        return Hash::combine($aProjects, '{n}.Project.id', '{n}.Project.title');
    }

    public function getProjectMembers($projectID) {
        $this->loadModel('ProjectMember');
        $return = $this->ProjectMember->findAllByProjectId( $projectID );

        return Hash::extract($return, '{n}.ProjectMember.user_id');
    }

    public function getProjectGroup($id) {
        $this->loadModel('Group');

        $return = $this->findById( $id );
        $return = $this->Group->findById( $return['Project']['group_id'] );

        return $return;
    }

    public function remove($id, $user_id) {
        $this->loadModel('Group');
        $project = $this->findById($id);

        if( !$project ) { return false; }

        $group = $this->Group->findById( Hash::get($project, 'Project.group_id') );

        if( !$group ) { return false; }
        if( $group['Group']['owner_id'] != $user_id ) { return false; }
        if($this->save( array('id' => Hash::get($project, 'Project.id'), 'is_deleted' => '1' ) )) { return true; }

        return false;
    }
}
