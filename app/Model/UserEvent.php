<?
App::uses('AppModel', 'Model');
class UserEvent extends AppModel {

    public $actsAs = array('Ratingable','Containable');

    public $hasMany = array(
        'UserEventRequest' => array(
            'className' => 'UserEventRequest',
			'foreignKey' => 'event_id',
        ),
        'UserEventShare' => array(
            'className' => 'UserEventShare',
			'foreignKey' => 'user_event_id',
        ),
    );
    public $belongsTo = array(
        'UserEventCategory' => array(
            'className' => 'UserEventCategory',
            'foreignKey' => 'event_category_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'UserMedia' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'user_id',
			'conditions' => array('UserMedia.object_type' => 'User'),
			'dependent' => true
		),
    );
    public $hasOne = array(
        'Media' => array(
            'className' => 'Media.Media',
            'foreignKey' => 'object_id',
            'conditions' => array(
                'Media.object_type' => 'Cloud',
                'Media.main' => 1
            ),
        )
    );

    public function timelineEvents($currUserID, $date, $date2, $view = 0, $mail = false) {
        if($view < 2) {
            $this->loadModel('UserEventShare');
            $conditions = array(
                'UserEventShare.acceptance <> ?' => '-1',
                'UserEventShare.user_id' => $currUserID
            );
            $aEventShare = $this->UserEventShare->find('all', compact('conditions'));
            $aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');

            $aEventShare = Hash::combine($aEventShare, '{n}.UserEventShare.user_event_id', '{n}.UserEventShare');

            //$dateRange =  $this->dateRange('UserEvent.event_time', $date, $date2);
            $dateRange = $this->dateRange('ProjectEvent.created', $date2, $date);
    		if((strtotime($date2) > strtotime($date))||($mail)) {
    			$dateRange = $this->dateTimeRange('ProjectEvent.created', $date, $date2);
    		}
            $conditions = array();
            $this->loadModel('Group');
            $this->loadModel('GroupMember');
            $conditions = array('GroupMember.user_id' => $currUserID, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
            $userGroups = $this->GroupMember->find('all', compact('conditions'));
            $userGroups = Hash::extract($userGroups,'{n}.GroupMember.group_id');

            $dateRange = array(
                $this->dateRange('UserEvent.created', $date2, $date),
                $this->dateRange('UserEvent.event_time', $date2, $date),
                $this->dateRange('UserEvent.event_end_time', $date2, $date)
            );
            if((strtotime($date2) > strtotime($date))||($mail)) {
                $dateRange = array(
                    $this->dateRange('UserEvent.created', $date, $date2),
                    $this->dateRange('UserEvent.event_time', $date, $date2),
                    $this->dateRange('UserEvent.event_end_time', $date, $date2)
                );
            }
            $conditions = array(
                'UserEvent.is_delayed' => '0',
                'UserEvent.type !=' => 'task',
                array('OR' => array(
                    'AND' => array(
                        'UserEvent.user_id' => $currUserID,
                        $dateRange
                    ),
                    'AND' => array(
                        'UserEvent.id' => $aEventID,
                        'UserEvent.shared' => '1',
                    ),
                    //Reason to show all non tasks for group members???
                    //'OR' => array(
                    //    'UserEvent.object_id' => $userGroups,
                    //)
                )),
                array('OR' => $dateRange),

            );

            if($mail) {
                $conditions[] = $dateRange;
            }

            $order = array('UserEvent.event_time', 'UserEvent.created');
            $a_Events = $this->find('all', array(
                'conditions' => $conditions,
                'order' => $order,
                'contain' => array('UserEventShare')
            //    'recursive' => -1
            ));

            $conditions = array(
                'UserEvent.is_delayed' => '0',
                'UserEvent.type' => 'task',
                'OR' => array(
                    array(
                      'AND' => array(
                          'UserEvent.user_id' => $currUserID,
                      ),
                    ),
                    array(
                        'AND' => array(
                            'UserEvent.id' => $aEventID,
                            'UserEvent.shared' => '1',
                        )
                    ),
                    array(
                      'AND' => array(
                          'UserEvent.object_id' => $userGroups,
                          'UserEvent.external' => '1',
                        //  'UserEvent.external_time <' => date('Y-m-d H:i:s'),
                      )
                    ),
                ),
            );

            if($mail) {
                $conditions[] = $dateRange;
            }
            $order = array('UserEvent.event_time', 'UserEvent.created');
            $contain = array('UserEventShare');
            $t_Events = $this->find('all', array(
                //'contain' => array('UserEventShare'),
                'conditions' => $conditions,
                'order' => $order,
                'rcursive' => -1
            ));

            $aEvents = array_merge($a_Events, $t_Events);
            // for unique event time
            $eventTime = hash::get($aEvents, '{0}.UserEvent.event_time');
            $count = 0;
            $unAccepted = 0;

            $this->loadModel('UserEventShare');
            foreach($aEvents as &$event) {

                if( !empty($aEventShare[$event['UserEvent']['id']]) && $aEventShare[$event['UserEvent']['id']]['acceptance'] == '0' && !$mail ) {
                    $dateX = date('Y-m-d H:').'00:'.( $unAccepted < 10 ? '0'.$unAccepted : $unAccepted );
                    $event['UserEvent']['real_event_time'] = $event['UserEvent']['event_time'];
                    $event['UserEvent']['event_time'] = $dateX;
                    $unAccepted++;
                } else {
                    if ($event['UserEvent']['event_time'] == $eventTime) {
                        $count++;
                        $event['UserEvent']['event_time'] = date('Y-m-d H:i', strtotime($eventTime)).':0'.$count;
                    } else {
                        $eventTime = $event['UserEvent']['event_time'];
                        $count = 0;
                    }
                }

                $conditions = array('UserEventShare.user_event_id' => $event['UserEvent']['id']);
                //Not sure but do not get all shares for non initiator user
                if($event['UserEvent']['user_id'] != $currUserID){
                    $conditions = array_merge($conditions, array('UserEventShare.user_id' => $currUserID));
                }
                $shareEv = $this->UserEventShare->find('all', array(
                    'conditions' => $conditions
                ));

                $ownShare = Hash::extract($shareEv, '{n}.user_id');
                $aSharesEvents = Hash::combine($shareEv, '{n}.user_id', '{n}');

                //Временно, если есть события, в которых в шарах ещё не прописан создатель
                //$ownShare = $this->UserEventShare->findByUserEventIdAndUserId($event['UserEvent']['id'], $event['UserEvent']['user_id']);
                if(!in_array($event['UserEvent']['user_id'], $ownShare)) {
                    $this->UserEventShare->save( array('user_event_id' => $event['UserEvent']['id'], 'user_id' => $event['UserEvent']['user_id'], 'acceptance' => '1') );
                }
                $aShares = array();
                foreach ($aSharesEvents as $key => $value) {
                    $aShares[$key]['UserEventShare'] = $value;
                }
                if(empty($aShares[$currUserID])){
                    $aShares[$currUserID] = array('UserEventShare' => array(
                        'id' => null,
                        'user_id' => $event['UserEvent']['user_id'],
                        'user_event_id' => $currUserID,
                        'acceptance' => 0,
                    ));
                }

                if($aShares[$currUserID]['UserEventShare']['acceptance'] >= 0){
                    $event['UserEvent']['accepted'] = $aShares;
                } else {
                    //remove all element wher acceptance < 1
                    $event = null;
                }
                unset($event['UserEventShare']);

            }

            $aEvents = array_filter( $aEvents );

            return array_reverse($aEvents);
        }
        return array();
    }

    public function totoalTime($events) {

        $timeInterval = 0;
        foreach($events as $event) {
            $start = strtotime($event['event_time']);
            $end = strtotime($event['event_end_time']);
            $timeInterval += $end - $start;
        }

        return $timeInterval;
    }

    public function userEventOptions() {
        $conditions = array('UserEvent.user_id' => AuthComponent::user('id'));
        $order = 'UserEvent.title';

        $aEvents = $this->find('all', compact('conditions', 'order') );
        $aEvents = Hash::combine($aEvents, '{n}.UserEvent.id', '{n}.UserEvent.title');

        $return = array();
        foreach($aEvents as $key => $event) {
            if(!in_array($event, $return)) {
                $return[$key] = $event;
            }
        }
        //$aEvents = array_unique($aEvents);
        return $return;
    }

    public function userEventOptionsJson() {
        $aEvents = $this->userEventOptions();

        $return = array();
        foreach($aEvents as $data => $value ) {
            $asd = 'asd-'.$data;
            array_push($return, compact('data', 'value', 'asd') );
        }

        return json_encode($return);
    }

    public function getUserEvents($currUserID, $date = null, $date2 = null, $mail = false, $map = false) {
        $this->loadModel('UserEventShare');
        $conditions = array(
            'UserEventShare.acceptance <> ?' => '-1',
            'UserEventShare.user_id' => $currUserID
        );
        $aEventShare = $this->UserEventShare->find('all', compact('conditions'));
        $aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');
        $aEventShare = Hash::combine($aEventShare, '{n}.UserEventShare.user_event_id', '{n}.UserEventShare');

        $dateRange = null;
        if($date && $date2) {
            $dateRange =  $this->dateRange('UserEvent.event_time', $date, $date2);
        }
        $conditions = array();
        $conditions = array(
            'UserEvent.is_delayed' => '0',
            'UserEvent.type !=' => 'task',
            'OR' => array(
                array(
                    'AND' => array(
                        'UserEvent.user_id' => $currUserID,
                    ),
                ),
                array(
                    'AND' => array(
                        'UserEvent.id' => $aEventID,
                        'UserEvent.shared' => '1',
                    )
                )
            )
        );

        $fields = '';
        if($map) {
            $conditions['NOT'] = array('UserEvent.place_coords' => null);
            $fields = array(
                'UserEvent.id',
                'UserEvent.user_id',
                'UserEvent.title',
                'UserEvent.descr',
                'UserEvent.type',
                'UserEvent.place_coords'
            );
        }

        if($dateRange) {
            $conditions['OR'][0]['AND'] = $dateRange;
        }
        if($mail && $dateRange) {
            $conditions[] = $dateRange;
        }
        $order = array('UserEvent.event_time', 'UserEvent.created');
        return $this->find('all', compact('conditions', 'order', 'fields'));
    }

    public function getUserTaskEvents($currUserID, $date = null, $date2 = null, $mail = false, $map = false) {
        $this->loadModel('UserEventShare');
        $conditions = array(
            'UserEventShare.acceptance <> ?' => '-1',
            'UserEventShare.user_id' => $currUserID
        );
        $aEventShare = $this->UserEventShare->find('all', compact('conditions'));
        $aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');
        $aEventShare = Hash::combine($aEventShare, '{n}.UserEventShare.user_event_id', '{n}.UserEventShare');

        $dateRange = null;
        if($date && $date2) {
            $dateRange =  $this->dateRange('UserEvent.event_time', $date, $date2);
        }

        $conditions = array();
        $this->loadModel('Group');
        $this->loadModel('GroupMember');
        $conditions = array('GroupMember.user_id' => $currUserID, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
        $userGroups = $this->GroupMember->find('all', compact('conditions'));
        $userGroups = Hash::extract($userGroups,'{n}.GroupMember.group_id');
        //echo date('Y-m-d H:i:s');exit;
        $conditions = array();
        $conditions = array(
            'UserEvent.is_delayed' => '0',
            'UserEvent.type' => 'task',

            'OR' => array(
                array(
                    'AND' => array(
                        'UserEvent.user_id' => $currUserID,
                    ),
                ),
                array(
                    'AND' => array(
                        'UserEvent.id' => $aEventID,
                        'UserEvent.shared' => '1',
                    )
                ),
                array(
                  'OR' => array(
                      'UserEvent.object_id' => $userGroups,
                      'UserEvent.external' => '1',
                  )
                ),
                array(
                  'AND' => array(
                    'UserEvent.external' => '1',
                    'UserEvent.external_time <' => date('Y-m-d H:i:s'),
                  )
                ),

            )
        );

        $fields = '';
        if($map) {
            $conditions['NOT'] = array('UserEvent.place_coords' => null);
            $fields = array(
                'UserEvent.id',
                'UserEvent.user_id',
                'UserEvent.title',
                'UserEvent.descr',
                'UserEvent.type',
                'UserEvent.place_coords'

            );
        }

        if($dateRange) {
            $conditions['OR'][0]['AND'] = $dateRange;
        }
        if($mail && $dateRange) {
            $conditions[] = $dateRange;
        }
        $order = array('UserEvent.event_time', 'UserEvent.created');

        $taskEvent = $this->find('all', compact('conditions', 'order'));

        return $taskEvent;
    }
}
