<?
App::uses('AppModel', 'Model');
class Note extends AppModel {
	public $actsAs = array('Tree', 'Ratingable');
	public $useTable = 'notes';
	public $name = 'Note';

//    public $belongsTo = array(
//        'User' => array(
//            'foreignKey' => 'last_updated_by',
//        )
//    );

	public $validate = array(
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field must not be blank',
			),

			'unique'=>array(
				'rule' => array('checkUnique', array('title', 'user_id', 'parent_id', 'is_folder')),
				'message' => 'Document with such name is already exist'
			)
		)
	);
	//4й параметр уберем как только николай сделает редактор таблиц
	public function search($currUserID, $parent, $q, $onlyText=false, $shared_id = null)
    {
        $this->loadModel('DocumentVersion');
        $this->loadModel('Share');
        if ($parent != 'shared') {

            if ($onlyText) {
                $conditions['Note.type'] = 'text';
            }

            if (!$q) {
                $conditions['Note.parent_id'] = $parent;
            } else {
                if (!preg_match('/[A-Za-z]/', $q)) {
                    $t = $this->transliterateRegex($q);
                    $conditions = array(
                        'OR' => array(
                            array('Note.title REGEXP ?' => $t),
                            array('Note.title  LIKE ?' => '%' . $q . '%')
                        )
                    );
                } else {
                    $conditions = array('Note.title LIKE ?' => '%' . $q . '%');
                }
            }

            $conditions['user_id'] = $currUserID;
            $order = 'Note.is_folder DESC, Note.created, Note.title';
            $aNotes = $this->find('all', compact('conditions', 'order'));
            $sharedDoc = $this->Share->DocumentSharedBy($currUserID);

            foreach ($aNotes as &$note) {
                $parentID = Hash::extract($note, 'Note.id');
                $notes = $this->findAllByParentIdAndIsFolder($parentID, '0');
                $note['Note']['fileCount'] = count($notes);
                if(in_array($note['Note']['id'], $sharedDoc))
                    $note['Note']['is_shared'] = true;
            }

            return (array(
                'aNote' => $parent ? $this->find('first', array('conditions' => array('Note.user_id' => $currUserID, 'Note.id' => $parent))) : null,
                'aNotes' => $aNotes)
            );
        }
        else {

            $conditions = array(
                'Share.user_id' => $currUserID,
                'Share.target' => Share::DOCUMENT,
                'Share.share_type' => array(Share::INDIVIDUAL_ACCESS, Share::EDIT_ACCESS),
            );
            if ($q) {
                $conditions['Cloud.name LIKE ?'] = '%' . $q . '%';
            }

            $order = 'Cloud.media_id, Cloud.created, Cloud.name';

            $conditions['Note.parent_id'] = $shared_id;

            $results = $this->Share->find('all', array(
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
                'fields' => array('Share.*' ,'Note.*','MAX(Share.share_type) as max_priv'),
                'order' => array('Share.share_type ASC'),
                'group' => array('Note.id')
            ));

            foreach ($results as &$doc) {
                $id = $doc['Note']['id'];
                $doc['Note']['url_preview'] = Router::url("/Cloud/documentView/$id", true);
                $doc['Note']['version'] = $this->DocumentVersion->lastUpdatedBy($id);
            }

            return (array(
                'aNote' => $parent ? $this->find('first', array('conditions' => array('Note.user_id' => $currUserID, 'Note.id' => $parent))) : null,
                'aNotes' => $results)
            );
        }

	}

	public function deleteFolder($id) {
		$this->id = $id;
		$childrenIds = array($id);
		foreach ($this->children() as $child) {
			$childrenIds[] = $child['Note']['id'];
		}
		$this->deleteAll(array('id' => $childrenIds));
		$this->delete($id);
	}

	public function move($userId, $id, $parentId = 0) {
		$item = $this->find('first', array('conditions' => array('Note.id' => $id, 'Note.user_id' => $userId)));
		if (!$item) {
			throw new Exception('Folder or file is not found');
		}
		if ($parentId) {
			$parent = $this->find('first',
				array('conditions' => array('Note.id' => $parentId, 'Note.user_id' => $userId)));
			if (!$parent) {
				throw new Exception('Folder or file is not found');
			}
		}

		$isFolder = Hash::get($item, 'Note.is_folder');
		$title = Hash::get($item, 'Note.title');

		$conditions = array('AND' => array('Note.title' => $title, 'Note.parent_id' => $parentId, 'Note.user_id' => $userId, 'is_folder' => $isFolder));
		$exists = $this->find('first', array('conditions' => $conditions));

		if ($exists) {
			throw new Exception(__('Folder or file with such name is already exist in destination folder'));
		}

		$this->id = $id;
		$this->parent_id = $parentId ? $parentId : '';
		$this->saveField('parent_id', $this->parent_id);
	}

    public function find_all_files($folder_ids) {
        $file_ids = [];
        foreach($folder_ids as $id) {
            $this->id = $id;
            foreach($this->children() as $child) {
                if($child['Note']['is_folder'] != 0) {
                    if(!in_array($child['Note']['id'], $file_ids))
                        $file_ids[] = $child['Note']['id'];
                }
            }
        }
        return $file_ids;
    }

}
