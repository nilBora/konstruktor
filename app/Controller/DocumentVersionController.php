<?php
App::uses('AppController', 'Controller');
class DocumentVersionController extends AppController {
//    public $name = 'DocumentVersion';
    public $layout = 'profile_new';
    public $uses = array('DocumentVersion');

    public function loadVersion() {
        if($this->request->is('ajax')) {
            $result = array('success' => false, 'data' => array());
            if(!empty($this->request->data('version_id'))) {
                $version_id = $this->request->data('version_id');
                if(is_numeric($version_id)) {
                    $db = $this->DocumentVersion->getDataSource();
                    $this->DocumentVersion->updateAll(
                        array('DocumentVersion.modified' => $db->expression('NOW()')->value),
                        array('DocumentVersion.id' => $version_id)
                    );
                    $version = $this->DocumentVersion->findById($version_id);

                    if(!empty($version)) {
                        $result['success'] = true;
                        $result['data']['doc'] = array(
                            'title' => $version['DocumentVersion']['title'],
                            'body' => $version['DocumentVersion']['body'],
                            'modified' => $version['DocumentVersion']['modified'],
                            'niceday' => $version['DocumentVersion']['niceday'],
                        );
                        $result['data']['user'] = array(
                            'id' => $version['User']['id'],
                            'full_name' => $version['User']['full_name'],
                        );
                        echo json_encode($result);
                    }
                }
            }
        }
        die();
    }

    public function loadLastVersion() {
        if($this->request->is('ajax')) {
            $result = array('success' => false, 'data' => array());
            if(!empty($this->request->data('doc_id'))) {
                $doc_id = $this->request->data('doc_id');

                $conditions = array(
                    'doc_id' => $doc_id
                );
                $limit = array('limit 1');
                $order = array('DocumentVersion.id DESC');

                $version = $this->DocumentVersion->find('all', compact('conditions', 'order', 'limit'));

//                var_dump($version);die;


                if (!empty($version)) {
                    $result['success'] = true;
                    $document = Hash::extract($version, '{n}.DocumentVersion');
                    $user = Hash::extract($version, '{n}.User');
                    $result['data']['doc'] = array(
                        'title' => $document[0]['title'],
                        'body' => $document[0]['body'],
                        'modified' => $document[0]['modified'],
                        'niceday' => $document[0]['niceday'],
                    );
                    $result['data']['user'] = array(
                        'id' => $user[0]['id'],
                        'full_name' => $user[0]['full_name'],
                    );
                    echo json_encode($result);
                }
            }
        }
        die();
    }

}