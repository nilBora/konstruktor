<?php
App::uses('AppHelper', 'View/Helper');
class MediaHelper extends AppHelper {
	private $MediaPath;
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		
		App::uses('MediaPath', 'Media.Vendor');
		$this->MediaPath = new MediaPath();
	}
	
	function imageUrl($media, $size) {
		/*
		if (!(isset($mediaRow['Media']) && $mediaRow['Media'] && isset($mediaRow['Media']['id']) && $mediaRow['Media']['id']) ) {
			return '';
		}
		*/
		/*
		if (!(isset($media['id']) && $media['id'])) {
			return '';
		}
		$media = $mediaRow['Media'];
		return $this->MediaPath->getImageUrl($media['object_type'], $media['id'], $size, $media['file'].$media['ext']);
		*/
		if (!isset($media['url_img']) && $media['url_img']) {
			return '';
		}
		return str_replace('noresize', $size, $media['url_img']);
	}
}
