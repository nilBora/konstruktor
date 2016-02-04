<?php
App::uses('AppController', 'Controller');
App::uses('SiteController', 'Controller');
class CreateLimitRecordsController extends AppController {

    public $uses = array('User', 'StorageLimit');
    public $layout = 'profile_new';
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function UpdateAllRecords() {
        $this->autoRender = false;
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $fields = array('full_name');
        $users = $this->User->find('list', compact('fields'));
        foreach($users as $id => $user) {
            $this->StorageLimit->updateAllFiles($id);
            $chat_size = intval($this->StorageLimit->chatFileSize($id));
            $cloud_size = intval($this->StorageLimit->cloudFileSize($id));
            $task_size = intval($this->StorageLimit->taskDiscussionFileSize($id));
            $conditions = array(
                'StorageLimit.user_id' => $id
            );

            $record = $this->StorageLimit->getStorageInfo($id);
            $data = array(
                'message_file_size' => $chat_size,
                'project_file_size' => $task_size,
                'cloud_size' => $cloud_size,
                );

            if(!empty($record)) {
                $data['id'] = $record['StorageLimit']['id'];
            }
            else {
                $data['user_id'] = $id;
                $this->StorageLimit->create();
                $data['storage_limit'] = 2*pow(1024,3);

            }

            $this->StorageLimit->save($data);
        }
        echo "Successfully Updated";
    }

}
