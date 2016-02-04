<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
class GroupAjaxController extends PAjaxController {
    public $name = 'GroupAjax';

    public $components = array('MembersLimit');

    public $helpers = array('Media');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_checkAuth();
    }

    public function jsSettings() {
    }

    public function panel() {
        $this->loadModel('Group');
        $this->loadModel('GroupMember');

        $aInvites = null;

        $this->request->data('q', htmlspecialchars( $this->request->data('q') ));
        $q = $this->request->data('q');
        if ($q) {
            $aGroups = $this->Group->search($this->currUserID, $q);
        } else {
            $aGroups = $this->GroupMember->getUserGroups($this->currUserID, 0, 1);

            $conditions = array('GroupMember.is_invited' => '1');
            $aID = Hash::extract($this->GroupMember->findAllByUserIdAndIsInvited($this->currUserID, 1), '{n}.GroupMember.group_id');
            $conditions = array('Group.id' => $aID);
            $aInvites = $this->Group->find('all', compact('conditions'));
        }

        foreach($aGroups as &$group) {
            $group['Group']['membersCount'] = $group['Group']['active_members'];
        }

        if($aInvites) {
            foreach($aInvites as &$group) {
                $id = $group['Group']['id'];
                $conditions = array('GroupMember.group_id' => $id, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
                $group['Group']['membersCount'] = $this->GroupMember->find('count', compact('conditions'));
            }
        }

        $this->set('aGroups', $aGroups);
        $this->set('aInvites', $aInvites);
    }

    public function getGallery() {
        try {
            $group_id = $this->request->data('group_id');
            if (!$group_id) {
                throw new Exception('Incorrect request');
            }
            $this->loadModel('Media.Media');
            $this->loadModel('GroupVideo');

            $images = $this->Media->getList(array('object_type' => 'GroupGallery', 'object_id' => $group_id), array('Media.id' => 'DESC'));
            $videos = $this->GroupVideo->findAllByGroupId($group_id);
            $this->setResponse(compact('videos', 'images'));
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function addGalleryVideo() {
        try {
            $group_id = $this->request->data('group_id');
            if (!$group_id) {
                throw new Exception('Incorrect request');
            }

            $url = $this->request->data('url');
            if (!$url) {
                throw new Exception('Incorrect request');
            } else if (!(strpos($url, 'youtube.com') === 0 || strpos($url, 'www.youtube.com') === 0
                    || strpos($url, 'http://youtube.com') === 0 || strpos($url, 'http://www.youtube.com') === 0
                    || strpos($url, 'https://youtube.com') === 0 || strpos($url, 'https://www.youtube.com') === 0)) {
                throw new Exception('Only youtube.com is allowed');
            }

            $this->loadModel('GroupVideo');
            $this->request->data('video_id', str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $url));
            $this->GroupVideo->save($this->request->data);

            $this->getGallery();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

	/**
	 * Save uploaded video in Group profile
	 */
	public function saveVideo() {
		$this->loadModel('MediaFile');
		$this->loadModel('Media');
		$this->loadModel('GroupVideo');

		try {
			$media_id = $this->request->data('media_id');
			$group_id = $this->request->data('group_id');
			$groupVideo = [
				"GroupVideo" => [
					"media_id" => $media_id,
					"group_id" => $group_id,
					"user_id" => $this->currUserID
				]];
			$this->GroupVideo->save($groupVideo);
			$this->setResponse($this->request->data);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
	}

	/**
	 * Delete Group video
	 */
    public function delGalleryVideo() {
        try {
            $media_id = $this->request->data('media_id');
            $group_id = $this->request->data('group_id');
            if (!$group_id) {
                throw new Exception('Incorrect request');
            }

            $id = $this->request->data('id');
            if (!$id) {
                throw new Exception('Incorrect request');
            }

            $this->loadModel('GroupVideo');
            $groupVideo = $this->GroupVideo->findById($id);
            if (!$groupVideo) {
                throw new Exception('Incorrect group video ID');
            }
            $this->GroupVideo->deleteVideo($id, $group_id, $media_id);

            $this->getGallery();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function join() {
        try {
            $this->loadModel('GroupMember');
            $member = $this->GroupMember->findByGroupIdAndUserId(
                $this->request->data('group_id'),
                $this->request->data('user_id')
            );

            if($member) {
                $this->request->data['id'] = Hash::get($member, 'GroupMember.id');
                $this->request->data['approved'] = '0';
                $this->request->data['is_deleted'] = '0';
            }

            $this->GroupMember->save($this->request->data);
            $this->setResponse();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function invite() {
        if(!$this->MembersLimit->canAddMember($this->request->data('group_id'))){
            throw new Exception(__('Members limit reached. Please by more members'));
        }
        try {
            $this->loadModel('GroupMember');

            $groupID = $this->request->data['group_id'];
            $userID = $this->request->data['user_id'];

            $member = $this->GroupMember->findByGroupIdAndUserId($groupID, $userID);
            if($member) {
                  $this->request->data['id'] =  $member['GroupMember']['id'];
            }
            $this->request->data['is_invited'] = '1';
            $this->GroupMember->save($this->request->data);
            $this->setResponse();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function acceptInvite() {

        /*if(!$this->MembersLimit->canAddMember($this->request->data('id'))){
            throw new Exception(__('Unable to process your invitation. Possible members limit reached for this group. Please try later'));
        }*/

        try {
            $this->loadModel('GroupMember');
            $id = $this->request->data['id'];

            if($id) {

                $invite = $this->GroupMember->findByGroupIdAndUserId($id, $this->currUserID);
                if(!$invite) {
                    throw new Exception('No invite found');
                }
                $invite['GroupMember']['is_invited'] = 0;
                $invite['GroupMember']['is_deleted'] = 0;
                $invite['GroupMember']['sort_order'] = 1;
                $invite['GroupMember']['approved'] = 1;
                $invite['GroupMember']['show_main'] = 1;
                $invite['GroupMember']['approve_date'] = date('Y-m-d H:i:s');

                $this->GroupMember->save($invite['GroupMember']);
                $this->setResponse('done');
            } else {
                throw new Exception('No id found');
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function declineInvite() {
        try {
            $id = $this->request->data['id'];
            $this->loadModel('GroupMember');
            if($id) {
                $invite = $this->GroupMember->findByGroupIdAndUserId($id, $this->currUserID);
                if(!$invite) {
                    throw new Exception('No invite found');
                }
                $this->GroupMember->delete($invite['GroupMember']);
                $this->setResponse('done');
            } else {
                throw new Exception('No id found');
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function memberApprove() {
        if(!$this->MembersLimit->canAddMember($this->request->data('group_id'))){
            $this->response->statusCode(403);
            echo json_encode(array(
                "name" => __('Unable to process your approval. Possible members limit reached for this group. Please try later'),
                "message" => __('Unable to process your approval. Possible members limit reached for this group. Please try later'),
                "url" => Router::url(array('action' => 'memberApprove', 'ext' => 'json')),
            ));
            return $this->response;
            //throw new CakeException(__('Unable to process your approval. Possible members limit reached for this group. Please try later'), 400);
        }
        try {
            $group_id = $this->request->data['group_id'];
            $user_id = $this->request->data['user_id'];
            $role = $this->request->data['role'];
            $this->loadModel('GroupMember');
            if($group_id && $user_id) {
                $invite = $this->GroupMember->findByGroupIdAndUserId($group_id, $user_id);
                if(!$invite) {
                    throw new CakeException('No join data found');
                }
                $invite['GroupMember']['role'] = htmlspecialchars( $role );
                $invite['GroupMember']['show_main'] = 1;
                $invite['GroupMember']['approved'] = 1;
                $invite['GroupMember']['approve_date'] = date('Y-m-d H:i:s');
                $this->GroupMember->save($invite['GroupMember']);
                $this->setResponse('done');
            } else {
                throw new CakeException('Insufficient data');
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function setDream() {
        if($this->request->is('ajax')) {
            if(!empty($this->request->data('group_id')) && !is_null($this->request->data('mark')))  {
                if(is_numeric($this->request->data('group_id'))) {

                    $this->Group->create();
                    $this->Group->set(
                        array(
                            'id' => $this->request->data('group_id'),
                            'is_dream' => $this->request->data('mark')
                        )
                    );
                    if($this->Group->save()){
                        echo json_encode(array('success' => true));
                    }
                    else {
                        echo json_encode(array('success' => false));
                    }
                }
            }
        }
        die();
    }

    public function memberRemove() {
        try {
            $group_id = $this->request->data['group_id'];
            $user_id = $this->request->data['user_id'];
            $this->loadModel('GroupMember');
            if($group_id && $user_id) {
                $invite = $this->GroupMember->findByGroupIdAndUserId($group_id, $user_id);
                if(!$invite) {
                    throw new Exception('No join data found');
                }
                $invite['GroupMember']['is_deleted'] = 1;
                $invite['GroupMember']['show_main'] = 0;
                $this->GroupMember->save($invite['GroupMember']);
                $this->setResponse('done');
            } else {
                throw new Exception('Insufficient data');
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function checkLimits($groupId = null){
        $response = $this->MembersLimit->canAddMember($groupId);
        echo json_encode(array('allowed' => $response));
        exit();
    }

    /*
    //deprecated
    protected function canAddMember($groupId = null){
        $response = false;
        if(!empty($groupId)){
            $this->loadModel('GroupLimit');
            $this->GroupLimit->recursive = -1;
            $limit = $this->GroupLimit->find('first', array(
                'conditions' => array('GroupLimit.owner_id' => $this->currUserID)
            ));
            $this->loadModel('Group');
            $this->Group->recursive = -1;
            $groups = $this->Group->findAllByOwnerId($this->currUserID);
            $currentGroup = null; $totalPaidMembers = 0;
            foreach($groups as $group){
                if($group['Group']['id'] ==$groupId){
                    $currentGroup = $group;
                }
                if($group['Group']['active_members'] - 5 > 0){
                    $totalPaidMembers += $group['Group']['active_members'] - 5;
                }
            }
            //Autocorrection used members count for group limits
            if($totalPaidMembers != $limit['GroupLimit']['members_used']){
                $this->GroupLimit->id = $limit['GroupLimit']['id'];
                $this->GroupLimit->saveField('members_used', $totalPaidMembers, array(
                    'validate' => false,
                    'callbacks' => false,
                    'counterCache' => false,
                ));
            }
            if($currentGroup['Group']['active_members'] <= 4){
                //if group contain less than 5 member it allow add 5 member
                $response = true;
            } else {
                //if group already contain 5 members we need to check paid members qty availabe
                if(!empty($limit)&&($totalPaidMembers < $limit['GroupLimit']['members_limit'])){
                    $response = true;
                }
            }
        }
        return $response;
    }
    */

    //Работа с вакансиями

    public function vacancyResponse() {
        try {
            $this->loadModel('VacancyResponse');
            $this->loadModel('GroupVacancy');

            $vacancy = $this->GroupVacancy->findById( $this->request->data['vacancy_id'] );

            if($vacancy) {
                $response = array(
                    'user_id' => $this->currUserID,
                    'vacancy_id' => $this->request->data['vacancy_id'],
                    'approve' => '0'
                );
            } else {
                throw new Exception('Vacancy data not found');
            }

            $this->VacancyResponse->save($response);
            $this->setResponse();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function vacancyApprove() {
        try {
            $this->loadModel('VacancyResponse');
            $this->loadModel('GroupVacancy');
            $this->loadModel('Group');

            $conditions = array(
                'VacancyResponse.id' => $this->request->data['response_id']
            );
            $response = $this->VacancyResponse->find('first', compact('conditions'));
            if($response) {
                $vacancy = $this->GroupVacancy->findById( $response['VacancyResponse']['vacancy_id'] );
                if($vacancy) {
                    $group = $this->Group->findById( $vacancy['GroupVacancy']['group_id'] );
                    if( $group['Group']['owner_id'] == $this->currUserID/* && $vacancy['GroupVacancy']['open'] */ ) {
                        $response['VacancyResponse']['approve'] = '1';
                        $response['VacancyResponse']['modified'] = date('Y-m-d H:i:s');
                        $this->VacancyResponse->save($response);
                        $this->setResponse();
                    } else {
                        throw new Exception('Group data not found');
                    }
                } else {
                    throw new Exception('Vacancy data not found');
                }
            } else {
                throw new Exception('Vacancy response data not found');
            }
            $this->setResponse($response);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function vacancyDecline() {
        try {
            $this->loadModel('VacancyResponse');
            $this->loadModel('GroupVacancy');
            $this->loadModel('Group');

            $conditions = array(
                'VacancyResponse.id' => $this->request->data['response_id']
            );
            $response = $this->VacancyResponse->find('first', compact('conditions'));
            if($response) {
                $vacancy = $this->GroupVacancy->findById( $response['VacancyResponse']['vacancy_id'] );
                if($vacancy) {
                    $group = $this->Group->findById( $vacancy['GroupVacancy']['group_id'] );
                    if( $group['Group']['owner_id'] == $this->currUserID/* && $vacancy['GroupVacancy']['open'] */ ) {
                        $response['VacancyResponse']['approve'] = '-1';
                        $response['VacancyResponse']['modified'] = date('Y-m-d H:i:s');
                        $this->VacancyResponse->save($response);
                        $this->setResponse();
                    } else {
                        throw new Exception('Group data not found');
                    }
                } else {
                    throw new Exception('Vacancy data not found');
                }
            } else {
                throw new Exception('Vacancy response data not found');
            }
            $this->setResponse($response);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function vacancyResponses() {
        $this->loadModel('Group');
        $this->loadModel('GroupVacancy');
        $this->loadModel('VacancyResponse');
        $this->loadModel('GroupMember');
        $this->loadModel('Country');
        $this->loadModel('User');

        $id = $this->request->data('id');

        $this->Group->unbindModel(
                array('hasMany' => array('GroupAchievement','GroupVideo','GroupGallery'))
        );
        $this->VacancyResponse->bindModel(
            array(
                'belongsTo' => array(
                    'GroupVacancy' => array(
                        'className' => 'GroupVacancy',
                        'foreignKey' => 'vacancy_id',
                        'fields' => 'id, title'
                    ),
                )
            )
        );

        $group = $this->Group->findById($id);
        if( !$group ) {
            throw new NotFoundException();
        }
        $isGroupAdmin = Hash::get($group, 'Group.owner_id') == $this->currUserID;
        $isGroupResponsible = Hash::get($group, 'Group.responsible_id') == $this->currUserID;
        $conditions = array(
//            'GroupVacancy.open' => '1',
            'GroupVacancy.group_id' => $id
        );
//        if( $isGroupAdmin || $isGroupResponsible ) { $conditions['GroupVacancy.open'] = '0'; }
        $aVacancies = $this->GroupVacancy->find('all', compact('conditions'));
        $this->set('isGroupAdmin', $isGroupAdmin);
        $this->set('isGroupResponsible', $isGroupResponsible);

        $aMembers = $this->GroupMember->getMainList($id);
        $aID = Hash::extract($aMembers, '{n}.GroupMember.user_id');
        $isMember = in_array($this->currUserID, $aID);

        $aID = Hash::extract($aVacancies, '{n}.GroupVacancy.id');
        if( $isGroupAdmin || $isGroupResponsible ) {
            $conditions = [
                'VacancyResponse.vacancy_id' => $aID
            ];
            //VacancyResponse.approve GroupVacancy.title
            $order = array('VacancyResponse.approve DESC', 'GroupVacancy.title');
            $vacancy_responses = $this->VacancyResponse->find('all', compact('conditions', 'order'));

            $aResponses = [];
            foreach($vacancy_responses as $id => $response) {
                switch($response['VacancyResponse']['approve']) {
                    case -1:
                        $aResponses['rejected'][] = $response;
                        break;
                    case 0:
                        $aResponses['pending'][] = $response;
                        break;
                    case 1:
                        $aResponses['approved'][] = $response;
                        break;
                }

            }
            $aUsers = $this->User->findAllById( Hash::extract($vacancy_responses, '{n}.VacancyResponse.user_id') );
            $this->set('aResponsedUsers', Hash::combine($aUsers, '{n}.User.id', '{n}'));

        }
        else if( !($isGroupAdmin || $isGroupResponsible) && !$isMember ) {
            $aResponses = $this->VacancyResponse->findAllByVacancyIdAndUserId($aID, $this->currUserID);
            $aResponseCheck = Hash::combine($aResponses, '{n}.VacancyResponse.vacancy_id', '{n}.VacancyResponse');
        }

        $this->set('aVacancies', Hash::combine($aVacancies, '{n}.GroupVacancy.id', '{n}'));
        $this->set('aResponses', $aResponses);
    }

    public function dreamStats($groupID = null) {

        try {
            $this->loadModel('Group');
            $this->loadModel('Project');
            $this->loadModel('Subproject');
            $this->loadModel('Task');

            // потом отделить в функцию getGroupTasks
            $group = $this->Group->findById($groupID);
            $closedTasks = $this->Project->findAllByGroupId( Hash::get($group, 'Group.id') );
            $subprojects = $this->Subproject->findAllByProjectId( Hash::extract($closedTasks, '{n}.Project.id') );
            $maximum = $this->Task->find('count', array('conditions' => array('subproject_id' => Hash::extract($subprojects, '{n}.Subproject.id'))));
            $closedTasks = $this->Task->find('all', array(
                'conditions' => array(
                    'Task.subproject_id' => Hash::extract($subprojects, '{n}.Subproject.id'),
                    'Task.closed' => 1,
                    'NOT' => array(
                        'Task.close_date' => null
                    )
                ),
                'order' => array('Task.close_date ASC')
            ));

            $initialClosedCount = $this->Task->find('count', array(
                'conditions' => array(
                    'Task.subproject_id' => Hash::extract($subprojects, '{n}.Subproject.id'),
                    'Task.closed' => 1,
                    'Task.close_date' => null
                )
            ));

            $dreamState = array();
            $prevConut = $initialClosedCount;

            $dreamState[ date('Y-m-d', strtotime($group['Group']['created'])) ] = 1;

            if($initialClosedCount > 0 && (strtotime($group['Group']['created']) < strtotime('2015-08-01'))) {
                $dreamState['2015-08-01'] = $initialClosedCount;
            }
            foreach($closedTasks as $task) {
                $prevConut++;
                $dreamState[ date('Y-m-d', strtotime($task['Task']['close_date'])) ] = $prevConut+1;
            }


            $this->setResponse(array(
                'state' => json_encode($dreamState),
                'count' => $maximum,
                'title' => $group['Group']['title'],
                'logo' => str_replace('noresize', 'thumb50x50', $group['GroupMedia']['url_img'])
            ));
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function groupDetails($id) {
        try {
            $this->loadModel('Group');
            $this->loadModel('GroupMember');
            $this->loadModel('Project');
            $this->loadModel('ProjectEvent');
            $this->loadModel('Subbproject');
            $this->loadModel('Task');
            $this->loadModel('User');
            $this->loadModel('Article');

            $renderList = array();

            $this->Group->unbindModel(
                    array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
                );
            $group = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));

            if($group['Group']['owner_id'] != $this->currUserID) {
                throw new Exception('Access denied');
            }

            $renderList[substr($group['Group']['created'], 0, 10)][] = array('date' => $group['Group']['created'], 'type' => 'group');

            // Получение членов группы
            $this->GroupMember->unbindModel(
                    array('belongsTo' => array('Group'))
                );

            $members = $this->GroupMember->find('all', array(
                'conditions' => array(
                    'GroupMember.group_id' => $group['Group']['id'],
                    'GroupMember.is_deleted' => 0,
                    'GroupMember.approved' => 1
                ),
                'fields' => array('GroupMember.id', 'GroupMember.user_id', 'GroupMember.approve_date', 'GroupMember.role'),
                'order' => array('GroupMember.approve_date DESC')
            ));

            $this->User->unbindModel(
                    array(
                        'hasOne' => array('GroupLimit', 'UniversityMedia'),
                        'hasMany' => array('UserAchievement', 'BillingSubscriptions'),
                    )
                );
            $users = $this->User->find('all', array(
                'conditions' => array(
                    'User.id' => Hash::extract($members, '{n}.GroupMember.user_id')
                )
            ));
            $users = Hash::combine($users, '{n}.User.id', '{n}');

            foreach($members as &$member) {
                $mid = $member['GroupMember']['user_id'];
                $member['GroupMember']['full_name'] = $users[$mid]['User']['full_name'];
                $member['GroupMember']['skills'] = $users[$mid]['User']['skills'];
                $member['GroupMember']['img_url'] = $users[$mid]['UserMedia']['url_img'];

                $renderList[substr($member['GroupMember']['approve_date'], 0, 10)][] = array('date' => $member['GroupMember']['approve_date'], 'type' => 'member', 'id' => $mid);
                $member = $member['GroupMember'];
            }
            $members = Hash::combine($members, '{n}.user_id', '{n}');

            // Получение проектов
            $pst = $this->Group->getGroupComponentsID($id);

            $this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
            $projects = $this->Project->find('all', array(
                'conditions' => array('Project.id' => $pst['project']),
                'fields' => array('id', 'title', 'descr', 'closed', 'created')
            ));

            foreach($projects as &$project) {
                $lastEvent = $this->ProjectEvent->find('first', array(
                    'conditions' => array('ProjectEvent.project_id' => $project['Project']['id']),
                    'order' => array('id' => 'DESC')
                ));

                $renderList[substr($lastEvent['ProjectEvent']['created'], 0, 10)][] = array('date' => $lastEvent['ProjectEvent']['created'], 'type' => 'project', 'id' => $project['Project']['id']);
                $project = $project['Project'];
                $project['last_update'] = $lastEvent['ProjectEvent']['created'];
            }
            $projects = Hash::combine($projects, '{n}.id', '{n}');

            // Получение статей
            $articles = $this->Article->find('all', array(
                'conditions' => array('group_id' => $id, 'deleted' => 0),
                //'fields' => array('Article.id', 'Article.title', 'Article.published', 'Article.created', 'ArticleMedia.url_img')
            ));

            foreach($articles as &$article) {
                //$articles['Artile']['img_url'] = $articles['Artile']['img_url']
                $article = array(
                    'id' => Hash::get($article, 'Article.id'),
                    'title' => Hash::get($article, 'Article.title'),
                    'published' => Hash::get($article, 'Article.published'),
                    'created' => Hash::get($article, 'Article.created'),
                    'url_img' => Hash::get($article, 'ArticleMedia.url_img')
                );
                $renderList[substr($article['created'], 0, 10)][] = array('date' => $article['created'], 'type' => 'article', 'id' => $article['id']);
            }
            $articles = Hash::combine($articles, '{n}.id', '{n}');

            krsort($renderList);
            $response = array(
                'Group' => $group,
                'Members' => $members,
                'Projects' => $projects,
                'Articles' => $articles,
                'Render_list' => $renderList
            );

            $this->setResponse($response);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function projectDetails($id) {
        try {
            $this->loadModel('Group');
            $this->loadModel('Project');
            $this->loadModel('ProjectEvent');
            $this->loadModel('Task');

            $renderList = array();

            $taskLastEvents = $this->ProjectEvent->find('all', array(
                'conditions' => array(
                    'ProjectEvent.project_id' => $id,
                    'NOT' => array('ProjectEvent.task_id' => null)
                ),
                //'group' => 'ProjectEvent.task_id',
                'order' => array('ProjectEvent.created ASC')
            ));

            $aID = Hash::extract($taskLastEvents, '{n}.ProjectEvent.task_id');
            $aTasks = $this->Task->find('all', array('conditions' => array('Task.id' => $aID)));
            $aTasks = Hash::combine($aTasks, '{n}.Task.id', '{n}.Task');

            foreach($taskLastEvents as &$event){
                $event = $event['ProjectEvent'];
                $event['title'] = $aTasks[$event['task_id']]['title'];
            };
            $taskLastEvents = Hash::combine($taskLastEvents, '{n}.task_id', '{n}');

            $this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
            $response['project'] = $this->Project->findById($id);

            $this->Group->unbindModel(
                    array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
                );
            $response['group'] = $this->Group->findById($response['project']['Project']['group_id']);
            $response['task-list'] = $taskLastEvents;

            if($response['group']['Group']['owner_id'] != $this->currUserID) {
                throw new Exception('Access denied');
            }

            foreach($response['task-list'] as $task){
                $renderList[substr($task['created'], 0, 10)][] = array('date' => $task['created'], 'type' => 'task', 'id' => $task['task_id']);
            };

            krsort($renderList);
            $response['Render_list'] = $renderList;

            $this->setResponse($response);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function taskDetails($id) {
        try {
            $this->loadModel('Group');
            $this->loadModel('Project');
            $this->loadModel('Subproject');
            $this->loadModel('ProjectEvent');
            $this->loadModel('Task');
            $this->loadModel('Media');
            $this->loadModel('ChatMessage');
            $this->loadModel('User');

            //отключение связанных данных от моделей
            $this->Project->unbindModel(array('hasOne' => array('ProjectFinance')));
            $this->Group->unbindModel(
                    array('hasMany' => array('GroupMember', 'GroupAchievement', 'GroupAddress', 'GroupGallery', 'GroupVideo'))
                );
            $this->User->unbindModel(
                array(
                    'hasMany' => array('BillingSubscriptions', 'UserAchievement'),
                    'hasOne' => array('GroupLimit', 'UniversityMedia')
                )
            );

            $renderList = array();

            $this->Task->unbindModel(array('hasOne' => array('CrmTask')));
            $task = $this->Task->findById($id);

            $project = $this->Subproject->findById($task['Task']['subproject_id']);
            $project = $this->Project->findById($project['Subproject']['project_id']);
            $group = $this->Group->findById($project['Project']['group_id']);

            if($group['Group']['owner_id'] != $this->currUserID) {
                throw new Exception('Access denied');
            }

            $aTaskEvents = $this->ProjectEvent->findAllByTaskId($task['Task']['id']);
            $aTaskEvents = Hash::combine($aTaskEvents, '{n}.ProjectEvent.id', '{n}.ProjectEvent');

            foreach($aTaskEvents as &$event) {
                if(!is_null($event['file_id'])) {
                    $event['media'] = $this->Media->findById($event['file_id']);

                    $renderList[substr($event['created'], 0, 10)][] = array(
                        'date' => $event['created'],
                        'type' => 'file',
                        'id' => $event['id'],
                        'own' => $event['user_id'] == $this->currUserID
                    );
                } else if(!is_null($event['msg_id'])) {
                    $msg = $this->ChatMessage->findById($event['msg_id']);

                    $conditions = array(
                        'Media.object_type' => 'TaskComment',
                        'Media.object_id' => $msg['ChatMessage']['id']
                    );
                    $aMedia = $this->Media->find('all', compact('conditions'));
                    if($aMedia) {
                        $tmp = array();
                        foreach($aMedia as $media) {
                            $tmp[] = $media['Media'];
                        }
                        $event['media'] = $tmp;
                    }

                    if($msg['ChatMessage']['message'] != "&nbsp;") {
                        $event['message'] = $msg['ChatMessage'];
                        $type = $aMedia ? "file_comment" : "comment";

                        $renderList[substr($event['created'], 0, 10)][] = array(
                            'date' => $event['created'],
                            'type' => $type,
                            'id' => $event['id'],
                            'own' => $event['user_id'] == $this->currUserID
                        );
                    } else {
                        $event['message'] = $msg['ChatMessage'];
                        $renderList[substr($event['created'], 0, 10)][] = array(
                            'date' => $event['created'],
                            'type' => 'file',
                            'id' => $event['id'],
                            'own' => $event['user_id'] == $this->currUserID
                        );
                    }
                } else if($event['event_type'] == 3) {
                    $renderList[substr($event['created'], 0, 10)][] = array(
                        'date' => $event['created'],
                        'type' => 'open',
                        'own' => $event['user_id'] == $this->currUserID
                    );
                } else if($event['event_type'] == 6) {
                    $renderList[substr($event['created'], 0, 10)][] = array(
                        'date' => $event['created'],
                        'type' => 'close',
                        'own' => $event['user_id'] == $this->currUserID
                    );
                }
            }

            $aUsers = Hash::extract($aTaskEvents, '{n}.user_id');

            $aUsers = $this->User->findAllById($aUsers);
            $aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}');

            $response['group'] = $group;
            $response['project'] = $project;
            $response['task'] = $task;
            $response['events'] = $aTaskEvents;
            $response['users'] = $aUsers;
            krsort($renderList);
            $response['Render_list'] = $renderList;
            $this->setResponse($response);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

}
