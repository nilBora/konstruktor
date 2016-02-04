<?php
App::uses('AppModel', 'Model');
class Share extends AppModel {
//    public $actsAs = array('Containable');
    public $useTable = 'share';

    public $belongsTo = array(
        'Cloud' => array(
            'className' => 'Cloud',
            'conditions' => array('Share.target' => Share::CLOUD),
            'foreignKey' => 'object_id',
        ),
    );

    const LINK_ACCESS = 1;
    const INDIVIDUAL_ACCESS = 2;
    const EDIT_ACCESS = 3;

    const CLOUD = 1;
    const DOCUMENT = 2;

    public function find_folder_items($currentUserID, $target) {
        $this->loadModel('Note');
        $conditions = array(
            'OR' => array(
                array(
                    'Share.user_id' => $currentUserID,
                    'Share.target' => $target
                ),
                array(
                    'Share.user_id' => null,
                    'Share.target' => $target
                ),
            )
        );
        if($target == Share::CLOUD)
            $conditions['Cloud.media_id'] = 0;
        else
            $conditions['Note.is_folder'] = 1;
        $records = $this->find('all', compact('conditions'));
        $ids = Hash::extract($records, '{n}.Cloud.id');
        $files = $this->Cloud->find_all_files($ids);
        $docs = $this->Note->find_all_files($ids);
        $result = array_merge($files, $docs);

        return $result;
    }

    public function sharedByLink($object = null) {
        $conditions = array(
            'Share.user_id' => null,
            'Share.target' => Share::CLOUD
        );
        if(!is_null($object))
            $conditions['Share.object_id'] = $object;

        $result = $this->find('all', compact('conditions'));

        if(!empty($result))
            $result = Hash::extract($result, '{n}.Cloud.id');
        return $result;
    }

    public function remove_already_shared($saved, $new, $target) {
        foreach($new as $key => $new_record) {
            foreach($saved as $saved_record) {
                if($new_record['Share']['object_id'] == $saved_record['Share']['object_id'] && $new_record['Share']['user_id'] == $saved_record['Share']['user_id'] && $saved_record['Share']['target'] == $target) {
                    unset($new[$key]);
                }
            }
        }
        return $new;
    }

    public function CloudSharedBy($user_id) {
        $result = [];
        if(!empty($user_id) && is_numeric($user_id)) {
            $conditions = array(
                'Cloud.user_id' => $user_id,
                'Share.target' => Share::CLOUD
            );
            $group = array('Share.object_id');
            $result = $this->find('all', compact('conditions', 'group'));
            if(!empty($result)) {
                $result = Hash::extract($result, '{n}.Share.object_id');
            }
        }
        return $result;
    }

    public function DocumentSharedBy($user_id) {
        $result = [];
        if(!empty($user_id) && is_numeric($user_id)) {
            $conditions = array(
                'Note.user_id' => $user_id,
                'Share.target' => Share::DOCUMENT
            );
            $group = array('Share.object_id');

            $result = $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'notes',
                        'alias' => 'Note',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Note.id = Share.object_id'
                        )
                    )
                ),
                'conditions' => $conditions,
                'fields' => array('Share.*' ,'Note.*'),
                'group' => $group,
                'contain' => false
            ));

            if(!empty($result)) {
                $result = Hash::extract($result, '{n}.Share.object_id');
            }

        }
        return $result;
    }

    public function timelineEvents($currUserID, $date, $date2, $view = 0, $mail = false) {
      $this->loadModel('Cloud');

      $conditions = $this->dateRange('Share.created', $date, $date2);
      if($mail) $conditions = $this->dateTimeRange('Article.created', $date, $date2);
      $conditions['Share.user_id'] = $currUserID;
  		$conditions['Share.active'] = 1;
      $order = 'Share.created DESC';
      $aShare = $this->find('all', compact('conditions', 'order'));
      //var_dump($aShare);exit;
      $ids = Hash::extract($aShare, '{n}.Share.object_id');
      $conditions = array(
          'Cloud.id' => $ids,
      );
      $aCloud = $this->Cloud->find('all', compact('conditions'));
      $aShare  = Hash::merge($aShare, $aCloud);
      return $aShare;
  	}
}
