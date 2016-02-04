<?php
App::uses('AbstractRating', 'Lib/Rating');

class UserRating extends AbstractRating {

	protected $_config = array(
		'profile' => array(
			'label' => 'User profile completeness',
			'target' => array('User' => 'id'),
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

		$fields = array('full_name', 'profile_url', 'video_url', 'skills', 'interests', 'birthday',
			'phone', 'live_place', 'live_address', 'university', 'speciality', 'live_country', 'timezone');
		foreach($fields as $field){
			if(empty($data[$this->context][$field])){
				return false;
			}
		}
		return true;
	}
}
