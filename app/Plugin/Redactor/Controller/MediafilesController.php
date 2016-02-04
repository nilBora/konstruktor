<?php
class MediafilesController extends AppController {

	public function index() {
		$this->set('mediafiles', $this->Mediafile->find('all'));
	}

	public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid mediafile'));
        }

        $mediafile = $this->Mediafile->findById($id);
        if (!$mediafile) {
            throw new NotFoundException(__('Invalid mediafile'));
        }
        $this->set('mediafile', $mediafile);
    }

	public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Mediafile->delete($id)) {
            $this->Session->setFlash(
                __('The mediafile with id: %s has been deleted.', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }

	public function upload($type = null) {

		//set dir for file
		$dir = WWW_ROOT.'uploads/';

		$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

		if ( //image
			$_FILES['file']['type'] == 'image/png'
			|| $_FILES['file']['type'] == 'image/jpg'
			|| $_FILES['file']['type'] == 'image/gif'
			|| $_FILES['file']['type'] == 'image/jpeg'
			|| $_FILES['file']['type'] == 'image/pjpeg') {

		    // randomize name and set file locations
		    $filename = md5(date('YmdHis')).'.jpg';
		    $file = $dir.$filename;
		    //filelink for save
		    $filelink = Router::url('/uploads/').$filename;
		    $thumblink = Router::url('/uploads/thumbnails/').$filename;


		    // copy and move the file
		    if(move_uploaded_file($_FILES['file']['tmp_name'], $file)) {

		   		$this->request->data['Mediafile']['id'] = null;
		   		$this->request->data['Mediafile']['name'] = $_FILES['file']['name'];
		   		$this->request->data['Mediafile']['type'] = $_FILES['file']['type'];
		   		$this->request->data['Mediafile']['mediatype'] = $type;
		   		$this->request->data['Mediafile']['tmp_name'] = $_FILES['file']['tmp_name'];
		   		$this->request->data['Mediafile']['error'] = $_FILES['file']['error'];
		   		$this->request->data['Mediafile']['size'] = $_FILES['file']['size'];
		   		$this->request->data['Mediafile']['filelink'] = $filelink;

		   		//render thumbnail for redactor view
		   		if($this->_createThumbnail($dir, $filename, $_FILES['file']['type'], 100, 74)) {

		   			$this->request->data['Mediafile']['thumblink'] = $thumblink; 

		   		} else {

		   			//error
		   			$result = array(
						'error' => 'Could not create thumbnail.'
					);
					echo stripslashes(json_encode($result));
					die;
		   		} 
		   	
		   		if($this->Mediafile->save($this->request->data['Mediafile'])) {
		   			
		   			//return display of file
					$result = array(
						'filelink' => $filelink
					);
					echo stripslashes(json_encode($result));
					die;

		   		} else {

		   			//error
		   			$result = array(
						'error' => 'Could not save to database.'
					);
					echo stripslashes(json_encode($result));
					die;
		   		}

		    } else {

		    	//error
		   		$result = array(
					'error' => 'Could not move file.'
				);
				echo stripslashes(json_encode($result));
				die;
		    }
		    
		} else if ( //file
			$_FILES['file']['type'] == 'application/pdf'
			|| $_FILES['file']['type'] == 'application/zip'
			|| $_FILES['file']['type'] == 'application/rar') { 

			$file = $dir.$filename;

			move_uploaded_file($_FILES['file']['tmp_name'], '/files/'.$_FILES['file']['name']);

			$array = array(
				'filelink' => '/files/'.$_FILES['file']['name'],
				'filename' => $_FILES['file']['name']
			);

			echo stripslashes(json_encode($array));
			die;

		} else {

			//error
	   		$result = array(
				'error' => 'Uknown file'
			);
			echo stripslashes(json_encode($result));
			die;

		}
	}

	public function getmediaimages() {

		$mediafiles = $this->Mediafile->find('all', array('conditions' => array('Mediafile.mediatype =' => 'image')));	
		$resultarray = array();
		foreach ($mediafiles as $mediafile) {
			$resultarray[] = array(
				'thumb' => $mediafile['Mediafile']['thumblink'],
				'image' => $mediafile['Mediafile']['filelink'],
				'title' => $mediafile['Mediafile']['name'],
				'folder' => 'Default'
			);
		}
		echo stripslashes(json_encode($resultarray));
		die; 

	}

	public function getmediafiles() {

		$mediafiles = $this->Mediafile->find('all', array('conditions' => array('Mediafile.mediatype =' => 'file')));

		echo stripslashes(json_encode($mediafiles));
		die;

	}

	function _createThumbnail($dir = null, $filename = null, $type = null, $width = null, $height = null) {

	    $width = 100;
	    $thumbnaildir = $dir.'thumbnails/';
			     
	    if ($type == 'image/jpg' || $type == 'image/jpeg') {
	        $image = imagecreatefromjpeg($dir.$filename);
	    } else if ($type == 'image/gif') {
	        $image = imagecreatefromgif($dir.$filename);
	    } else if ($type == 'image/png') {
	        $image = imagecreatefrompng($dir.$filename);
	    }
	     
	    $ox = imagesx($image);
	    $oy = imagesy($image);
	     
	    $nx = $width;
	    $ny = floor($oy * ($width / $ox));
	     
	    $nm = imagecreatetruecolor($nx, $ny);
	     
	    imagecopyresized($nm, $image, 0,0,0,0,$nx,$ny,$ox,$oy);
	 
	    imagejpeg($nm, $thumbnaildir . $filename);
	    return true;
	}

}