<?php
App::uses('AppHelper', 'View/Helper');

class TranslateHelper extends AppHelper {

	public $helpers = array(
		'Form' => array('className' => 'BoostCake.BoostCakeForm')
	);

	public function input($fieldName, $options = array()) {
		$chunks = explode(".", $fieldName);
		if(count($chunks) <= 2){
			array_unshift($chunks, key($this->request->params['models']));
		}
		if(!isset($options['value'])){
			$values = Hash::extract($this->request->data, $chunks[2]."Translation.{n}[locale=".$chunks[1]."].content");
			if(!empty($values)){
				$options['value'] = reset($values);
			}
		}
		$options['beforeInput'] = '<div class="input-group"><span class="input-group-addon">'.$chunks[1].'</span>';
		$options['afterInput'] = '</div>';

		return $this->Form->input(implode(".", $chunks), $options);

	}

}
