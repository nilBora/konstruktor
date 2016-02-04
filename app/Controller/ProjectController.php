<?php
/**
 * TODO:
 * - check Project admin rights (beforeFilter)
 */
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');

/**
 * Class ProjectController
 * @property ProjectEvent ProjectEvent
 */
class ProjectController extends SiteController {
    public $name = 'Project';
    public $layout = 'profile_new';
    public $uses = array('Project', 'ProjectEvent', 'ProjectFinance', 'Subproject', 'Task', 'CrmTask', 'GroupMember', 'User', 'Group', 'Media.Media', 'ChatMessage', 'ProjectMember');
    public $helpers = array('Media', 'LocalDate');

    public function edit($id = 0) {
        $Project = $this->Project->findById($id);
		$this->set('project',$Project);

        if(!$Project && $id) {
            throw new NotFoundException();
        }

        if(!$id) {
            $group = $this->Group->findById($this->request->named['Project.group_id']);
        } else {
            $groupID = $Project['Project']['group_id'];
            $group = $this->Group->findById($groupID);
        }
		$this->set('group', $group);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if (!$isGroupAdmin) {
            return $this->redirect(array('controller' => 'Project', 'action' => 'view', $id));
        }

        $title = __('Project').': '.__('edit');
        $this->set(compact('title'));

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!$id) {
                $this->request->data('Project.group_id', $this->request->named['Project.group_id']);
                $this->request->data('Project.owner_id', $this->currUserID);
                $this->request->data('Project.hidden', $this->request->data('Project.hidden') && true);
            }

            $responsibleID = $this->request->data['Project']['responsible_id'];

            //подвязка к счету
            if( !$this->request->data('Project.use_account')) {
                $this->request->data('Project.finance_account_id', null);
                $this->request->data('Project.finance_category_id', null);
            } else {
            //для подвязки к категории: если есть связь со счётом - создавать категорию либо брать существующую
                $this->loadModel('FinanceCategory');
                $financeProjectID = $this->Group->findById($this->request->data('Project.group_id'));
                $financeProjectID = $financeProjectID['Group']['finance_project_id'];
                $conditions = array(
                    'FinanceCategory.project_id' => $financeProjectID,
                    'FinanceCategory.name' => $this->request->data('Project.title')
                );
                $financeCategory = $this->FinanceCategory->find('first', compact('conditions'));
                if( !$financeCategory ) {
                    $data = array(
                        'FinanceCategory' => array(
                            'name' => $this->request->data('Project.title'),
                            'type' => '0'
                        )
                    );
                    $financeCategoryID = $this->FinanceCategory->addCategory($financeProjectID, $data);
                    $this->request->data('Project.finance_category_id', $financeCategoryID);
                } else {
                    $this->request->data('Project.finance_category_id', $financeCategory['FinanceCategory']['id']);
                }
            }

            $this->Project->save($this->request->data);

            $this->request->data('Project.id', $this->Project->id);

            if( !$id ) {
                $this->Project->createFinanceCategories($this->request->data('Project'), $this->currUserID);
            } else {
                $this->Project->updateFinanceCategories($this->request->data('Project'), $this->currUserID);
            }

            if (!$id) {
                $this->ProjectEvent->addEvent(ProjectEvent::PROJECT_CREATED, $this->Project->id, $this->currUserID);
                $this->ProjectMember->save(array('project_id' => $this->Project->id, 'user_id' => $this->currUserID, 'sort_order' => '0'));
                $id = $this->Project->id;
            }

            //при множественых ответственных этот блок с обнулением уберётся
            $member = $this->ProjectMember->findByProjectIdAndIsResponsible($this->Project->id,'1');
            if($member) {
                $mID = Hash::get($member, 'ProjectMember.id');
                $this->ProjectMember->save(array( 'id' => $mID, 'is_responsible' => 0, 'sort_order' => 1));
            }

            $member = $this->ProjectMember->findByProjectIdAndUserId($this->Project->id, $responsibleID);
            $this->ProjectMember->clear();
            if($member) {
                $this->ProjectMember->save(array(
                    'id' => $member['ProjectMember']['id'],
                    'is_responsible' => 1,
                    'sort_order' => 0
                ));
            } else {
                $this->ProjectMember->save(array(
                    'project_id' => $id,
                    'owner_id' => $group['Group']['owner_id'],
                    'user_id' => $responsibleID,
                    'is_responsible' => 1,
                    'sort_order' => 0
                ));
            }

            // return $this->redirect(array('controller' => $this->name, 'action' => 'edit', $this->Project->id, '?' => array('success' => '1')));
            return $this->redirect(array('controller' => $this->name, 'action' => 'view', $this->Project->id));
        } else {
            if(!$id) {
                $groupID = (isset($this->request->named['Project.group_id'])) ? $this->request->named['Project.group_id'] : 0;
            } else {
                $groupID = $Project['Project']['group_id'];
                $responsibleID = $this->ProjectMember->findByProjectIdAndIsResponsible($id, '1');
                $this->set('responsibleID', Hash::get($responsibleID, 'ProjectMember.user_id'));
            }

            // создавать и редактировать проект может только админ и ответственный родительской группы
            if ( !$group || !$isGroupAdmin ) {
                return $this->redirect(array('controller' => 'User', 'action' => 'view'));
            }

            $this->request->data = $Project;
            $aMembers = $this->GroupMember->getList($groupID, null, 0);
            $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
            $aUsers = $this->User->getUsers($aID);
            $this->set('aMemberOptions', Hash::combine($aUsers, '{n}.User.id', '{n}.User.full_name'));

            $this->loadModel('FinanceProject');
            $this->loadModel('FinanceAccount');

            $aFinanceAccounts = $this->FinanceAccount->search($group['Group']['finance_project_id']);
            $aFinanceAccounts = Hash::combine($aFinanceAccounts, 'aFinanceAccount.{n}.FinanceAccount.id', 'aFinanceAccount.{n}.FinanceAccount.name');

            $hasAccount = false;
            if($id) {
                $hasAccount = $Project['Project']['finance_account_id'] != null;
            }

            $this->set('aFinanceAccounts',$aFinanceAccounts);
            $this->set('hasAccount',$hasAccount);
        }
    }

    public function view($id) {
        $project = $this->Project->findById($id);
        $this->set('project', $project);
        $this->set('isProjectAdmin', Hash::get($project, 'Project.owner_id') == $this->currUserID);

        if( !$project || $project['Project']['deleted'] == '1' ) {
            throw new NotFoundException();
        }

        $group = $this->Project->getProjectGroup($project['Project']['id']);
		$this->set('group', $group);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);
        $this->set('isGroupAdmin', $isGroupAdmin);

        if( !$project['ProjectFinance']['id'] ) {
            $this->Project->createFinanceCategories($project['Project'], $project['Project']['owner_id']);
            $project = $this->Project->findById($id);
        }

        $title = __('Project').': '.$project['Project']['title'];
        $this->set(compact('title'));

        $responsible = $this->ProjectMember->findByUserIdAndProjectId($this->currUserID, $id);
        $this->set('isResponsible', (bool)Hash::get($responsible, 'ProjectMember.is_responsible'));

        $aGroupMembers = $this->GroupMember->getList(Hash::get($project, 'Project.group_id'), null, 0);
        $aGID = Hash::extract($aGroupMembers, '{n}.GroupMember.user_id');

        $aMembers = $this->ProjectMember->getList($id);
        $aID = Hash::extract($aMembers, '{n}.ProjectMember.user_id');

        if( !in_array($this->currUserID, $aID) && !$isGroupAdmin ) {
            return $this->redirect(array('controller' => 'Group', 'action' => 'view', $project['Project']['group_id']));
        }

        $aMembers = Hash::combine($aMembers, '{n}.ProjectMember.user_id', '{n}');
        $aGroupMembers = Hash::combine($aGroupMembers, '{n}.GroupMember.user_id', '{n}');

        $aProjectMembers = array_intersect_key($aMembers, $aGroupMembers);
        $this->set('aProjectMembers', $aProjectMembers );

        $aMembers = $this->GroupMember->getList($project['Project']['group_id'], null, null, null);
        $aMembers = Hash::combine($aMembers, '{n}.GroupMember.user_id', '{n}');
        $this->set('aMembers', $aMembers);

        $aID = array_keys($aMembers);
        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);

        $aMembers = $this->GroupMember->getList($project['Project']['group_id'], null, 0);
        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aMemberOptions', Hash::combine($aUsers, '{n}.User.id', '{n}'));

        $aID = Hash::extract($aProjectMembers, '{n}.ProjectMember.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aProjectMemberOptions', Hash::combine($aUsers, '{n}.User.id', '{n}.User.full_name'));

        $conditions = array(
            'Subproject.project_id' => $id
        );
        $subprojects = $this->Subproject->find('all', compact('conditions'));
        $subprojects = Hash::combine($subprojects, '{n}.Subproject.id', '{n}');

        $aID = array_keys($subprojects);

        $conditions = array(
            'Task.subproject_id' => $aID
        );
        $aTasks = $this->Task->find('all', array('conditions' => $conditions));

        $conditions = array('ProjectEvent.project_id' => $id);
        $order = 'ProjectEvent.created DESC';
        $limit = 5;
        $aEvents = $this->ProjectEvent->find('all', compact('conditions', 'order', 'limit'));

        $aID = Hash::extract($aEvents, '{n}.ProjectEvent.file_id');
        $files = $this->Media->getList(array('id' => $aID), 'Media.id');
        $files = Hash::combine($files, '{n}.Media.id', '{n}.Media');

        $this->set(compact('subprojects', 'aEvents', 'aTasks', 'files'));
    }

    public function task($id) {
        $this->loadModel('StorageLimit');
        $this->StorageLimit->UpdateTaskDiscussionFileSize($this->Auth->user('id'));
//        $this->StorageLimit->taskDiscussionFileSize($this->Auth->user('id'));
        $this->loadModel('MediaFile');
        $task = $this->Task->findById($id);

        if( !$task || $task['Task']['deleted'] == '1' ) {
            throw new NotFoundException();
        }

        $subproject = $this->Subproject->findById($task['Task']['subproject_id']);

        if( !$subproject || $subproject['Subproject']['deleted'] == '1' ) {
            throw new NotFoundException();
        }

        $project_id = $subproject['Subproject']['project_id'];
		$this->set('projectID', $project_id);
        $project = $this->Project->findById($project_id);

        if( !$project || $project['Project']['deleted'] == '1' ) {
            throw new NotFoundException();
        }

        $owner_id = Hash::get($project, 'Project.owner_id');

        if(!$task['CrmTask']['id']) {
            $crmData = array(
                'task_id' => Hash::get($task, 'Task.id'),
                'contractor_id' => null,
                'money' => 0,
                'currency' => 'USD',
            );
            $this->CrmTask->save($crmData);

            $this->Task->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
            $task = $this->Task->findById($id);
        }

        $title = __('Task').': '.$task['Task']['title'];
        $this->set(compact('title'));

        if( !$task['CrmTask']['account_id'] ) {
            $this->Task->createFinanceAccount( Hash::get($task, 'Task.id'), $owner_id );
        }
        $task = $this->Task->findById($id);

        $aUsers = $this->User->getUsers(array($task['Task']['manager_id'], $task['Task']['user_id']));
        $this->set('aUsers', Hash::combine($aUsers, '{n}.User.id', '{n}'));
        $group = $this->Group->findById($project['Project']['group_id']);

        $members = $this->ProjectMember->getList($project_id);
        $aID = Hash::extract($members, '{n}.ProjectMember.user_id');

        $aGroupMembers = $this->GroupMember->getList(Hash::get($project, 'Project.group_id'), null, 0);
        $aGID = Hash::extract($aGroupMembers, '{n}.GroupMember.user_id');

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if( !in_array($this->currUserID, $aID) && !$isGroupAdmin ) {
            return $this->redirect(array('controller' => 'Group', 'action' => 'view', $project['Project']['group_id']));
        }

        $members = $this->GroupMember->getList($project['Project']['group_id'], null, null, null);
        $aID = Hash::extract($members, '{n}.GroupMember.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);

        if ($this->request->is('put') || $this->request->is('post')) {
            $media = is_array($this->request->data('media')) ?  $this->request->data('media') : array();
            $this->ProjectEvent->addTaskComment(
                $this->currUserID,
                $this->request->data('message'),
                $id,
                $project_id,
                $media
            );
            return $this->redirect(array('action' => 'task', $id));
        }
        $conditions = array('ProjectEvent.project_id' => $project_id, 'ProjectEvent.task_id' => $id);
        $order = 'ProjectEvent.created DESC';
        $aEvents = $this->ProjectEvent->find('all', compact('conditions', 'order'));

        $aID = Hash::extract($aEvents, '{n}.ProjectEvent.msg_id');
        $messages = $this->ChatMessage->findAllById($aID);
        $messages = Hash::combine($messages, '{n}.ChatMessage.id', '{n}.ChatMessage');

        // media for comments
        $commentsMedia = array();
        if (!empty($messages)) {
            $commentsMediaResult = $this->MediaFile->getList(array(
                'object_id' => array_keys($messages),
                'object_type' => "TaskComment",
            ), 'Media.id');
            $commentsMediaResult = Hash::combine($commentsMediaResult, '{n}.Media.id', '{n}.Media');
            foreach ($commentsMediaResult as $mediaItem) {
                $mediaItem['size'] = (isset($mediaItem['media_type']) && $mediaItem['media_type'] == 'image') ? $mediaItem['orig_w'] . 'x' . $mediaItem['orig_h'] : false;
                $commentsMedia[$mediaItem['object_id']][] = $mediaItem;
            }
        }
        $this->set('commentsMedia', $commentsMedia);

        $aID = Hash::extract($aEvents, '{n}.ProjectEvent.file_id');
        $files = $this->Media->getList(array('id' => $aID), 'Media.id');
        $files = Hash::combine($files, '{n}.Media.id', '{n}.Media');

        $this->set(compact('task', 'subproject', 'project', 'group', 'messages', 'files', 'members', 'aEvents'));

        $responsibleUser = $this->ProjectMember->findAllByProjectIdAndUserIdAndIsResponsible($project_id, $this->currUserID, '1');

        $this->set('isProjectAdmin', $this->currUserID == Hash::get($project, 'Project.owner_id'));
        $this->set('isProjectResponsible', $responsibleUser);

        //Данные связанные с CRM
        $this->loadModel('FinanceAccount');
        $this->loadModel('FinanceOperation');
        $this->loadModel('UserEvent');
        $this->loadModel('FavouriteUser');

        $contractor = $this->User->findById(Hash::get($task, 'CrmTask.contractor_id'));
        $initialBalance = Hash::get($task, 'CrmTask.money');
        $taskAccount = $this->FinanceAccount->findById( Hash::get($task, 'CrmTask.account_id') );

        $actualBalance = $this->FinanceOperation->accountCurrentBalance( Hash::get($task, 'CrmTask.account_id'));
        $lastMonth = date('Y-m-t', strtotime('-1 month'));
        $lastMonthBalance = Hash::get($task, 'CrmTask.money', $lastMonth);

        $accoundID = Hash::get($task, 'CrmTask.account_id');

        $fullExpense = $this->FinanceAccount->fullExpense( $accoundID );
        $fullIncome = $this->FinanceAccount->fullIncome( $accoundID );

        if(!$fullExpense) $fullExpense = 0.00;
        if(!$fullIncome) $fullIncome = 0.00;

        //затраченное время, считается по событиям
        $timeInterval = 0;

        $conditions = array(
            'UserEvent.object_type' => 'task',
            'UserEvent.object_type' => Hash::get($task, 'CrmTask.account_id'),
            'UserEvent.event_time < ?' => date('Y-m-d H:i:s'),
            'UserEvent.is_delayed' => '0'
        );

        $aTaskEvents = $this->UserEvent->find('all', compact('conditions'));
        foreach($aTaskEvents as $event) {
            $start = strtotime($event['UserEvent']['event_time']);
            $end = strtotime($event['UserEvent']['event_end_time']);
            $timeInterval += $end - $start;
        }
        $days = floor($timeInterval/(60*60*24));
        $hours = floor( $timeInterval /(60*60) - $days*24 );
        $minutes = floor($timeInterval%(60*60))/60;
        $timeInterval = compact('days', 'hours', 'minutes');

        $conditions = array('user_id' => $this->currUserID);
        $fu = $this->FavouriteUser->find('all', array('conditions' => $conditions));
        $au = Hash::extract($fu, '{n}.FavouriteUser.fav_user_id');
        $au = $this->User->findAllById($au);
        $aUserOptions = Hash::combine($au, '{n}.User.id', '{n}.User.full_name');

        $operationsCount = $this->FinanceOperation->findAllByAccountId( Hash::get($task, 'CrmTask.account_id') );
        $operationsCount = count($operationsCount);

        $this->set( compact('contractor', 'initialBalance', 'actualBalance', 'lastMonthBalance', 'taskAccount', 'fullIncome', 'fullExpense', 'timeInterval', 'aUserOptions', 'operationsCount') );
    }

    public function remove($id) {
        $project = $this->Project->findById($id);
        if(!$project) {
            throw new NotFoundException();
        }
        if( !$this->Project->remove($id, $this->currUserID) ) {
            throw new NotFoundException();    //restricted!
        }
        $this->redirect(array('controller' => 'Group', 'action' => 'view', Hash::get($project, 'Project.group_id')));
    }

    public function removeSubproject($id) {
        $subproject = $this->Subproject->findById($id);
        if(!$subproject) {
            throw new NotFoundException();
        }
        if( !$this->Subproject->remove($id, $this->currUserID) ) {
            throw new NotFoundException();    //restricted!
        }
        $project_id =  $subproject['Subproject']['project_id'];
        $this->ProjectEvent->addEvent(ProjectEvent::SUBPROJECT_DELETED, $project_id, $this->currUserID, $id);
        $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
    }

    public function removeTask($id) {
        $task = $this->Task->findById($id);
        if(!$task) {
            throw new NotFoundException();
        }
        if( !$this->Task->remove($id, $this->currUserID) ) {
            throw new NotFoundException();    //restricted!
        }
        $subproject = $this->Subproject->findById($task['Task']['subproject_id']);
        $project_id =  $subproject['Subproject']['project_id'];
        $this->ProjectEvent->addEvent(ProjectEvent::TASK_DELETED, $project_id, $this->currUserID, $id);
        $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
    }

    public function subproject($id) {
        $subproject = $this->Subproject->findById($id);

        if( !$subproject ) {
            throw new NotFoundException();
        }

        $project = $this->Project->findById( $subproject['Subproject']['project_id'] );

        if( !$project || $project['Project']['deleted'] == '1' ) {
            throw new NotFoundException();
        }

        $this->redirect(array('controller' => 'Project', 'action' => 'view', Hash::get($subproject, 'Subproject.project_id')));
    }

    /* Старый тип подпроектов без подвязки к фин.менеджеру. Пока оставил, возможно вернёмся к старой схеме */
    /*
    public function addSubproject() {
        $project_id = $this->request->data('Subproject.project_id');

        $project = $this->Project->findById($project_id);
        $responsibleUsers = $this->ProjectMember->findAllByProjectIdAndIsResponsible($project_id, '1');
        $responsibleUsers = Hash::extract($responsibleUsers, '{n}.ProjectMember.user_id');

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && (!in_array($this->currUserID, $responsibleUsers)))
        {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
        }

        $this->Subproject->save($this->request->data);
        $project_id = $this->request->data('Subproject.project_id');
        $this->ProjectEvent->addEvent(ProjectEvent::SUBPROJECT_CREATED, $project_id, $this->currUserID, $this->Subproject->id);
        $this->redirect(array('action' => 'view', $project_id));
    }
    */

    public function addCrmSubproject() {
        $project_id = $this->request->data('Subproject.project_id');

        $project = $this->Project->findById($project_id);
        $responsibleUsers = $this->ProjectMember->findAllByProjectIdAndIsResponsible($project_id, '1');
        $responsibleUsers = Hash::extract($responsibleUsers, '{n}.ProjectMember.user_id');

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && (!in_array($this->currUserID, $responsibleUsers)) && !$isGroupAdmin)
        {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
        }

        $this->Subproject->save($this->request->data);
        if(!$this->request->data('Subproject.id')) {
            $project_id = $this->request->data('Subproject.project_id');
            $this->ProjectEvent->addEvent(ProjectEvent::SUBPROJECT_CREATED, $project_id, $this->currUserID, $this->Subproject->id);
        }
        $this->redirect(array('action' => 'view', $project_id));
    }

    public function addUserEvent() {
        $this->loadModel('UserEvent');

        $event = array(
            'user_id' => $this->currUserID,
            'title' => $this->request->data('UserEvent.title'),
            'descr' => $this->request->data('UserEvent.descr'),
            'task_id' => $this->request->data('UserEvent.task_id'),
            'type' => $this->request->data('UserEvent.type'),
            'recipient_id' => $this->request->data('UserEvent.recipient_id'),
            'event_category_id' => $this->request->data('UserEvent.event_category_id')
        );

        $id = $this->request->data('UserEvent.id');
        if( $id ) {
            $event['id'] = $id;
        }

        if( $this->request->data('UserEvent.duration') == 'day' ) {
            $begin = $this->request->data('UserEvent.eventTime').' '.$this->request->data('UserEvent.timeBegin');
            $end = $this->request->data('UserEvent.eventTime').' '.$this->request->data('UserEvent.timeEnd');

            $begin = new DateTime(  $begin );
            $end = new DateTime( $end );
        } else {
            $begin =  new DateTime( $this->request->data('UserEvent.eventTime') );
            $end = new DateTime( $this->request->data('UserEvent.periodEnd') );
        }

        $event['event_time'] = $begin->format('Y-m-d H:i:s');
        $event['event_end_time'] = $end->format('Y-m-d H:i:s');

        $this->UserEvent->save( $event );
        $this->redirect($this->referer());
    }
    /* Старый тип задач без подвязки к фин.менеджеру. Пока оставил, возможно вернёмся к старой схеме */
    /*
    public function addTask($project_id) {
        $project = $this->Project->findById($project_id);
        $responsibleUsers = $this->ProjectMember->findAllByProjectIdAndIsResponsible($project_id, '1');
        $responsibleUsers = Hash::extract($responsibleUsers, '{n}.ProjectMember.user_id');

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && (!in_array($this->currUserID, $responsibleUsers)))
        {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
        }

        $this->Task->save($this->request->data);
        $this->ProjectEvent->addEvent(ProjectEvent::TASK_CREATED, $project_id, $this->currUserID, $this->Task->id);

        $this->redirect(array('action' => 'view', $project_id));
    }
    */
    public function addCrmTask($project_id) {
        $project = $this->Project->findById($project_id);
        $owner_id = Hash::get($project, 'Project.owner_id');
        $responsibleUsers = $this->ProjectMember->findAllByProjectIdAndIsResponsible($project_id, '1');
        $responsibleUsers = Hash::extract($responsibleUsers, '{n}.ProjectMember.user_id');

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && (!in_array($this->currUserID, $responsibleUsers)) && !$isGroupAdmin) {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
        }

        if($this->request->data('Task.id')) {
            unset($this->request->data['Task']['subproject_id']);
        }

        $this->Task->save($this->request->data('Task'));

        if(!$this->request->data('Task.id')) {
            $this->ProjectEvent->addEvent(ProjectEvent::TASK_CREATED, $project_id, $this->currUserID, $this->Task->id);

            $crmData = array(
                'task_id' => $this->Task->id,
                'contractor_id' => null,
                'money' => $this->request->data('CrmTask.money'),
                'currency' => $this->request->data('CrmTask.currency'),
            );
            $this->CrmTask->save($crmData);

            $this->Task->createFinanceAccount($this->Task->id, $owner_id);
        }

        $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
    }

    public function addTaskContractor() {
        $this->autoRender = false;

        $crmTask = $this->CrmTask->findByTaskId( $this->request->data('task_id') );
        $crmTask['CrmTask']['contractor_id'] = $this->request->data('user_id');
        $this->CrmTask->save($crmTask);
    }

    public function taskAccountManage() {
        $this->autoRender = false;

        $this->loadModel('FinanceOperation');
        $this->loadModel('FinanceAccount');
        $this->loadModel('FinanceProject');

        $task = $this->Task->findById($this->request->data('Operation.task_id'));
        $projectFinance = $this->Task->getProject($task);
        $owner_id = Hash::get($projectFinance, 'Project.owner_id');
        $projectFinance = $this->ProjectFinance->findByProjectId(Hash::get($projectFinance, 'Project.id'));

        $accoundID = Hash::get($task, 'CrmTask.account_id');
        $finProjectID = $this->FinanceAccount->findById($accoundID);
        $finProjectID = $this->FinanceProject->findById(Hash::get($finProjectID, 'FinanceAccount.project_id'));
        $finProjectID = Hash::get($finProjectID, 'FinanceProject.id');

        $data = array(
            'FinanceOperation' => array(
                'project_id' => $finProjectID,
                'account_id' => $accoundID,
                'is_planned' => 0,
                'comment' => __('added from task')
            ),
            'FinanceOperationHasCategory' => array ()
         );

        $percentAmount = $this->request->data('Operation.percent') * 0.01 * $this->request->data('Operation.income');

        $incomeData = $data;
        $expenseData = $data;
        $taxData = $data;
        $percentData = $data;

        $incomeData['FinanceOperation']['type'] = 0;
        $incomeData['FinanceOperation']['amount'] = $this->request->data('Operation.income');
        $incomeData['FinanceOperationHasCategory']['category_id'] = Hash::get($projectFinance, 'ProjectFinance.income_id');

        $expenseData['FinanceOperation']['type'] = 1;
        $expenseData['FinanceOperation']['amount'] = $this->request->data('Operation.expense');
        $expenseData['FinanceOperationHasCategory']['category_id'] = Hash::get($projectFinance, 'ProjectFinance.expense_id');

        $taxData['FinanceOperation']['type'] = 1;
        $taxData['FinanceOperation']['amount'] = $this->request->data('Operation.tax');
        $taxData['FinanceOperationHasCategory']['category_id'] = Hash::get($projectFinance, 'ProjectFinance.tax_id');

        $percentData['FinanceOperation']['type'] = 1;
        $percentData['FinanceOperation']['amount'] = $percentAmount;
        $percentData['FinanceOperationHasCategory']['category_id'] = Hash::get($projectFinance, 'ProjectFinance.percent_id');

        if($this->request->data('Operation.income')) {
            $this->FinanceOperation->addOperation($incomeData);
        }

        if($this->request->data('Operation.expense')) {
            $this->FinanceOperation->addOperation($expenseData);
        }

        if($this->request->data('Operation.tax')) {
            $this->FinanceOperation->addOperation($taxData);
        }

        if($this->request->data('Operation.percent')) {
            $this->FinanceOperation->addOperation($percentData);
        }

        return $this->redirect(array('action' => 'task', $task['Task']['id']));
    }

    public function closeTask($id) {
        $task = $this->Task->findById($id);
        $subproject = $this->Subproject->findById($task['Task']['subproject_id']);
        $project_id = $subproject['Subproject']['project_id'];
        $project = $this->Project->findById($subproject['Subproject']['project_id']);
        $responsibleUser = $this->ProjectMember->findAllByProjectIdAndUserIdAndIsResponsible($project_id, $this->currUserID, '1');

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && !$responsibleUser && !$isGroupAdmin)
        {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $project_id));
        }

        $this->Task->save(array('id' => $id, 'closed' => 1, 'close_date' => date('Y-m-d H:i:s')));
        $task = $this->Task->findById($id);
        $subproject = $this->Subproject->findById($task['Task']['subproject_id']);
        $this->ProjectEvent->addEvent(ProjectEvent::TASK_CLOSED, $subproject['Subproject']['project_id'], $this->currUserID, $this->Task->id);
        $this->redirect(array('action' => 'task', $id));
    }

    public function close($id) {
        $project = $this->Project->findById($id);
        $responsibleUser = $this->ProjectMember->findAllByProjectIdAndUserIdAndIsResponsible($id, $this->currUserID, '1');

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if(($this->currUserID != Hash::get($project, 'Project.owner_id'))  && !$responsibleUser && !$isGroupAdmin)
        {
            $this->redirect(array('controller' => 'Project', 'action' => 'view', $id));
        }

        $this->Project->close($this->currUserID, $id);
        $this->redirect(array('controller' => 'Group', 'action' => 'view', $project['Project']['group_id']));
    }

    public function addMember() {
        $project = $this->Project->findById($this->request->data('project_id'));
        $responsible = $this->ProjectMember->findByProjectIdAndIsResponsible($this->request->data('project_id'), 1);

        $group = $this->Project->getProjectGroup($project['Project']['id']);
        $isGroupAdmin = ($group['Group']['owner_id'] == $this->currUserID) || ($group['Group']['responsible_id'] == $this->currUserID);

        if ($this->currUserID != $project['Project']['owner_id'] && $this->currUserID != $responsible['ProjectMember']['user_id'] && !$isGroupAdmin) {
            $response = array('status' => 'ERROR', 'message' => __('Access denied'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        $this->autoRender = false;
        $saveArray = array();
        foreach( $this->request->data('users') as $key => $userId ) {
            $saveArray[$key] = array('user_id' => $userId, 'project_id' => $this->request->data('project_id'));
        }

        if( !$this->ProjectMember->saveAll($saveArray) ) {
            $response = array('status' => 'ERROR', 'message' => __('Error while saving members'));
            header('Content-Type: application/json');
            return json_encode($response);
        }
        $response = array('status' => 'OK');
        header('Content-Type: application/json');
        return json_encode($response);
        //$this->ProjectMember->save($this->request->data);
        // $this->ProjectEvent->addEvent(ProjectEvent::TASK_CREATED, $project_id, $this->currUserID, $this->Task->id);
        //$this->redirect(array('action' => 'view', $project_id));
    }

    public function editComment() {
        $this->autoRender = false;
        $event = $this->ProjectEvent->findByIdAndUserId($this->request->data('event_id'), $this->currUserID);

        if (!$event) {
            $response = array('status' => 'ERROR', 'data' => __('Error updaing event'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        $message = $this->ChatMessage->findById($event['ProjectEvent']['msg_id']);

        if (!$message) {
            $response = array('status' => 'ERROR', 'data' => __('Error updaing event'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        $message['ChatMessage']['message'] = $this->request->data('message');

        if (!$this->ChatMessage->save($message)) {
            $response = array('status' => 'ERROR', 'data' => __('Error cant save message'));
            header('Content-Type: application/json');
            return json_encode($response);
        }
        $response = array('status' => 'OK');
        header('Content-Type: application/json');
        return json_encode($response);
    }

    public function removeComment() {
        $this->autoRender = false;
        $event = $this->ProjectEvent->findByIdAndUserId($this->request->data('event_id'), $this->currUserID);

        if (!$event) {
            $response = array('status' => 'ERROR', 'data' => __('Error updaing event'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        $message = $this->ChatMessage->findById($event['ProjectEvent']['msg_id']);

        if (!$message) {
            $response = array('status' => 'ERROR', 'data' => __('Error updaing event'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        $files = $this->Media->getList(array('object_id' => $message['ChatMessage']['id'], 'object_type' => 'TaskComment'));
        if(count($files)) {
            foreach ($files as $file) {
                $file = $file['Media'];
                $this->Media->delete($file['id']);
            }
        }

        $message['ChatMessage']['message'] = $this->request->data('message');

        if (!$this->ChatMessage->delete($message['ChatMessage']['id'])) {
            $response = array('status' => 'ERROR', 'data' => __('Error deleting message'));
            header('Content-Type: application/json');
            return json_encode($response);
        }

        if (!$this->ProjectEvent->delete($this->request->data('event_id'))) {
            $response = array('status' => 'ERROR', 'data' => __('Error deleting event'));
            header('Content-Type: application/json');
            return json_encode($response);
        }
        $response = array('status' => 'OK');
        header('Content-Type: application/json');
        return json_encode($response);
    }
}
