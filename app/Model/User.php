<?php
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('UserAchievement', 'Model');
App::uses('GeoLocationBehavior', 'Model');
App::uses('Validation', 'Utility');

class User extends AppModel {

    public $hasOne = array(
        'GroupLimit' => array(
            'className' => 'GroupLimit',
            'foreignKey' => 'owner_id',
        ),
        'UserEventRequestLimit' => array(
            'className' => 'UserEventRequestLimit',
            'foreignKey' => 'user_id',
        ),
        'UserMedia' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('UserMedia.object_type' => 'User'),
            'dependent' => true
        ),
        'UniversityMedia' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('UniversityMedia.object_type' => 'UserUniversity'),
            'dependent' => true
        )
    );

    public $hasMany = array(
        'UserAchievement' => array(
            'order' => array('UserAchievement.id DESC'),
            'dependent' => true
        ),
        'BillingSubscriptions' => array(
            'className' => 'Billing.BillingSubscriptions',
            'foreignKey' => 'user_id',
            'conditions' => array('BillingSubscriptions.active' => true),
            'dependent' => true
        )
    );

    public $actsAs = array('Ratingable','Containable');

    public $validate = array(
        'username' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory',
            ),
            'checkEmail' => array(
                'rule' => 'email',
                'message' => 'Email is incorrect'
            ),
            'checkIsUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email has already been used'
            )
        ),
        'password' => array(
            'checkNotEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Field is mandatory'
            ),
            'checkPswLen' => array(
                'rule' => array('between', 4, 15),
                'message' => 'The password must be between 4 and 15 characters'
            ),
        ),
        'full_name' => array(
            'rule' => 'alphaNumericWhitespaceDashUnderscore',
            'message' => 'Full name can only be letters, numbers, whitespaces, dash and underscore'
        ),
        'video_url' => array(
            'isUrl' => array(
                'rule' => 'url',
                'allowEmpty' => true,
                'message' => 'Invalid video url. Leave blank or insert valid youtube url'
            )
        ),
        'profile_url' => array(
            'checkIsUnique' => array(
                'rule' => 'isUnique',
                'allowEmpty' => true,
                'message' => 'Already taken'
            )
        ),
    );

    protected $ChatEvent, $ChatRoom, $Group, $UserEvent, $GroupMember, $ProjectEvent, $Article, $ProjectMember, $Order, $OrderProduct, $Project, $Task;
/*
    public function matchPassword($data){
        if($data['password'] == $this->data['User']['password_confirm']){
            return true;
        }
        $this->invalidate('password_confirm', 'Your password and its confirmation do not match');
        return false;
    }
*/
/*
    public function beforeValidate($options = array()) {
        if (Hash::get($options, 'validate')) {
            if (!Hash::get($this->data, 'User.password')) {
                $this->validator()->remove('password');
                $this->validator()->remove('password_confirm');
            }
        }
    }
*/
    public function afterFind($results, $primary = false) {

        foreach($results as &$_row) {
            if (is_array($_row) && isset($_row[$this->alias])) { // почему то иногда создает массив данных без [User] :(
                $row = $_row[$this->alias];
                if (isset($row['username']) && isset($row['full_name'])) {
                    if (empty($row['full_name'])) {
                        $_row[$this->alias]['full_name'] = $row['username'];
                    }
                }
                if(isset($row['rating']) && !isset($row['rating_class'])){
                    //TODO: Ratingable behavior des not work here so hardcode rating styles
                    $style = '';
                    $rating = $row['rating'];
                    if(($rating >= 10)&&($rating < 20)){
                        $style = 'rating10';
                    } elseif(($rating >= 20)&&($rating < 30)){
                        $style = 'rating20';
                    } elseif(($rating >= 30)&&($rating < 40)){
                        $style = 'rating30';
                    } elseif(($rating >= 40)&&($rating < 50)){
                        $style = 'rating40';
                    } elseif(($rating >= 50)&&($rating < 60)){
                        $style = 'rating50';
                    } elseif(($rating >= 60)&&($rating < 70)){
                        $style = 'rating60';
                    } elseif(($rating >= 70)&&($rating < 80)){
                        $style = 'rating70';
                    } elseif(($rating >= 80)&&($rating < 90)){
                        $style = 'rating80';
                    } elseif(($rating >= 90)&&($rating < 100)){
                        $style = 'rating90';
                    } elseif($rating == 100){
                        $style = 'rating100';
                    }
                    $_row[$this->alias]['rating_class'] = 'thumb avatar '.$style;
                }
            }
        }
        return $results;
    }

    public function beforeSave($options = array()) {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }

    /* Untested feature */
    public function afterSave($created, $options = array()) {
        if ($created) {
            App::uses('GroupLimit', 'Model');
            $groupLimitModel = new GroupLimit();
            $groupLimitModel->save(array(
                'owner_id' => $this->data['User']['id'],
                'members_used' => 0,
                'members_limit' => 0,
            ));

            $this->bindEvent($this->data['User']);

            $user_id = $this->getLastInsertID();
            App::uses('Group', 'Model');
            $groupModel = new Group();
            $data = array(
                'Group' =>array(
                    'owner_id' => $user_id,
                    'title' => __('My Group'),
                    'hidden' => 1,
                    'cat_id' => 0
                ),
                'GroupAdministrator' =>array(
                    'role' => __('Administrator'),
                    'user_id' => $user_id,
                    'approved' => 1,
                    'sort_order' => 0,
                    'show_main' => 1,
                    'approve_date' => date('Y-m-d H:i:s'),
                )
            );
            $data['Group']['finance_project_id'] = $groupModel->addFinanceProject($data['Group'], $user_id, true);
            $groupModel->saveAssociated($data);
        }
        return true;
    }

    public function bindEvent($user) {
        $this->loadModel('Invitation');
        $invitation = new Invitation();

        $conditions = [
          'Invitation.email' => $user['username']
        ];
        $result = $invitation->find('first', compact('conditions'));
        if(!empty($result)) {

            $this->loadModel('UserEvent');
            $this->loadModel('UserEventShare');
            $userEvent = new UserEvent();
            $userEventShare = new UserEventShare();
            $event = $result['UserEvent'];
            $invitation = $result['Invitation'];

            $ueShare = array(
                'user_id' => $user['id'],
                'user_event_id' => $invitation['object_id'],
            );

            $event['recipient_id'] = $user['id'];
            $userEvent->create();
            $userEvent->set($event);

            $userEvent->save();
            $userEventShare->create();
            $userEventShare->set($ueShare);
            $userEventShare->save();
        }
    }

    public function getUser($id) {
        return $this->findByIdOrProfileUrl($id, $id);
    }

    public function getUsers($aID = array()) {
        $this->Behaviors->load('Containable');

        $aUsers = $this->find('all', array(
            'contain' => array('UserMedia', 'UniversityMedia', 'UserAchievement'),
            'conditions' => array('User.id' => $aID),
        ));
        return Hash::combine($aUsers, '{n}.User.id', '{n}');
    }
    public function getEventsUsers($aID = array()) {
        $this->Behaviors->load('Containable');
        $aUsers = $this->find('all', array(
            'fields' => array(
                'User.id', 'User.fbid', 'User.created', 'User.modified', 'User.is_admin',
                'User.is_confirmed', 'User.username', 'User.full_name', 'User.profile_url',
                'User.video_url', 'User.video_id', 'User.skills', 'User.interests', 'User.birthday',
                'User.lang', 'User.phone', 'User.live_place', 'User.live_address', 'User.university',
                'User.speciality', 'User.live_country', 'User.timezone', 'User.news_update',
                'User.last_update', 'User.karma', 'User.rating', 'UserMedia.*'
            ),
            'contain' => array('UserMedia'),
            'conditions' => array('User.id' => $aID),
        ));
        return Hash::combine($aUsers, '{n}.User.id', '{n}');
    }
    public function search($currUserID, $q, $limit = 20, $state = null, $locale = null) {
        $this->loadModel('Country');
        $this->loadModel('Synonym');

        if(mb_strlen($q) == 1) {
            $fields = 'User.id, User.username, User.full_name, User.skills, User.rating, UserMedia.*';
            $conditions = array(
                'User.id <> '.$currUserID,
                'OR' => array(
                    array('User.full_name LIKE ?' => $q.'%')
                )
            );
        } else {
            $conditions = array('OR' => array(
                array('Synonym.title' => $q),
                array('Synonym.variations LIKE ?' => '%'.$q.'|%')
            ));
            $synRec = $this->Synonym->find('first', array('conditions' => $conditions));
            if($synRec) {
                $synCheck = explode('|', $synRec['Synonym']['variations']);
                array_pop($synCheck);
                array_push($synCheck, $synRec['Synonym']['title']);

                $conditions = array('country_name' => $synCheck);
            } else {
                $conditions = array( 'country_name LIKE ?' => $q.'%' );
            }

            $fields = array('country_code', 'country_name');
            $countries = array_keys($this->Country->find('list', compact('fields', 'conditions')));
            if (!$countries) $countries = '';
            $fields = 'User.id, User.username, User.full_name, User.skills, User.rating, UserMedia.*';
            if(!($q == '@')) {
                if(!preg_match('/[A-Za-z]/', $q)) {
                    $t = $this->transliterateArray($q);

                    foreach($t as $term){
                        $sql[] = array('User.full_name LIKE ?' => $term.'%');
                        $sql[] = array('User.username LIKE ?' => $term.'%');
                        $sql[] = array('User.skills LIKE ?' => $term.'%');
                        $sql[] = array('User.live_place LIKE ?' => $term.'%');
                        $sql[] = array('User.full_name LIKE ?' => '% '.$term.'%');
                        $sql[] = array('User.username LIKE ?' => '% '.$term.'%');
                        $sql[] = array('User.skills LIKE ?' => '% '.$term.'%');
                        $sql[] = array('User.live_place LIKE ?' => '% '.$term.'%');
                    }

                    $sql[] = array('User.full_name LIKE ?' => '% '.$q.'%');
                    $sql[] = array('User.username LIKE ?' => '% '.$q.'%');
                    $sql[] = array('User.skills LIKE ?' => '% '.$q.'%');
                    $sql[] = array('User.live_place LIKE ?' => '% '.$q.'%');
                    $sql[] = array('User.full_name LIKE ?' => $q.'%');
                    $sql[] = array('User.username LIKE ?' => $q.'%');
                    $sql[] = array('User.skills LIKE ?' => $q.'%');
                    $sql[] = array('User.live_place LIKE ?' => $q.'%');

                    $conditions = array(
                        'User.id <> '.$currUserID,
                        'AND' => array(
                            'OR' => $sql
                        )
                    );
                } else {
                    $conditions = array(
                        'User.id <> '.$currUserID,
                        'AND' => array(
                            'OR' => array(
                                array('User.full_name LIKE ?' => '% '.$q.'%'),
                                array('User.username LIKE ?' => '% '.$q.'%'),
                                array('User.skills LIKE ?' => '% '.$q.'%'),
                                array('User.live_place LIKE ?' => '% '.$q.'%'),
                                array('User.full_name LIKE ?' => $q.'%'),
                                array('User.username LIKE ?' => $q.'%'),
                                array('User.skills LIKE ?' => $q.'%'),
                                array('User.live_place LIKE ?' => $q.'%')
                            )
                        )
                    );
                }
                if($countries) $conditions['AND']['OR'][] = array('User.live_country' => $countries);
            }
        }
        $conditions['AND'][] = array('User.is_deleted' => 0);
        if(!empty($locale)){
          $conditions['AND'][] = array(
            array(
              'User.lat >=' => $locale['minlat'],
              'User.lat <=' => $locale['maxlat'],
            ),
            array(
              'User.lng >=' => $locale['minlng'],
              'User.lng <=' => $locale['maxlng'],
            ),
          );
        }

        if($state == 'map') {
            $conditions['AND'][] = array(
                'not' => array(
                    'User.lat' => null,
                    'User.lng' => null
                )
            );
        }

        $order = array('User.full_name', 'User.username', 'User.skills');
        $aUsers = $this->find('all', compact('fields', 'conditions', 'limit'/*, 'order' => $order*/));

        if(empty($aUsers) && Validation::email($q)) {
            $aUsers[] = [
                'User' => [
                    'id' => null,
                    'img_url' => 'img/no-photo.jpg',
                    'name' => $q,
                ]
            ];
        }
        return $aUsers;
    }

    public function timelineEvents($currUserID, $date, $date2) {
        /*
        $fields = array('User.created', 'User.id');
        // $conditions = $this->dateRange('User.created', $date, $date2);
        $order = 'User.created DESC';
        $limit = 5;
        $recursive = -1;
        return $this->find('all', compact('fields', 'conditions', 'order', 'limit', 'recursive'));
        */
        $this->contain('UserMedia');
        $this->loadModel('Statistic');
        $this->loadModel('Skill');
        $fields = array(
            'User.id', 'User.fbid', 'User.created', 'User.modified', 'User.is_admin',
            'User.is_confirmed', 'User.username', 'User.full_name', 'User.profile_url',
            'User.video_url', 'User.video_id', 'User.skills', 'User.interests', 'User.birthday',
            'User.lang', 'User.phone', 'User.live_place', 'User.live_address', 'User.university',
            'User.speciality', 'User.live_country', 'User.timezone', 'User.news_update',
            'User.last_update', 'User.karma', 'User.rating', 'UserMedia.*'
        );

        //$user = $this->findById($currUserID);
        $this->Behaviors->load('Containable');
        $user = $this->find('first', array(
            'fields' => $fields,
            'contain' => array('UserMedia'),
            'conditions' => array('User.id' => $currUserID)
        ));
        $extractedSkills = explode(', ', Hash::get($user, 'User.skills'));
        $aUsers = array();
        //echo '<pre>';
        if(count($extractedSkills) > 0){
            $conditions = array(
              'User.id <> ?' => $currUserID
            );
            foreach( $extractedSkills as $skill ) {
                if(!empty($skill)){
                    $conditions['OR'][] = array( 'User.skills LIKE ?' => '%'.$skill.'%',);
                }
            }
            if(isset($conditions['OR'])&&!empty($conditions['OR'])){
                $this->Behaviors->load('Containable');
                $tmp = $this->find('all', array(
                    'fields' => $fields,
                    'contain' => array('UserMedia'),
                    'conditions' => $conditions,
                    'order' => 'User.id',
                ));
                $aUsers = array_merge($aUsers, $tmp);
            }
        }

      //  $aUsers = Hash::extract($aUsers, '{n}.User.id');
      //  $aUsers = array_combine($aUsers, $aUsers);
      //  $this->contain('UserMedia');
      //  $aUsers = $this->findAllById($aUsers);

        if($aUsers && count($aUsers)>1 ) {
            $aUsers = Hash::combine($aUsers, '{n}.User.id', '{n}');
            $UID = Hash::extract($aUsers, '{n}.User.id');

            $conditions = array(
                'Statistic.type' => 0,
                'Statistic.pk' => $UID,
                'Statistic.visitor_id <>' => $currUserID
            );

            $statData = $this->Statistic->find('all', compact('conditions') );
            if($statData) {
                $temp = array();

                foreach( $statData as $visit ) {
                    if( !isset( $aUsers[ $visit['Statistic']['pk'] ]['User'][ 'stat' ] ) ) $aUsers[ $visit['Statistic']['pk'] ]['User'][ 'stat' ] = 0;
                    $aUsers[ $visit['Statistic']['pk'] ]['User'][ 'stat' ]++;
                }

                foreach( $aUsers as &$user ) {
                    if( !isset( $user['User'][ 'stat' ] ) ) $user['User'][ 'stat' ] = 0;
                }
            }
            usort($aUsers, function ($a, $b) { return $b['User']['stat'] - $a['User']['stat']; });
        }

        $UID = Hash::extract($aUsers, '{n}.User.id');

        // вся статистика по группам
        $uStats = $this->Statistic->query('SELECT Stat.pk, Count.cnt
                                              FROM statistic  Stat
                                                   INNER JOIN (SELECT pk, count(pk) as cnt
                                                                 FROM statistic WHERE statistic.type = 0
                                                                GROUP BY pk) Count ON Stat.pk = Count.pk GROUP BY Stat.pk ORDER BY Count.cnt DESC');

        $uStats = array_slice($uStats, 0, 5);
        $uStats = Hash::extract($uStats, '{n}.Stat.pk' );
        $uStats = array_diff($uStats, $UID);
        $this->contain('UserMedia');

        $this->Behaviors->load('Containable');
        $uTop = $this->find('all', array(
            'fields' => $fields,
            'conditions' => array('User.id' => $uStats),
        ));
        $return = array_merge($aUsers, $uTop);
        return array_slice($return, 0, 5);
    }

    public function getTimeline($currUserID, $date = '', $date2 = '', $view = 0, $mail = false, $search = null) {
        if(!$search) {
            $currUser = $this->findById($currUserID);
            if (!$date) {
                $date = date('Y-m-d');
            }
            if (strtotime($date) < strtotime(Configure::read('Konstructor.created'))) {
                $date = Configure::read('Konstructor.created');
            }
            if (!$date2) {
                $date2 = date('Y-m-d', strtotime($date) + DAY * Configure::read('timeline.loadPeriod.normal'));
            }

            $aModels = array(
                'User' => 'last_users',
                'Group' => 'last_groups',
                'ChatEvent' => 'unread_msgs',
                'UserEvent' => 'user_events',
                'GroupMember' => 'group_member',
                'Share' => 'cloud_share',
                'VacancyResponse' => 'vacancy_response',
                'ProjectEvent' => 'project_events',
                'Article' => 'articles',
                'ArticleEvent' => 'article_events',
                'ProjectMember' => 'joined_projects',
                'Order' => 'created_orders',
                'OrderProduct' => 'given_devices'
            );
            $data = array();
            foreach($aModels as $model => $key) {
                $this->loadModel($model);
                $data[$key] = $this->{$model}->timelineEvents($currUserID, $date, $date2, $view, $mail, $search);
                // $data[$key] = array();
            }

            $this->loadModel('Statistic');
            $conditions = $this->dateTimeRange('Statistic.created', date("Y-m-d H:i:s", time() - 86400), date("Y-m-d H:i:s", time()));
            $conditions[] = array(
                'Statistic.type' => '0',
                    'Statistic.pk' => $currUserID,
                    'NOT' => array(
                        'Statistic.visitor_id' => $currUserID
                )
            );
            $data['stats'] = $this->Statistic->find('count', compact('conditions'));
			/** @var Article $article */
			$article = $this->Article;
            $data['last_articles'] = $article->lastArticles($currUserID, $date, $date2);
            $data['popular_articles'] = $article->popularArticles($currUserID);
//			var_dump($data['popular_articles']);die;
            if($currUser !== array())
            $conditions = array('Article.created <?' => $currUser['User']['created'], 'Article.deleted' => '0');

            $data['counters']['articles'] = $article->find('count', compact('conditions'));

            //Get group invites
            $conditions = array(
                'GroupMember.is_invited' => '1',
                'GroupMember.user_id' => $currUserID
            );
            $data['invites'] = $this->GroupMember->find('all', compact('conditions'));

            $data['group_join_requests'] = $this->Group->find('all', array(
                'contain' => array(
                    'GroupMember' => array(
                        'conditions' => array(
                            'GroupMember.is_deleted' => '0',
                            'GroupMember.is_invited' => '0',
                            'GroupMember.approved' => '0',
                        ),
                        'fields' => array('id','user_id','group_id')
                    )
                ),
                'conditions' => array(
                    'Group.owner_id' => $currUserID,
                ),
                'fields' => array('id'),
            ));
            $data['group_join_requests'] = Hash::extract($data['group_join_requests'],'{n}.GroupMember.{n}');

            $myGroups = $this->GroupMember->getTimelineUserGroups($currUserID);

            // Get joined groups data
            $aID = array_merge(
                Hash::extract($data['last_groups'], '{n}.Group.id'),
                Hash::extract($data['articles'], '{n}.Article.group_id'),
                Hash::extract($myGroups, '{n}.GroupMember.group_id'),
                Hash::extract($data['last_articles'], '{n}.Article.group_id'),
                Hash::extract($data['invites'], '{n}.GroupMemper.group_id'),
                Hash::extract($data['group_join_requests'], '{n}.GroupMemper.group_id')
            );

            $group_list = $this->Group->find('all', array(
                'contain' => array(
                    'GroupMedia' => array(
                        'fields' => array('id','object_id','orig_fname','ext','file','media_type','orig_fsize','object_type','orig_w','orig_h')
                    ),
                    'GroupMember' => array(
                        'conditions' => array(
                            'GroupMember.is_deleted' => '0',
                            'GroupMember.approved' => '1',
                        ),
                        'fields' => array('id','group_id','user_id')
                    ),
                ),
                'conditions' => array(
                    'Group.id' => $aID,
                ),
                //'fields' => array('id','owner_id','title','descr'),
            ));

            $data['groups'] = Hash::combine($group_list, '{n}.Group.id', '{n}');

            $members = Hash::combine($group_list, '{n}.Group.id', '{n}.GroupMember' );
            $data['group_members'] = array();
            foreach($members as $group_id => $member) {
                $data['group_members'][$group_id] = Hash::extract($member, '{n}.user_id');
            }
            $conditions = array('Group.created <?' => $currUser['User']['created'] );
            $data['counters']['groups'] = $this->Group->find('count', compact('conditions'));

            // Get users data ("vocabluary" array(ID => data))
            //always current user presence in users list

            $aID = array($currUserID);
            $aID = array_merge(
                $aID,
                Hash::extract($data['last_users'], '{n}.User.id'),
                Hash::extract($data['unread_msgs'], '{n}.ChatEvent.initiator_id'),
                Hash::extract($data['unread_msgs'], '{n}.ChatEvent.recipient_id'),
                Hash::extract($data['group_member']['request'], '{n}.GroupMember.user_id'),
                Hash::extract($data['project_events'], '{n}.ProjectEvent.user_id'),
                Hash::extract($data['articles'], '{n}.Article.owner_id'),
                Hash::extract($data['joined_projects'], '{n}.ProjectMember.user_id'),
                Hash::extract($data['groups'], '{n}.Group.owner_id'),
                Hash::extract($data['last_articles'], '{n}.Article.owner_id'),
                Hash::extract($data['popular_articles'], '{n}.Article.owner_id'),
                Hash::extract($data['user_events'], '{n}.UserEvent.user_id'),
                Hash::extract($data['article_events'], '{n}.ArticleEvent.user_id'),
                Hash::extract($data['group_join_requests'], '{n}.user_id')
            );
            foreach($data['user_events'] as $eventData) {
                $ueIDs = explode(',', Hash::get($eventData, 'UserEvent.recipient_id'));
                $aID = array_merge($aID, $ueIDs);
            }

            $data['users'] = $this->getEventsUsers($aID);
            $data['users'] = Hash::combine($data['users'], '{n}.User.id', '{n}');

            // Get messages data
            $this->loadModel('ChatMessage');
            $aID = array_merge(
                Hash::extract($data['unread_msgs'], '{n}.ChatEvent.msg_id'),
                Hash::extract($data['project_events'], '{n}.ProjectEvent.msg_id'),
                Hash::extract($data['article_events'], '{n}.ArticleEvent.msg_id')
            );
            $data['messages'] = $this->ChatMessage->findAllById($aID);
            $data['messages'] = Hash::combine($data['messages'], '{n}.ChatMessage.id', '{n}.ChatMessage');

            // Get Media data
            $this->loadModel('Media.Media');
            $aTaskID = Hash::extract($data['project_events'], '{n}.ProjectEvent.msg_id');
            $multipleTaskMedia = array();
            if(!empty($aTaskID)){
                $conditions = array(
                    'object_type' => 'TaskComment',
                    'object_id' => $aTaskID
                );
                $multipleTaskMedia = $this->Media->find('all', array('conditions' => $conditions));
            }

            $aID = array_merge(
                Hash::extract($data['unread_msgs'], '{n}.ChatEvent.file_id'),
                Hash::extract($data['project_events'], '{n}.ProjectEvent.file_id'),
                Hash::extract($multipleTaskMedia, '{n}.Media.id')
            );
            $this->loadModel('MediaFile');
            $data['files'] = $this->MediaFile->getList(array('id' => $aID), 'Media.id');
            $data['files'] = Hash::combine($data['files'], '{n}.Media.id', '{n}.Media');

            $this->loadModel('Project');
            $aID = array_merge(
                Hash::extract($data['project_events'], '{n}.ProjectEvent.project_id'),
                Hash::extract($data['joined_projects'], '{n}.ProjectMember.project_id')
            );

            $this->Project->bindModel(
                array('hasMany' => array(
                        'ProjectMember' => array(
                            'className' => 'ProjectMember',
                            'foreignKey' => 'project_id',
                        )
                    )
                )
            );
            $projects = $this->Project->find('all',array(
                'conditions' => array(
                    'Project.id' => $aID,
                ),
            ));
            $data['projects'] = Hash::combine($projects, '{n}.Project.id', '{n}');

            $members = Hash::combine($projects, '{n}.Project.id', '{n}.ProjectMember' );
            $data['project_members'] = array();
            foreach($members as $group_id => $member) {
                $data['project_members'][$group_id] = Hash::extract($member, '{n}.user_id');
            }

            if($currUser !== array())
            $conditions = array('Project.created < ?' => $currUser['User']['created'] );
            $data['counters']['projects'] = $this->Project->find('count', compact('conditions'));

            $this->loadModel('Task');
            $this->loadModel('Subproject');
            $this->Task->bindModel(
                array('belongsTo' => array(
                        'Subproject' => array(
                            'className' => 'Subproject',
                            'foreignKey' => 'subproject_id',
                        )
                    )
                )
            );

            $tasks_id = Hash::extract($data['project_events'], '{n}.ProjectEvent.task_id');
            $tasks = $this->Task->find('all', array(
                'conditions' => array(
                    'Task.id' => $tasks_id,
                ),
                //'fields' => array('id','subproject_id','title','user_id'),
            ));
            $TIDS = Hash::extract($tasks, '{n}.Subproject.project_id');
            $this->Project->bindModel(
                    array('belongsTo' => array(
                            'Group' => array(
                                'className' => 'Group',
                                'foreignKey' => 'group_id',
                            )
                        )
                    )
                );
            $pIDs = $this->Project->find('all',array(
                'conditions' => array(
                    'Project.id' => $TIDS,
                )
            ));

            $tGroups = Hash::combine($pIDs, '{n}.Project.id', '{n}.Group');
            $data['tasks'] = Hash::combine($tasks, '{n}.Task.id', '{n}');

            $this->loadModel('Subproject');
            foreach($data['tasks'] as &$task) {
                //$task['Task']['Group'] = Hash::extract($this->Subproject->getSubprojectGroup($task['Task']['subproject_id']), 'Group');
                $task['Task']['Group'] = $tGroups[$task['Subproject']['project_id']];
            }

            $this->loadModel('ProductType');
            $data['productTypes'] = $this->ProductType->options();

            // Sort all sortable events by time creation

            $this->loadModel('ChatRoom');
            $data['rooms'] = $this->ChatRoom->findAllById( Hash::extract($data['unread_msgs'], '{n}.ChatEvent.room_id') );
            $data['rooms'] = Hash::combine($data['rooms'], '{n}.ChatRoom.id', '{n}');


            // get groups
            $gid = array_merge(
                Hash::extract($data['vacancy_response'], '{n}.VacancyResponse.group_id'),
                Hash::extract( $data['rooms'], '{n}.ChatRoom.group_id' )
            );
            $groups = $this->Group->find('all', array(
                'contain' => array(
                    'GroupMedia' => array(
                        'fields' => array('id','object_id','orig_fname','ext','file','media_type','orig_fsize','object_type','orig_w','orig_h')
                    ),
                    'GroupMember' => array(
                        'conditions' => array(
                            'GroupMember.is_deleted' => '0',
                            'GroupMember.approved' => '1',
                        ),
                        'fields' => array('id','group_id','user_id')
                    )
                ),
                'conditions' => array(
                    'Group.id' => $gid,
                ),

                //'fields' => array('id')
            ));

            $groups = Hash::combine($groups, '{n}.Group.id', '{n}');
            $data['groups'] = array_merge( $data['groups'], $groups );
            $data['groups'] = Hash::combine($data['groups'], '{n}.Group.id', '{n}');

            $aResponses = array();
            foreach($data['vacancy_response'] as $response) {
                if($response['VacancyResponse']['approve'] == 0) {
                    $aResponses[$response['VacancyResponse']['created']] = $response;
                } else {
                    $aResponses[$response['VacancyResponse']['modified']] = $response;
                }
            }

            $data['unread_msgs'] = Hash::combine($data['unread_msgs'], '{n}.ChatEvent.created', '{n}');
            $data['user_events'] = Hash::combine($data['user_events'], '{n}.UserEvent.event_time', '{n}');
            $data['project_events'] = Hash::combine($data['project_events'], '{n}.ProjectEvent.created', '{n}');
            $data['joined_groups'] = Hash::combine($data['group_member']['joined'], '{n}.GroupMember.approve_date', '{n}');
            $data['joined_projects'] = Hash::combine($data['joined_projects'], '{n}.ProjectMember.created', '{n}');
            $data['articles'] = Hash::combine($data['articles'], '{n}.Article.created', '{n}');
            $data['cloud_share'] = Hash::combine($data['cloud_share'], '{n}.Share.created', '{n}');
            $data['article_events'] = Hash::combine($data['article_events'], '{n}.ArticleEvent.created', '{n}');
            $data['created_orders'] = Hash::combine($data['created_orders'], '{n}.Order.created', '{n}');
            $data['given_devices'] = Hash::combine($data['given_devices'], '{n}.OrderProduct.distrib_date', '{n}');
            $data['events'] = Hash::merge(
                $data['unread_msgs'],
                $data['user_events'],
                $data['project_events'],
                $data['joined_groups'],
                $data['joined_projects'],
                $data['articles'],
                $data['cloud_share'],
                $data['article_events'],
                $data['created_orders'],
                $data['given_devices'],
                $aResponses
            );

            // remove already unused data
            unset($data['unread_msgs']);
            unset($data['user_events']);
            unset($data['project_events']);
            unset($data['group_member']);
            unset($data['article_events']);
            unset($data['created_orders']);
            unset($data['given_devices']);
            unset($data['vacancy_response']);

            //total messages
            if($currUser !== array())
            $conditions = array('ChatEvent.event_type' => 1, 'ChatEvent.created < ?' => $currUser['User']['created'] );
            $data['counters']['messages'] = $this->ChatEvent->find('count', compact('conditions'));

            // -= Special events =-
            // Self-registration
            $conditions = array_merge(
                array('User.id' => $currUserID),
                $this->dateRange('User.created', $date, $date2)
            );
            $user = $this->User->find('first', array(
                'conditions' => $conditions,
                'recursive' => -1,
                'fields' => array('created')
            ));
            if ($user) {
                $created = $user['User']['created'];
                $data['events'][$created]['SelfRegistration'] = array(
                    'created' => $created,
                );
            }

            $created = Configure::read('Konstructor.created');
            if (strtotime($date) <= strtotime($created) && strtotime($created) <= strtotime($date2)) {
                $data['events'][$created]['KonstructorCreation'] = array(
                    'created' => $created,
                );
            }
            krsort($data['events']);

            if($view == 0 || $view == null) {
                // Group events by day
                $data['view_state'] = 'day';
                $data['days'] = array();
                foreach($data['events'] as $datetime => $event) {
                    $_date = strtotime($datetime);
                    $data['days'][date('Y-m-d', $_date)][date('H', $_date)][] = $datetime;
                }
            } else if($view == 1) {
                // Group events by day
                $data['view_state'] = 'week';
                $data['days'] = array();
                foreach($data['events'] as $datetime => $event) {
                    $_date = strtotime($datetime);
                    $data['days'][date('Y-m-d', $_date)][] = $datetime;
                }
            } else if($view == 2) {
                //TODO группировка по месяцам
                $data['view_state'] = 'month';
                $data['months'] = array();
                foreach($data['events'] as $datetime => $event) {
                    $_date = strtotime($datetime);
                    $data['months'][date('Y-m', $_date)][] = $datetime;
                }
            } else if($view == 3) {
                //TODO сортировка по годам
                $data['view_state'] = 'year';
                $data['years'] = array();
                foreach($data['events'] as $datetime => $event) {
                    $_date = strtotime($datetime);
                    $data['years'][date('Y', $_date)][] = $datetime;
                }
            }
        } else {
            $this->loadModel('User');
            $this->loadModel('Group');
            $this->loadModel('Article');
            $this->loadModel('Cloud');

            $data['search_users'] = $this->User->search($currUserID, $search);
            $data['search_groups'] = $this->Group->search($currUserID, $search);
            $data['search_articles'] = $this->Article->search($search, $currUserID);
            $data['search_files'] = $this->Cloud->searchByName($search, $currUserID);
        }

        return $data;
    }

    //TODO: Maybe not needed anymore notifications for profile incompleteness
    /*
    public function checkData($currUserID) {
        $user = $this->findById($currUserID);
        if (Hash::get($user, 'User.full_name') && Hash::get($user, 'User.skills') && Hash::get($user, 'User.live_country')
            && Hash::get($user, 'User.live_place') && (($birthday = Hash::get($user, 'User.birthday')) && ($birthday != '0000-00-00'))
        ) {
            return true;
        }
        return false;
    }
    */

    public function getBalance($userID) {
        $user = $this->findById($userID);
        $balance = Hash::get($user, 'User.balance');
        return ($user && $balance) ? $balance : 0;
    }

}
