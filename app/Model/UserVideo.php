<?
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class UserVideo extends AppModel
{

	/**
	 * Find all media that belong to User
	 *
	 * @param $user_id
	 * @return array
	 */
	public function findMedia($user_id, $object_type)
	{
		$this->loadModel('Media');
		/* All user video - media $allMedia */
		$allMedia = $this->find('all', array(
			'conditions' => array('UserVideo.user_id' => $user_id, 'UserVideo.object_type' => $object_type)
		));
		/** @var Media $mediaFile */
		$mediaFile = $this->Media;
		$userMedia = [];

		foreach ($allMedia as $media) {
			$file = $mediaFile->findById($media['UserVideo']['media_id']);
			if (count($file)) {
				$file['Media']['url_preview'] = $this->getFilePreviewUrl($file['Media']['id']);
				$userMedia[] = $file;
			}
		}

		return $userMedia;
	}

	/**
	 * Get url for preview video
	 *
	 * @param $fileId
	 * @return string
	 */
	public function getFilePreviewUrl( $fileId )
	{
		return Router::url('/File/preview/' . $fileId , true);
	}

	/**
	 * Delete user video files
	 *
	 * @param $userId
	 * @param $media_id
	 * @throws Exception
	 */
	public function deleteVideo($userId, $media_id) {
		$this->loadModel('Media.Media');
		$item = $this->find('first', array('conditions' => array('UserVideo.media_id' => $media_id, 'UserVideo.user_id' => $userId)));
		if (!$item) {
			throw new Exception('Folder or file is not found');
		}

		$this->delete($item['UserVideo']['id']);
		$this->Media->delete($media_id);

	}
}
