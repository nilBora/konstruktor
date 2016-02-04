<?
/**
 * @param $fieldName
 */
/*
	if (!isset($options['name'])) {
		$fieldName = 'period';
	}
*/
	if (!isset($options['options'])) {
		$options['options'] = array();
		for($i = 2; $i <= 5; $i++) {
			$years = ($i >= 5 && Configure::read('Config.language') == 'rus') ? 'лет' : __('years');
			$options['options'][$i * 12] = $i.' '.$years;
		}
	}
	
	if (!isset($options['label'])) {
		$options['label'] = false;
	}
	
	if (!isset($options['class'])) {
		$options['class'] = 'formstyler';
	}
	$options['div'] = false;
	echo $this->Form->input($fieldName, $options);
?>