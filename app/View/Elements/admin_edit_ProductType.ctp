<?
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->input('title');
	echo $this->PHForm->input('teaser', array('type' => 'textarea'));
	echo $this->PHForm->input('arenda_price', array(
		'class' => 'input-small', 
		'label' => array('text' => __('Arenda price, $/month'), 'class' => 'control-label'),
		'after' => '<div class="small-text muted">* '.__('For printers it is a price $/page').'</div></div>'
	));
	
	echo $this->PHForm->input('min_qty', array(
		'class' => 'input-small',
		'label' => array('text' => __('Min.qty'), 'class' => 'control-label')
	));
