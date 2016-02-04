<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');
class HFormHelper extends FormHelper {
	
	public function input($fieldName, $options = array()) {
		$html = $this->input($fieldName, $options);
		if ($this->isFieldError($fieldName)) {
			$html.= $this->error($fieldName);
		}
		return $html;
	}
}
