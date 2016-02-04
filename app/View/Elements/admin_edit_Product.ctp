<?
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->input('product_type_id', array('options' => $aProductTypeOptions));
	echo $this->PHForm->input('serial', array('class' => 'input-medium'));
	echo $this->PHForm->input('ip', array(
		'class' => 'input-medium', 
		'label' => array('text' => __('IP address'), 'class' => 'control-label'),
		'after' => '<div class="small-text muted">* '.__('Only for printer').'</div></div>'
	));
	echo $this->PHForm->input('prev_counter', array(
		'class' => 'input-medium', 
		'label' => array('text' => __('Counter'), 'class' => 'control-label'),
		'after' => '<div class="small-text muted">* '.__('Only for printer').'</div></div>'
	));
