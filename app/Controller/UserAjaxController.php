<?php
App::uses('AppController', 'Controller');
App::uses('PAjaxController', 'Core.Controller');
App::uses('CakeEmail', 'Network/Email');
class UserAjaxController extends PAjaxController {
    public $name = 'UserAjax';
    public $uses = array();
    public $helpers = array('Media', 'Avatar');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->_checkAuth();
        $this->Auth->allow(array('switchLang'));
    }

    public function switchLang() {
        $this->loadModel('Lang');
        $langs = $this->Lang->options();
        if(isset($_POST['lang']) && isset($langs[$_POST['lang']])) {
            $lang = $_POST['lang'];
        } else {
            $lang = $this->Lang->detect();
        }
        $this->Lang->setLang($lang);
        $this->setResponse(array('success' => true, 'lang' => $lang));
    }

    public function jsSettings() {
        $this->loadModel('User');
        //TODO: Maybe not needed anymore notifications for profile incompleteness
        //$this->set('notifyProfile', !$this->User->checkData($this->currUserID));
    }

    public function panel() {
        $this->loadModel('User');
        $this->loadModel('Group');


        $this->request->data('q', htmlspecialchars( $this->request->data('q') ));
        $q = htmlspecialchars( $this->request->data('q') );
        $state = htmlspecialchars( $this->request->data('state') );
        $locale = $this->request->data('location');
        if ($q) {
            $this->set('aUsers', $this->User->search($this->currUserID, $q));
            $this->set('aGroups', $this->Group->search($this->currUserID, $q));
        } else if($state == 'map') {
            if(empty($locale))
              $locale = '';
            $this->set('aUsers', $this->User->search($this->currUserID, '', 20, $state, $locale));

            // Use just user
            //$this->set('aGroups', $this->Group->search($this->currUserID, '', 20, $state));
        }
    }

    public function userList() {
        $this->loadModel('User');
        $q = $this->request->data('q');
        if ($q) {
            $this->set('aUsers', $this->User->search($this->currUserID, $q));
        }
    }

    public function getById($id = null) {
        $this->loadModel('User');
        $user = $this->User->findById($id);
        $user['ChatContact'] = array();
        if($this->currUserID != $id){
            $this->loadModel('ChatContact');
            $contact = $this->ChatContact->find('first', array(
                'conditions' => array(
                    'ChatContact.user_id' => $id,
                    'ChatContact.initiator_id' => $this->currUserID,
                ),
                'order' => array('ChatContact.modified DESC'),
                'recursive' => -1
            ));
            if(!empty($contact)){
                $user = Hash::merge($user, $contact);
            }
        }
        $this->set('user', $user);

        $q = Hash::get($user, 'User.full_name');
        if ($q) {
            $this->set('aUsers', $this->User->search($this->currUserID, $q));
        }
    }

    public function timelineEvents() {
        $this->loadModel('User');
        try {
            $view = $this->request->data('view') ? $this->request->data('view') : 0;
            $data = $this->User->getTimeline($this->currUserID, $this->request->data('date'), $this->request->data('date2'), $view, false, $this->request->data('search'));
            $this->setResponse($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function updateEvent() {
        $this->loadModel('UserEvent');
        $this->loadModel('Group');
        $this->loadModel('UserEventShare');
        try {
            $event = array();
            $owner = true;
            $origEvent = null;

            $id = $this->request->data('UserEvent.id');
            if( $id ) {
                $origEvent = $this->UserEvent->findById($id);
            }

            if( !in_array($this->request->data('UserEvent.type'), array('pay', 'purchase') )) {
                $event = array(
                    'title' => $this->request->data('UserEvent.title'),
                    'descr' => $this->request->data('UserEvent.descr'),
                    'object_type' => $this->request->data('UserEvent.object_type'),
                    'object_id' => $this->request->data('UserEvent.object_id'),
                    'type' => $this->request->data('UserEvent.type'),
                    'recipient_id' => $this->request->data('UserEvent.recipient_id'),
                    'shared' => isset($this->request->data['UserEvent']['shared']) ? '1' : '0',
                    'is_delayed' => $this->request->data('UserEvent.is_delayed'),
                    'price' => $this->request->data('UserEvent.price'),

                );

                if($this->request->data('UserEvent.type') == 'task'){
                  if(empty($this->request->data('UserEvent.event_category_id'))){
                    $event_category_id = 1;
                  }else{
                    $event_category_id = $this->request->data('UserEvent.event_category_id');
                  }
                  $event['event_category_id'] = $event_category_id;
                  if(!empty($this->request->data('UserEvent.external'))){
                    $event['external'] = $this->request->data('UserEvent.external');
                    $group = null;
                    if(!empty($this->request->data('UserEvent.object_type')) && ($this->request->data('UserEvent.object_type') == 'group')){
                      $group = $this->Group->find('first',array(
                          'conditions' => array(
                            array('Group.id'=>$this->request->data('UserEvent.object_id')))
                      ));
                    }
                    if(!empty($this->request->data('UserEvent.recipient_id')) || (!empty($group) && $group['Group']['active_members'] > 1)){
                      $cur_time=date("Y-m-d H:i:s");
                      if(Configure::read('debug') > 0){
                        $duration='+2 minutes';
                      }else{
                        $duration='+12 hours';
                      }

                      $external_time = date('Y-m-d H:i:s', strtotime($duration, strtotime($cur_time)));
                      $event['external_time'] = $external_time;
                    }
                  }
                }

            } else {
                $this->loadModel('FinanceOperation');
                $event = array(
                    'title' => $this->request->data('UserEvent.title'),
                    'descr' => $this->request->data('UserEvent.descr'),
                    'object_type' => $this->request->data('UserEvent.object_type'),
                    'object_id' => $this->request->data('UserEvent.object_id'),
                    'type' => $this->request->data('UserEvent.type'),
                    'recipient_id' => '',
                    'shared' => '1',
                    'is_delayed' => $this->request->data('UserEvent.is_delayed'),
                );

                $financeData = array(
                    'FinanceOperation' => array(
                        'type' => 1,
                        'account_id' => $this->request->data('UserEvent.finance_account'),
                        'project_id' => $this->request->data('UserEvent.finance_project'),
                        'amount' => $this->request->data('UserEvent.amount'),
                        'comment' => 'created from event data',
                    ),
                    'FinanceOperationHasCategory' => array ('category_id' => $this->request->data('UserEvent.finance_category'))
                );

                if($origEvent && $origEvent['UserEvent']['finance_operation_id']) {
                    $financeData['FinanceOperation']['id'] = $origEvent['UserEvent']['finance_operation_id'];
                    $operationId = $this->FinanceOperation->editOperation($financeData);
                    $event['finance_operation_id'] = $operationId;
                } else {
                    $operationId = $this->FinanceOperation->addOperation($financeData);
                    $event['finance_operation_id'] = $operationId;
                }
            }

            if( $id ) {
                $event['id'] = $id;
                $event['user_id'] = $origEvent['UserEvent']['user_id'];

                $oldRecipients = explode(',', $origEvent['UserEvent']['recipient_id']);
                if( ($this->currUserID != $origEvent['UserEvent']['user_id']) && (in_array($this->currUserID, $oldRecipients)) ) {

                    $event['title'] = $origEvent['UserEvent']['title'];
                    $event['descr'] = $origEvent['UserEvent']['descr'];
                    $event['object_type'] = $origEvent['UserEvent']['object_type'];
                    $event['object_id'] = $origEvent['UserEvent']['object_id'];
                    $event['type'] = $origEvent['UserEvent']['type'];
                    $event['recipient_id'] = $origEvent['UserEvent']['recipient_id'];
                    $event['shared'] = $origEvent['UserEvent']['shared'];

                    $owner = false;
                } else if($this->currUserID == $origEvent['UserEvent']['user_id']) {
                    $owner = true;
                } else {
                    throw new Exception(__('Access denied'));
                }
                $event['previous_event_time'] = $origEvent['UserEvent']['event_time'];
            } else {
                $event['user_id'] = $this->currUserID;
            }

            //Если событие моё - даю редактировать место
            if(strlen($this->request->data('UserEvent.place_name')) >= 3 && strlen($this->request->data('UserEvent.place_coords')) >= 5) {
                $event['place_name'] = $this->request->data('UserEvent.place_name');
                $event['place_coords'] = $this->request->data('UserEvent.place_coords');
            }

            //назначение категории исходя из типа события либо исходя из схожего события (одинаковое название и создатель)
            $conditions = array(
                'UserEvent.user_id' => $event['user_id'],
                'UserEvent.title' => $event['title']
            );

            if($id) $conditions[] = array('UserEvent.id <> ?' => $event['id']);

            $sameTitledEvent = $this->UserEvent->find('first', compact('conditions'));
            if( $sameTitledEvent ) {
                $event['category'] = $sameTitledEvent['UserEvent']['category'];
            } else {
                $event['category'] = in_array($event['type'], array('sport', 'none'));
            }

            $y1 = $this->request->data('UserEvent.yearStart');
            $m1 = $this->request->data('UserEvent.monthStart');
            $d1 = $this->request->data('UserEvent.dayStart');
            $h1 = $this->request->data('UserEvent.timeStart');
            $min1 = $this->request->data('UserEvent.minuteStart');

            $event['event_time'] = $y1.'-'.$m1.'-'.$d1.' '.$h1.':'.$min1;
            $time = new DateTime($event['event_time']);
            $time->add(new DateInterval('PT' . $this->request->data('UserEvent.duration') . 'M'));

            $event['event_end_time'] = $time->format('Y-m-d H:i:s');

            if( in_array($this->request->data('UserEvent.type'), array('pay', 'purchase') )) {
                $event['event_end_time'] = $event['event_time'];
                $event['place_name'] = null;
                $event['place_coords'] = null;
            }

            $this->UserEvent->save( $event );

            if( strpos($event['recipient_id'], ',') !== false ) {
                $aRecipients = explode(',', $event['recipient_id']);
            } else if( strlen($event['recipient_id']) > 0 ) {
                $aRecipients[] = $event['recipient_id'];
            }
            $aRecipients[] = $this->currUserID;

            if($owner) {
                if( !$id ) {
                    foreach($aRecipients as $uID) {
                        $acceptance = ($uID == $this->currUserID) ? '1' : '0';
                        $ueShare = array(
                            'user_id' => $uID,
                            'user_event_id' => $this->UserEvent->id,
                            'acceptance' => $acceptance,
                        );
                        $this->UserEventShare->save($ueShare);
                        $this->UserEventShare->clear();
                    }
                } else {
                    $aAcceptances = $this->UserEventShare->findAllByUserEventId($id);
                    foreach($aAcceptances as $acceptance) {
                        $this->UserEventShare->delete($acceptance['UserEventShare']['id']);
                    }
                    foreach($aRecipients as $uID) {
                        $acceptance = $uID == $this->currUserID ? 1 : 0;
                        $ueShare = array(
                            'user_id' => $uID,
                            'user_event_id' => $this->UserEvent->id,
                            'acceptance' => $acceptance,
                        );
                        $this->UserEventShare->save($ueShare);
                        $this->UserEventShare->clear();
                    }
                }
            } else {
                $aAcceptances = $this->UserEventShare->findAllByUserEventId($id);
                foreach($aAcceptances as $key => $acceptance) {
                    $acceptance['UserEventShare']['acceptance'] = $acceptance['UserEventShare']['user_id'] == $this->currUserID ? '1' : '0';
                    $this->UserEventShare->save($acceptance['UserEventShare']);
                    $this->UserEventShare->clear();
                }
            }
            $data = $this->User->getTimeline($this->currUserID, $event['event_time'], $event['event_end_time']);
            $data['event'] = $this->UserEvent->findById( $this->UserEvent->id );

            App::uses('CakeTime', 'Utility');
            if(empty($this->request->data['UserEvent']['recipient_id']) && !empty($this->request->data['UserEvent']['new_user'])) {
                $this->loadModel('Invitation');
                $invitation = [
                    'object_id' => $data['event']['UserEvent']['id'],
                    'object_type' => Invitation::USER_EVENT,
                    'email' => $this->request->data['UserEvent']['new_user'],
                ];

                $this->Invitation->set($invitation);
                $this->Invitation->save();

                $Email = new CakeEmail('smtp');
                $Email->template('reg_invitation', 'mail')
                    ->to($this->request->data('UserEvent.new_user'))
                    ->viewVars(
                        array(
                            'eventId' => $data['event']['UserEvent']['id'],
                            'eventType' => $this->request->data['UserEvent']['type'],
                            'eventTitle' => $this->request->data['UserEvent']['title'],
                            'creator_id' => $this->currUserID,
                            'date' => CakeTime::format($data['event']['UserEvent']['event_time'], '%B %e, %Y'),
                            'start_time' => CakeTime::format($data['event']['UserEvent']['event_time'], '%H:%M %p'),
                            'end_time' => CakeTime::format($data['event']['UserEvent']['event_end_time'], '%H:%M %p'),
                        )
                    )
                    ->subject('Invitation to join Konstruktor.com')
                    ->send();
            }

            $this->setResponse($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function acceptEvent() {
        $this->loadModel('UserEvent');
        $this->loadModel('UserEventShare');

        try {
            $id = $this->request->data('id');
            $user_id = $this->request->data('user_id');
            if (!$id) throw new Exception(__('Missing parameter'));
            $event = $this->UserEvent->findById($id);
            if (!$event) throw new Exception(__('User event not found'));
            $conditions = array(
                'UserEventShare.user_event_id' => $event['UserEvent']['id'],
                'UserEventShare.user_id' => $this->currUserID
            );
            $share = $this->UserEventShare->find('first', compact('conditions'));
            if ((!$share && $this->currUserID == $event['UserEvent']['user_id']) || $user_id) {
                if($user_id){
                  $user_id = $user_id;
                }else{
                  $user_id = $this->currUserID;
                }
                $this->UserEventShare->save( array('user_event_id' => $event['UserEvent']['id'], 'user_id' => $user_id, 'acceptance' => '1') );
            } else if(!$share) {
                throw new Exception(__('Share data not found'));
            }
            $share['UserEventShare']['acceptance'] = '1';
            if (!$this->UserEventShare->save($share)) throw new Exception(__('Error on saving'));

            $data = $this->User->getTimeline($this->currUserID, $event['UserEvent']['event_time'], $event['UserEvent']['event_end_time']);
            $data['event'] = $this->UserEvent->findById( $id );


            $conditions = array(
                'UserEventShare.user_event_id' => $event['UserEvent']['id']
            );
            $aShares = $this->UserEventShare->find('all', compact('conditions'));
            $allAccepted = true;
            foreach($aShares as $share) {
                if($share['UserEventShare']['acceptance'] != 1) {
                    $allAccepted = false;
                }
            }

            // запись о задаче в фин. проекте
            // если все шары задачи приняты, событие - задача, событие привязано к задаче либо проекту
            if( in_array($data['event']['UserEvent']['object_type'], array('task', 'project')) && $data['event']['UserEvent']['type'] == 'task' && $allAccepted ) {
                $this->loadModel('Project');
                $this->loadModel('Task');
                $this->loadModel('Group');
                $this->loadModel('GroupMember');
                $this->loadModel('FinanceOperation');

                $group = $data['event']['UserEvent']['object_type'] == 'task' ?
                    $this->Task->getTaskGroup($data['event']['UserEvent']['object_id']) : $this->Project->getProjectGroup($data['event']['UserEvent']['object_id']);
                $project = $data['event']['UserEvent']['object_type'] == 'task' ?
                    $this->Task->getTaskProject($data['event']['UserEvent']['object_id']) : $this->Project->findById($data['event']['UserEvent']['object_id']);

                // если задача создана ответственным за группу или админом группы, а так же если проект привязан к счету
                if( in_array($data['event']['UserEvent']['user_id'], array($group['Group']['owner_id'], $group['Group']['responsible_id'])) && $project['Project']['finance_account_id'] ) {
                    $conditions = array(
                        'GroupMember.user_id' => $data['event']['UserEvent']['recipient_id'],
                        'GroupMember.group_id' => $group['Group']['id'],
                        'GroupMember.is_deleted' => '0',
                        'GroupMember.approved' => '1'
                    );
                    $groupMember = $this->GroupMember->find('first', compact('conditions'));
                    $eventFrom = strtotime( $data['event']['UserEvent']['event_time'] );
                    $eventTo = strtotime( $data['event']['UserEvent']['event_end_time'] );
                    $eventDuration = ($eventTo - $eventFrom)/3600;

                    $financeData = array(
                        'FinanceOperation' => array(
                            'type' => 1,
                            'account_id' => $project['Project']['finance_account_id'],
                            'project_id' => $group['Group']['finance_project_id'],
                            'amount' => $groupMember['GroupMember']['wages'] * $eventDuration,
                            'comment' => 'created from task event data',
                        ),
                        'FinanceOperationHasCategory' => array ('category_id' => $project['Project']['finance_category_id'])
                    );

                    if($data['event']['UserEvent']['finance_operation_id']) {
                        $financeData['FinanceOperation']['id'] = $data['event']['UserEvent']['finance_operation_id'];
                        $operationId = $this->FinanceOperation->editOperation($financeData);
                        $data['event']['UserEvent']['finance_operation_id'] = $operationId;
                    } else {
                        $operationId = $this->FinanceOperation->addOperation($financeData);
                        $data['event']['UserEvent']['finance_operation_id'] = $operationId;
                    }
                    $this->UserEvent->clear();
                    $this->UserEvent->save($data['event']);
                }
            }


            $this->setResponse($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function declineEvent() {
        $this->loadModel('UserEvent');
        $this->loadModel('UserEventShare');
        try {

            $id = $this->request->data('id');
            $user_id = $this->request->data('user_id');
            if (!$id) throw new Exception(__('Missing parameter'));
            $event = $this->UserEvent->findById($id);
            if (!$event) throw new Exception(__('User event not found'));
            $conditions = array(
                'UserEventShare.user_event_id' => $event['UserEvent']['id'],
                'UserEventShare.user_id' => $this->currUserID
            );
            $share = $this->UserEventShare->find('first', compact('conditions'));
            if ((!$share && $this->currUserID == $event['UserEvent']['user_id']) || $user_id) {
                if($user_id){
                  $user_id = $user_id;
                }else{
                  $user_id = $this->currUserID;
                }

                $this->UserEventShare->save( array('user_event_id' => $event['UserEvent']['id'], 'user_id' => $user_id, 'acceptance' => '-1') );
            } else if(!$share) {
                throw new Exception(__('Share data not found'));
            }
            $share['UserEventShare']['acceptance'] = '-1';
            if (!$this->UserEventShare->save($share)) throw new Exception(__('Error on saving'));
            $this->checkExternalStatus($event['UserEvent']['id']);
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function changeEventTitle() {
        $this->loadModel('UserEvent');
        $this->loadModel('UserEventShare');
        try {
            $oldTitle = $this->request->data('old_title');
            $newTitle = htmlspecialchars( $this->request->data('new_title') );

            $conditions = array(
                'UserEvent.title' => $oldTitle,
                'UserEvent.user_id' => $this->currUserID
            );
            $aEvents = $this->UserEvent->find('all', compact('conditions'));

            if (!$share && $this->currUserID == $event['UserEvent']['user_id']) {
                throw new Exception(__('Events not found'));
            }

            foreach($aEvents as $event) {
                $updEvent['UserEvent']['id'] = $event['UserEvent']['id'];
                $updEvent['UserEvent']['title'] = $newTitle;
                $this->UserEvent->clear();
                $this->UserEvent->save($updEvent);
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function deleteEvent() {
        $this->loadModel('UserEvent');
        $this->loadModel('UserEventShare');
        try {
            $id = $this->request->data('UserEvent.id');
            $data['event'] = $this->UserEvent->findById($id);
            if (!$data['event']) {
                throw new Exception(__('Incorrect event ID'));
            }

            $this->UserEvent->delete($id);
            $event_time = Hash::get($data, 'event.UserEvent.event_time');

            $conditions = array(
                'UserEventShare.user_event_id' => $id
            );
            $aShares = $this->UserEventShare->find('all', compact('conditions'));
            foreach($aShares as $share) {
                $this->UserEventShare->delete($share['UserEventShare']['id']);
            }

            $data['timeline'] = $this->User->getTimeline($this->currUserID, $event_time, $event_time);
            $this->setResponse($data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function changeEventCategory() {
        $this->loadModel('UserEvent');
        try {
            $conditions = array(
                'UserEvent.title' => $this->request->data('title'),
                'UserEvent.user_id' => $this->currUserID
            );
            $aEvents = $this->UserEvent->find('all', compact('conditions'));

            foreach($aEvents as $event) {
                $event['UserEvent']['category'] = $this->request->data('category');
                $this->UserEvent->save($event['UserEvent']);
                $this->UserEvent->clear();
            }
            $this->setResponse('done');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function getFinanceOperation() {
        $this->loadModel('FinanceOperation');
        try {
            $item = $this->FinanceOperation->findById($this->request->data('id'));
            $this->setResponse($item);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    public function saveToCloud() {
        $this->loadModel('MediaFile');
        $this->loadModel('Media');
        $this->loadModel('Cloud');
        try {
            $fileId = $this->request->data('file_id');
            if(!$fileId) {
                throw new Exception(__('No file ID'));
            }
            $file = $this->MediaFile->getMedia($fileId);
            $file['Media']['old_id'] = $file['Media']['id'];
            $file['Media']['old_object_type'] = $file['Media']['object_type'];
            $file['Media']['object_type'] = 'Cloud';
            unset($file['Media']['id']);
            $media_id = $this->Media->cloneMedia($file['Media']);
            $cloud = [
                "Cloud" => [
                    "media_id" => $media_id,
                    "name" => $file['Media']['orig_fname'],
                    "parent_id" => '',
                    "user_id" => $this->currUserID
            ]];
            $this->Cloud->save($cloud);
            $this->setResponse($this->Cloud->id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
        //return $this->redirect(['controller' => 'Cloud', 'action' => 'index']);
    }

    public function saveSettings() {
        $this->loadModel('MediaFile');
        $this->loadModel('Media');
        $this->loadModel('Cloud');
        $this->loadModel('Lang');
        try {
             //load on Profile Update GeoLocation Behaviour
            $this->User->Behaviors->load('OnProfileUpdateGeoLocation');
            $this->request->data('User.id', $this->currUserID);
            $url = $this->request->data('User.video_url');
            if($url) {
                $this->request->data('User.video_id', str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $url));
            }
            if ($this->request->data('UserAchievement')) {
                $this->loadModel('UserAchievement');
                /** I think in new edit page we no need next line of code :) */
//                $this->UserAchievement->deleteAll(array('user_id' => $this->currUserID));
                foreach($this->request->data('UserAchievement') as $i => $data) {
                    $url = $this->request->data('UserAchievement.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('UserAchievement.'.$i.'.url', $url);
                }
            }
            /** Add achievements to new edit page, so we need to save all data, we leave old "save" but not enabled it */
//            $this->User->save($this->request->data('User'));
            $this->User->saveAll($this->request->data);

            $this->Lang->setLang($this->request->data('User.lang'));

            $this->setResponse($this->request->data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
        //return $this->redirect(['controller' => 'Cloud', 'action' => 'index']);
    }

    /**
     * Save uploaded video in User profile
     */
    public function saveVideo() {
        $this->loadModel('MediaFile');
        $this->loadModel('Media');
        $this->loadModel('UserVideo');

        try {
            $media_id = $this->request->data('media_id');
            $object_type = $this->request->data('object_type');
            $userVideo = [
                "UserVideo" => [
                    "media_id" => $media_id,
                    "user_id" => $this->currUserID,
                    "object_type" => $object_type
                ]];
            $this->UserVideo->save($userVideo);
            $this->setResponse($this->request->data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /**
     * @todo Delete UserVideo and delete User video
     */

    /**
     * This function will delete achievements from db
     * receive id of achievement
     */
    public function deleteAchievements() {

        try {
            $achievementId = $this->request->data;
            $this->loadModel('UserAchievement');
            $this->UserAchievement->deleteAll(array('id' => $achievementId));
            $this->setResponse($this->request->data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
    /**
     * This function will update achievements in db
     */
    public function updateAchievements() {

        try {
            $achievementId = $this->request->data['id'];
            $achievementTitle = $this->request->data['title'];
            $achievementUrl = $this->request->data['url'];
            /** @var UserAchievement $achievment */
            $this->loadModel('UserAchievement');
            $achievment = $this->UserAchievement;
            $achievment->updateAll(array('title' => "'$achievementTitle'", 'url' => "'$achievementUrl'"), array('id' => $achievementId));

            $this->setResponse($achievementId);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

    /*
    public function mapData() {
        $this->loadModel('MediaFile');
        $this->loadModel('Media');
        $this->loadModel('Cloud');
        try {
             //load on Profile Update GeoLocation Behaviour
            $this->User->Behaviors->load('OnProfileUpdateGeoLocation');
            $this->request->data('User.id', $this->currUserID);
            $url = $this->request->data('User.video_url');
            if($url) {
                $this->request->data('User.video_id', str_replace(array('http://', 'https://', 'www.', 'youtube.com/', 'youtu.be/', 'watch?v='), '', $url));
            }
            if ($this->request->data('UserAchievement')) {
                $this->loadModel('UserAchievement');
                $this->UserAchievement->deleteAll(array('user_id' => $this->currUserID));
                foreach($this->request->data('UserAchievement') as $i => $data) {
                    $url = $this->request->data('UserAchievement.'.$i.'.url');
                    $url = (strpos($url, 'http://') === false) ? 'http://'.$url : $url;
                    $this->request->data('UserAchievement.'.$i.'.url', $url);
                }
            }
            $this->User->save($this->request->data('User'));
            $this->setResponse($this->request->data);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
        //return $this->redirect(['controller' => 'Cloud', 'action' => 'index']);
    }
    */

    public function getEventsComment(){
      $this->loadModel('UserEventEvent');
      $this->loadModel('User');
      $data = $this->request->data;

      $conditions = array(
                'UserEventEvent.event_id' => $this->request->data('event_id'),
        'OR' => array(
                    'AND' => array(
                        'UserEventEvent.user_id' => $this->request->data('recepient_id'),
                        'UserEventEvent.recepient_id' => $this->currUserID,
                    ),
          array(
            'UserEventEvent.user_id' => $this->currUserID,
            'UserEventEvent.recepient_id' => $this->request->data('recepient_id'),
          )
        )
      );
      $chatMessages = $this->UserEventEvent->find('all', compact('conditions'));

      $conditions = array(
        'User.id' => $this->request->data('recepient_id'),
      );

      $users = $this->User->find('first', compact('conditions'));

      foreach ($chatMessages as $comment){?>
        <?php if($comment['UserEventEvent']['user_id'] == $this->currUserID): ?>
          <div class="item ">
            <a href="/User/view/<?=$this->currUserID;?>" id="user<?=$this->currUserID;?>">
              <img src="<?=$this->currUser['UserMedia']['url_img']?>" class="thumb avatar pull-left rating100" style="width:50px;" alt="<?=$this->currUser['User']['full_name']?>">
            </a>
            <div class="description">
              <span class="msgText"><?=Hash::get($comment, 'ChatMessage.message')?></span>
              <div class="clearfix">
                <div class="time"><?=date('d-m-Y H:i:s',strtotime($comment['UserEventEvent']['created']));?></div>
              </div>
            </div>
          </div>
        <?php elseif($comment['UserEventEvent']['recepient_id'] == $this->currUserID):?>
          <div class="item ">
            <a href="/User/view/<?=$comment['UserEventEvent']['user_id'];?>" id="user<?=$comment['UserEventEvent']['user_id'];?>">
              <img src="<?=$users['UserMedia']['url_img']?>" class="thumb avatar pull-left rating100" style="width:50px;" alt="<?=$users['User']['full_name']?>">
            </a>
            <div class="description">
              <span class="msgText"><?=Hash::get($comment, 'ChatMessage.message')?></span>
              <div class="clearfix">
                <div class="time"><?=date('d-m-Y H:i:s',strtotime($comment['UserEventEvent']['created']));?></div>
              </div>
            </div>
          </div>

        <?php endif;
      }
      exit;
    }

    public function addComment() {
        $this->loadModel('UserEventEvent');
        try {
            $this->UserEventEvent->addComment($this->request->data);
            $event_id = $this->request->data('UserEventEvent');
            $event_id = $event_id['event_id'];
            $this->setResponse($event_id);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
          //$this->redirect($this->referer());
      exit;
    }

    public function getEventInfo() {
            $this->loadModel('UserEvent');
      try {
        $id = $this->request->query('id');

          $id = $this->request->query('id');
          $conditions = array(
            'UserEvent.id' => $id,
          );
          $aMonths = array( __(' January'), __(' February'), __(' March'), __(' April'), __(' May'), __(' June'),
            __(' July'), __(' August'), __(' September'), __(' October'), __(' November'), __(' December') );
          $event = $this->UserEvent->find('first',compact('conditions'));
          if(!empty($this->request->query('span'))){

          }
            $created = Hash::get($event,'UserEvent.created');
          $starttime = strtotime(Hash::get($event,'UserEvent.event_time'));
            $endtime = strtotime(Hash::get($event,'UserEvent.event_end_time'));
            $period = ($endtime - $starttime);
            $hours = $period/(60*60);
            if($hours >= (7*24)){
                $p = round($hours/(7*24)).' '.__('weeks');
            }elseif($hours >= (24)){
                $p = round($hours/(24)).' '.__('days');
            }elseif($hours >= 1){
                $p = round($hours).' '.__('hours');
            }elseif($hours <= 0){
                $p = '0 '.__('minutes');
            }else{
                $p = round($hours*60).' '.__('minutes');
            }
            $month = $aMonths[date('m',strtotime($created))];
            $day = date('d',strtotime($created));
          $event['UserEvent']['created'] = $day.' '.$month;
          $event['UserEvent']['event_time'] = $p;

          echo json_encode($event);

      /*  $this->UserEvent->addComment();
        $event_id = $this->request->data('UserEventEvent');
        $event_id = $event_id['event_id'];
        $this->setResponse($event_id);*/
      } catch (Exception $e) {
          $this->setError($e->getMessage());
      }
          //$this->redirect($this->referer());
      exit;
        }

    function setInvites(){
      $this->loadModel('UserEventRequest');
      $user_id = $this->request->data('user_id');
      $event_id = $this->request->data('event_id');
      $data = array(
        'user_id' => $user_id,
        'event_id' => $event_id,
        'status' => '0'
      );
      $this->UserEventRequest->save($data);
      exit;
    }
    function saveTask(){
      $this->loadModel('UserEvent');
      $this->UserEvent->save($this->request->data('UserEvent'));
      exit;
    }

    function loadPlanetData(){
        $data = $this->request->data;
        $this->loadModel('User');

        $myId = $this->currUserID;

        $response = array();
        $response['myId'] = $myId;
        echo json_encode($response);
        exit;
    }

    function loadPlanet(){
        $data = $this->request->data;

        $this->loadModel('User');
        $this->loadModel('UserEvent');

        $conditions = array(
            'User.is_deleted' => '0',
            'AND' => array(
                array(
                    'User.lat >=' => $data['location']['minlat'],
                    'User.lat <=' => $data['location']['maxlat'],
                    ),
                array(
                    'User.lng >=' => $data['location']['minlng'],
                    'User.lng <=' => $data['location']['maxlng'],
                    ),
			),
		);
		/* Users will be find in window location open, if need find at all planet need some code rework */
		if(!empty($data['search'])) {
			$conditions['AND'][] = array('User.full_name LIKE ?' => '%' . $data['search'] . '%');
		}
		if(!empty($data['userKeys']) && empty($data['search'])){
			$conditions['AND'][] = array('User.id NOT' => $data['userKeys']);
		}

		// USERS
		$users = $this->User->find('all',compact('conditions'));
//		$UIDs = Hash::extract($users,'{n}.User.id');
		$users = $this->getUserInfo($users);

		// GROUP
		$groups = [];
		if(!empty($data['search'])) {
			$groups = $this->getGroupInfo($data);
		}

		$this->loadModel('GroupMember');
		$conditions = array('GroupMember.user_id' => $this->currUserID, 'GroupMember.is_deleted' => 0, 'GroupMember.approved' => 1);
		$userGroups = $this->GroupMember->find('all', compact('conditions'));
		$userGroups = Hash::extract($userGroups,'{n}.GroupMember.group_id');

        // UserEvent
		$this->loadModel('UserEventShare');
		$conditions = array(
			'UserEventShare.acceptance <> ?' => '-1',
			'UserEventShare.user_id' => $this->currUserID
			);

		$aEventShare = $this->UserEventShare->find('all', compact('conditions'));
		$aEventID = Hash::extract($aEventShare, '{n}.UserEventShare.user_event_id');

		$conditions = array(
			'UserEvent.is_delayed' => '0',
			'UserEvent.place_coords NOT' => null,
			/*'UserEvent.user_id' => $UIDs,*/
			'OR' => array(
				array(
					'AND' => array(
						'UserEvent.id' => $aEventID,
						'UserEvent.shared' => '1',
						)
				),
				array(
					'UserEvent.object_id' => $userGroups,
				),
				array(
					'AND' => array(
						'UserEvent.external' => '1',
						'UserEvent.external_time <' => date('Y-m-d H:i:s'),
						)
				),
			)
		);

		/* User Events - taskEvents  */
		if(!empty($data['search'])) {
			$conditions['AND'][] = array('UserEvent.title LIKE ?' => '%' . $data['search'] . '%');
		}
		if(!empty($data['eventKeys']) && empty($data['search'])){
			$conditions['AND'][] = array('UserEvent.id NOT' => $data['eventKeys']);
		}
		$order = array('UserEvent.event_time', 'UserEvent.created');
		$taskEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
		$taskArray = $this->getTaskInfo($taskEvents, $data);

		// Invest Project
		$investProjects = [];
		if(!empty($data['search'])) {
			$investProjects = $this->getInvestProjectInfo($data);
		}
		// External Event
		$externalEvents = [];
		if(!empty($data['search'])) {
			$externalEvents = $this->getExternalEventInfo($data);
		}

        // Data
		$data = array();
		$data['users'] = $users;
		$data['events'] = $taskArray;
		$data['groups'] = $groups;
		$data['invests'] = $investProjects;
		$data['externals'] = $externalEvents;
		//$data['tmp'] = $taskEvents;
		echo json_encode($data);
		exit;
    }

	/**
	 * Get User info like Image, skills, name... and push it to frontend
	 *
	 * @param $users
	 * @return array
	 */
	private function getUserInfo($users)
	{
		$out = [];
		/** @var View $view */
		$view = new View($this);
		$avatar = $view->loadHelper('Avatar');

		foreach($users as $user) {
			$img =  $avatar->getMediaLink($user, array(
				'size' => 'thumb50x50'
			));

			$out[$user['User']['id']] = array(
				'username' => $user['User']['full_name'],
				'lat' => $user['User']['lat'],
				'lng' => $user['User']['lng'],
				'image' => $img,
				'skills' => $user['User']['skills']
			);
		}
		return $out;
	}

	/**
	 * Get Group info, select id, title, desc which located in open now window location... and push it to frontend
	 *
	 * @param $data
	 * @return array
	 */
	private function getGroupInfo($data)
	{
		$this->loadModel('Group');
		/** @var View $view */
		$view = new View($this);
		$avatar = $view->loadHelper('Avatar');

		$clearGroups = [];
		$conditions = array(
			'Group.hidden' => '0',
			'AND' => array(
				array('Group.title LIKE ?' => '%' . $data['search'] . '%'),
				array(
					'User.lat >=' => $data['location']['minlat'],
					'User.lat <=' => $data['location']['maxlat'],
				),
				array(
					'User.lng >=' => $data['location']['minlng'],
					'User.lng <=' => $data['location']['maxlng'],
				),
			),
		);
		$groups = $this->Group->find('all', compact('conditions'));

		foreach ($groups as $key => $group) {
			$clearGroups[$key]['id'] = $group['Group']['id'];
			$clearGroups[$key]['title'] = $group['Group']['title'];
			$clearGroups[$key]['descr'] = $group['Group']['descr'];
			$clearGroups[$key]['lat'] = $group['User']['lat'];
			$clearGroups[$key]['lng'] = $group['User']['lng'];
			$clearGroups[$key]['created'] = $group['Group']['created'];
			$clearGroups[$key]['class'] = 'group';
			$clearGroups[$key]['image'] =  $avatar->getMediaLink($group, array(
				'size' => 'thumb50x50'
			));
		}
		return $clearGroups;
	}

	/**
	 * Get InvestProject info, select id, name which located in open now window location... and push it to frontend
	 *
	 * @param $data
	 * @return array
	 */
	private function getInvestProjectInfo($data)
	{
		$this->loadModel('InvestProject');

		$clearInvestProject = [];
		$conditions = array(
			'AND' => array(
				array('InvestProject.name LIKE ?' => '%' . $data['search'] . '%'),
				array(
					'User.lat >=' => $data['location']['minlat'],
					'User.lat <=' => $data['location']['maxlat'],
				),
				array(
					'User.lng >=' => $data['location']['minlng'],
					'User.lng <=' => $data['location']['maxlng'],
				),
			),
		);
		$investProjects = $this->InvestProject->find('all', compact('conditions'));

		foreach ($investProjects as $key => $investProject) {
			$clearInvestProject[$key]['id'] = $investProject['InvestProject']['id'];
			$clearInvestProject[$key]['title'] = $investProject['InvestProject']['name'];
			$clearInvestProject[$key]['descr'] = $investProject['InvestProject']['note'];
			$clearInvestProject[$key]['lat'] = $investProject['User']['lat'];
			$clearInvestProject[$key]['lng'] = $investProject['User']['lng'];
			$clearInvestProject[$key]['created'] = $investProject['InvestProject']['created'];
			$clearInvestProject[$key]['class'] = 'invest';
		}
		return $clearInvestProject;
	}

	/**
	 * Get External Events info and push it to frontend
	 *
	 * @param $data
	 * @return array
	 */
	private function getExternalEventInfo($data)
	{
		$this->loadModel('UserEvent');

		$conditions = array(
			'AND' => array(
				array(
					'UserEvent.title LIKE ?' => '%' . $data['search'] . '%',
					'UserEvent.external' => '1',
					),
				array(
					'User.lat >=' => $data['location']['minlat'],
					'User.lat <=' => $data['location']['maxlat'],
				),
				array(
					'User.lng >=' => $data['location']['minlng'],
					'User.lng <=' => $data['location']['maxlng'],
				),
			),
		);
		$order = array('UserEvent.event_time', 'UserEvent.created');
		$externalEvents = $this->UserEvent->find('all', compact('conditions', 'order'));
		$clearExternalEvent = $this->getTaskInfo($externalEvents, $data);

		return $clearExternalEvent;
	}

	/**
	 * Get Task with location as window location open
	 *
	 * @param $taskEvents
	 * @param $data
	 * @return array
	 */
	private function getTaskInfo(array $taskEvents, array $data)
	{
		$flag = true;
		$taskArray = [];
		/** @var View $view */
		$view = new View($this);
		$avatar = $view->loadHelper('Avatar');

		foreach ($taskEvents as &$event) {
			$coords = explode(',',trim($event['UserEvent']['place_coords'],'()'));
			$lat = isset($coords[0]) ? trim($coords[0]) : false;
			$lng = isset($coords[1]) ? trim($coords[1]) : false;
			if (($lat>=$data['location']['minlat']
				AND
				$lat<=$data['location']['maxlat'])
				AND
				($lng>=$data['location']['minlng']
				AND
				$lng<=$data['location']['maxlng']))
			{
				$flag = true;
			} else {
				$flag = false;
			}
			$event['visible'] = $flag;
			if ($flag) {

				$userEvent = $this->User->findById($event['User']['id']);
				$img =  $avatar->getMediaLink($userEvent, array(
					'size' => 'thumb300x200'
				));
				$recipientUser = 'recipientUser';
				$recipientId = $event['UserEvent']['recipient_id'];
				if (strlen($recipientId) > 0) {
					$userRec = $this->User->findById($recipientId);
					$recipientUser = $userRec['User']['full_name'];
				}
				$taskArray[$event['UserEvent']['id']] = array(
					'image'=>$img,
					'lat'=>$lat,
					'lng'=>$lng,
					'event_id'=>$event['UserEvent']['id'],
					'is_delayed'=>$event['UserEvent']['is_delayed'],
					'address'=>$event['UserEvent']['place_name'],
					'type'=>$event['UserEvent']['type'],
					'title'=>$event['UserEvent']['title'],
					'descr'=>$event['UserEvent']['descr'],
					'price'=>$event['UserEvent']['price'],
					'user_id'=>$event['UserEvent']['user_id'],
					'user_full_name'=>$event['User']['full_name'],
					'recipient_id'=>$recipientId,
					'recipient_full_name'=>$recipientUser,
					'created'=>Hash::get($event,'UserEvent.created'),
					'starttime'=>strtotime(Hash::get($event,'UserEvent.event_time')),
					'endtime'=>strtotime(Hash::get($event,'UserEvent.event_end_time')),
					'UserEventShare'=>$event['UserEventShare']
				);
			}
		}
		return $taskArray;
	}

    function acceptInvites(){
      $this->loadModel('UserEventRequest');
      $user_id = $this->request->data('user_id');
      $id = $this->request->data('id');
      $event_id = $this->request->data('event_id');
      $data = array(
        'UserEventRequest.user_id' => $user_id,
        'UserEventRequest.event_id' => $event_id,
        'UserEventRequest.status' => '1'
      );
      $conditions = array(
        'UserEventRequest.id' => $id,
      );
      $this->UserEventRequest->updateAll($data,$conditions);
      exit;
    }
    function discartInvites(){
      $this->loadModel('UserEventRequest');
      $user_id = $this->request->data('user_id');
      $id = $this->request->data('id');
      $event_id = $this->request->data('event_id');
      $data = array(
        'UserEventRequest.user_id' => $user_id,
        'UserEventRequest.event_id' => $event_id,
        'UserEventRequest.status' => '-1'
      );
      $conditions = array(
        'UserEventRequest.id' => $id,
      );
      $this->UserEventRequest->updateAll($data,$conditions);
      $this->checkExternalStatus($event_id);
      exit;
    }

    function acceptShareInvites(){
        $this->loadModel('UserEventShares');
        $id = $this->request->data('id');
        $data = array(
            'UserEventShares.acceptance' => '1'
        );
        $conditions = array(
            'UserEventShares.id' => $id,
        );
        $this->UserEventShares->updateAll($data,$conditions);
        exit;
    }

    function deleteShareInvites() {
        $this->loadModel('UserEventShares');
        $id = $this->request->data('id');
        $event_id = $this->request->data('event_id');
        $this->UserEventShares->delete($id);
        $this->checkExternalStatus($event_id);
        exit;
    }

    function checkUserEventRequest(){
      $this->loadModel('UserEventRequestLimit');
      $response = $this->UserEventRequestLimit->checkUsedRequest($this->currUserID);
//exit;
      echo json_encode(array('allowed' => $response));
      exit();
    }
    function getMediaURL(){
      $id = $this->request->query('user_id');
      $this->loadModel('User');
      $user = $this->User->findById($id);
      $view = new View($this);
      $avatar = $view->loadHelper('Avatar');

      $img =  $avatar->getMediaLink($user, array(
         'size' => 'thumb300x150'
     ));

      echo json_encode($img);
      exit();
    }

    function getCategories(){
      $this->loadModel('UserEventCategory');

      $categories = $this->UserEventCategory->find('all');

      echo json_encode($categories);
      exit();
    }

    public function checkExternalStatus($event_id = null) {
        $this->loadModel('UserEvent');
        $this->loadModel('UserEventShare');
        if($event_id !== null) {
            $event = $this->UserEvent->findById($event_id);
            if( (int) $event['UserEvent']['external'] == 0 ) {
                $countShares = $this->UserEventShare->find('count',array(
                    'conditions' => array(
                        'UserEventShare.user_event_id' => $event_id,
                        'UserEventShare.user_id <> ?' => $this->currUserID,
                        'UserEventShare.acceptance <> ?' => '-1'
                    )
                ));
                if($countShares == 0) {
                    $this->UserEvent->updateAll(array('UserEvent.external' => "1"), array('UserEvent.id' => $event_id));
                }
            }
        }
    }


    /**
     * Delete Video File
     * @throws Exception
     */
    public function delVideo() {
        try {
            $media_id = $this->request->data('id');
            if (!$media_id) {
                throw new Exception('Incorrect request');
            }
            $this->loadModel('UserVideo');
            $this->UserVideo->deleteVideo($this->currUserID, $media_id);
            exit;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}
