<?php
App::uses('AbstractRating', 'Lib/Rating');

class GroupRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Group creation',
			'createdOnly' => true,
			'target' => array(
				'Group' => 'id',
				'User' => 'owner_id'
			),
			'value' => 3
		),
		'profile' => array(
			'label' => 'Group profile completeness',
			'target' => array(
				'Group' => 'id',
				'User' => 'owner_id'
			),
			'value' => 3
		)
	);

	protected function profile($action, $foreignModel, $foreignKey, array $data = array()){
		$rate = $this->Rating->find('first', array(
			'conditions' => array(
				'Rating.foreign_model LIKE' => $foreignModel,
				'Rating.foreign_id' => $data[$this->context][$foreignKey],
				'Rating.context LIKE' => 'Rating.'.$this->context.'.'.$action,
			),
			'recursive' => -1
		));
		if(!empty($rate)){
			return false;
		}

		$fields = array('title', 'descr', 'group_url', 'video_url');
		foreach($fields as $field){
			if(empty($data[$this->context][$field])){
				return false;
			}
		}
		return true;
	}
}
