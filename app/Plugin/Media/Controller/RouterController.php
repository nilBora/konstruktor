<?
App::uses('AppController', 'Controller');

//Deprecated and should remowed soon
class RouterController extends AppController {
	var $name = 'Router';
	var $layout = false;
	var $uses = false;
	var $autoRender = false;

	public function beforeFilter() {
		/*if(isset($this->request->params['named']['token'])){
			$this->loadModel('ApiAccess');
			$userId = $this->ApiAccess->getUserByToken($this->request->params['named']['token']);
			if($userId){
				$this->Auth->allow('index');
			}
		}*/
		//позволил для открытой системы, пока не придумал что с этим делать
		$this->Auth->allow('index');
	}

	public function beforeRender() {
	}
	public function index($type, $id, $size, $filename) {
		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();

		$fname = $this->PHMedia->getFileName($type, $id, $size, $filename);
		$aFName = $this->PHMedia->getFileInfo($filename);

		$this->response->header("Cache-Control", "max-age=2592000, must-revalidate");
		$this->response->header("Expires", gmdate("D, d M Y H:i:s", time() + MONTH) . " GMT");
		$this->response->header("Pragma", "cache");

		if (file_exists($fname)) {
			$this->response->type($aFName['ext']);
			$this->response->file($fname);
			return $this->response;
		}

		App::uses('Image', 'Media.Vendor');
		$image = new Image();
		$aSize = $this->PHMedia->getSizeInfo($size);
		$method = $this->PHMedia->getResizeMethod($size);
		$origImg = $this->PHMedia->getFileName($type, $id, null, $aFName['fname'].'.'.$aFName['orig_ext']);
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
		//debug($fname);
		if ($aFName['ext'] == 'jpg') {
			$image->outputJpg($fname);
			$image->outputJpg();
		} elseif ($aFName['ext'] == 'png') {
			$image->outputPng($fname);
			$image->outputPng();
		} else {
			$image->outputGif($fname);
			$image->outputGif();
		}
		exit;
	}
}
