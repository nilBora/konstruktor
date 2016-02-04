<?php


/**
* файл модели ApiProjectMember
*
* LICENSE: MIT
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com>
*/

App::uses('AppModel', 'Model');
App::uses('ProjectMember', 'Model');

/**
* Модель ApiProjectMember. Обертка под модель ProjectMember
*
* @category   API
* @package    Api
* @version    Release: 1.0
* @author     Alexander B <answer3ster@gmail.com> 
*/

class ApiProjectMember extends AppModel {

	public $useTable = 'project_members';
	
	protected function _afterInit() {
		$this->loadModel('ProjectMember');
	}
	
	/**
	* Является ли участником проекта
	*  
	* @param int $userId 
	* @param int $projectId
	* @param int $isResponsible  
	* @return bool
	*/
	public function isProjectMember($userId,$projectId,$isResponsible = null){
		$fields = array('user_id'=>$userId,'project_id'=>$projectId);
		if($isResponsible!==null and in_array($isResponsible, array(0,1))){
			$fields['is_responsible'] = $isResponsible;
		}	
		$id = $this->field('id', $fields);
		if($id){
			return true;
		}
		return false;
	}
	
	/**
	* Список проектов пользователя
	*  
	* @param int $userId
	* @param array $projectIds   
	* @return array
	*/
	public function getUsersProjectList($userId,$projectIds){
		$fields = array('ProjectMember.project_id');
		$conditions = array('ProjectMember.project_id'=>$projectIds,'ProjectMember.user_id'=>$userId);
		$result = $this->ProjectMember->find('list',  compact('fields','conditions'));
		return $result;
	}
	
	public function saveMember($data){
		$this->ProjectMember->save($data);
		return $this->ProjectMember->id;
	}
}
?>
