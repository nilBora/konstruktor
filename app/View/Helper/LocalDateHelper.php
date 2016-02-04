<?php
App::uses('AppHelper', 'View/Helper');
class LocalDateHelper extends AppHelper {

	public function date($date) {
		if (!$date || !strtotime($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
			return '';
		}
		if ($this->viewVar('currUser.User.lang') == 'rus') {
			return date('d.m.Y', strtotime($date));
		}
		return date('m/d/Y', strtotime($date));
	}
	
	public function dateTime($date) {
		if (!$date || !strtotime($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
			return '';
		}
		if ($this->viewVar('currUser.User.lang') == 'rus') {
			return date('d.m.Y H:i', strtotime($date));
		}
		return date('m/d/Y h:ia', strtotime($date));
	}
	
	public function birthDate($date) {
		
		$aMonths = array( __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), 
			__('July'), __('August'), __('September'), __('October'), __('November'), __('December') );
		
		if (!$date || !strtotime($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
			return '';
		}
		$age = date_diff(date_create($date), date_create('now'))->y;
		$month = $aMonths[date('n', strtotime($date))-1];
		$ageWord = $this->num2word($age, array(__('year'), __('years'), __('years ')));
		if ($this->viewVar('currUser.User.lang') == 'rus') {
			return $age.' '.$ageWord.', '.date('j ', strtotime($date)).$month;
		}
		return $age.' '.$ageWord.', '.$month.date(' j', strtotime($date));
	}
	
	public function num2word($num, $words) {
		$num = $num % 100;
		if ($num > 19) {
			$num = $num % 10;
		}
		switch ($num) {
			case 1: {
				return($words[0]);
			}
			case 2: case 3: case 4: {
				return($words[1]);
			}
			default: {
				return($words[2]);
			}
		}
    }
	
	public function time($date) {
		if (!$date || !strtotime($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
			return '';
		}
		if ($this->viewVar('currUser.User.lang') == 'rus') {
			return date('H:i', strtotime($date));
		}
		return date('h:ia', strtotime($date));
	}
}
