<?php
/**
* файл модели ApiNote
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('User', 'Model');
App::uses('Note', 'Model');

/**
* Модель ApiNote. Обертка под модель Note
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiNote extends AppModel {

	public $useTable = 'notes';
	public $validate = array(
		'title' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'unique'=>array( 
				'rule' => array('checkUnique', array('title', 'user_id', 'parent_id', 'is_folder')), 
				'message' => 'Document with such name is already exist' 
			)
		),
		'body' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
		),
		'is_folder' => array(
			'checkNotEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Field is mandatory'
			),
			'isfolderCheck' => array(
				'rule' => array('inList',array(0,1)),
				'message' => 'hidden option is empty'
			),
		),
		'parent_id' => array(
			'numericCheck' => array(
				'rule' => 'numeric',
				'message' => 'Only digits',
				'allowEmpty' => true
			),
		),
	);
	
	protected function _afterInit() {
		$this->loadModel('User');
		$this->loadModel('Note');
	}
	
	/**
	* Поиск по документам
	*  
	* @param int $userId
	* @param int $parent
	* @param string $query   
	* @return array
	*/
	public function search($userId,$parent,$query=''){
		$result = $this->Note->search($userId, $parent, $query,true);
		if(!$result){
			return array();
		}

		return $this->formatSearchResult($result);
	}
	
	/**
	* Форматирует вывод поиска по документам
	*  
	* @param array $data   
	* @return array
	*/
	private function formatSearchResult($data){
		$aResult = array();
		if(!isset($data['aNotes'])){
			return array();
		}
		foreach ($data['aNotes'] as $id=>$item){
			$aResult['Note'][$id]['id'] = $item['Note']['id'];
			$aResult['Note'][$id]['title'] = $item['Note']['title'];
			$aResult['Note'][$id]['is_folder'] = $item['Note']['is_folder'];
			$aResult['Note'][$id]['parent_id'] = $item['Note']['parent_id'];
			$aResult['Note'][$id]['fileCount'] = $item['Note']['fileCount'];		
		}
		return $aResult;
	}
	
	/**
	* Доступ к документу
	*  
	* @param int $userId
	* @param int $docId   
	* @return bool
	*/
	public function checkAccessToDoc($userId,$docId){
		$result = $this->Note->field('id',array('user_id'=>$userId,'id'=>$docId,'type'=>'text','is_folder'=>0));
		if(!$result){
			return false;
		}
		return true;
	}
	
	/**
	* Доступ к папке
	*  
	* @param int $userId
	* @param int $docId   
	* @return bool
	*/
	public function checkAccessToParent($userId,$parentId){
		$result = $this->Note->field('id',array('user_id'=>$userId,'id'=>$parentId,'is_folder'=>1));
		if(!$result){
			return false;
		}
		return true;
	}

	/**
	* Содержимое документа
	*  
	* @param int $docId   
	* @return array
	*/
	public function getDocumentBody($docId){
		$data = $this->Note->findById($docId);
		if(!$data){
			return array();
		}
		$result['Note']['body'] = $data['Note']['body'];
		$result['Note']['created'] = gmdate('Y-m-d\TH:i:s\Z', strtotime($data['Note']['created']));
		return $result;
	}
	
	/**
	* Сохранение документа
	*  
	* @param array $data   
	* @return int
	*/
	public function saveDocument($data){
		if(!$this->Note->saveAll($data)){
			throw new Exception('Server Error');
		}
		return $this->Note->id;
	}
	
	/**
	* Сохранение документа
	*  
	* @param array $data   
	* @return int
	*/
	public function moveDocument($userId,$docId,$parentId){
		try{
			$this->Note->move($userId,$docId,$parentId);
			return true;
		}  catch (Exception $e){
			return false;
		}
	}
}
?>
