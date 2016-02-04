<?php
App::uses('DispatcherFilter', 'Routing');
App::uses('Folder', 'Utility');

class MediaDispatcher extends DispatcherFilter {

	public $priority = 11;

	protected $PHMedia = null;

	public function beforeDispatch(CakeEvent $event) {
		if(!preg_match("/media\/router\/index\//i", $event->data['request']->url)){
			return;
		}
		$event->stopPropagation();

		$requestData = $event->data['request']->params;
		list($type, $id, $size, $filename) = $requestData['pass'];

		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();

		$fname = $this->PHMedia->getFileName($type, $id, $size, $filename);
		$aFName = $this->PHMedia->getFileInfo($filename);

		$response = $event->data['response'];

		if (!file_exists($fname)) {
			$this->_processMedia($requestData['pass'], $fname, $aFName);
		}
		$this->_deliverMedia($response, $fname, $aFName);
		return $response;
	}

	protected function _processMedia($requestData, $mediaFile, $mediaInfo) {
		list($type, $id, $size, $filename) = $requestData;
		App::uses('Image', 'Media.Vendor');
		$image = new Image();
		$aSize = $this->PHMedia->getSizeInfo($size);
		$method = $this->PHMedia->getResizeMethod($size);
		$origImg = $this->PHMedia->getFileName($type, $id, null, $mediaInfo['fname'].'.'.$mediaInfo['orig_ext']);
		if ($method == 'thumb') {
			$thumb = $this->PHMedia->getFileName($type, $id, null, 'thumb.png');
			if (file_exists($thumb)) {
				$origImg = $thumb;
			}
		}

		if (!file_exists($origImg)){
			$origImg = ROOT.DS.Configure::read('App.webroot').DS.'img'.DS.'no-photo.jpg';
		}
		$image->load($origImg);
		if ($aSize) {
			$method = $this->PHMedia->getResizeMethod($size);
			$image->{$method}($aSize['w'], $aSize['h']);
		}

		$folder = new Folder(dirname($mediaFile), true, 0755);
		if ($mediaInfo['ext'] == 'jpg') {
			$image->outputJpg($mediaFile);
		} elseif ($mediaInfo['ext'] == 'png') {
			$image->outputPng($mediaFile);
		} else {
			$image->outputGif($mediaFile);
		}
	}

	protected function _deliverMedia(CakeResponse $response, $mediaFile, $mediaInfo) {
		$response->sharable(true, 2592000);
		//$response->mustRevalidate(true);
		$response->expires('+30 days');
		$modTime = filemtime($mediaFile);
		$response->modified($modTime);
		$response->etag(md5($mediaFile.$modTime));
		//$response->header("Pragma", "cache");
		$response->type($mediaInfo['ext']);
		$response->file($mediaFile);
		$response->send();
	}

}
