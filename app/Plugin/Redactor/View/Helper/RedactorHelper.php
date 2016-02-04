<?php
class RedactorHelper extends AppHelper{

	//dependancies
	public $helpers = array('Html','Form');

	//load
	public function redactor($field, $options = array()) {
		$scripts = array(
			'redactor/redactor', // main editor script
			'redactor/plugins/table/table',
			'redactor/plugins/video/video',
			'redactor/init'
		);
		$this->Html->script($scripts, array('inline' => false));
		$this->Html->css('/js/redactor/redactor', null, array('inline' => false));

		return $this->textarea($field, 'redactor', $options);

	}

	//input type
	public function textarea($field, $editor = false, $options = array()){
		$options = array_merge(array(
			'label' => false,
			'type' => 'textarea',
			'class' => "redactor_box $editor"),
			$options
		);

		$html = $this->Form->input($field, $options);

		return $html;
	}

}
