<?php
App::uses('AppModel', 'Model');
class StorageLimit extends AppModel {
    public $useTable = 'storage_limit';
    public $actsAs = array(
        'Containable'
    );

    public function getChatFileIdList($user_id) {
        $this->loadModel('ChatEvent');
        $conditions = array(
            'ChatEvent.initiator_id' => $user_id,
            'ChatEvent.user_id' => $user_id,
            'ChatEvent.event_type' => 6,
        );

        $this->ChatEvent->recursive = -1;
        $fields = array('ChatEvent.file_id');
        $file_ids = $this->ChatEvent->find('list', compact('conditions', 'fields'));
        return $file_ids;
    }

    function updateChatFileSize($user_id) {
        $this->loadModel('Media');
        $file_ids = $this->getChatFileIdList($user_id);
        $this->Media->updateFileSize($file_ids);
    }


    public function chatFileSize($user_id) {
        $total_size = 0;
        $this->loadModel('Media');
        $file_ids = $this->getChatFileIdList($user_id);
        if(!empty($file_ids)) {

            $fields = array('SUM(Media.orig_fsize) as total');
            $conditions = array(
                'Media.id' => array_values($file_ids)
            );
            $files = $this->Media->find('first', compact('conditions', 'fields'));
            if (!empty($files))
                $total_size = $files[0]['total'];
        }

        return $total_size;
    }

    public function fileSizeByKey($user_id, $key) {
        $size = 0;
        if(!empty($user_id) && !empty($key)) {
            if($this->hasField($key)) {
                $conditions = array(
                    'user_id' => $user_id
                );
                $fields = array(
                    "StorageLimit.id",
                    "StorageLimit.$key"
                );
                $result = $this->find('list', compact('conditions', 'fields'));
                if(!empty($result)) {
                    $temp = reset($result);
                    if(!is_null($temp))
                        $size = $temp;
                }
            }
        }
        return $size;
    }

    public function cloudFileSize($user_id) {
        $total_size = 0;
        $this->loadModel('Cloud');
        $this->loadModel('Media');
        $conditions = array(
            'user_id' => $user_id,
            'media_id !=' => 0
        );
        $fields = array('Media.*');
        $results = $this->Cloud->find('all', compact('conditions', 'fields'));

        if(!empty($results)) {
            foreach($results as $result) {
                if(!is_null($result['Media']['orig_fsize']))
                    $total_size += $result['Media']['orig_fsize'];
            }
        }
        return $total_size;
    }

    public function updateCloudFileSize($user_id) {
        $this->loadModel('Cloud');
        $this->loadModel('Media');
        $conditions = array(
            'user_id' => $user_id,
            'media_id !=' => 0
        );
        $fields = array('Media.*');
        $results = $this->Cloud->find('all', compact('conditions', 'fields'));
        $file_datas = Hash::combine($results, '{n}.Media.id', '{n}.Media.orig_fsize');
        $file_ids = array();
        foreach($file_datas as $id => $data) {
            if(is_null($data))
                $file_ids[] = $id;
        }

        $this->Media->updateFileSize($file_ids);

    }

    public function taskDiscussionFileSize($user_id) {
        $total_size = 0;
        $this->loadModel('ProjectEvent');
        $this->loadModel('ChatMessage');

        $conditions = array(
            'ProjectEvent.user_id' => $user_id,
            'ProjectEvent.event_type' => 7
        );

        $results = $this->ProjectEvent->find('all', array(
            'joins' => array(
                array(
                    'table' => 'chat_messages',
                    'alias' => 'ChatMessage',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ChatMessage.id = ProjectEvent.msg_id'
                    )
                ),
                array(
                    'table' => 'media',
                    'alias' => 'Media',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Media.object_id = ChatMessage.id'
                    )
                )
            ),
            'conditions' => $conditions,
            'fields' => array('Media.orig_fsize'),
        ));

        if(!empty($results)) {
            $results = Hash::extract($results, '{n}.Media.orig_fsize');
            foreach($results as $result)
                if(!is_null($result))
                    $total_size += $result;
        }

        return $total_size;

    }

    public function UpdateTaskDiscussionFileSize($user_id) {
        $this->loadModel('ProjectEvent');
        $this->loadModel('ChatMessage');
        $this->loadModel('Media');
        $conditions = array(
            'ProjectEvent.user_id' => $user_id,
            'ProjectEvent.event_type' => 7
        );

        $results = $this->ProjectEvent->find('all', array(
            'joins' => array(
                array(
                    'table' => 'chat_messages',
                    'alias' => 'ChatMessage',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ChatMessage.id = ProjectEvent.msg_id'
                    )
                ),
                array(
                    'table' => 'media',
                    'alias' => 'Media',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Media.object_id = ChatMessage.id'
                    )
                )
            ),
            'conditions' => $conditions,
            'fields' => array('Media.id' ,'Media.orig_fsize'),
        ));

        if(!empty($results)) {
            $file_datas = Hash::combine($results, '{n}.Media.id', '{n}.Media.orig_fsize');
            $file_ids = array();
            foreach($file_datas as $id => $data) {
                if(is_null($data))
                    $file_ids[] = $id;
            }

            $this->Media->updateFileSize($file_ids);
        }

    }

    public function getStorageInfo($user_id) {
        $conditions = array(
            'StorageLimit.user_id' => $user_id
        );


        $result = $this->find('first', compact('conditions'));

        return $result;
    }

    public function updateAllFiles($user_id) {
        $this->UpdateTaskDiscussionFileSize($user_id);
        $this->updateCloudFileSize($user_id);
        $this->updateChatFileSize($user_id);
    }

    public function getStats($user_id) {
        $result = array();

        $conditions = array(
            'StorageLimit.user_id' => $user_id
        );

        $fields = array(
            'message_file_size',
            'project_file_size',
            'cloud_size',
            'storage_limit',
        );

        $usage_data = $this->find('first', compact('conditions', 'fields'));

        $usage_size = 0;

        if(!empty($usage_data)) {
            foreach($usage_data['StorageLimit'] as $key => $data) {
                if($key == 'storage_limit') {
                    $result['limit_bytes'] = $data;
                    $result['limit'] = $this->human_filesize($data);
                }
                else {;
                    $usage_size += $data;
                    $result[$key] = $data;
                }
            }
            $result['usage_percent'] = round($usage_size/$result['limit_bytes']*100, 2);
            $result['already_used_bytes'] = $usage_size;
            $result['already_used'] = $this->human_filesize($usage_size);

        }

        return $result;
    }

    public function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public function checklimit($filesize, $userId = null) {
        if(empty($userId)){
            App::uses('CakeSession', 'Model/Datasource');
            $userId = CakeSession::read('Auth.User.id');
        }
        $result = array();

        $conditions = array(
            'StorageLimit.user_id' => $userId
        );

        $fields = array(
            'message_file_size',
            'project_file_size',
            'cloud_size',
            'storage_limit',
        );

        $usage_data = $this->find('first', compact('conditions', 'fields'));
        $usage_size= 0;

        if(!empty($usage_data)) {
            foreach($usage_data['StorageLimit'] as $key => $data) {
                if($key == 'storage_limit') {
                    $result['limit_bytes'] = $data;
                }
                else {;
                    $usage_size += $data;
                    $result[$key] = $data;
                }
            }
            if($filesize + $usage_size <= $result['limit_bytes'])
                return true;
            else {
                return false;
            }

        }
        return true;
    }

}
