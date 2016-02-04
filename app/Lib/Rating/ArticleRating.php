<?php
App::uses('AbstractRating', 'Lib/Rating');

class ArticleRating extends AbstractRating {

	protected $_config = array(
		'add' => array(
			'label' => 'Article creation',
			'target' => array(
				'Group' => 'group_id',
				'User' => 'owner_id'
			),
			'value' => 5
		),
	);

	public function add($action, $foreignModel, $foreignKey, array $data = array()){
		$result = parent::validate($action, $foreignModel, $foreignKey, $data);
		if(!$data[$this->context]['published']){
			$result = false;
		}
		return $result;
	}
}
