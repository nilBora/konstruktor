<?
App::uses('AppModel', 'Model');
class Subproject extends AppModel {

	public function timeUserSubprojects($userID, $aProjects){
		$this->loadModel('GroupMember');
		$this->loadModel('Project');
		$this->loadModel('ProjectMember');


		//Собираем подпроекты и названия проектов, в которых мы, всё же, учавствуем
		$conditions = array(
			'Subproject.project_id' => Hash::extract($aProjects, '{n}.ProjectMember.project_id')
		);
		$order = 'Subproject.title';
		$aSubprojects = $this->find('all', array(
			'conditions' => $conditions,
			'order' => $order,
			'recursive' => -1,
			'fields' => array('id', 'title')
		));

		return Hash::combine($aSubprojects, '{n}.Subproject.id', '{n}.Subproject.title');
	}
	public function userSubprojects($userID) {
		$this->loadModel('GroupMember');
		$this->loadModel('Project');
		$this->loadModel('ProjectMember');

		//Находим группы/проекты, из которых исключены, что бы "списать" проекты, в которых мы уже не учавствуем
		$conditions = array(
			'GroupMember.user_id' => $userID,
			'OR' => array(
				'GroupMember.is_deleted' => '1',
				'GroupMember.approved' => '0'
			)
		);
		$aExcludeProjects = $this->GroupMember->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'fields' => array('group_id'),
		));
		$aExcludeProjects = $this->Project->findAllByGroupId( Hash::extract($aExcludeProjects, '{n}.GroupMember.group_id') );

		//C учётом того, откуда мы исключены, собираем проекты, в которых мы учавствуем
		$conditions = array(
			'ProjectMember.user_id' => $userID,
			'NOT' => array(
				'ProjectMember.project_id' => Hash::extract($aExcludeProjects, '{n}.Project.id')
			)
		);
		$aProjects = $this->ProjectMember->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'fields' => array('project_id')
		));

		//Собираем подпроекты и названия проектов, в которых мы, всё же, учавствуем
		$conditions = array(
			'Subproject.project_id' => Hash::extract($aProjects, '{n}.ProjectMember.project_id')
		);
		$order = 'Subproject.title';
		$aSubprojects = $this->find('all', array(
			'conditions' => $conditions,
			'order' => $order,
			'recursive' => -1,
			'fields' => array('id', 'title')
		));

		return Hash::combine($aSubprojects, '{n}.Subproject.id', '{n}.Subproject.title');
	}

	public function getSubprojectGroup($id) {
		$this->loadModel('Group');
		$this->loadModel('Project');

		$return = $this->findById( $id );
		$return = $this->Project->findById( $return['Subproject']['project_id'] );
		$return = $this->Group->findById( $return['Project']['group_id'] );

		return $return;
	}

	public function remove($id, $user_id) {

		$group = $this->getSubprojectGroup($id);

		if( !$group || $group['Group']['owner_id'] != $user_id ) { return false; }
		if($this->save( array('id' => $id, 'deleted' => '1' ) )) { return true; }

		return false;
	}
}
