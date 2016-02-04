<?php
App::uses('AppHelper', 'View/Helper');

//TODO: Change to UserHelper needed!
class AvatarHelper extends AppHelper {

	public $helpers = array('Html', 'Media');

	public function user($user, $options = array()){
		$_options = array(
			'size' => 'thumb210x210',
		);
		$options = array_merge($_options, $options);
		$img = $this->Media->imageUrl($user['UserMedia'], $options['size']);

		if(!isset($options['alt'])&&isset($user['User']['full_name'])){
			$options['alt'] = $user['User']['full_name'];
		}
		if(!isset($options['class'])){
			$options['class'] = 'thumb avatar';
		} else {
			$options['class'] = 'thumb avatar '.$options['class'];
		}
		if(isset($user['User']['rating'])){
			$options['class'] .= ' '.$this->rating($user['User']['rating']);
		}

		unset($options['size']);
		return $this->Html->image($img, $options);
	}

	public function userLink($user, $options = array()){
		$_options = array(
			'escape' => false,
			'id' => 'user'.$user['User']['id']
		);
		if(isset($options['linkID'])){
			$_options = $options['linkID'];
			unset($options['linkID']);
		}
		echo $this->Html->link(
			$this->user($user, $options),
			array('plugin' => false, 'controller' => 'User', 'action' => 'view', $user['User']['id']),
			$_options
		);
	}
	public function getMediaLink($user, $options = array()){
		$_options = array(
			'size' => 'thumb210x210',
		);
		$options = array_merge($_options, $options);
		if (isset($user['UserMedia'])) {
			$media = $user['UserMedia'];
		}
		if (isset($user['GroupMedia'])) {
			$media = $user['GroupMedia'];
		}
		$img = $this->Media->imageUrl($media, $options['size']);
		return $img;
	}
	public function group($group, $options = array()){
		$_options = array(
			'size' => 'thumb210x210',
		);
		$options = array_merge($_options, $options);
		$img = $this->Media->imageUrl($group['GroupMedia'], $options['size']);
		if(!isset($options['alt'])&&isset($group['Group']['title'])){
			$options['alt'] = $group['Group']['title'];
		}
		if(!isset($options['class'])){
			$options['class'] = 'thumb avatar';
		} else {
			$options['class'] = 'thumb avatar '.$options['class'];
		}
		if(isset($group['Group']['rating'])){
			$options['class'] .= ' '.$this->rating($group['Group']['rating']);
		}

		unset($options['size']);
		return $this->Html->image($img, $options);
	}

	public function groupLink($group, $options = array()){
		$_options = array(
			'escape' => false,
			'id' => 'group'.$group['Group']['id']
		);
		if(isset($options['linkID'])){
			$_options = $options['linkID'];
			unset($options['linkID']);
		}
		echo $this->Html->link(
			$this->group($group, $options),
			array('plugin' => false, 'controller' => 'Group', 'action' => 'view', $group['Group']['id']),
			$_options
		);
	}

	public function rating($rating){
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

	/*
	 * $skills string
	 */
	public function skills($skills){
		if(!empty($skills)){
			$skills = explode(',', $skills);
			foreach($skills as $i=>$skill){
				$skills[$i] = __(trim($skill));
			}
			$skills = implode(', ', $skills);
		}
		return $skills;
	}

}
