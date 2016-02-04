<?php
App::uses('AppModel', 'Model');
App::uses('ModelBehavior', 'Model');

class RatingableBehavior extends ModelBehavior {

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'autoRate' => true,
			);
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		$ratingConfig = Configure::read("Rating.".$Model->alias);
		if(empty($ratingConfig)){
			$ratingConfig = array();
		}
		$className = $Model->alias.'Rating';
		App::uses($Model->alias.'Rating', 'Lib/Rating');
		if(class_exists($Model->alias.'Rating')){
			$this->Rating[$Model->alias] = new $className($ratingConfig);
		}
	}

	public function afterSave(Model $Model, $created, $options = array()) {
		if($created){
			$id = $Model->getLastInsertID();
		} else {
			$id = $Model->data[$Model->alias][$Model->primaryKey];
		}
		if($this->settings[$Model->alias]['autoRate']){
			$this->rate($Model, $id, $created);
		}
	}

	public function rate(Model $Model, $id, $created = false){
		if(!isset($this->Rating[$Model->alias])||empty($this->Rating[$Model->alias])){
			return;
		}
		$data = $Model->find('first', array(
			'conditions' => array($Model->alias.'.'.$Model->primaryKey => $id),
			'recursive' => -1,
		));
		//if($Model->alias == 'Project'){
		//	debug($data);
		//}
		$this->Rating[$Model->alias]->rate($data, $created);
		//if($Model->alias == 'Project'){
		//	exit();
		//}
	}

	public function ratingStyle($rating){
		$style = '';
		if(($rating >= 10)&&($rating < 20)){
			$style = 'rating10';
		} elseif(($rating >= 20)&&($rating < 30)){
			$style = 'rating20';
		} elseif(($rating >= 30)&&($rating < 40)){
			$style = 'rating30';
		} elseif(($rating >= 40)&&($rating < 50)){
			$style = 'rating40';
		} elseif(($rating >= 50)&&($rating < 60)){
			$style = 'rating50';
		} elseif(($rating >= 60)&&($rating < 70)){
			$style = 'rating60';
		} elseif(($rating >= 70)&&($rating < 80)){
			$style = 'rating70';
		} elseif(($rating >= 80)&&($rating < 90)){
			$style = 'rating80';
		} elseif(($rating >= 90)&&($rating < 100)){
			$style = 'rating90';
		} elseif($rating == 100){
			$style = 'rating100';
		}
		return $style;
	}
}
