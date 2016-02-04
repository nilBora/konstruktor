<?
App::uses('AppModel', 'Model');
class GroupVideo extends AppModel
{
	/**
	 * Find all media that belong to Group
	 *
	 * @param $user_id
	 * @return array
	 */
	public function findMedia($group_id) {

		$allMedia = $this->find('all', array('group_id' => $group_id));

		$this->loadModel('Media');
		/** @var Media $mediaFile */
		$mediaFile = $this->Media;
		$groupMedia = [];

		foreach ($allMedia as $media) {
			$file = $mediaFile->findById($media['GroupVideo']['media_id']);
			if (count($file)) {
				$file['Media']['url_preview'] = $this->getFilePreviewUrl($file['Media']['id']);
				$file['Media']['group_video_id'] = $media['GroupVideo']['id'];
				$groupMedia[] = $file;
			}
		}
		return $groupMedia;
	}

	/**
	 * Get url for preview video
	 *
	 * @param $fileId
	 * @return string
	 */
	public function getFilePreviewUrl( $fileId ) {
		return Router::url('/File/preview/' . $fileId , true);
	}

	/**
	 * Delete group video files
	 *
	 * @param $id
	 * @param $group_id
	 * @param $media_id
	 * @throws Exception
	 */
	public function deleteVideo($id, $group_id, $media_id) {
		$this->loadModel('Media.Media');
		$item = $this->find('first', array('conditions' => array('GroupVideo.id' => $id, 'GroupVideo.group_id' => $group_id)));
		if (!$item) {
			throw new Exception('Folder or file is not found');
		}

		$this->delete($id);
		$this->Media->delete($media_id);

	}
}
