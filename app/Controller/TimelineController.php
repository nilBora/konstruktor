<?php
/**
 * Отдельный контроллер потому что отдельный layout!!!
 */
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class TimelineController extends SiteController {
    public $name = 'Timeline';
    public $layout = 'timeline_new';
    public $helpers = array('Media');

    public function planet() {
        $this->layout = 'profile_new';
        $this->loadModel('User');
        $this->loadModel('Group');
        $this->loadModel('GroupAddress');

        $conditions = array(
            'not' => array(
                'User.lat' => null,
                'User.lng' => null,
                'User.id' => $this->currUserID
            )
        );
        $users = $this->User->find('all', compact('conditions'));
        $out = array();

        foreach($users as $user) {
            $out[$user['User']['id']] = array(
                'username' => $user['User']['full_name'],
                'lat' => $user['User']['lat'],
                'lng' => $user['User']['lng'],
                'image' => $user['UserMedia']['url_img'],
            );
        }
        $users = $out;

        $this->set('users', $users);
        //стартовые координаты - координаты Калифорнии
        $initCoords = array('lat' => '37.2718745', 'lng' => '-119.2732788');
        if($this->currUser['User']['lat'] != null) {
            $initCoords['lat'] = $this->currUser['User']['lat'];
            $initCoords['lng'] = $this->currUser['User']['lng'];
        }
        $this->set('initCoords', $initCoords);

        $conditions = array(
            'not' => array(
                'GroupAddress.address' => null,
                'GroupAddress.country' => null
            )
        );
        $groupAddresses = $this->GroupAddress->find('all', compact('conditions'));
        $groups = Hash::extract($groupAddresses, '{n}.GroupAddress.group_id');
        $groups = $this->Group->findAllByIdAndHidden($groups, 0);
        $groups = Hash::combine($groups, '{n}.Group.id', '{n}');
        $out = array();
        foreach($groupAddresses as $address) {
            $addrString = $address['GroupAddress']['country'].', '.$address['GroupAddress']['address'];
            if( strlen($addrString) > 8 && isset($groups[$address['GroupAddress']['group_id']])) {
                $entry['address'] = $addrString;
                $entry['title'] = $groups[$address['GroupAddress']['group_id']]['Group']['title'];
                $entry['descr'] = $groups[$address['GroupAddress']['group_id']]['Group']['descr'];
                $entry['image'] = $groups[$address['GroupAddress']['group_id']]['GroupMedia']['url_img'];
                $out[$address['GroupAddress']['group_id']] = $entry;
            }
        }
        $this->set('groupAddresses', $out);

        $this->loadModel('UserEvent');
        $events = $this->UserEvent->getUserEvents($this->currUserID, null, null, false, true);
        $taskEvents = $this->UserEvent->getUserTaskEvents($this->currUserID, null, null, false, true);
        $_events = array_merge($taskEvents, $events);

        $this->set('aEvents', $_events);
    }

    public function index() {
        if(isset($_SESSION['returnTo'])){
            $r = $_SESSION['returnTo'];
            unset($_SESSION['returnTo']);
            $this->redirect($r);
        }
        $this->loadModel('Group');
        $this->loadModel('Project');
        $this->loadModel('Subproject');
        $this->loadModel('Task');
        $this->loadModel('CrmTask');
        $this->loadModel('UserEvent');

        $this->loadModel('FinanceProject');
        $this->loadModel('FinanceAccount');

        // Даные для привязки события к объектам
        //$this->set('eventAutocomplete', $this->UserEvent->userEventOptionsJson());
        $conditions = array(
            'GroupMember.user_id' => $this->currUserID,
            'GroupMember.is_deleted' => '0',
            'GroupMember.approved' => '1'
        );
        $aGroups = $this->GroupMember->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1,
        ));


        $aGroups = Hash::extract($aGroups, '{n}.GroupMember.group_id');
        //$aGroups = $this->Group->userGroups($this->currUserID);

        $aExcludeProjects = $this->Project->findAllByGroupId( $aGroups );
        $conditions = array(
                'ProjectMember.user_id' => $this->currUserID,
                'ProjectMember.project_id' => Hash::extract($aExcludeProjects, '{n}.Project.id')
        );

        $this->loadModel('ProjectMember');
        $allowProjects = $this->ProjectMember->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1,
            'fields' => array('project_id')
        ));

        $aProjects = $this->Project->timeUserProjects($this->currUserID, $allowProjects);
        //$aProjects = $this->Project->userProjects($this->currUserID);

        $aSubprojects = $this->Subproject->timeUserSubprojects($this->currUserID, $allowProjects);
        //$aSubprojects = $this->Subproject->userSubprojects($this->currUserID);

        $aTasks = $this->Task->getTimeMyTasks(true,$allowProjects);
        //$aTasks = $this->Task->getMyTasks();

        $aBindOptions = array();
        foreach($aGroups as $id) {
            $data = array('category' => __('Groups'), 'type' => 'group', 'id' => $id);
            array_push($aBindOptions, compact('value', 'data') );
        }
        foreach($aProjects as $id => $value ) {
            $data = array('category' => __('Projects'), 'type' => 'project', 'id' => $id);
            array_push($aBindOptions, compact('value', 'data') );
        }
        foreach($aSubprojects as $id => $value ) {
            $data = array('category' => __('Subprojects'), 'type' => 'subproject', 'id' => $id);
            array_push($aBindOptions, compact('value', 'data') );
        }
        foreach($aTasks as $id => $value ) {
            $data = array('category' => __('Tasks'), 'type' => 'task', 'id' => $id);
            array_push($aBindOptions, compact('value', 'data') );
        }
        $this->set('aBindOptions', json_encode($aBindOptions));

        $this->loadModel('UserEventCategory');
        $aCategories = array();
        $categories = $this->UserEventCategory->find('all');

        $aBindCatOptions = array();
        foreach($categories as $id => $value ) {
            $aCategories[Hash::get($value, 'UserEventCategory.id')] = __(Hash::get($value, 'UserEventCategory.title'));
            $data = array('category' => __('Category'),'id' => Hash::get($value, 'UserEventCategory.id'));
            $value = Hash::get($value, 'UserEventCategory.title');
            array_push($aBindCatOptions, compact('value', 'data') );
        }
        $this->set('aCategories', $aCategories);
        $this->set('aBindCategories', json_encode($aBindCatOptions));

        // Данные для привязки счетов к фин.проекту
        $financeData = $this->FinanceProject->search($this->currUserID, '');
        $aFinanceProjects = Hash::combine($financeData, 'aFinanceProjects.{n}.FinanceProject.id', 'aFinanceProjects.{n}.FinanceProject.name');

        $aTasks = $this->Task->getTimeMyTasks(false, $allowProjects);

        $exclude = array_keys($aTasks);
        $conditions = array(
            'CrmTask.task_id' => $exclude
        );

        $exclude = $this->CrmTask->find('all', array(
            'conditions' => $conditions
        ));

        $exclude = Hash::extract($exclude, '{n}.CrmTask.account_id');

        $aFinanceAccounts = array();
        foreach($financeData['aFinanceProjects'] as $financeProject) {
            $pID = Hash::get($financeProject, 'FinanceProject.id');
            $pAccounts = Hash::combine($financeProject, 'Accounts.{n}.id', 'Accounts.{n}.name');
            foreach($exclude as $exId) {
                unset($pAccounts[$exId]);
            }
            $aFinanceAccounts[$pID]    = $pAccounts;
        }
        $this->set('aFinanceProjectOptions', $aFinanceProjects);
        $this->set('aProjectAccounts', json_encode($aFinanceAccounts));

        $topDay = Configure::read('timeline.initialPeriod.1');
        $bottomDay = Configure::read('timeline.initialPeriod.0');
        $startDay = -floor((strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime(Hash::get($this->currUser, 'User.created'))))) / DAY);
        if ($bottomDay < $startDay) {
            $bottomDay = $startDay;
        }

        $this->set('topDay', $topDay);
        $this->set('bottomDay', $bottomDay);
        $this->set('startDay', $bottomDay);

        $today = time();
        $date = $today + DAY * $bottomDay;
        $date2 = $today + DAY * $topDay;
        //return $this->response;
        if(isset($this->request->query['search'])) {
            $this->set('aTimeline', $this->User->getTimeline($this->currUserID, date('Y-m-d', $date), date('Y-m-d', $date2), 0, false, $this->request->query['search']));
        } else {
            $this->set('aTimeline', $this->User->getTimeline($this->currUserID, date('Y-m-d', $date), date('Y-m-d', $date2)));
        }
    }
}
