<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class GroupController extends SiteController {
    public $name = 'Group';

    public $layout = 'profile_new';

    public $uses = array('Group', 'GroupMember', 'GroupCategory', 'ProjectMember', 'Lang');

    public $components = array('MembersLimit');

    public $helpers = array('Media', 'Redactor.Redactor');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('view', 'addresses'));
        if( in_array($this->action, array('view', 'members', 'edit', 'vacancies')) && isset($this->passedArgs[0]) ) {
            $group = $this->Group->findById($this->passedArgs[0]);
            if($group) {
                $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
                $isGroupResponsible = (Hash::get($group, 'Group.responsible_id') == $this->currUserID) && (Hash::get($group, 'Group.responsible_id') != null);
                $this->set('isGroupAdmin', $isGroupAdmin);
                $this->set('isGroupResponsible', $isGroupResponsible);
            }
        }
    }

    public function edit($id = 0) {
        $this->loadModel('Country');
        $group = $this->Group->findById($id);
        $groupAdmin = $this->GroupMember->findByGroupIdAndUserId($id, Hash::get($group, 'Group.owner_id'));
//        echo "<pre>";
//        var_dump($groupAdmin);
//        die;

        $aMainCountries = $this->Country->getMainCountries();
        $this->set('aMainCountries', $aMainCountries);
        $this->set('aAllCountries', $this->Country->options());
        $this->set('aGroupCategories', $this->GroupCategory->getCategoriesList());
        $this->set('aMembers', $this->GroupMember->getGroupMembersFormatted($id, $this->currUserID));

        if ($id && Hash::get($group, 'Group.owner_id') != $this->currUserID) {
            return $this->redirect(array('controller' => 'Group', 'action' => 'view', $id));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data('Group.owner_id', $this->currUserID);
            $this->request->data('Group.hidden', $this->request->data('Group.hidden') && true);
            if ($id) {
                $this->loadModel('GroupAchievement');
                $this->GroupAchievement->deleteAll(array('group_id' => $id));
            }
            if ($this->request->data('GroupAchievement')) {
                foreach($this->request->data('GroupAchievement') as $i => $data) {
                    $url = $this->request->data('GroupAchievement.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('GroupAchievement.'.$i.'.url', $url);
                }
            }

            if ($id) {
                $this->loadModel('GroupAddress');
                $this->GroupAddress->deleteAll(array('group_id' => $id));
            }
            if ($this->request->data('GroupAddress')) {
                foreach($this->request->data('GroupAddress') as $i => $data) {
                    $url = $this->request->data('GroupAddress.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('GroupAddress.'.$i.'.url', $url);
                }
            }

            //Создание/корректировка проекта в фин.менеджере
            $fpID = $this->request->data('Group.finance_project_id');
            if(!$id) {
                $fpID = $this->Group->addFinanceProject( $this->request->data('Group'), $this->currUserID, $this->request->data('Group.hide_finance') );
                $this->request->data('Group.finance_project_id', $fpID);
            } else if($id && $fpID) {
                $fpID = $this->Group->updateFinanceProject( $this->request->data('Group'), $this->currUserID, !$this->request->data('Group.hide_finance') );
                $this->request->data('Group.finance_project_id', $fpID);
            }

            if (!$id) {
                $this->request->data('GroupAdministrator.user_id', $this->currUserID);
                if(empty($this->request->data('GroupAdministrator.role'))){
                    $this->request->data('GroupAdministrator.role', __('Administrator'));
                }
                $this->request->data('GroupAdministrator.approved', 1);
                $this->request->data('GroupAdministrator.sort_order', 0);
                $this->request->data('GroupAdministrator.show_main', 1);
                $this->request->data('GroupAdministrator.approve_date', date('Y-m-d H:i:s'));
            }
            if( $this->Group->saveAll($this->request->data) ) {
                return $this->redirect(array('controller' => $this->name, 'action' => 'view', $this->Group->id));
            }
        } else {
            $this->request->data = array_merge($group, $groupAdmin);

            if (!$id) {
                $this->request->data('GroupAdministrator.role', __('Administrator'));
            } else {
                $this->loadModel('FinanceProject');
                $fpHidden = $this->FinanceProject->findById(Hash::get($group, 'Group.finance_project_id'));

                $fpHidden = Hash::get($fpHidden, 'FinanceProject.hidden');

                $this->request->data('Group.hide_finance', !$fpHidden);
            }
        }
    }

    public function addresses($id = 0) {
        $this->loadModel('Country');
        $this->Group->unbindModel(
                array('hasMany' => array('GroupAchievement','GroupVideo','GroupGallery'))
        );
        $group = $this->Group->findById($id);

        $this->set('countryNames', $this->Country->options());

        $this->set('group', $group);

        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = (Hash::get($group, 'Group.responsible_id') == $this->currUserID) && (Hash::get($group, 'Group.responsible_id') != null);
        $this->set('isGroupAdmin', $isGroupAdmin);
        $this->set('isGroupResponsible', $isGroupResponsible);

        $aMembers = $this->GroupMember->getMainList($id);
        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
        $this->set('isGroupMember', in_array($this->currUserID, $aID));

        $conditions = array('group_id' => $id, 'user_id' => $this->currUserID);
        $joined = $this->GroupMember->find('first', compact('conditions'));
        $this->set('joined', $joined);
    }

    public function delete($id) {
        $this->autoRender = false;

        $group = $this->Group->findById($id);
        if ($id && Hash::get($group, 'Group.owner_id') != $this->currUserID) {
            return $this->redirect(array('controller' => 'Group', 'action' => 'view', $id));
        }

        $this->Group->delete($id);
        $this->redirect(array('controller' => 'User', 'action' => 'view'));
    }

    public function all() {
        $aGroups = $this->Group->findAllByHidden(0);
        foreach($aGroups as &$group) {
            $group_id = $group['Group']['id'];
            // $aGroupMembers[$group_id] = Hash::extract($this->GroupMember->getList($group_id), '{n}.GroupMember.user_id');
            $group['Group']['members'] = count(Hash::extract($this->GroupMember->getList($group_id, null, 0), '{n}.GroupMember.user_id'));
        }
        $this->set('aGroups', $aGroups);
    }

    public function view($id) {
        if(!$this->Auth->loggedIn()) {
            Configure::write('Config.language', $this->Lang->detect());
        }

        $this->loadModel('Project');
        $this->loadModel('Article');
        $this->loadModel('ArticleCategory');
        $this->loadModel('Subscription');
        $this->loadModel('Country');
        $this->loadModel('GroupVacancy');

        $group = $this->Group->findByIdOrGroupUrl($id, $id);
        if(empty($group)) {
            throw new NotFoundException();
        }

        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = (Hash::get($group, 'Group.responsible_id') == $this->currUserID) && (Hash::get($group, 'Group.responsible_id') != null);
        $id = Hash::get($group, 'Group.id');
        $this->set('isGroupAdmin', $isGroupAdmin);
        $this->set('isGroupResponsible', $isGroupResponsible);

        if($isGroupAdmin) {
            setcookie('Group', $id, time()+60*60*24*61, '/');
        }

        $title = __('Group').': '.$group['Group']['title'];
        $this->set(compact('title'));

        $aMembers = $this->GroupMember->getList($id, null, 0, 1);

        if(!in_array($this->currUserID, Hash::extract($aMembers, '{n}.GroupMember.user_id')) && $group['Group']['hidden'] == 1) {
            throw new NotFoundException();
        }

        $fpID = $group['Group']['finance_project_id'];
        if(!$fpID) {
            $fpID = $this->Group->addFinanceProject( $group['Group'], $group['Group']['owner_id'] );
            $group['Group']['finance_project_id'] = $fpID;
            $this->Group->save($group);
        }

        $this->set('group', $group);
        $conditions = array('group_id' => $id, 'user_id' => $this->currUserID, 'is_deleted' => '0');
        $joined = $this->GroupMember->find('first', compact('conditions'));
        $this->set('joined', $joined);

        $aMembers = $this->GroupMember->getMainList($id);
        $this->set('aMembers', $aMembers);
        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');// array_keys($aMembers);

        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);
        $this->set('isGroupMember', in_array($this->currUserID, $aID));

        $conditions = array(
            'Project.group_id' => $id,
            'Project.closed' => '0',
            'Project.deleted' => '0'
        );
        $aProjects = $this->Project->find('all', compact('conditions'));
        $this->set('aProjects', $aProjects);

        $aProjectMembers = array();
        foreach($aProjects as $project) {
            $project_id = $project['Project']['id'];
            $aProjectMembers[$project_id] = Hash::extract($this->ProjectMember->getList($project_id), '{n}.ProjectMember.user_id');
        }
        $this->set('aProjectMembers', $aProjectMembers);

        $conditions = array(
            'Article.group_id' => $id,
            'Article.published' => '1',
            'Article.deleted' => '0',
        );
        $order = 'Article.created DESC';

        $aArticles = $this->Article->find('all', compact('conditions', 'order'));
        $this->set('aArticles', $aArticles);
        $this->set('aCategoryOptions', $this->ArticleCategory->options());
        $this->set('countryNames', $this->Country->options());

        $conditions = array(
            'GroupVacancy.open' => '1',
            'GroupVacancy.group_id' => $id
        );
        $aVacancies = $this->GroupVacancy->find('count', compact('conditions'));

        $this->set('isVacancies', $aVacancies > 0);
        $this->set('subscription', $this->Subscription->findByTypeAndObjectIdAndSubscriberId('group', $id, $this->currUserID));

        // group settings element
        $group = $this->Group->findById($id);
        $groupAdmin = $this->GroupMember->findByGroupIdAndUserId($id, Hash::get($group, 'Group.owner_id'));

        $aMainCountries = $this->Country->getMainCountries();
        $this->set('eMainCountries', $aMainCountries);
        $this->set('eAllCountries', $this->Country->options());
        $this->set('eGroupCategories', $this->GroupCategory->getCategoriesList());
        $this->set('eMembers', $this->GroupMember->getGroupMembersFormatted($id, $this->currUserID));

		$this->loadModel('GroupVideo');
		/** @var UserVideo $video */
		$video = $this->GroupVideo;
		$groupVideos = $video->findMedia($id);
		$this->set('groupVideos', $groupVideos);


        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data('Group.owner_id', $this->currUserID);
            $this->request->data('Group.hidden', $this->request->data('Group.hidden') && true);
            if ($id) {
                $this->loadModel('GroupAchievement');
                $this->GroupAchievement->deleteAll(array('group_id' => $id));
            }
            if ($this->request->data('GroupAchievement')) {
                foreach($this->request->data('GroupAchievement') as $i => $data) {
                    $url = $this->request->data('GroupAchievement.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('GroupAchievement.'.$i.'.url', $url);
                }
            }

            if ($id) {
                $this->loadModel('GroupAddress');
                $this->GroupAddress->deleteAll(array('group_id' => $id));
            }
            if ($this->request->data('GroupAddress')) {
                foreach($this->request->data('GroupAddress') as $i => $data) {
                    $url = $this->request->data('GroupAddress.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('GroupAddress.'.$i.'.url', $url);
                }
            }

            //Создание/корректировка проекта в фин.менеджере
            $fpID = $this->request->data('Group.finance_project_id');
            if(!$id) {
                $fpID = $this->Group->addFinanceProject( $this->request->data('Group'), $this->currUserID, $this->request->data('Group.hide_finance') );
                $this->request->data('Group.finance_project_id', $fpID);
            } else if($id && $fpID) {
                $fpID = $this->Group->updateFinanceProject( $this->request->data('Group'), $this->currUserID, !$this->request->data('Group.hide_finance') );
                $this->request->data('Group.finance_project_id', $fpID);
            }

            if (!$id) {
                $this->request->data('GroupAdministrator.user_id', $this->currUserID);
                if(empty($this->request->data('GroupAdministrator.role'))){
                    $this->request->data('GroupAdministrator.role', __('Administrator'));
                }
                $this->request->data('GroupAdministrator.approved', 1);
                $this->request->data('GroupAdministrator.sort_order', 0);
                $this->request->data('GroupAdministrator.show_main', 1);
                $this->request->data('GroupAdministrator.approve_date', date('Y-m-d H:i:s'));
            }
            if( $this->Group->saveAll($this->request->data) ) {
                return $this->redirect(array('controller' => $this->name, 'action' => 'view', $this->Group->id));
            }
        }

        else {
            $this->request->data = array_merge($group, $groupAdmin);

            if (!$id) {
                $this->request->data('GroupAdministrator.role', __('Administrator'));
            } else {
                $this->loadModel('FinanceProject');
                $fpHidden = $this->FinanceProject->findById(Hash::get($group, 'Group.finance_project_id'));

                $fpHidden = Hash::get($fpHidden, 'FinanceProject.hidden');

                $this->request->data('Group.hide_finance', !$fpHidden);
            }
        }



    }

    public function members($id) {
        //$this->layout = 'profile';

        $group = $this->Group->findById($id);
        $this->set('group', $group);

        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = Hash::get($group, 'Group.responsible_id') == $this->currUserID;

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data('action') == 'edit_main') {
                $this->GroupMember->saveAll($this->request->data('GroupMember'));
                if($isGroupAdmin) {
                    foreach($this->request->data('GroupMember') as $member){
                        if($member['responsible']) {
                            $this->Group->setResponsible($id, $member['uid']);
                        }
                    }
                }
            } else {
                if(!$this->MembersLimit->canAddMember($id)){
                    $this->redirect(array('action' => 'members', $id));
                }
                $this->request->data('GroupMember.approved', 1);
                $this->request->data('GroupMember.approve_date', date('Y-m-d H:i:s'));
                $this->GroupMember->save($this->request->data);
            }
            return $this->redirect(array('action' => 'members', $id));
        }

        $title = $group['Group']['title'].': '.__('members');
        $this->set(compact('title'));

        $conditions = array('GroupMember.group_id' => $id, 'GroupMember.is_deleted' => 0);
        $order = array('GroupMember.approved', 'GroupMember.sort_order', 'GroupMember.created');
        $aMembers = $this->GroupMember->find('all', compact('conditions', 'order'));
        $this->set('aMembers', $aMembers);

        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
        $aUsers = $this->User->getUsers($aID);
        $this->set('aUsers', $aUsers);

        if( !isset($aUsers[$this->currUserID]) && !$isGroupAdmin ) {
            return $this->redirect(array('action' => 'view', $id));
        }
    }

    public function vacancies($group_id) {
        $this->loadModel('GroupVacancy');
        $this->loadModel('VacancyResponse');
        $this->loadModel('GroupMember');
        $this->loadModel('Country');
        $this->loadModel('User');
        $this->Group->unbindModel(
                array('hasMany' => array('GroupAchievement','GroupVideo','GroupGallery'))
        );
        $group = $this->Group->findById($group_id);
        if( !$group ) {
            throw new NotFoundException();
        }

        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = Hash::get($group, 'Group.responsible_id') == $this->currUserID;

        $loggedIn = $this->currUserID ? true : false;
        $this->set(compact('loggedIn'));

        $conditions = array(
//            'GroupVacancy.open' => '1',
            'GroupVacancy.group_id' => $group_id
        );
//        if( $isGroupAdmin || $isGroupResponsible ) { $conditions['GroupVacancy.open'] = '0'; }
        $aVacancies = $this->GroupVacancy->find('all', compact('conditions'));
        $this->set('group', $group);
        $this->set('countryNames', $this->Country->options());

        $aMembers = $this->GroupMember->getMainList($group_id);
        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
        $isMember = in_array($this->currUserID, $aID);
        $this->set('isMember', $isMember);
        $this->set('groupID', $group_id);

        $aID = Hash::extract($aVacancies, '{n}.GroupVacancy.id');
        $aResponses = array();
        if( $isGroupAdmin || $isGroupResponsible ) {
            $aResponses = $this->VacancyResponse->findAllByVacancyId($aID);
            $aUsers = $this->User->findAllById( Hash::extract($aResponses, '{n}.VacancyResponse.user_id') );
            $this->set('aResponsedUsers', Hash::combine($aUsers, '{n}.User.id', '{n}'));
        } else if( !($isGroupAdmin || $isGroupResponsible) && !$isMember ) {
            $aResponses = $this->VacancyResponse->findAllByVacancyIdAndUserId($aID, $this->currUserID);
            $aResponseCheck = Hash::combine($aResponses, '{n}.VacancyResponse.vacancy_id', '{n}.VacancyResponse');
        }

        $data = array();
        foreach($aVacancies as $vacancy) {
            if( !($isGroupAdmin || $isGroupResponsible) && !$isMember && isset($aResponseCheck[$vacancy['GroupVacancy']['id']]) ) {
                $vacancy['GroupVacancy']['approve'] = $aResponseCheck[$vacancy['GroupVacancy']['id']]['approve'];
            }
            $data[$vacancy['GroupVacancy']['country']][] = $vacancy;
        }
        $logo = isset($group['GroupMedia']['url_img'])? $group['GroupMedia']['url_img'] : null;

        $this->set(compact('group'));
        $this->set('aVacancies', Hash::combine($aVacancies, '{n}.GroupVacancy.id', '{n}'));
        $this->set('data', $data);
        $this->set('aResponses', $aResponses);
    }

    public function addVacancy($id) {
        $this->loadModel('GroupVacancy');
        $this->Group->unbindModel(
            array('hasMany' => array('GroupAchievement','GroupVideo','GroupGallery'))
        );
        $group = $this->Group->findById($id);
        if( !$group ) {
            throw new NotFoundException();
        }
        $isGroupAdmin = (Hash::get($group, 'Group.owner_id') == $this->currUserID);
        $isGroupResponsible = (Hash::get($group, 'Group.responsible_id') == $this->currUserID);
        if(!($isGroupAdmin || $isGroupResponsible)) {
            throw new NotFoundException();
        }
        $this->request->data('GroupVacancy.group_id', $id);
        $this->GroupVacancy->save( $this->request->data );
        return $this->redirect(array('action' => 'vacancies', $id));
    }

    public function removeVacancy($id) {
        $this->loadModel('GroupVacancy');
        $this->loadModel('VacancyResponse');
        $vacancy = $this->GroupVacancy->findById($id);
        $id = $vacancy['GroupVacancy']['group_id'];
        $this->Group->unbindModel(
            array('hasMany' => array('GroupAchievement','GroupVideo','GroupGallery'))
        );
        $group = $this->Group->findById($id);
        if( !$group ) {
            throw new NotFoundException();
        }

        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = Hash::get($group, 'Group.responsible_id') == $this->currUserID;

        if( !($isGroupAdmin || $isGroupResponsible) ) {
            throw new NotFoundException();
        }

        $aResponses = $this->VacancyResponse->findAllByVacancyId($vacancy['GroupVacancy']['id']);
        foreach($aResponses as $response) {
            $this->VacancyResponse->clear();
            $this->VacancyResponse->delete($response['VacancyResponse']['id']);
        }
        $this->GroupVacancy->delete( $vacancy['GroupVacancy']['id'] );
        return $this->redirect(array('action' => 'vacancies', $id));
    }

    public function memberApprove($group_id, $user_id) {
        $this->autoRender = false;
        $member = $this->GroupMember->findByGroupIdAndUserId($group_id, $user_id);
        if(!$this->canAddMember($group_id)){
            $this->redirect(array('action' => 'members', $group_id));
        }
        $this->GroupMember->save(array(
            'id' => $member['GroupMember']['id'],
            'approved' => 1
        ));
        $this->redirect(array('action' => 'members', $group_id));
    }

    public function memberRemove($group_id, $user_id) {
        $this->autoRender = false;
        $member = $this->GroupMember->findByGroupIdAndUserId($group_id, $user_id);
        $this->GroupMember->save(array('id' => $member['GroupMember']['id'], 'is_deleted' => 1, 'show_main' => 0));
        $this->redirect(array('action' => 'members', $group_id));
    }

    public function addSubscription($object_id) {
        $this->loadModel('Subscription');

        $subscriber_id = $this->currUserID;
        $type = 'group';

        $data = compact('type', 'object_id', 'subscriber_id');

        $this->Subscription->save($data);
        $this->redirect($this->referer());
    }

    public function deleteSubscription($id) {
        $this->loadModel('Subscription');

        $this->Subscription->delete($id);
        $this->redirect($this->referer());
    }

    public function addArticle($group_id) {
        $this->layout = 'profile';
        $group = $this->Group->findById($group_id);
        if ($this->currUserID !=Hash::get($group, 'Group.owner_id')) {
            return $this->redirect(array('controller' => 'Group', 'action' => 'view', $group_id));
        }

        $this->loadModel('Article');
        $this->loadModel('ArticleCategory');

        if ($this->request->is(array('post', 'put'))) {
            $this->request->data('Article.owner_id', $this->currUserID);
            $this->request->data('Article.group_id', $group_id);
            if ($this->Article->save($this->request->data)) {
                return $this->redirect(array('controller' => 'Article', 'action' => 'view', $this->Article->id));
            }
        }

        $aCategoryOptions = $this->ArticleCategory->options();
        unset($aCategoryOptions[0]);
        $this->set('aCategoryOptions', $aCategoryOptions);
    }

    public function acceptInvite($id = 0) {
        $this->loadModel('GroupMember');
        if($id) {
            $invite = $this->GroupMember->findByGroupIdAndUserId($id, $this->currUserID);
            $invite['GroupMember']['is_invited'] = 0;
            $invite['GroupMember']['is_deleted'] = 0;
            $invite['GroupMember']['sort_order'] = 1;
            $invite['GroupMember']['approved'] = 1;
            $invite['GroupMember']['show_main'] = 1;
            $invite['GroupMember']['approve_date'] = date('Y-m-d H:i:s');
            $this->GroupMember->save($invite['GroupMember']);
        }
        $this->redirect($this->referer());
    }

    public function declineInvite($id = 0) {
        $this->loadModel('GroupMember');
        if($id) {
            $invite = $this->GroupMember->findByGroupIdAndUserId($id, $this->currUserID);
            $this->GroupMember->delete($invite['GroupMember']);
        }
        $this->redirect($this->referer());
    }
}
