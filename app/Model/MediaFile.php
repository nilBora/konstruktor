<?php
App::import('Model', 'Media.Media');
class MediaFile extends Media {

    public $useTable = 'media';

    public function __construct() {
        $this->loadModel('Media.Media');
    }

    public function getFileGooglePreviewUrl( $downloadUrl ) {
		//maybe dirty hack
		//$file_url = preg_replace("/\//", Router::url('/', true), $downloadUrl, 1);
        return 'https://docs.google.com/gview?url=' . urlencode('http://konstruktor.com'. $downloadUrl) . '&embedded=true';
    }

    public function getFilePreviewUrl( $fileId ) {
        return Router::url('/File/preview/' . $fileId , true);
    }

    public function getList($findData = array(), $order = array('Media.main' => 'DESC', 'Media.id' => 'DESC')) {

        $aRows = $this->Media->find('all', array('conditions' => $findData, 'order' => $order));
        $this->alias = 'Media';

        foreach($aRows as &$_row) {
            $row = $_row[$this->alias];

            if ($row['media_type'] == 'image') {
                $_row[$this->alias]['image'] = $this->Media->PHMedia->getImageUrl($row['object_type'], $row['id'], '100x100', $row['file'].$row['ext']);
            } elseif ($row['ext'] == '.pdf') {
                $_row[$this->alias]['image'] = '/media/img/pdf.png';
            } else {
                $_row[$this->alias]['image'] = '/media/img/'.$row['media_type'].'.png';
            }
            $_row[$this->alias]['url_download'] = $this->Media->PHMedia->getRawUrl($row['object_type'], $row['id'], $row['file'].$row['ext']);
            $_row[$this->alias]['url_preview'] = $this->getFilePreviewUrl( $row['id'] );

        }


        return $aRows;
    }

    public function getMedia( $fileId ) {
        return $this->Media->find('first', array('conditions' => array('Media.id' => $fileId) ) );
    }

    public function getFileUri( $fileId ) {

        $file = $this->find('first', array('conditions' => array('Media.id' => $fileId)));
        return $file['Media']['url_download'];

    }

}
