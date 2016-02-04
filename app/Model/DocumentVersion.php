<?php
App::uses('AppModel', 'Model');
class DocumentVersion extends AppModel {

    public $useTable = 'document_versions';
    public $belongsTo = array(
        'User' => array(
            'foreignKey' => 'user_id',
        )
    );

    public function updateLastVersion( $note ) {

        if(!empty($note)) {
            $conditions = array(
                'DocumentVersion.doc_id' => $note['Note']['id'],
            );
            $order = array('DocumentVersion.modified DESC');
            $results = $this->find('all', compact('conditions', 'order'));

            if (count($results) >= Configure::read('DocumentVersion.versionCount')) {

                $ids = Hash::extract($results, '{n}.DocumentVersion.id');
                $toDelete = array_slice($ids, Configure::read('DocumentVersion.versionCount') - 1);

                $this->deleteAll(array('DocumentVersion.id' => $toDelete));
            }
            $data = array(
                'DocumentVersion' => array(
                    'doc_id' => $note['Note']['id'],
                    'user_id' => $note['Note']['last_updated_by'],
                    'body' => $note['Note']['body'],
                    'title' => $note['Note']['title']
                )
            );
            $this->save($data);
        }

    }

    public function getVersions($id) {
        $results = [];
        if(!empty($id) && is_numeric($id)) {
            $conditions = array(
                'DocumentVersion.doc_id' => $id
            );
            $order = array('DocumentVersion.modified DESC');

            $fields = array(
                'DocumentVersion.id',
                'DocumentVersion.modified',
                'DocumentVersion.user_id',
                'User.full_name'
            );

            $results = $this::find('all', compact('conditions', 'order'));
        }
        return $results;
    }

    public function lastUpdatedBy($id) {
        $user = [];
        $conditions = array(
            'DocumentVersion.doc_id' => $id
        );
        $limit = array('limit' => 1);
        $order = array('DocumentVersion.id DESC');
        $result = $this->find('all', compact('conditions', 'order', 'limit'));

        if(!empty($result)) {

            $user['user_id'] = $result[0]['User']['id'];
            $user['user_full_name'] = $result[0]['User']['full_name'];
            $user['last_modified'] = $result[0]['DocumentVersion']['niceday'];
        }

        return $user;
    }

    public function afterFind($results, $primary = false) {
        App::uses('CakeTime', 'Utility');
        foreach($results as &$result) {
            if(isset($result[$this->alias]['modified']))
                $result[$this->alias]['niceday'] = CakeTime::niceShort($result[$this->alias]['modified']);
        }
        return $results;
    }

}
