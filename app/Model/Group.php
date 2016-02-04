<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('GroupAddress', 'Model');
App::uses('GroupAchievement', 'Model');
App::uses('GroupVideo', 'Model');
class Group extends AppModel {

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id'
		)
	);

    public $hasOne = array(
        'GroupMedia' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('GroupMedia.object_type' => 'Group'),
            'dependent' => true
        ),
        'GroupAdministrator' => array(
            'className' => 'GroupMember',
            'foreignKey' => 'group_id',
            'conditions' => array('Group.owner_id = GroupAdministrator.user_id'),
        ),
        'InvestProject' => array(
            'className' => 'InvestProject',
            'foreignKey' => 'group_id',
        ),
    );

    public $hasMany = array(
        'GroupAddress' => array(
            'order' => array('GroupAddress.head_office DESC'),
            'dependent' => true
        ),
        'GroupAchievement' => array(
            'order' => array('GroupAchievement.id DESC'),
            'dependent' => true
        ),
        'GroupMember' => array(
            'className' => 'GroupMember',
            'foreignKey' => 'group_id',
        ),
        'GroupGallery' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array('GroupGallery.object_type' => 'GroupGallery'),
            'dependent' => true,
            'order' => array('GroupGallery.id' => 'DESC')
        ),
        'GroupVideo' => array(
            'dependent' => true
        )
    );

    public $actsAs = array(
        'MembersLimitable',
        'Ratingable' =>array(
            'createdOnly' => false,
        ),
        'Containable'
    );

    public $validate = array(
        'title' => array(
            'rule' => 'alphaNumericWhitespaceDashUnderscore',
            'message' => 'Group name can only be letters, numbers, whitespaces, dash and underscore'
        ),
        'video_url' => array(
            'isUrl' => array(
                'rule' => 'url',
                'allowEmpty' => true,
                'message' => 'Invalid video url. Leave blank or insert valid youtube url'
            )
        ),
        'group_url' => array(
            'checkIsUnique' => array(
                'rule' => 'isUnique',
                'allowEmpty' => true,
                'message' => 'Already taken'
            )
        ),
    );
    public function afterFind($results, $primary = false) {
        foreach($results as &$_row) {
            if (is_array($_row) && isset($_row[$this->alias])) {
                // почему то иногда создает массив данных без [User] :(
                $row = $_row[$this->alias];
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

    public function afterSave($created, $options = array()){
        $ownerId = 0;
        if(!isset($this->data[$this->alias]['owner_id'])&&!$created){
            $ownerId = $this->field('owner_id', array(
            	'id' => $this->data[$this->alias]['id']
            ));
        } else {
            $ownerId = $this->data[$this->alias]['owner_id'];
        }
        if($ownerId){
            $this->countUsedMembers($ownerId);
        }
    }

    public function afterDelete() {
        $ownerId = 0;
        if(!isset($this->data[$this->alias]['owner_id'])&&!$created){
            $ownerId = $this->field('owner_id', array(
            	'id' => $this->data[$this->alias]['id']
            ));
        } else {
            $ownerId = $this->data[$this->alias]['owner_id'];
        }
        if($ownerId){
            $this->countUsedMembers($ownerId);
        }
    }

    /*
    public function search($currUserID, $q) {
        if(mb_strlen($q) == 1) {
            $conditions = array('Group.title LIKE ?' => $q.'%', 'Group.hidden' => '0');
        } else {
            if(!preg_match('/[A-Za-z]/', $q)) {
                $t = $this->transliterateRegex($q);
                $conditions = array(
                    'Group.hidden' => '0',
                    'OR' => array(
                            array('Group.title REGEXP ?' => $t),
                            array('Group.title LIKE ?' => '%'.$q.'%')
                        )
                    );
            } else {
                $conditions = array('Group.hidden' => '0', 'Group.title LIKE ?' => '%'.$q.'%');
            }
        }

        $order = array('Group.title');
        return $this->find('all', compact('conditions', 'order'));
    }
    */
    public function search($currUserID, $q, $limit = 20, $state = null) {
        $this->loadModel('GroupMember');

        if(mb_strlen($q) == 1) {
            $conditions = array('OR' => array('Group.title LIKE ?' => $q.'%'));
        } else {
            if(!preg_match('/[A-Za-z]/', $q)) {
                $t = $this->transliterateArray($q);

                foreach($t as $term){
                    $sql[] = array('Group.title LIKE ?' => $term.'%');
                    $sql[] = array('Group.title LIKE ?' => '% '.$term.'%');
                }

                $sql[] = array('Group.title LIKE ?' => '% '.$q.'%');
                $sql[] = array('Group.title LIKE ?' => $q.'%');

                $conditions = array('OR' => $sql);
            } else {
                $conditions = array(
                    'OR' => array(
                        array('Group.title LIKE ?' => '% '.$q.'%'),
                        array('Group.title LIKE ?' => $q.'%')
                    )
                );
            }
        }
        $conditions[] = array('Group.hidden' => '0');

        if($state == 'map') {
            $addConditions = array(
                'not' => array(
                    'GroupAddress.address' => null,
                    'GroupAddress.country' => null
                )
            );
            $groupAddresses = $this->GroupAddress->find('all', array('conditions' => $addConditions));

            $gIDs = array();
            foreach($groupAddresses as $address) {
                $addrString = $address['GroupAddress']['country'].', '.$address['GroupAddress']['address'];
                if( strlen($addrString) > 8 && !in_array($address['GroupAddress']['group_id'], $gIDs)) {
                    $gIDs[] = $address['GroupAddress']['group_id'];
                }
            }

            $conditions[] = array('Group.id' => $gIDs);
        }

        $order = array('Group.title');
        $fields = 'Group.id, Group.title, Group.descr, Group.rating, GroupMedia.*';
        $aGroups = $this->find('all', compact('conditions', 'order', 'fields', 'limit'));

        foreach($aGroups as &$group) {
            $id = $group['Group']['id'];
            $conditions = array('GroupMember.group_id' => $id, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
            $group['Group']['membersCount'] = $this->GroupMember->find('count', compact('conditions'));
        }

        return $aGroups;
    }

    public function timelineEvents($currUserID, $date, $date2) {
        $this->loadModel('Statistic');
        $this->loadModel('User');
        $this->loadModel('Skill');
        $fields = array('Group.id', 'Group.title', 'Group.created', 'Group.descr', 'Group.rating');
        // $conditions = $this->dateRange('Group.created', $date, $date2);

        /*
        $order = 'Group.created DESC';
        $limit = 2;
        $aGroups = $this->find('all', compact('conditions', 'order', 'limit'));
        */

        $user = $this->User->findById($currUserID);
        $extractedSkills = explode(', ', Hash::get($user, 'User.skills'));
        $conditions = array( 'OR' => array('rus' => $extractedSkills, 'eng' => $extractedSkills) );
        $aSkills = $this->Skill->find('all', compact('conditions'));
        $aSkillCat = Hash::extract($aSkills, '{n}.Skill.cat_id');
        $aSkillCat = array_combine($aSkillCat, $aSkillCat);
        unset($aSkillCat['']);

        $this->Behaviors->load('Containable');
        $aGroups = $this->find('all', array(
            'contain' => array('GroupMedia', 'GroupVideo', 'GroupAdministrator'), //, 'InvestProject', 'GroupAddress', 'GroupAchievement'
            'conditions' => array('Group.cat_id' => $aSkillCat),
        ));
        if($aGroups && count($aGroups)>1) {
            $aGroups = Hash::combine($aGroups, '{n}.Group.id', '{n}');
            $GID = Hash::extract($aGroups, '{n}.Group.id');

            $conditions = array(
                'Statistic.type' => 2,
                'Statistic.pk' => $GID,
                'Statistic.visitor_id <>' => $currUserID
            );

            $statData = $this->Statistic->find('all', compact('conditions') );
            if($statData) {
                $temp = array();

                foreach( $statData as $visit ) {
                    if( !isset( $aGroups[ $visit['Statistic']['pk'] ]['Group'][ 'stat' ] ) ) $aGroups[ $visit['Statistic']['pk'] ]['Group'][ 'stat' ] = 0;
                    $aGroups[ $visit['Statistic']['pk'] ]['Group'][ 'stat' ]++;
                }

                foreach( $aGroups as &$group ) {
                    if( !isset( $group['Group'][ 'stat' ] ) ) $group['Group'][ 'stat' ] = 0;
                }
            }
            usort($aGroups, function ($a, $b) { return $b['Group']['stat'] - $a['Group']['stat']; });
        }

        $GID = Hash::extract($aGroups, '{n}.Group.id');

        // вся статистика по группам
        $gStats = $this->Statistic->query('SELECT Stat.pk, Count.cnt
                                              FROM statistic  Stat
                                                   INNER JOIN (SELECT pk, count(pk) as cnt
                                                                 FROM statistic WHERE statistic.type = 2
                                                                GROUP BY pk) Count ON Stat.pk = Count.pk GROUP BY Stat.pk ORDER BY Count.cnt DESC');
        $gStats = array_slice($gStats, 0, 2);
        $gStats = Hash::extract($gStats, '{n}.Stat.pk' );
        $gStats = array_diff($gStats, $GID);
        $this->Behaviors->load('Containable');
        $gTop = $this->find('all', array(
            'contain' => array('GroupMedia', 'GroupVideo', 'GroupAdministrator'), //, 'InvestProject', 'GroupAddress', 'GroupAchievement'
            'conditions' => array('Group.id' => $gStats)
        ));
        $return = array_merge($aGroups, $gTop);

        return array_slice($return, 0, 2);
    }

    public function addFinanceProject($groupData, $userID = null, $hidden = 1) {
        $this->loadModel('FinanceProject');

        $financeData = array('FinanceProject' => array( 'name' => $groupData['title'], 'user_id' => $groupData['owner_id'] ) );
        if($hidden == 1) {
            $financeData['FinanceProject']['hidden'] = true;
        } else {
            $financeData['FinanceProject']['hidden'] = false;
        }

        $trigger = true;
        while($trigger) {
            try {
                $this->FinanceProject->addProject($userID, $financeData);
                $trigger = false;
            }
            catch( Exception $e ) {
                $financeData['FinanceProject']['name'] .= '_group'.rand(1,1000);
            }
        }

        return $this->FinanceProject->id;
    }

    public function updateFinanceProject($groupData, $userID, $hidden) {
        $this->loadModel('FinanceProject');

        $financeData = array('FinanceProject' => array( 'id' => $groupData['finance_project_id'], 'name' => $groupData['title'], 'user_id' => $groupData['owner_id'] ) );

        $financeData['FinanceProject']['hidden'] = $hidden;

        $financeData['FinanceProject']['id'] = $groupData['finance_project_id'];
        $this->FinanceProject->save( $financeData );
        return $this->FinanceProject->id;
    }

    public function userGroups($userID, $admin = false) {
        $this->loadModel('GroupMember');

        //Находим группы/проекты, из которых исключены, что бы "списать" проекты, в которых мы уже не учавствуем
        $conditions = array(
            'GroupMember.user_id' => $userID,
            'GroupMember.is_deleted' => '0',
            'GroupMember.approved' => '1'
        );
        $aGroups = $this->GroupMember->find('all', compact('conditions'));

        $conditions = array(
            'Group.id' => Hash::extract($aGroups, '{n}.GroupMember.group_id')
        );
        if($admin) {
            $conditions[]['Group.owner_id'] = $userID;
        }
        $order = 'Group.title';

        $aGroups = $this->find('all', compact('conditions', 'order') );

        return Hash::combine($aGroups, '{n}.Group.id', '{n}.Group.title');
    }

    public function getGroupComponentsID($group_id) {
        $this->loadModel('Project');
        $this->loadModel('Subproject');
        $this->loadModel('Task');

        $conditions = array('Project.group_id' => $group_id);
        $aProjects = $this->Project->find('all', compact('conditions'));
        $aProjects = Hash::extract($aProjects, '{n}.Project.id');

        $conditions = array('Subproject.project_id' => $aProjects);
        $aSubprojects = $this->Subproject->find('all', compact('conditions'));
        $aSubprojects = Hash::extract($aSubprojects, '{n}.Subproject.id');

        $conditions = array('Task.subproject_id' => $aSubprojects);
        $aTasks = $this->Task->find('all', compact('conditions'));
        $aTasks = Hash::extract($aTasks, '{n}.Task.id');

        return array('project' => $aProjects, 'subproject' => $aSubprojects, 'task' => $aTasks);
    }

    public function isGroupAdmin($groupID, $userID) {
        $group = $this->findById($groupID);
        if(!$group) return false;
        return $group['Group']['owner_id'] == $userID;
    }

    public function setResponsible($groupID, $userID) {
        $group = $this->findById($groupID);
        $this->save( array('id' => $groupID, 'responsible_id' => $userID) );
    }
}
