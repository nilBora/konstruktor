<?php

/**
 * Class InvestProject
 */
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');

/**
 * Class InvestProject
 * @property Media Media
 * @property InvestVideo InvestVideo
 * @property InvestReward InvestReward
 */
class InvestProject extends AppModel {

	public $useTable = 'invest_project';

	public $hasOne = array(
		'Avatar' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Avatar.object_type' => 'InvestProjectAvatar')
		)
	);
	public $belongsTo = array(
		'Group' => array(
            'className' => 'Group',
			'foreignKey' => 'group_id'
        ),
		'InvestCategory' => array(
            'className' => 'InvestCategory',
			'foreignKey' => 'category_id'
        ),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	public $hasMany = array(
		'GalleryImages' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('GalleryImages.object_type' => 'InvestProjectGallery')
		),
		'GalleryVideos' => array(
			'className' => 'InvestVideo',
			'foreignKey' => 'project_id',
		),
		'Rewards' => array(
			'className' => 'InvestReward',
			'foreignKey' => 'project_id',
			'order' => array('Rewards.id' => 'DESC')
		),
	);

	public $actsAs = array('Ratingable');

	public function search($userId = null, $categoryId = null, $q = null) {
		$conditions = array();
		if ($userId) {
			$conditions['InvestProject.user_id'] = $userId;
		}
		if ($categoryId) {
			$conditions['InvestProject.category_id'] = $categoryId;
		}
		if ($q) {
			$conditions['InvestProject.name LIKE ?'] = '%' . $q . '%';
		}
		$investProject = $this->find('all', array('conditions' => $conditions));
		$investProject = Hash::combine($investProject, '{n}.InvestProject.id', '{n}');
		return array(
			'aInvestProject' => $investProject,
		);
	}

	/**
	 * @param $id - Project Id
	 * @return array
	 * @throws Exception
	 */
	public function getOne($id) {
		$this->loadModel('InvestReward');
		$item = $this->find('first', array('conditions' => array('InvestProject.id' => $id)));
		if (!$item) {
			throw new Exception(__('Project is not found'));
		}
		$item['Rewards'] = $this->InvestReward->get( Hash::extract($item, 'Rewards.{n}.id') );
		return array(
			'aInvestProject' => $item,
		);
	}

	public function addProject($userId, $data) {
		// project
		$data['InvestProject']['user_id'] = $userId;
		$data['InvestProject']['body'] = $data['body'];
		if (!$this->save($data)) {
			throw new Exception(__('Invalid operation'));
		}
		$projectId = $this->id;
		$this->loadModel('Media');
		$this->loadModel('InvestVideo');
		$this->loadModel('InvestReward');
		// avatar
		if (isset($data['InvestProjectAvatar']['id']) && $data['InvestProjectAvatar']['id']) {
			$this->Media->updateAll(
				array('Media.object_id' => $projectId, 'Media.object_type' => "'InvestProjectAvatar'"),
				array('Media.id' => (int) $data['InvestProjectAvatar']['id'])
			);
		}
		// gallery images
		if (!empty($data['InvestProjectGallery'])) {
			$this->Media->updateAll(
				array('Media.object_id' => $projectId, 'Media.object_type' => "'InvestProjectGallery'"),
				array('Media.id' => $data['InvestProjectGallery'])
			);
		}
		// project body images (from wysiwyg redactor)
		if (!empty($data['InvestProjectBody'])) {
			$this->Media->updateAll(
				array('Media.object_id' => $projectId, 'Media.object_type' => "'InvestProjectBody'"),
				array('Media.id' => $data['InvestProjectBody'])
			);
		}
		// video
		if (!empty($data['InvestVideo'])) {
			foreach ($data['InvestVideo'] as $youtubeId) {
				$this->InvestVideo->addVideo($projectId, array('InvestVideo' => array('youtube_id' => $youtubeId)));
			}
		}
		// rewards
		if (!empty($data['InvestReward'])) {
			foreach ($data['InvestReward'] as $item) {
				$this->InvestReward->addReward($userId, $projectId, array('InvestReward' => $item));
			}
		}

		return $projectId;
	}

	public function editProject($id, $userId, $data) {
		$this->id = $id;
		// project
		$data['InvestProject']['user_id'] = $userId;
		$data['InvestProject']['body'] = $data['body'];
		if (!$this->save($data)) {
			throw new Exception(__('Invalid operation'));
		}
		$projectId = $this->id;
		$this->loadModel('InvestVideo');
		$this->loadModel('InvestReward');
		// video
		$this->InvestVideo->deleteAll(array('InvestVideo.project_id' => $projectId));
		if (!empty($data['InvestVideo'])) {
			foreach ($data['InvestVideo'] as $youtubeId) {
				$this->InvestVideo->addVideo($projectId, array('InvestVideo' => array('youtube_id' => $youtubeId)));
			}
		}
		// rewards
		if (!empty($data['InvestReward'])) {
			foreach ($data['InvestReward'] as $item) {
				if (isset($item['id'])) {
					$this->InvestReward->editReward($item['id'], array('InvestReward' => $item));
				} else {
					$this->InvestReward->addReward($userId, $projectId, array('InvestReward' => $item));
				}
			}
		}
	}
}
